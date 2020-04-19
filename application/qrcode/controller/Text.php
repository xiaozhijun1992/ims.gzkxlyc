<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-10
 * Time: 19:18
 */

namespace app\qrcode\controller;


use app\api\controller\KdNiao;
use app\common\controller\WxPay;
use app\common\model\DistributeRecord;
use app\common\model\User;
use think\Controller;
use think\Exception;
use think\facade\Config;
use think\facade\Env;
use think\facade\Log;
use think\facade\Request;
use app\common\controller\WXBizDataCrypt;

require_once Env::get("extend_path") . "wxpay/lib/WxPay.Api.php";
require_once Env::get("extend_path") . "wxpay/example/WxPay.NativePay.php";
require_once Env::get("extend_path") . "wxpay/example/log.php";

class Text extends Controller
{
    public function index(){
        if(isWxClient()){
            return "微信浏览器";
        }else if(isAliClient()){
            return "支付宝浏览器";
        }else {
            return "其他浏览器";
        }
    }

    public function weixin(){
        $logHandler= new \CLogFileHandler(Env::get("extend_path") . "wxpay/logs/".date('Y-m-d').'.log');
        $log = \Log::Init($logHandler, 15);

        $notify = new \NativePay();

        $input = WxPay::unifiedorder(
            "凉都美食",
            createChildOrderCode(),
            1,
            url('qrcode/index/notify',"",false,true),
            "NATIVE",
            "商品标签",
            "商品ID",
            "",
            "商品详情"
        );

        $result = $notify->GetPayUrl($input);
        if($result["return_code"] == 'SUCCESS' && $result["result_code"] == 'SUCCESS'){
            $this->assign("url", $result["code_url"]);
            return $this->fetch();
        }else {
            return $result["err_code_des"];
        }
    }

    public function test(){

        $a = 0.1;
        $b = 0.7;
        echo round(99 * 5 / 1000,2);
        Log::record(round(98 * 5 / 1000,2));
        Log::record(bcdiv(99 * 5,1000,2));
        return dump(bcadd($a,$b,2));
    }

    public function searchKd(){
        try {
            $res =  KdNiao::search("123456","ZTO","640041334612");
            return dump($res);
        } catch (\Exception $e) {
            return result([],1,$e->getMessage());
        }
    }

    public function refund(){
        if(!Request::has("order_code")){
            return "缺少参数";
        }

        try {
            return dump(WxPay::refund(Request::param("order_code")));
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function aa(){
        $appid = Config::get("wxapp.appid");
        $sessionKey = '4PTXbEijv5dIuufQDKLooQ==';
        $encryptedData="21QvkObebwdDCOQyTw4t3L4iE/ysjV/EQK0jSDCYecU8yt9ujGO1A8eezkEe0Ykab09NJjg+3CO+/ckI5TS1T7xfclysOyJaHJsG60tJhQSYxVUKsMTaEOTuz+pACI1q4xOMhKnGrGavKJe5OZQ59h4uFWx6PlRBXZYP/MpzFzOwggwJCzKBdnWrI9dXQxKn+mhkkCJtUuX7Y8hBbeJ0yW5VARMDTYoWgVM0dMJDOTgH3Wwf2RVfIWnuqTMfQBEfxNxOvz4ndh9fKJtDXfjOqzmM3eAoVu14S9+QhGdeVjzfTEOhEMj5RJ0T0WSy9Wm96rqYDcHSCm0O4pRrLYIHsRWoWX8UOJa0tA/pUlUb4wHumwejc7LhEey2790eRJLdfPGBsOX0hrwTbFNA5E6kizkQ50nPBjGdqwOJn88WmmIlIgYVR1zgnhm4CdqFtMlrxQielSeRKB2NufP5kg99BH5uxkqQTKuYC0G5w1P7MXv5sTskrWJz7rY7QNux0N0+b8ULZlnmLQw4V057zuo6Gw==";
        $iv = 'IHqWSAz1AxKYzlnpsjHnhg==';
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $dataTmp );
        if ($errCode == 0) {
            $data = json_decode($dataTmp);
            $data1 = Request::param();
            $data1["bb"] = $data->openId;
            dump($data1);
            dump($data);
        } else {
            print($errCode . "\n");
        }
    }

    public function newUser(){
        $data = json_decode(Request::param("data"));
        dump($data);
        // 判断是否存在unionid
        if(!isset($data->wx_unionid) && isset($data->sessionKey) && isset($data->encryptedData) && isset($data->iv)){
            echo "进入了";
            $appid = Config::get("wxapp.appid");
            $sessionKey = $data->sessionKey;
            $encryptedData = $data->encryptedData;
            $iv = $data->iv;
            $pc = new WXBizDataCrypt($appid, $sessionKey);
            $errCode = $pc->decryptData($encryptedData, $iv, $dataTmp );
            if ($errCode == 0) {
                $dataTmp = json_decode($dataTmp);
                dump($dataTmp);
                $data->wxapp_openid = $dataTmp->openId;
                $data->wx_unionid = $dataTmp->unionId;
                dump($data);
            } else {
                return result([],1,$errCode);
            }
        }else {
            return "没有进入";
        }
    }

}