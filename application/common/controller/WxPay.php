<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-10
 * Time: 21:35
 */

namespace app\common\controller;


use app\common\model\Area;
use app\common\model\DistributeRecord;
use app\common\model\GoodOrder;
use app\common\model\GoodOrderRelationship;
use app\common\model\Industries;
use app\common\model\OrderDetail;
use app\common\model\Salesman;
use app\common\model\ShareHolder;
use app\common\model\Store;
use app\common\model\TransfersRecord;
use app\common\model\WxTradeRecord;
use think\Controller;
use think\Db;
use think\facade\Config;
use think\facade\Env;
use think\facade\Log;
use app\common\model\User;
use think\facade\Request;
use think\facade\Session;

require_once Env::get("extend_path") . "wxpay/lib/WxPay.Data.php";
require_once Env::get("extend_path") . "wxpay/example/WxPay.JsApiPay.php";
require_once Env::get("extend_path") . "wxpay/example/WxPay.Config.php";
require_once Env::get("extend_path") . "wxpay/example/WxPay.Config.WxApp.php";
require_once Env::get("extend_path") . "wxpay/lib/WxPay.Api.php";

class WxPay extends Controller
{
    /**
     * 统一下单接口
     * @param $body
     * @param string $detail
     * @param string $attach
     * @param $trade_no
     * @param $total_fee
     * @param $notify_url
     * @param $trade_type
     * @param string $product_id
     * @param string $openid
     * @return \WxPayUnifiedOrder
     */
    static function unifiedorder($body,$trade_no,$total_fee,$notify_url,$trade_type,$goods_tag="",$product_id="",$openid="",$detail="",$attach=""){
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetDetail($detail);
        $input->SetAttach($attach);
        $input->SetOut_trade_no($trade_no);
        $input->SetTotal_fee($total_fee);
        $input->SetTime_start(date("YmdHis"));
        $input->SetGoods_tag($goods_tag);
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type($trade_type);
        $input->SetProduct_id($product_id);
        $input->SetOpenid($openid);
		
        return $input;
    }

    /**
     * 获取用户OpenID
     * @return string
     */
    static function getOpenid(){
        $tools = new \JsApiPay();
        $openid = $tools->GetOpenid();
        return $openid;
    }

