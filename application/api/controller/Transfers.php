<?php
/**
 * 提现到零钱
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-25
 * Time: 21:23
 */

namespace app\api\controller;


use app\common\controller\ApiBase;
use think\Db;
use think\Exception;
use think\facade\Request;

class Transfers extends ApiBase
{
    /**
     * 获取用户佣金总额
     */
    public function getUserDistributeAmount(){
        if(Request::has("user_id")){
            $user_id = Request::param("user_id");
            try {
                $res = Db::view("user_distribute_amount")->where("id",$user_id)->find();
                return result($res,0);
            } catch (Exception $e) {
                return result([],1,$e->getMessage());
            }

        }else {
            return result([],1,"参数错误");
        }
    }

    /**
     * 拥挤提现到零钱
     */

    public function transfers(){
        if(Request::has("user_id") && Request::has("amount")){
            $user_id = Request::param("user_id");
            // 提现金额
            $amount = Request::param("amount");
            try {
                $data = Db::view("user_distribute_amount")->where("id",$user_id)->find();
                $data1 = Db::view("user_transfers_amount")->where("id",$user_id)->find();
                if(!$data){
                    return result([],1,"未找到您的信息，请联系平台处理");
                }
                // 判断输入的提现金额是不是数字并且只能是两位消失
                if(!is_numeric($amount) || round($amount, 2) != $amount){
                    return result([],1,"输入的提现金额错误");
                }

                if($amount < 1 || $amount > 1000){
                    return result([],1,"付款金额超出限制。低于最小金额1.00元或超过1000.00元。");
                }
                if($amount > ($data["sum_amount"] - $data1["sum_amount"])){
                    return result([],1,"最多提现金额为：" . ($data["sum_amount"] - $data1["sum_amount"]));
                }

                if(!$data["wxapp_openid"]){
                    return result([],1,"用户标识不存在，请联系平台处理");
                }

                // 校验通过后发起请求
                $res = \app\common\controller\WxPay::transfers($data["wxapp_openid"],$amount);
                if($res){
                    return result("",0,"佣金提现成功，请进入微信零钱查看");
                }else {
                    return result("",0,"佣金提现失败");
                }
            } catch (Exception $e) {
                return result([],1,$e->getMessage());
            }

        }else {
            return result([],1,"参数错误");
        }
    }


}