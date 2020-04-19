<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-07
 * Time: 19:20
 */

namespace app\area\controller;


use app\common\controller\AreaBase;
use app\common\controller\StoreBase;
use app\common\model\AreaSettleRecord;
use app\common\model\StoreSettleRecord;
use think\Exception;
use think\facade\Request;
use think\facade\Session;

class Settle extends AreaBase
{
    public function index(){
        $this->assign("nav_list",["店铺首页","我的财务","区域结算单"]);
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
            $res = AreaSettleRecord::where($data)->limit(($page - 1) * $limit, $limit)->select();
            foreach ( $res as $record){
                $record->area;
            }
            $count = AreaSettleRecord::where($data)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

}