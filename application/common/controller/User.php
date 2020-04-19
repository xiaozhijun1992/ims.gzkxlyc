<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-11
 * Time: 23:42
 */

namespace app\common\controller;


use think\exception\DbException;

class User
{
    /**
     * 更新或新增用户信息（微信授权）
     * @param $data
     * @return bool
     */
    static function updateUserFromWeixin($data){
        try{
            $user = \app\common\model\User::where("wx_unionid",$data["unionid"])->find();
            if(!$user){
                $data["wx_openid"] = $data["openid"];
                $data["wx_unionid"] = $data["unionid"];
                $data["nick_name"] = $data["nickname"];
                $data["avator"] = $data["headimgurl"];
                $data["one_level_user_id"] = 0;
                $data["two_level_user_id"] = 0;
                $data["province"] = unicode_decode($data["province"]);
                $data["city"] = unicode_decode($data["city"]);
                $data["country"] = unicode_decode($data["country"]);
                $data["code"] = newUserCode();
                $res = \app\common\model\User::create($data);
            }else {
                $user->avator == null && $data["avator"] = $data["headimgurl"];
                $user->code == null &&  $data["code"] = newUserCode();
                $data["wx_openid"] = $data["openid"];
                $data["province"] = unicode_decode($data["province"]);
                $data["city"] = unicode_decode($data["city"]);
                $data["country"] = unicode_decode($data["country"]);
                $res =$user->save($data);
            }
            if($res){
                return true;
            }else {
                return false;
            }
        }catch (DbException $e){
            return false;
        }
    }

    static function updateUserUpLevel($user_id,$one,$two){
        $user = \app\common\model\User::get($user_id);
        $res = $user->save([
            "one_level_user_id" => $one,
            "two_level_user_id" => $two
        ]);
        if($res){
            return true;
        }else {
            return false;
        }
    }

}