<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-07
 * Time: 19:20
 */

namespace app\store\controller;


use app\common\controller\StoreBase;
use app\common\model\GoodOrder;
use app\common\model\StoreSettleRecord;
use think\Exception;
use think\exception\DbException;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;

class Settle extends StoreBase
{
    public function index(){
        $this->assign("nav_list",["店铺首页","我的财务","店铺结算单"]);
        return $this->fetch();
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            $data["store_id"] = Session::get("store_info.id");
            $res = StoreSettleRecord::where($data)->limit(($page - 1) * $limit, $limit)->select();
            foreach ( $res as $record){
                $record->store;
            }
            $count = StoreSettleRecord::where($data)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    /**
     * 展示店铺支付二维码
     * @return mixed|string
     */
    public function qrcodePayment(){
        $this->assign("nav_list",["店铺首页","我的信息","店铺收款二维码"]);
        $url = Request::domain() . '/qrcode/index?store_id=' . Session::get("store_info.id");
        $this->assign("url",$url);
        return $this->fetch();
    }

    /**
     * 结算单明细
     */

    public function detail(){
        if(!Request::has("id")){
            return "参数错误";
        }

        $settle_id = Request::param("id");
        try {
            $orders = GoodOrder::where("settle_id",$settle_id)->select();
            $this->assign("orders",$orders);
            return $this->fetch();
        } catch (DbException $e) {
            return $e->getMessage();
        }
    }

}