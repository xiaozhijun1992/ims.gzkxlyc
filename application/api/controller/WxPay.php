<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-24
 * Time: 08:34
 */

namespace app\api\controller;


use think\Controller;
use think\facade\Env;


require_once Env::get("extend_path") . "wxpay/example/log.php";
require_once Env::get("extend_path") . "wxpay/example/WxPay.Config.WxApp.php";

class WxPay extends Controller
{

    /**
     * 根据订单(good_order表)获取支付信息
     * @param $data array [order_code,amount,wxapp_openid]
     * @return array
     */
    static function getWxAppPayParameters($data){
        $input = \app\common\controller\WxPay::unifiedorder(
            "快享立赢商城",
            $data["order_code"],
            $data["amount"] * 100,
            url('qrcode/index/notify','',false,true),
            "JSAPI",
            "",
            "",
            $data["wxapp_openid"],
            "小程序支付",
            \app\common\controller\WxPay::attach(0,"orderpay")
        );
        $config = new \WxAppPayConfig();
		
        try{
            $order = \WxPayApi::unifiedOrder($config, $input);
            $tools = new \JsApiPay();
            $jsApiParameters = $tools->GetJsApiParameters($order);
			
            return [
                "data" =>json_decode($jsApiParameters),
                "code" => 0,
                "message" => ""
            ];
        }catch (\WxPayException $e){
            return [
                "data" => [],
                "code" => 1,
                "message" => $e->getMessage()
            ];
        }

    }

}