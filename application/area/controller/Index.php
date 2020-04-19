<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/4/30
 * Time: 20:54
 */

namespace app\area\controller;


use app\api\controller\Weixin;
use app\common\controller\AreaBase;
use app\common\model\Area;
use app\common\model\AreaManager;
use app\common\model\User;
use think\Exception;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Config;
use think\facade\Session;

class Index extends AreaBase
{
    public function index(){
        try {
            $bankAccount = \app\common\model\AreaBankAccount::where("area_id",Session::get("area_info.id"))->find();
            if($bankAccount){
                $this->assign("hasBankAccount",true);
            }else {
                $this->assign("hasBankAccount",false);
            }
        } catch (DbException $e) {
            $this->error($e->getMessage());
        }
        return $this->fetch();
    }

    public function welcome(){
        $this->assign("nav_list",["首页","统计信息",]);
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
                        $area_manager = AreaManager::where("user_id",$user->id)->find();
                        if($area_manager){
                            $area = Area::where("manager_id",$user->id)->select();
                            if($area->count() > 0) {
                                //如果用户只有一个区域，就直接进入后台
                                if($area->count() === 1) {
                                    Session::set("area_info", $area[0]);
                                    Session::set("uid",$user->id);
                                    $this->success("登录成功", "/area/index/index");
                                }else {
                                    // 如果用户有多个区域，进入选择区域页面
                                    Session::set("areas",$area);
                                    Session::set("uid",$user->id);
                                    $this->redirect("/area/index/selectArea");
                                }
                            }else {
                                $this->error("您还没有对应的代理区域，请先申请",'/area/index/login',"",8);
                            }
                        }else {
                            $this->error("您还不是商城的代理人，请先申请",'/area/index/login',"",8);
                        }
                    }else {
                        $this->error("没有找到对应的用户信息",'/area/index/login',"",8);
                    }
                }catch (Exception $e){
                    $this->error($e->getMessage(),'/area/index/login',"",8);
                }
            }else {
                $this->error("登录失败",'/area/index/login');
            }
        }else {
            $this->assign("state",newWeiXinState());
            return $this->fetch();
        }

    }


    public function selectArea(){
        if(Request::param("area_id")) {
            $id = Request::param("area_id");
            foreach (Session::get('areas') as $area){
                if($area->id == $id){
                    Session::delete("areas");
                    Session::set("area_info",$area);
                    $this->redirect("/area/index");
                }
            }
        }
        return $this->fetch();
    }

    public function logout(){
        Session::clear();
        $this->success("已退出登录",'/area/index/login');
    }

}