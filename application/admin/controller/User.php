<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-21
 * Time: 12:32
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\Exception;
use think\facade\Request;

class User extends AdminBase
{
    public function index(){
        $this->assign("nav_list",["首页","平台管理","用户管理"]);
        return $this->fetch();
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            $res = \app\common\model\User::where($data)->limit(($page - 1) * $limit, $limit)->withSum("money","get_amount")->select();
            $count = \app\common\model\User::where($data)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function  toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $user = \app\common\model\User::get($id);
            $user->status = $status;
            $count = $user->save();
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }
    }

    public function order(){
        if(!Request::has("user_id")){
            return "参数错误";
        }else {
            $this->assign("user_id",Request::param("user_id"));
        }
        return $this->fetch();
    }

    public function getOrder(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        $data1 = array_filter($data,function($val){
            if($val == ""){
                return false;
            }else {
                return true;
            }
        });
        try {
            $res = \app\common\model\GoodOrder::where($data1)->limit(($page - 1) * $limit, $limit)->order("create_time desc")->select();
            foreach ($res as $r){
                $r->store;
                $r->user;
                $r->ship;
            }
            $count = \app\common\model\GoodOrder::where($data1)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

}