<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-11
 * Time: 19:41
 */

namespace app\qrcode\controller;

use app\common\controller\User;
use app\common\controller\WxPay;
use app\common\model\Store;
use think\exception\DbException;
use think\facade\Env;
use think\facade\Log;

require_once Env::get("extend_path") .  "wxpay/lib/WxPay.Api.php";
require_once Env::get("extend_path") . 'wxpay/lib/WxPay.Notify.php';
require_once Env::get("extend_path") . "wxpay/example/WxPay.Config.php";
require_once Env::get("extend_path") . 'wxpay/example/log.php';

class PayNotifyCallBack extends \WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);

        $config = new \WxPayConfig();
        try{
            $result = \WxPayApi::orderQuery($config, $input);
        }catch (\WxPayException $e){
            return false;
        }
        \Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    /**
     *
     * 回包前的回调方法
     * 业务可以继承该方法，打印日志方便定位
     * @param string $xmlData 返回的xml参数
     *
     **/
    public function LogAfterProcess($xmlData)
    {
        \Log::DEBUG("call back， return xml:" . $xmlData);
        return;
    }

    //重写回调处理函数
    /**
     * @param \WxPayNotifyResults 回调解释出的参数
     * @param \WxPayConfigInterface $config
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return bool true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        //TODO 1、进行参数校验
        if(!array_key_exists("return_code", $data) ||(array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
            $msg = "异常异常";
            Log::record("微信支付异步通知返回码不正确");
            return false;
        }
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            Log::record("微信支付异步通知中没有交易流水号");
            return false;
        }

        //TODO 2、进行签名验证
        try {
            $checkResult = $objData->CheckSign($config);
            if($checkResult == false){
                //签名错误
                \Log::ERROR("签名错误...");
                Log::record("微信支付异步通知数据签名错误");
                return false;
            }
        } catch(\Exception $e) {
            Log::record(json_encode($e));
            \Log::ERROR(json_encode($e));
        }

        //TODO 3、处理业务逻辑
        $dataLog = json_encode($data);
        \Log::DEBUG("call back:" . $dataLog);
        Log::record($dataLog);

        // 查询是否已经记录在数据表中
        $attach = json_decode($data["attach"],true);
        $store_id = $attach["store_id"];
        try{
            $user = null;
            // 小程序支付
            if($attach["pay_type"] == "orderpay"){
                $user = \app\common\model\User::where("wxapp_openid",$data["openid"])->find();
            }else if($attach["pay_type"] == "wxscan"){
                // 扫码支付
                $user = \app\common\model\User::where("wx_openid",$data["openid"])->find();
            }

            $data["user_id"] = $user->id;
            // 一级代理和二级代理都为0表示用户是新加入的，并且是扫码支付的
            if($user->one_level_user_id == 0 && $user->two_level_user_id == 0 && $attach["pay_type"] == "wxscan"){
                // 查找店铺对应的用户ID，作为新加入会员的一级代理
                $store = Store::get($store_id);
                Log::record("用户:" . json_encode($user));
                Log::record("店主:" . json_encode($store));
                // 如果店主自己扫码就不更新
                if($user->id !== $store->user_id){
                    $one = $store->user_id;
                    Log::record("一级代理用户ID：" . $one);
                    // 查找一级代理用户
                    $level_one_user = \app\common\model\User::get($one);

                    // 获取一级代理用户的上级
                    $level_two_user = \app\common\model\User::get($level_one_user->one_level_user_id);
                    if($level_two_user){
                        $two = $level_two_user->id;
                    }else {
                        $two = null;
                    }
                    Log::record("二级代理用户ID：" . $two);
                    User::updateUserUpLevel($user->id,$one,$two);
                }
            }
            //查询订单，判断订单真实性
            if(!$this->Queryorder($data["transaction_id"])){
                $msg = "订单查询失败";
                Log::record("订单查询失败");
                return false;
            }else {
                if(WxPay::successProcess($data)){
                    return true;
                }else {
                    Log::record("执行successProcess失败");
                    $msg = "执行successProcess失败";
                    return false;
                }
            }
        }catch (DbException $e){
            $msg = "更新数据库失败";
            Log::record("根据openID没有找到对应的会员" . $data["openid"]);
            return false;
        }
    }

}