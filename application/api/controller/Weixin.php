<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-26
 * Time: 12:22
 */

namespace app\api\controller;


use app\common\model\WeiXinAuthTemp;
use think\Controller;
use think\facade\Config;
use think\facade\Request;


class Weixin extends Controller
{
    /**
     * 改函数用于微信授权回调获取code，也就是设置redirect_uri时设置该函数的访问地址
     * 微信平台传回code后，再通过code去获取用户的openid和unionid，并存入weixin_auth_temp表中
     * state是在发起认证请求的时候携带的参数
     * 技术文档：https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419316505&token=&lang=zh_CN
     * @return mixed
     */
    public function authCallback(){
        $code = Request::param("code");
        if($code){
            $res = self::getOpenIdAndUnionIdByCode(Config::get("wxweb.appid"),Config::get("wxweb.secret"),$code);
            $state = Request::param("state");
            $res = json_decode($res);
            $data = [
                "state" => $state,
                "openid" => $res->openid,
                "unionid" => $res->unionid,
            ];
            $result = WeiXinAuthTemp::create($data);
            if($result){
                return "<div style='text-align: center;color: green;'>已授权，请稍等...</div>";
            }else {
                return "<div style='text-align: center;color: red;'>授权失败</div>";
            }
        }else {
            return "<div style='text-align: center;color: red;'>授权失败</div>";
        }
    }

    /**
     * 通过code获取用户信息（openid/unionid）
     * @param $appid string
     * @param $secret string
     * @param $code string
     * @param string $grant_type string
     * @return bool|string
     */
    static function getOpenIdAndUnionIdByCode($appid,$secret,$code,$grant_type="authorization_code"){
        $res = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $appid . '&secret=' . $secret . '&code=' . $code . '&grant_type=' . $grant_type);
        return $res;
    }

    public function getWeixinAuth(){
        if(Request::isPost()){
            if(Request::param("state")){
                $state = Request::param("state");
                $res = WeiXinAuthTemp::get($state);
                if($res){
                    return result($res,0,"",0);
                }else {
                    return result([],1,"参数不合法",0);
                }
            }else {
                return result([],1,"缺少参数",0);
            }
        }else {
            return result([],1,"请求非法",0);
        }
    }

}