    /**
     * 微信支付成功处理流程，插入订单记录表，分销佣金记录表、发送微信模板消息等，调用该函数必须是支付成功的通知，并且已经处理好一级代理和二级代理
     * @param $data array
     * @return bool
     * $data 是支付返回的结果
     */
    static function successProcess($data){
        // 店铺ID
        $attach = json_decode($data["attach"],true);
        $pay_type = $attach["pay_type"];
        // 支付金额
        $data["total_fee"] = $data["total_fee"]/100;
        // 父订单号
        $parentOrderCode = $data["out_trade_no"];

        // 开始数据库操作
        Db::startTrans();
        try {
            // 更新或插入微信交易记录表
            $record = WxTradeRecord::get($data["out_trade_no"]);
            if($record){
                $record->save($data);
            }else {
                WxTradeRecord::create($data);
            }

            // 查询子订单
            $childOrderList = GoodOrderRelationship::where("order_code_parent",$parentOrderCode)->select();

            // 更新商品订单表支付状态
            foreach ($childOrderList as $childOrder){
                $goodOrder = GoodOrder::get($childOrder->order_code_child);
                $store = Store::get($goodOrder->store_id);
                $industry = Industries::get($store->industry_id);

                $distributeParam = [
                    "platform" => $industry->platform,
                    "shareholder" => $industry->shareholder,
                    "hold" => $industry->hold,
                    "area" => $industry->area,
                    "manager" => $industry->manager,
                    "self" => $industry->self,
                    "one" => $industry->one,
                    "two" => $industry->two,
                ];

                // 扫码支付优惠
                if($pay_type ===  "wxscan"){
                    $benefitPercent = Config::get("distribute.scan_benefit_percent"); // 扫码支付优惠比例，在配置文件中配置
                    foreach ($distributeParam as $key => $value){
                        $distributeParam[$key] = $value * $benefitPercent;
                    }
                }

                // 商户所属行业应扣除的分销佣金
                $distributeSumPercent = 0;
                foreach($distributeParam as $value){
                    $distributeSumPercent += $value;
                }
                $distribute_amount = $goodOrder->amount * $distributeSumPercent / 1000;
                $goodOrder->distribute_amount = round($distribute_amount,2);// 四舍五入保留两位小数
                $goodOrder->get_amount = $goodOrder->amount - round($distribute_amount,2);
                if($pay_type ===  "wxscan"){
                    $goodOrder->pay_status = 4;
                    $goodOrder->ship_status = 3;
                    $goodOrder->delivery_date =  date('Y-m-d H:i:s', time());
                }else {
                    $goodOrder->pay_status = 1;
                }
                $goodOrder->pay_time = date("Y-m-d H:i:s",strtotime($data["time_end"]));
                $goodOrder->transaction_id = $data["transaction_id"];
                $goodOrder->save();

                // 给商户发送新订单提醒通知
                if($goodOrder->pay_type == 2){
                    $store = Store::get($goodOrder->store_id);
                    $user = User::get($store->user_id);
                    $buyer = User::get($goodOrder->user_id);
                    if($user && $buyer && $user->wx_openid) {
                        sendMpMessage("new_order",
                            $user->wx_openid,
                            newOrderNotificationData(
                                "您收到一笔新订单已支付成功",
                                $goodOrder->amount,
                                "订单支付",
                                $goodOrder->order_code,
                                $buyer->nick_name,
                                "请尽快登陆商户后台处理订单"
                            )
                        );
                    }

                    if($buyer && $buyer->wx_openid){
                        sendMpMessage("order_pay_success",$buyer->wx_openid,orderPaySuccessData("订单支付成功",$goodOrder->amount,$goodOrder->order_code,"如有疑问，请联系：0858-8699077"));
                    }
                }
                // 给商户发送扫码支付
                Log::record("订单类型pay_type: " . $goodOrder->pay_type);
                if($goodOrder->pay_type == 1){
                    $store = Store::get($goodOrder->store_id);
                    $storeUser = User::get($store->user_id);
                    if($storeUser && $storeUser->wx_openid) {
                        sendMpMessage("scan_pay_success",
                            $storeUser->wx_openid,
                            scanPayNotificationData("线下扫码支付成功", $goodOrder->amount, "扫码支付", "请认真核对客户支付金额是否正确")
                        );
                    }
                }


                /* 分销操作 */
                $user_id = $goodOrder->user_id;
                $user = User::get($user_id);
                // 自购
                $self = new DistributeRecord();
                $self->save([
                    "order_code" => $goodOrder->order_code,
                    "level" => 0,
                    "store_id" => $goodOrder->store_id,
                    "pay_type" => $pay_type,
                    "user_id" => $user_id,
                    "amount" => $goodOrder->amount,
                    "percent" => $distributeParam["self"],
                    "get_amount" => $goodOrder->amount * $distributeParam["self"] /1000,
                    "desc" => "自购分销佣金"
                ]);

                if($goodOrder->amount * $distributeParam["self"] /1000 >= 0.01 && $user &&  $user->wx_openid){
                    sendMpMessage("commission",$user->wx_openid,
                        commissionNotificationData(
                            "您获得一笔自购佣金",
                            bcdiv($goodOrder->amount * $distributeParam["self"],1000,2),
                            date("Y-m-d H:i:s",strtotime($data["time_end"])),
                            "请进入快享立赢商城小程序查看")
                    );
                }

                // 一级代理
                $one_user_id = $user->one_level_user_id;
                $oneUser = User::get($one_user_id);
                $one = new DistributeRecord();
                if($oneUser && $oneUser->getData("status") == 1){
                    $one->save([
                        "order_code" => $goodOrder->order_code,
                        "level" => 1,
                        "store_id" => $goodOrder->store_id,
                        "pay_type" => $pay_type,
                        "user_id" => $one_user_id,
                        "amount" => $goodOrder->amount,
                        "percent" => $distributeParam["one"],
                        "get_amount" => $goodOrder->amount * $distributeParam["one"] /1000,
                        "desc" => "一级分销佣金"
                    ]);
                    if($goodOrder->amount * $distributeParam["one"] /1000 >= 0.01 && $oneUser && $oneUser->wx_openid){
                        sendMpMessage("commission",$oneUser->wx_openid,
                            commissionNotificationData(
                                "您获得一笔一级佣金",
                                bcdiv($goodOrder->amount * $distributeParam["one"],1000,2),
                                date("Y-m-d H:i:s",strtotime($data["time_end"])),
                                "请进入快享立赢商城小程序查看")
                        );
                    }
                }else {
                    $one->save([
                        "order_code" => $goodOrder->order_code,
                        "level" => 1,
                        "store_id" => $goodOrder->store_id,
                        "pay_type" => $pay_type,
                        "amount" => $goodOrder->amount,
                        "percent" => $distributeParam["one"],
                        "get_amount" => $goodOrder->amount * $distributeParam["one"] /1000,
                        "desc" => "无一级代理或一级代理被禁用，佣金归平台"
                    ]);
                }

                // 二级代理
                $two_user_id = $user->two_level_user_id;
                $twoUser = User::get($two_user_id);
                $two = new DistributeRecord();
                if($twoUser && $twoUser->getData("status") === 1){
                    $two->save([
                        "order_code" => $goodOrder->order_code,
                        "level" => 2,
                        "store_id" => $goodOrder->store_id,
                        "pay_type" => $pay_type,
                        "user_id" => $two_user_id,
                        "amount" => $goodOrder->amount,
                        "percent" => $distributeParam["two"],
                        "get_amount" => $goodOrder->amount * $distributeParam["two"] /1000,
                        "desc" => "二级分销佣金"
                    ]);
                    if($goodOrder->amount * $distributeParam["two"] /1000 >= 0.01 && $twoUser && $twoUser->wx_openid){
                        sendMpMessage("commission",$twoUser->wx_openid,
                            commissionNotificationData(
                                "您获得一笔二级佣金",
                                bcdiv($goodOrder->amount * $distributeParam["two"],1000,2),
                                date("Y-m-d H:i:s",strtotime($data["time_end"])),
                                "请进入快享立赢商城小程序查看")
                        );
                    }
                }else {
                    $two->save([
                        "order_code" => $goodOrder->order_code,
                        "level" => 2,
                        "store_id" => $goodOrder->store_id,
                        "pay_type" => $pay_type,
                        "amount" => $goodOrder->amount,
                        "percent" => $distributeParam["two"],
                        "get_amount" => $goodOrder->amount * $distributeParam["two"] /1000,
                        "desc" => "无二级代理或二级代理被禁用，佣金归平台"
                    ]);
                }

                // 业务经理分销佣金
                $saleman = Salesman::get($store->manager_id);
                if($saleman && $saleman->getData("status") == 1){
                    $manger_user = User::get($saleman->user_id);
                    if($manger_user && $manger_user->status == 1){
                        $manager = new DistributeRecord();
                        $manager->save([
                            "order_code" => $goodOrder->order_code,
                            "level" => 3,
                            "store_id" => $goodOrder->store_id,
                            "pay_type" => $pay_type,
                            "user_id" => $saleman->user_id,
                            "amount" => $goodOrder->amount,
                            "percent" => $distributeParam["manager"],
                            "get_amount" => $goodOrder->amount * $distributeParam["manager"] /1000,
                            "desc" => "业务经理分销佣金"
                        ]);
                        if($goodOrder->amount * $distributeParam["manager"] /1000 >= 0.01 && $manger_user && $manger_user->wx_openid){
                            sendMpMessage("commission",$manger_user->wx_openid,
                                commissionNotificationData(
                                    "您获得一笔业务佣金",
                                    bcdiv($goodOrder->amount * $distributeParam["manager"],1000,2),
                                    date("Y-m-d H:i:s",strtotime($data["time_end"])),
                                    "请进入快享立赢商城小程序查看")
                            );
                        }
                    }else {
                        $manager = new DistributeRecord();
                        $manager->save([
                            "order_code" => $goodOrder->order_code,
                            "level" => 3,
                            "store_id" => $goodOrder->store_id,
                            "pay_type" => $pay_type,
                            "amount" => $goodOrder->amount,
                            "percent" => $distributeParam["manager"],
                            "get_amount" => $goodOrder->amount * $distributeParam["manager"] /1000,
                            "desc" => "业务经理对应的用户状态不正常，分销佣金归平台"
                        ]);
                    }
                }else {
                    $manager = new DistributeRecord();
                    $manager->save([
                        "order_code" => $goodOrder->order_code,
                        "level" => 3,
                        "store_id" => $goodOrder->store_id,
                        "pay_type" => $pay_type,
                        "amount" => $goodOrder->amount,
                        "percent" => $distributeParam["manager"],
                        "get_amount" => $goodOrder->amount * $distributeParam["manager"] /1000,
                        "desc" => "业务经理状态不正常，分销佣金归平台"
                    ]);
                }

                // 区域代理分销佣金
                $areaRecode = Area::get($store->area_id);
                if($areaRecode && $areaRecode->getData("status") == 1){
                    $area = new DistributeRecord();
                    $area->save([
                        "order_code" => $goodOrder->order_code,
                        "level" => 4,
                        "store_id" => $goodOrder->store_id,
                        "pay_type" => $pay_type,
                        "area_id" => $store->area_id,
                        "amount" => $goodOrder->amount,
                        "percent" => $distributeParam["area"],
                        "get_amount" => $goodOrder->amount * $distributeParam["area"] /1000,
                        "desc" => "区域代理分销佣金"
                    ]);
                }else {
                    $area = new DistributeRecord();
                    $area->save([
                        "order_code" => $goodOrder->order_code,
                        "level" => 4,
                        "store_id" => $goodOrder->store_id,
                        "pay_type" => $pay_type,
                        "amount" => $goodOrder->amount,
                        "percent" => $distributeParam["area"],
                        "get_amount" => $goodOrder->amount * $distributeParam["area"] /1000,
                        "desc" => "区域状态不正常，用户归平台"
                    ]);
                }


                // 平台留存
                $hold = new DistributeRecord();
                $hold->save([
                    "order_code" => $goodOrder->order_code,
                    "level" => 5,
                    "store_id" => $goodOrder->store_id,
                    "pay_type" => $pay_type,
                    "amount" => $goodOrder->amount,
                    "percent" => $distributeParam["hold"],
                    "get_amount" => $goodOrder->amount * $distributeParam["hold"] /1000,
                    "desc" => "平台留存佣金"
                ]);

                // 平台提点
                $platform = new DistributeRecord();
                $platform->save([
                    "order_code" => $goodOrder->order_code,
                    "level" => 7,
                    "store_id" => $goodOrder->store_id,
                    "pay_type" => $pay_type,
                    "amount" => $goodOrder->amount,
                    "percent" => $distributeParam["platform"],
                    "get_amount" => $goodOrder->amount * $distributeParam["platform"] /1000,
                    "desc" => "平台提点佣金"
                ]);

                // 股东分红
                $shareHolders = ShareHolder::all();
                $count = $shareHolders->count();
                foreach ($shareHolders as $shareHolderUser){
                    $shareholder = new DistributeRecord();
                    $shareholder->save([
                        "order_code" => $goodOrder->order_code,
                        "level" => 6,
                        "store_id" => $goodOrder->store_id,
                        "pay_type" => $pay_type,
                        "user_id" => $shareHolderUser->user_id,
                        "amount" => $goodOrder->amount,
                        "percent" => $distributeParam["shareholder"] / $count,
                        "get_amount" => $goodOrder->amount * $distributeParam["shareholder"] / $count /1000,
                        "desc" => "股东分红佣金"
                    ]);
                    if($goodOrder->amount * $distributeParam["shareholder"] / $count /1000 >= 0.01 && $shareHolderUser){
                        $share = User::get($shareHolderUser->user_id);
                        sendMpMessage("commission",$share->wx_openid,
                            commissionNotificationData(
                                "您获得一笔股东分红佣金",
                                bcdiv($goodOrder->amount * $distributeParam["shareholder"],1000,2),
                                date("Y-m-d H:i:s",strtotime($data["time_end"])),
                                "请进入快享立赢商城小程序查看")
                        );
                    }
                }
            }
            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Log::record("支付异步通知处理失败" . $e->getTraceAsString());
            Db::rollback();
            return false;
        }
    }

