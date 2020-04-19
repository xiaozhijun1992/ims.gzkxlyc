<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-26
 * Time: 20:00
 */

namespace app\area\controller;


use app\common\controller\AreaBase;
use think\Db;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Session;

class Distribute extends AreaBase
{
    public function index(){
        $this->assign("nav_list",["区域代理","我的财务","佣金明细"]);
        return $this->fetch();
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            $data["area_id"] = Session::get("area_info.id");
            $res = Db::view("area_cansettle_distribute_record")->where("area_id",Session::get("area_info.id"))
                ->order("create_time desc")
                ->limit(($page - 1) * $limit, $limit)->select();
            foreach ( $res as $index=>$record){
                $store = \app\common\model\Store::get($record["store_id"]);
                $res[$index]["store"] = $store;
            }
            $count = Db::view("area_cansettle_distribute_record")->where("area_id",Session::get("area_info.id"))->count();
            return result($res,0,"",$count);
        }catch (DbException $e){
            return result([],1,$e->getMessage(),0);
        }
    }

}