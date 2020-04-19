<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-11
 * Time: 11:08
 */

namespace app\qrcode\controller;


use app\common\controller\WxPay;
use app\common\model\GoodOrder;
use app\common\model\GoodOrderRelationship;
use app\common\model\Store;
use app\common\model\User;
use think\Controller;
use think\Db;
use think\Exception;
use think\facade\Log;
use think\facade\Request;
use think\facade\Env;

require_once Env::get("extend_path") . "wxpay/example/log.php";
require_once Env::get("extend_path") . "wxpay/example/WxPay.Config.php";

class Index extends Controller
{
    public function  index(){
        $openid = WxPay::getOpenid();
        if(Request::has("store_id") && $store = Store::get(Request::param("store_id"))){
            $store_id = Request::param("store_id");
            $this->assign("store_name",$store->name);
            $this->assign("store_id",$store_id);
            $this->assign("openid",$openid);
            return $this->fetch();
        }
        return "非法请求";
    }

    public function authRedirect(){
        return "支付宝授权回调地址";
    }

    /**
     * 微信支付（根据店铺id，金额，openid获取jsApi支付的参数）
     * @return \think\response\Json
     */
    public function getJsApiParameters(){
        if(Request::has("store_id") && Request::has("amount") && Request::has("openid")){
            $store =  Store::get(Request::param("store_id"));
            $amount = Request::param("amount") * 100;
            $openid = Request::param("openid");
            try {
                $user = User::where("wx_openid",$openid)->find();
                if($user){
                    if($user->getData("status") !== 1){
                        return result([],1,"您的账号已被禁用");
                    }
                }else {
                    return result([],1,"未找到会员信息");
                }
            } catch (Exception $e) {
                return result([],1,$e->getMessage());
            }

            if(!is_int(intval($amount))){
                return result([],1,"付款金额不合法");
            }

            $childOrderCode = createChildOrderCode();
            $parentOrderCode = createParentOrderCode();

            Db::startTrans();
            try {
                $dataOrder = [
                    "order_code" => $childOrderCode,
                    "user_id" => $user->id,
                    "store_id" => $store->id,
                    "pay_status" => 0,
                    "amount" => $amount /100,
                    "benefit_amount"=> 0,
                    "pay_amount" => $amount/100,
                    "admin_status" => 1,
                    "pay_type" => 1,
                ];
                $goodOrder = new GoodOrder();
                $goodOrder->save($dataOrder);
                $goodOrderRelationship = new GoodOrderRelationship();
                $goodOrderRelationship->save([
                    "order_code_parent" => $parentOrderCode,
                    "order_code_child" => $childOrderCode,
                    "user_id" => $user->id
                ]);
                Db::commit();
            } catch (\Exception $e) {
                return result([],0,$e->getMessage());
            }

            $input = WxPay::unifiedorder(
                $store->name,
                $parentOrderCode,
                $amount,
                url('qrcode/index/notify','',false,true),
                "JSAPI",
                "",
                "",
                $openid,
                "扫码支付",
                WxPay::attach($store->id,"wxscan")
            );
            $config = new \WxPayConfig();
            try{
                $order = \WxPayApi::unifiedOrder($config, $input);
                $tools = new \JsApiPay();
                $jsApiParameters = $tools->GetJsApiParameters($order);
                Log::record($jsApiParameters);
                return result($jsApiParameters, 0, "");
            }catch (\WxPayException $e){
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"缺少参数",0);
        }
    }

    // 支付成功返回
    public function wxPaySuccess(){
        return $this->fetch();
    }

    /**
     * 微信支付回调地址函数
     */
    public function notify(){
        $logHandler= new \CLogFileHandler(Env::get("extend_path") . "wxpay/logs/".date('Y-m-d').'.log');
        \Log::Init($logHandler, 15);
        $config = new \WxPayConfig();
        \Log::DEBUG("begin notify");
        $notify = new PayNotifyCallBack();
        $notify->Handle($config, false);
    }

}