<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-08
 * Time: 23:52
 */

namespace app\admin\controller;
use app\common\controller\AdminBase;
use think\facade\Request;
use app\api\controller\Weixin;
use think\facade\Config;
use app\common\model\User;
use think\Exception;
use think\facade\Session;

class Index extends AdminBase
{
    public function index(){
        return $this->fetch();
    }

    public function welcome(){
        $this->assign('nav_list',['首页','我的桌面']);
        $store_count = \app\common\model\Store::count("id");
        $this->assign("store_count",$store_count);
        $user_count = User::count("id");
        $this->assign("user_count",$user_count);
        $order_count = \app\common\model\GoodOrder::count(1);
        $this->assign("order_count",$order_count);
        $good_category = \app\common\model\GoodCategory::where("pid","<>",0)->count(1);
        $this->assign("good_category",$good_category);
        $industry_count = \app\common\model\Industries::count(1);
        $this->assign("industry_count",$industry_count);
        $salesman_count = \app\common\model\Salesman::count(1);
        $this->assign("salesman_count",$salesman_count);
        return $this->fetch();
    }

    public function login(){
        if(Request::param("code")){
            $res = Weixin::getOpenIdAndUnionIdByCode(Config::get("wxweb.appid"),Config::get("wxweb.secret"),Request::param("code"));
            $res = json_decode($res);
            if ($res->unionid){
                try{
                    $user = User::where("wx_unionid",$res->unionid)->find();
                    if($user){
                        $member = \app\common\model\Member::where("user_id",$user->id)->where("status",1)->find();
                        if($member){
                            Session::set("admin_info", $member);
                            Session::set("uid",$user->id);
                            $this->success("登录成功", "/admin/index/index");
                        }else {
                            $this->error("非法登录",'/admin/index/login',"",8);
                        }
                    }else {
                        $this->error("没有找到对应的用户信息",'/admin/index/login',"",8);
                    }
                }catch (Exception $e){
                    $this->error($e->getMessage(),'/admin/index/login',"",8);
                }
            }else {
                $this->error("登录失败",'/admin/index/login');
            }
        }else {
            $this->assign("state",newWeiXinState());
            return $this->fetch();
        }
    }

    public function logout(){
        Session::clear();
        $this->success("退出成功","/admin/index/login");
    }

}