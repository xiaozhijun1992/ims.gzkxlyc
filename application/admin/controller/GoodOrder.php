<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 16:42
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\OrderDetail;
use think\Exception;
use think\exception\DbException;
use think\facade\Config;
use think\facade\Request;

class GoodOrder extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','商城管理','订单管理']);
        return $this->fetch();
    }

    public function get(){
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

    /**
     *冻结订单
     */
    public function freezeOrder(){
        if(!Request::has("pass") || !Request::has("order_code")){
            return result([],1,"参数不正确");
        }else if( Request::param("pass") !== Config::get("pass.order_freeze_pass")) {
            return result([],1,"口令不正确,请重新输入");
        }
        $order = \app\common\model\GoodOrder::get(Request::param("order_code"));
        if($order){
            $order->admin_status = 0;
            $order->save();
            return result([],0,"已冻结");
        }else {
            return result([],1,"没有找到对应的订单，请刷新页面重试");
        }
    }

    /**
     *取消冻结订单
     */
    public function unFreezeOrder(){
        if(!Request::has("pass") || !Request::has("order_code")){
            return result([],1,"参数不正确");
        }else if( Request::param("pass") !== Config::get("pass.order_freeze_pass")) {
            return result([],1,"口令不正确,请重新输入");
        }
        $order = \app\common\model\GoodOrder::get(Request::param("order_code"));
        if($order){
            $order->admin_status = 1;
            $order->save();
            return result([],0,"已取消冻结");
        }else {
            return result([],1,"没有找到对应的订单，请刷新页面重试");
        }
    }

    /**
     * 订单详情
     */
    public function detail(){
        if(!Request::has("order_code")){
            return "缺少订单号";
        }

        $order_code = Request::param("order_code");
        try {
            $details = OrderDetail::where("order_code",$order_code)->select();
            $this->assign("details",$details);
            return $this->fetch();
        } catch (DbException $e) {
            return $e->getMessage();
        }
    }

}