    /**
     * 生成微信支付附加数据
     * @param $store_id integer 店铺ID
     * @param $pay_type string 支付类型：扫码支付->wxscan;订单支付->orderpay
     * @return string
     */
    static function attach($store_id,$pay_type){
        $data = [
            "store_id" => $store_id,
            "pay_type" => $pay_type
        ];
        return json_encode($data);
    }

    /**
     * 企业付款到零钱
     * @param $openid
     * @param $amount number 单位为元
     * @param string $desc
     * @return bool|string
     */
    static function transfers($openid,$amount,$desc="佣金提现"){
        try {

            $user = User::where("wxapp_openid",$openid)->find();
            if(!$user){
                return false;
            }
            $partner_trade_no = createTransfersOrderCode();
            $spbill_create_ip = Request::ip();
            $input = new \WxPayTransfers();
            $input->SetPartner_trade_no($partner_trade_no);
            $input->SetOpenid($openid);
            $input->SetAmount($amount*100);
            $input->SetDesc($desc);
            $input->SetSpbill_create_ip($spbill_create_ip);

            // 插入提现记录
            $transfers = new TransfersRecord();
            $data = [
                "transfers_order_code" => $partner_trade_no,
                "wxapp_openid" => $openid,
                "amount" => $amount,
                "user_code" => $user->code,
                "user_id" => $user->id,
                "status" => 0
            ];
            if(!$transfers->save($data)){
                exception("佣金提现失败，创建提现记录失败");
            }

            $config = new \WxAppPayConfig;
            $result = \WxPayApi::transfers($config, $input);
            Log::record(json_encode($result));
            if(array_key_exists("result_code",$result) && $result["result_code"] == "SUCCESS" && array_key_exists("partner_trade_no",$result)){
                // 说明佣金提现成功
                $transfers = TransfersRecord::get($transfers->id);
                $data = [
                    "transfers_order_code" => $result["partner_trade_no"],
                    "payment_no" => $result["payment_no"],
                    "payment_time" => $result["payment_time"],
                    "status" => 1
                ];

                if(!$transfers->save($data)){
                    Log::record("佣金提现成功，但是插入数据库失败，请排查，订单号为：" . $result["partner_trade_no"]);
                    exception("佣金提现成功，但是插入数据库失败，请排查，订单号为：" . $result["partner_trade_no"]);
                }

                // 不管是否保存成功，都返回true
                return true;
            }else if(array_key_exists("result_code",$result) && $result["result_code"] == "FAIL"){
                $transfers = TransfersRecord::get($transfers->id);
                $data = [
                    "transfers_order_code" => $result["partner_trade_no"],
                    "payment_no" => $result["payment_no"],
                    "payment_time" => $result["payment_time"],
                    "status" => 2
                ];
                $transfers->save($data);
                // 佣金提现失败，需要调用接口查询结果
                Log::record($result["err_code_des"] . ",提现单号为：" .$result["partner_trade_no"] );
                return false;
            }else {
                return false;
            }
        } catch (\Exception $e) {
            Log::record("佣金提现失败：" . $openid . '------' . $amount . "错误信息：" . $e->getMessage());
            return false;
        }
    }


