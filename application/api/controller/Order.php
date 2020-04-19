<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-24
 * Time: 23:19
 */

namespace app\api\controller;


use app\common\controller\ApiBase;
use app\common\model\ExpressCompany;
use app\common\model\Good;
use app\common\model\GoodComment;
use app\common\model\GoodOption;
use app\common\model\GoodOrder;
use app\common\model\GoodOrderRelationship;
use app\common\model\GoodOrderShipTraces;
use app\common\model\OrderDetail;
use app\common\model\Store;
use app\common\model\User;
use think\Db;
use think\exception\DbException;
use think\facade\Log;
use think\facade\Request;

class Order extends ApiBase
{
    public function createOrder(){
        if(Request::has("data") && Request::has("user_id") && Request::has("receiver_id")){
            $data = json_decode(urldecode(Request::param("data")));
            $user_id = Request::param("user_id");
            $receiver_id = Request::param("receiver_id");
            $pay_amount = 0;

            $hasOnline = false;
            // 启动事务
            Db::startTrans();
            try {
                $user = User::get($user_id);
                if($user){
                    // 用户被禁用
                   if($user->status == 0){
                       exception("您的账号已被禁用");
                   }
                }else {
                    exception("您的账号不存在");
                }

                // 传给微信支付系统的订单号（总订单号）
                $parentOrderCode = createParentOrderCode();
                foreach ($data as $store){
                    $store_info = Store::where(["id"=>$store->store_id,"status" => 1])->find();
                    if($store_info){
                        // 商家订单号
                        $childOrderCode = createChildOrderCode();
                        foreach($store->goods as $good){
                            $good_info = Good::where(["id"=>$good->good_id,"status"=>1])->find();
                            if($good_info){
                                if($good_info->order_type == 0){
                                    $hasOnline = true;
                                }
                                $option_info = GoodOption::get($good->option_id);
                                $detailData = null;
                                if($option_info){
                                    if($good_info->totalcnf == 0) {
                                        $option_info->stock = $option_info->stock - $good->count;
                                        $good_info->save();
                                    }
                                    $detailData = [
                                        "order_code" => $childOrderCode,
                                        "good_id" => $good_info->id,
                                        "good_name" => $good_info->name,
                                        "option_id" => $option_info->id,
                                        "option_name" => $option_info->title,
                                        "price" => $option_info->marketprice,
                                        "number" => $good->count,
                                        "amount" => $option_info->marketprice * $good->count,
                                        "offline" => $good_info->order_type,
                                    ];
                                }else {
                                    if($good_info->totalcnf == 0) {
                                        $good_info->total = $good_info->total - $good->count;
                                        $good_info->save();
                                    }
                                    $detailData = [
                                        "order_code" => $childOrderCode,
                                        "good_id" => $good_info->id,
                                        "good_name" => $good_info->name,
                                        "option_id" => 0,
                                        "option_name" => null,
                                        "price" => $good_info->marketprice,
                                        "number" => $good->count,
                                        "amount" => $good_info->marketprice * $good->count,
                                        "offline" => $good_info->order_type,
                                    ];
                                }

                                // 创建订单明细
                                $orderDetail = new OrderDetail();
                                $orderDetail->save($detailData);

                            }else {
                                exception("商品暂时不能购买");
                            }
                        }

                        // 创建商家订单
                        $sumAmount = OrderDetail::where("order_code",$childOrderCode)->sum("amount");
                        Log::record("商家订单总金额：" . $sumAmount);
                        $benefit_amount = 0; // 优惠金额
                        $receive = \app\common\model\ReceiveAddress::get($receiver_id);
                        if($hasOnline){
                            if(!$receive){
                                exception("未添加收货地址");
                            }
                            $orderData = [
                                "order_code" => $childOrderCode,
                                "store_id" => $store_info->id,
                                "user_id" => $user->id,
                                "pay_status" => 0,
                                "ship_status" => 0,
                                "amount" => $sumAmount,
                                "benefit_amount" => $benefit_amount,
                                "pay_amount" => $sumAmount - $benefit_amount ,
                                "receiver_name" => $receive->name,
                                "receiver_mobile" => $receive->phone,
                                "receiver_province" => $receive->province,
                                "receiver_city" => $receive->city,
                                "receiver_area" => $receive->county,
                                "receiver_address" => $receive->detail_address,
                                "admin_status" => 1,
                                "pay_type"=> 2,
                                "remark" => null,
                            ];
                        }else {
                            $orderData = [
                                "order_code" => $childOrderCode,
                                "store_id" => $store_info->id,
                                "user_id" => $user->id,
                                "pay_status" => 0,
                                "ship_status" => 0,
                                "amount" => $sumAmount,
                                "benefit_amount" => $benefit_amount,
                                "pay_amount" => $sumAmount - $benefit_amount ,
                                "admin_status" => 1,
                                "pay_type"=> 2,
                                "remark" => null,
                            ];
                        }


                        $order = new GoodOrder();
                        $order->save($orderData);
                        $pay_amount += ($sumAmount - $benefit_amount);
                        $relationShip = new GoodOrderRelationship();
                        $relationShip->save([
                            "order_code_parent" => $parentOrderCode,
                            "order_code_child" => $childOrderCode,
                            "user_id" => $user->id
                        ]);
                    }else {
                        exception("店铺信息有误");
                    }
                }
                $payData = [
                    "order_code" => $parentOrderCode,
                    "amount" => $pay_amount,
                    "wxapp_openid" => $user->wxapp_openid
                ];

                $wxOrderPay = WxPay::getWxAppPayParameters($payData);
				
                if($wxOrderPay["code"] <> 0){
                    exception($wxOrderPay["message"]);
                }else {
                    // 提交事务
                    Db::commit();
                    return result($wxOrderPay["data"],$wxOrderPay["code"],$wxOrderPay["message"]);
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"参数错误");
        }
    }
	public function createOrder2(){
        if(Request::has("data") && Request::has("user_id") && Request::has("receiver_id")){
            $data = json_decode(urldecode(Request::param("data")));
            $user_id = Request::param("user_id");
            $receiver_id = Request::param("receiver_id");
            $pay_amount = 0;

            $hasOnline = false;
            // 启动事务
            Db::startTrans();
            try {
                $user = User::get($user_id);
                if($user){
                    // 用户被禁用
                   if($user->status == 0){
                       exception("您的账号已被禁用");
                   }
                }else {
                    exception("您的账号不存在");
                }

                // 传给微信支付系统的订单号（总订单号）
                $parentOrderCode = createParentOrderCode();
                foreach ($data as $store){
                    $store_info = Store::where(["id"=>$store->store_id,"status" => 1])->find();
                    if($store_info){
                        // 商家订单号
                        $childOrderCode = createChildOrderCode();
                        foreach($store->goods as $good){
                            $good_info = Good::where(["id"=>$good->good_id,"status"=>1])->find();
                            if($good_info){
                                if($good_info->order_type == 0){
                                    $hasOnline = true;
                                }
                                $option_info = GoodOption::get($good->option_id);
                                $detailData = null;
                                if($option_info){
                                    $detailData = [
                                        "order_code" => $childOrderCode,
                                        "good_id" => $good_info->id,
                                        "good_name" => $good_info->name,
                                        "option_id" => $option_info->id,
                                        "option_name" => $option_info->title,
                                        "price" => $option_info->marketprice,
                                        "number" => $good->count,
                                        "amount" => $option_info->marketprice * $good->count,
                                        "offline" => $good_info->order_type,
                                    ];
                                }else {
                                    $detailData = [
                                        "order_code" => $childOrderCode,
                                        "good_id" => $good_info->id,
                                        "good_name" => $good_info->name,
                                        "option_id" => 0,
                                        "option_name" => null,
                                        "price" => $good_info->marketprice,
                                        "number" => $good->count,
                                        "amount" => $good_info->marketprice * $good->count,
                                        "offline" => $good_info->order_type,
                                    ];
                                }

                                // 创建订单明细
                                $orderDetail = new OrderDetail();
                                $orderDetail->save($detailData);

                            }else {
                                exception("商品暂时不能购买");
                            }
                        }

                        // 创建商家订单
                        $sumAmount = OrderDetail::where("order_code",$childOrderCode)->sum("amount");
                        Log::record("商家订单总金额：" . $sumAmount);
                        $benefit_amount = 0; // 优惠金额
                        $receive = \app\common\model\ReceiveAddress::get($receiver_id);
                        if($hasOnline){
                            if(!$receive){
                                exception("未添加收货地址");
                            }
                            $orderData = [
                                "order_code" => $childOrderCode,
                                "store_id" => $store_info->id,
                                "user_id" => $user->id,
                                "pay_status" => 0,
                                "ship_status" => 0,
                                "amount" => $sumAmount,
                                "benefit_amount" => $benefit_amount,
                                "pay_amount" => $sumAmount - $benefit_amount ,
                                "receiver_name" => $receive->name,
                                "receiver_mobile" => $receive->phone,
                                "receiver_province" => $receive->province,
                                "receiver_city" => $receive->city,
                                "receiver_area" => $receive->county,
                                "receiver_address" => $receive->detail_address,
                                "admin_status" => 1,
                                "pay_type"=> 2,
                                "remark" => null,
                            ];
                        }else {
                            $orderData = [
                                "order_code" => $childOrderCode,
                                "store_id" => $store_info->id,
                                "user_id" => $user->id,
                                "pay_status" => 0,
                                "ship_status" => 0,
                                "amount" => $sumAmount,
                                "benefit_amount" => $benefit_amount,
                                "pay_amount" => $sumAmount - $benefit_amount ,
                                "admin_status" => 1,
                                "pay_type"=> 2,
                                "remark" => null,
                            ];
                        }


                        $order = new GoodOrder();
                        $order->save($orderData);
                        $pay_amount += ($sumAmount - $benefit_amount);
                        $relationShip = new GoodOrderRelationship();
                        $relationShip->save([
                            "order_code_parent" => $parentOrderCode,
                            "order_code_child" => $childOrderCode,
                            "user_id" => $user->id
                        ]);
                    }else {
                        exception("店铺信息有误");
                    }
                }
                $payData = [
                    "order_code" => $parentOrderCode,
                    "amount" => $pay_amount,
                    "wx_openid" => $user->wx_openid
                ];

                $wxOrderPay = WxPays::getWxAppPayParameters($payData);
                if($wxOrderPay["code"] <> 0){
                    exception($wxOrderPay["message"]);
                }else {
                    // 提交事务
                    Db::commit();
                    return result($wxOrderPay["data"],$wxOrderPay["code"],$wxOrderPay["message"]);
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"参数错误");
        }
    }
    /**
     * 订单重新支付
     */
    public function rePay(){
        if(Request::has("order_code") && Request::has("wxapp_openid")){
            // 总订单号
            $order_code = Request::param("order_code");
            // 会员的小程序openid
            $wxapp_openid = Request::param("wxapp_openid");
            $pay_amount = Db::table("good_order")
                ->where("order_code","in",function($query) use ($order_code){
                    $query->table('good_order_relationship')
                        ->where('order_code_parent', $order_code)
                        ->field('order_code_child');
                })->sum("pay_amount");
            $payData = [
                "order_code" => $order_code,
                "amount" => $pay_amount,
                "wxapp_openid" => $wxapp_openid
            ];

            $wxOrderPay = WxPay::getWxAppPayParameters($payData);
            if($wxOrderPay["code"] <> 0){
                return result([],1,$wxOrderPay["message"]);
            }else {
                return result($wxOrderPay["data"],$wxOrderPay["code"],$wxOrderPay["message"]);
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    /*
     * 获取用户的订单
     */

    public function getOrderByUserId(){
        if(Request::has("user_id") && Request::param("page") && Request::param("limit")){
            $user_id = Request::param("user_id"); // 用户ID
            $type = Request::param("type"); // 订单类型，0：全部订单，1：待付款，2：待发货，3：待收货，4：待核销，5：待退货，6：待评价，7：已退货，8：拒绝退货，9：已完成
            $limit = Request::param("limit"); // 每次加载的订单数量
            $page = ( Request::param("page") - 1 ) * $limit; // 第几页
            $parentOrderList = [];
            try {
                switch ($type){
                    // 待付款订单
                    case 1:
                        // 1. 取父订单
                        $parentOrderList = Db::view("order_relationship_dfk")->where("user_id",$user_id)->limit($page,$limit)->select();
                        // 2. 取子订单
                        foreach ($parentOrderList as $parentIndex => $parent){
                            $childOrderList = GoodOrder::where("order_code","IN",$parent["order_code_child"])->select();
                            // 3. 取子订单明细
                            foreach ($childOrderList as $child){
                                $child->store;
                                $detailList = OrderDetail::where("order_code",$child->order_code)->select();
                                foreach ($detailList as $detail){
                                    $detail->img;
                                }
                                $child["detail"] = $detailList;
                            }
                            $parentOrderList[$parentIndex]["childs"] = $childOrderList;
                        }
                        break;
                    // 待发货
                    case 2:
                        // 1. 取父订单
                        $parentOrderList = Db::view("order_relationship_dfh")->where("user_id",$user_id)->limit($page,$limit)->select();
                        // 2. 取子订单
                        foreach ($parentOrderList as $parentIndex => $parent){
                            $childOrderList = GoodOrder::where("order_code","IN",$parent["order_code_child"])->select();
                            // 3. 取子订单明细
                            foreach ($childOrderList as $child){
                                $child->store;
                                $detailList = OrderDetail::where(["order_code"=>$child->order_code,"offline" => 0])->select();
                                foreach ($detailList as $detail){
                                    $detail->img;
                                }
                                $child["detail"] = $detailList;
                            }
                            $parentOrderList[$parentIndex]["childs"] = $childOrderList;
                        }
                        break;
                    // 待收货
                    case 3:
                        // 1. 取父订单
                        $parentOrderList = Db::view("order_relationship_dsh")->where("user_id",$user_id)->limit($page,$limit)->select();
                        // 2. 取子订单
                        foreach ($parentOrderList as $parentIndex => $parent){
                            $childOrderList = GoodOrder::where("order_code","IN",$parent["order_code_child"])->select();
                            // 3. 取子订单明细
                            foreach ($childOrderList as $child){
                                $child->store;
                                $detailList = OrderDetail::where(["order_code"=>$child->order_code,"offline" => 0])->select();
                                foreach ($detailList as $detail){
                                    $detail->img;
                                }
                                $child["detail"] = $detailList;
                            }
                            $parentOrderList[$parentIndex]["childs"] = $childOrderList;
                        }
                        break;
                    // 待核销
                    case 4:
                        // 1. 取父订单
                        $parentOrderList = Db::view("order_relationship_dhx")->where("user_id",$user_id)->limit($page,$limit)->select();
                        // 2. 取子订单
                        foreach ($parentOrderList as $parentIndex => $parent){
                            $childOrderList = GoodOrder::where("order_code","IN",$parent["order_code_child"])->select();
                            // 3. 取子订单明细
                            foreach ($childOrderList as $child){
                                $child->store;
                                $detailList = OrderDetail::where(["order_code"=>$child->order_code,"offline" => 1,"is_verification" => 0])->select();
                                foreach ($detailList as $detail){
                                    $detail->img;
                                }
                                $child["detail"] = $detailList;
                            }
                            $parentOrderList[$parentIndex]["childs"] = $childOrderList;
                        }
                        break;
                    // 待退货
                    case 5:
                        // 1. 取父订单
                        $parentOrderList = Db::view("order_relationship_dth")->where("user_id",$user_id)->limit($page,$limit)->select();
                        // 2. 取子订单
                        foreach ($parentOrderList as $parentIndex => $parent){
                            $childOrderList = GoodOrder::where("order_code","IN",$parent["order_code_child"])->select();
                            // 3. 取子订单明细
                            foreach ($childOrderList as $child){
                                $child->store;
                                $detailList = OrderDetail::where(["order_code"=>$child->order_code])->select();
                                foreach ($detailList as $detail){
                                    $detail->img;
                                }
                                $child["detail"] = $detailList;
                            }
                            $parentOrderList[$parentIndex]["childs"] = $childOrderList;
                        }
                        break;
                    // 待评价
                    case 6:
                        // 1. 取父订单
                        $parentOrderList = Db::view("order_relationship_dpj")->where("user_id",$user_id)->limit($page,$limit)->select();
                        // 2. 取子订单
                        foreach ($parentOrderList as $parentIndex => $parent){
                            $childOrderList = GoodOrder::where("order_code","IN",$parent["order_code_child"])->select();
                            // 3. 取子订单明细
                            foreach ($childOrderList as $child){
                                $child->store;
                                $detailList = OrderDetail::where(["order_code"=>$child->order_code])->select();
                                foreach ($detailList as $detail){
                                    $detail->img;
                                }
                                $child["detail"] = $detailList;
                            }
                            $parentOrderList[$parentIndex]["childs"] = $childOrderList;
                        }
                        break;
                    // 全部订单
                    case 0:
                        // 1. 取父订单
                        $parentOrderList = Db::view("order_relationship_all")->where("user_id",$user_id)->limit($page,$limit)->select();
                        // 2. 取子订单
                        foreach ($parentOrderList as $parentIndex => $parent){
                            $childOrderList = GoodOrder::where("order_code","IN",$parent["order_code_child"])->select();
                            // 3. 取子订单明细
                            foreach ($childOrderList as $child){
                                $child->store;
                                $detailList = OrderDetail::where(["order_code"=>$child->order_code])->select();
                                foreach ($detailList as $detail){
                                    $detail->img;
                                }
                                $child["detail"] = $detailList;
                            }
                            $parentOrderList[$parentIndex]["childs"] = $childOrderList;
                        }
                        break;

                }
            } catch (DbException $e) {
                Log::record($e->getMessage());
                return result([],1,$e->getMessage());
            }

            return result($parentOrderList);
        }else {
            return result([],1,"参数错误");
        }
    }

    public function deleteOrder(){
        if(Request::has("order_code")){
            Db::startTrans();
            try {
                $relationship = GoodOrderRelationship::where("order_code_parent",Request::param("order_code"))->select();
                foreach ($relationship as $r){
                    // 恢复商品库存
                    $order_detail = OrderDetail::where("order_code",$r->order_code_child)->find();
                    if($order_detail->option_id == 0){
                        $good = Good::get($order_detail->good_id);
                        if($good && $good->totalcnf == 0){
                            $good->total = $good->total + $order_detail->number;
                            $good->save();
                        }
                    }else {
                        $good = Good::get($order_detail->good_id);
                        if($good && $good->totalcnf == 0){
                            $good_option = GoodOption::get($order_detail->option_id);
                            $good_option->stock = $good_option->stock + $order_detail->number;
                            $good_option->save();
                        }
                    }
                    // 删除订单
                    GoodOrder::where("order_code",$r->order_code_child)->delete();
                    OrderDetail::where("order_code",$r->order_code_child)->delete();
                }
                GoodOrderRelationship::where("order_code_parent",Request::param("order_code"))->delete();

                // 提交事务
                Db::commit();
                return result([],0,"删除订单成功");
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    /**
     * 获取核销订单的信息
     */
    public function getVerificationOrderInfo(){
        if(Request::has("data")){
            if(!Request::has("user_id")){
                return result([],1,"为获取到店主信息");
            }
            $user_id = Request::param("user_id");
            $data = Request::param("data");
            $data = explode("_", $data);
            if(count($data) != 4){
                return result([],1,"不是合法的付款二维码");
            }

            if(md5($data[0] . $data[1] . 'gzkxly') != $data[3]){
                return result([],1,"不是合法的付款二维码");
            }

            $r = [
                "order_code" => $data[0],
                "good_id" => $data[1],
                "option_id" => (array_key_exists(2,$data) && $data[2] == "null") ? 0: $data[2]
            ];
            try {
                $res = OrderDetail::where($r)->find();
                if($res){
                    // 检查订单是否是店主的
                    $order = GoodOrder::where("order_code",$r["order_code"])->find();
                    if($order->getData("pay_status") == 0 || $order->getData("pay_status") == 2){
                        return result([],1,"订单未支付完成");
                    }
                    $stores = Store::where(["user_id"=>$user_id,"id" => $order->store_id])->find();
                    if(!$stores){
                        return result([],1,"订单不属于你，不能核销");
                    }
                    if($res->is_verification == 1){
                        return result([],1,"订单已核销，不能再次核销");
                    }
                    return result($res,0);
                }else {
                    return result([],1,"未找到相应的订单");
                }
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    public function verificationConfirm(){
        if(Request::has("data")){
            if(!Request::has("user_id")){
                return result([],1,"为获取到店主信息");
            }
            $user_id = Request::param("user_id");
            $data = Request::param("data");
            $data = explode("_", $data);
            if(count($data) != 4){
                return result([],1,"不是合法的付款二维码");
            }
            if(md5($data[0] . $data[1] . 'gzkxly') != $data[3]){
                return result([],1,"不是合法的付款二维码");
            }

            $r = [
                "order_code" => $data[0],
                "good_id" => $data[1],
                "option_id" => $data[2] == "null" ? 0: $data[2],
                "offline" => 1,
            ];
            try {
                $res = OrderDetail::where($r)->find();
                if($res){
                    // 检查订单是否是店主的
                    $order = GoodOrder::where("order_code",$r["order_code"])->find();
                    if($order->getData("pay_status") == 0 || $order->getData("pay_status") == 2){
                        return result([],1,"订单未支付完成");
                    }
                    if($order->getData("admin_status") == 0){
                        return result([],1,"订单已被冻结");
                    }
                    if($order->getData("return_status") != 0){
                        return result([],1,"订单已退货");
                    }
                    $stores = Store::where(["user_id"=>$user_id,"id" => $order->store_id])->find();
                    if(!$stores){
                        return result([],1,"订单不属于你，不能核销");
                    }
                    // 更新订单明细的核销状态
                    $res->is_verification = 1;
                    $res->save();
                    // 查看订单明细的商品是否都已经核销了，如果都已经核销，将订单状态和物流状态修改
                    $detail = Db::table("good_order_detail")->whereOr(function($query){
                        $query->whereOr([["offline","<>",1],["is_verification","<>",1]]);
                    })->where("order_code",$r["order_code"])->select();
                    if(!$detail){
                        // 如果查询的结果为空，说明所有的子订单都已核销，则更新good_order表的交易状态
                        $order->save([
                            "pay_status" => 4,
                            "ship_status" => 3
                        ]);
                    }
                    return result([],0,"核销成功");
                }else {
                    return result([],1,"未找到相应的订单");
                }
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    /**
     * 申请退款
     * @return \think\response\Json
     */
    public function refundRequest(){
        if(!Request::has("order_code") || !Request::has("user_id")){
            return result([],1,"参数错误");
        }

        if(!Request::has("reason") || Request::param("reason") == ""){
            return result([],1,"请输入退款原因");
        }

        $order_code = Request::param("order_code");
        $user_id = Request::param("user_id");

        $order = GoodOrder::get($order_code);
        if(!$order){
            return result([],1,"未找到相应的订单");
        }

        if($order->getData("return_status") != 0){
            return result([],1,"订单已进入退款流程，不能发起退款");
        }

        if($order->getData("pay_status") == 0 || $order->getData("pay_status") == 2){
            return result([],1,"订单未支付或已取消，不能退款");
        }

        if($order->getData("pay_status") == 4){
            return result([],1,"订单已确认收货或已核销，不能退款");
        }

        if($order->getData("settle_id") != null){
            return result([],1,"订单已进入结算流程，不能退款");
        }

        if($order->user_id != $user_id){
            return result([],1,"请勿非法操作");
        }

        // 判断是否有商品已经核销
        if(OrderDetail::where(["order_code"=>$order_code,"offline"=>1,"is_verification"=>1])->count() >0){
            return result([],1,"该订单下有商品已经核销，不能退款");
        }

        $order->return_status = 1;
        $order->refund_reason = Request::param("reason");
        Request::has("uploadImg") && $order->refund_img = Request::param("uploadImg");
        if($order->save()){
            return result([],0,"申请退款成功");
        }else {
            return result([],0,"申请退款失败");
        }
    }

    /**
     * 取消申请退款
     * @return \think\response\Json
     */
    public function refundCancel(){
        if(!Request::has("order_code") || !Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $order_code = Request::param("order_code");
        $user_id = Request::param("user_id");

        $order = GoodOrder::get($order_code);
        if(!$order){
            return result([],1,"未找到相应的订单");
        }

        if($order->getData("return_status") != 1 && $order->getData("return_status") != 3){
            return result([],1,"订单没有发起退款，不能取消");
        }

        if($order->getData("pay_status") == 0 || $order->getData("pay_status") == 2){
            return result([],1,"订单未支付或已取消，不能取消退款");
        }

        if($order->getData("pay_status") == 4){
            return result([],1,"订单已确认收货或已核销，不能取消退款");
        }

        if($order->getData("settle_id") != null){
            return result([],1,"订单已进入结算流程，不能取消退款");
        }

        if($order->user_id != $user_id){
            return result([],1,"请勿非法操作");
        }

        // 判断是否有商品已经核销
        if(OrderDetail::where(["order_code"=>$order_code,"offline"=>1,"is_verification"=>1])->count() >0){
            return result([],1,"该订单下有商品已经核销，不能取消退款");
        }

        $order->return_status = 0;
        if($order->save()){
            return result([],0,"取消退款申请成功");
        }else {
            return result([],0,"取消退款申请失败");
        }
    }

    /**
     * 确认收货
     * @return \think\response\Json
     */
    public function receiveConfirm(){
        if(!Request::has("order_code") || !Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $order_code = Request::param("order_code");
        $user_id = Request::param("user_id");

        $order = GoodOrder::get($order_code);
        if(!$order){
            return result([],1,"未找到相应的订单");
        }

        if($order->getData("return_status") == 2 || $order->getData("return_status") == 4){
            return result([],1,"订单已经退款，不能确认收货");
        }

        if($order->getData("pay_status") == 0 || $order->getData("pay_status") == 2){
            return result([],1,"订单未支付或已取消，不能确认收货");
        }

        if($order->getData("pay_status") == 4){
            return result([],1,"订单已确认收货或已核销，不能确认收货");
        }
        if($order->user_id != $user_id){
            return result([],1,"请勿非法操作");
        }
        $order->pay_status = 4;
        $order->ship_status = 3;
        $order->return_status = 0;
        if($order->save()){
            return result([],0,"确认收货成功");
        }else {
            return result([],0,"确认收货失败");
        }
    }

    /**
     * 获取订单详细信息，用于退款，收货等等
     */
    public function getOrderDetail(){
        if(!Request::has("order_code") || !Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $order_code = Request::param("order_code");
        $user_id = Request::param("user_id");

        $order = GoodOrder::get($order_code);
        if(!$order){
            return result([],1,"未找到相应的订单");
        }

        if($order->user_id != $user_id){
            return result([],1,"请勿非法操作");
        }

        $order->store;
        foreach ($order->detail as $detail){
            $detail->img;
        }

        return result($order,0);
    }

    /**
     * 图片上传
     * @return \think\response\Json
     */
    public function uploadImg(){
        $file = request()->file('data');
        $info = $file->validate(['ext'=>'jpg,png'])->move('uploads/');
        if($info){
            return result(conventPath('uploads/' . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result([],0,$info->getError(),0);
        }
    }

    /**
     * 查询物流信息
     */
    public function getOrderTraces(){
        if(!Request::has("order_code") || !Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $order_code = Request::param("order_code");
        $user_id = Request::param("user_id");

        try {
            $order = GoodOrder::where(["order_code"=>$order_code,"user_id"=>$user_id])->find();
            if(!$order){
                return result([],1,"未找到相应的订单或该订单不属于您");
            }

            // 获取快递公司
            $company = ExpressCompany::where("code",$order["shipper_code"])->find();

            if(!$company){
                return result([],1,"快递公司错误");
            }
            // 调用快递鸟API函数
            KdNiao::search($order_code,$order["shipper_code"],$order["logistic_code"]);

            // 获取物流轨迹信息
            $res = GoodOrderShipTraces::get($order_code);
            if(!$res){
                return result([]);
            }else {
                return result(json_decode($res->Traces));
            }

        } catch (\Exception $e) {
            return result([],1,$e->getTraceAsString());
        }

    }

    public function commentRequest(){
        if(!Request::has("comments") || !Request::has("order_code") || !Request::has("user_id")){
            return result([],1,"参数错误");
        }
        $order_code =Request::param("order_code");
        $user_id = Request::param("user_id");
        $comments = Request::param("comments");
        $comments = json_decode($comments);
        foreach ($comments as $comment){
            Log::record($comment);
            if($comment->star == 0){
                return result([],1,"请评分");
            }else if(!$comment->comment || $comment->comment == ""){
                return result([],1,"请输入评论");
            }
        }

        $order = GoodOrder::get($order_code);
        if(!$order){
            return result([],1,"未找到相应的订单");
        }

        Db::startTrans();
        try {
            foreach ($comments as $comment){
                $data = [
                    "good_id" => $comment->good_id,
                    "order_code" => $order_code,
                    "user_id" => $user_id,
                    "detail_id" => $comment->id,
                    "star" => $comment->star,
                    "imgs" => json_encode($comment->uploadImg),
                    "comment" => $comment->comment,
                ];

                GoodComment::create($data);
            }

            $order->comment_status = 1;
            $order->save();


            Db::commit();
            return result([],0,"评论成功");

        } catch (\Exception $e) {
            Db::rollback();
            return result([],1,$e->getMessage());
        }
    }

    public function getAddress(){
        if(!Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");
        try {
            $res = \app\common\model\ReceiveAddress::where("user_id",$user_id)->order("is_default desc")->select();
            return result($res);
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }


}