    /**
     * 商家退款接口
     * @param $order_code
     * @return mixed
     * @throws \Exception
     */
    static function refund($order_code){
        $order = GoodOrder::get($order_code);
        // 如果订单不存在，则抛出异常
        if(!$order){
            exception("订单不存在");
        }

        // 判断订单是否属于店铺
        if($order->store_id != Session::get("store_info.id")){
            exception("该订单不属于您，不能进行退款操作");
        }

        // 判断订单是否已结算
        if($order->settle_status !== 0){
            exception("订单已结算，不能退款");
        }

        // 判断订单是否已经生成结算单
        if($order->settle_id != null){
            exception("该订单已经生成结算单，不能退款");
        }

        // 判断订单是否是扫码支付
        if($order->pay_type != 2){
            exception("扫码支付订单不能退款");
        }

        // 判断订单是否已经退款
        if($order->return_status == 2 || $order->return_status == 4 ){
            exception("订单已退款，不能重复退款");
        }

        // 判断订单的退款状态是否为申请中和拒绝退款
        if($order->return_status != 1 && $order->return_status != 3 ){
            exception("用户没有申请退款，不能退款");
        }

        // 判断是否有已经线下核销的订单
        if(OrderDetail::where(["order_code" => $order_code,"offline" => 1,"is_verification" => 1])->count() > 0){
            exception("该订单下有已核销商品，不能退款");
        }

        // 判断是否已确认收货
        if($order->pay_status == 4 ){
            exception("该订单已确认收货，不能退款");
        }


        try{
            $transaction_id = $order->transaction_id;

            // 计算订单金额（订单金额是多个子订单的合计）
            // 1. 查找父订单号
            $relation_record  = GoodOrderRelationship::where("order_code_child",$order_code)->distinct(true)->field("order_code_parent")->find();

            if(!$relation_record){
                exception("查找订单关系表失败,退款失败");
            }

            // 2. 查找父订单号下面对应的所有子订单，并计算订单总金额
            $pOrderCode = $relation_record["order_code_parent"];
            Log::record("pOrderCode:" . $pOrderCode);
            $relation = GoodOrderRelationship::where("order_code_parent",$pOrderCode)->select();
            $amount = 0;
            foreach ($relation as $r){
                $goodOrder = GoodOrder::get($r->order_code_child);
                $amount = $amount + $goodOrder->amount;
            }

            $total_fee = $amount * 100;
            $refund_fee = $order->amount * 100;
            $out_refund_no = createRefundCode(); // 商户退款单号
            $input = new \WxPayRefund();
            $input->SetTransaction_id($transaction_id);
            $input->SetTotal_fee($total_fee);
            $input->SetRefund_fee($refund_fee);

            $config = new \WxAppPayConfig();
            $input->SetOut_refund_no($out_refund_no);
            $input->SetOp_user_id($config->GetMerchantId());
            $result = \WxPayApi::refund($config, $input);

            // 结果校验
            // 1. 如果返回结果中return_code不为SUCCESS，这说明退款失败
            if($result["return_code"] != "SUCCESS"){
                exception($result["return_msg"]);
            }

            // 2. 如果result_code不为SUCCESS，说明提交退款申请失败
            if($result["result_code"] != "SUCCESS"){
                exception("错误代码:" . $result["err_code"] . "," . $result["err_code_des"]);
            }

            // 经过上面两部的校验，到达这一步，说明退款成功
            // 1. 更新订单表的退款状态和退款单号
            $order->save([
                "return_status" => 2,
                "out_refund_no" => $out_refund_no
            ]);

            return true;
        } catch(\Exception $e) {
            Log::record("错误：");
            Log::record(json_encode($e->getTraceAsString()));
            exception($e->getMessage());
        }
    }

}