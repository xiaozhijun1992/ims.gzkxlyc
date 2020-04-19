<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-08
 * Time: 18:17
 */

namespace app\store\controller;


use app\api\controller\KdNiao;
use app\common\controller\StoreBase;
use app\common\controller\WxPay;
use app\common\model\ExpressCompany;
use app\common\model\GoodOrder;
use app\common\model\GoodOrderRelationship;
use app\common\model\GoodOrderShipTraces;
use app\common\model\OrderDetail;
use app\common\model\StoreSender;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;

class Order extends StoreBase
{
    public function index(){
        $this->assign("nav_list",["店铺首页","我的商城","订单管理"]);
        return $this->fetch();
    }

    public function get(){
        $store_id = Session::get("store_info.id");
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = [];
        $start = null;
        $end = null;
        Request::has("key") && $data = Request::param("key");
        array_key_exists("start",$data) && $start = $data["start"];
        array_key_exists("end",$data) && $end = $data["end"] . ' 23:59:59';
        Log::record($end);
        unset($data["start"]);
        unset($data["end"]);
        if(array_key_exists("pay_status",$data) && $data["pay_status"] == ""){
            unset($data["pay_status"]);
        }
        if(array_key_exists("pay_type",$data) && $data["pay_type"] == ""){
            unset($data["pay_type"]);
        }
        if(array_key_exists("order_code",$data) && $data["order_code"] == ""){
            unset($data["order_code"]);
        }
        if(array_key_exists("admin_status",$data) && $data["admin_status"] == ""){
            unset($data["admin_status"]);
        }
        try {
            $res = GoodOrder::where("store_id", $store_id)
                ->where('create_time', '>= time', $start ? $start:'1990-11-21')
                ->where('create_time', '<= time', $end ? $end: date("Y-m-d H:i:s",time()))
                ->where($data)
                ->limit(($page-1)*$limit,$limit)
                ->withCount("onlineDetail")
                ->order("create_time desc")
                ->select();

            foreach ($res as $order){
                $order->ship;
                $order->user;
            }
            $count = GoodOrder::where("store_id", $store_id)
                ->where('create_time', '>= time', $start ? $start:'1990-11-21')
                ->where('create_time', '<= time', $end ? $end: date("Y-m-d H:i:s",time()))
                ->where($data)
                ->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    /**
     * 删除订单
     */
    public function delete(){
        // 检查是否有店铺ID参数
        if(!Request::has("order_code")){
            return result([],1,"参数错误");
        }

        $order_code = Request::param("order_code");

        Db::startTrans();
        try {
            $order = GoodOrder::get($order_code);
            if(!$order){
                return result([],1,"删除失败，未找到相应的订单，请刷新重试");
            }

            Log::record('$order->admin_status:' . $order->admin_status);
            if($order->getData('admin_status') == 0){
                return result([],1,"删除失败，订单已被冻结");
            }

            if($order->pay_status != 0 && $order->pay_status !== 2){
                return result([],1,"删除失败，订单已支付");
            }

            // 删除订单
            $order->delete();
            // 删除订单明细
            OrderDetail::where("order_code",$order_code)->update(["delete_time"=>date('Y-m-d H:i:s', time())]);

            // 删除父子订单表
            GoodOrderRelationship::where("order_code_child",$order_code)->update(["delete_time"=>date('Y-m-d H:i:s', time())]);

            Db::commit();
            return result([],0,"删除订单成功");
        } catch (\Exception $e) {
            Db::rollback();
            return result([],1,$e->getTraceAsString());
        }

    }

    public function shipConfirm(){
        if(!Request::has("order_code")){
            return "参数错误";
        }

        $order_code = Request::param("order_code");
        try {
            $order = GoodOrder::where("order_code",$order_code)->find();
            if(!$order){
                return "订单不存在";
            }

            if($order["store_id"] !== Session::get("store_info.id")){
                return "该订单不属于您的店铺";
            }

            $details = OrderDetail::where("order_code",$order_code)->select()->order("offline");
            $senders = StoreSender::where("store_id",Session::get("store_info.id"))->select();
            $companys = ExpressCompany::where("status",1)->select();

            $this->assign("order",$order);
            $this->assign("details",$details);
            $this->assign("senders",$senders);
            $this->assign("companys",$companys);


            return $this->fetch();

        } catch (DbException $e) {
            return $e->getMessage();
        }
    }

    /**
     * 订单发货
     */
    public function orderShip(){
        $data = Request::param();
        $validate = Validate::make([
            "order_code" => 'require',
            "sender_id|发件地址" => 'require|number',
            "shipper_code|快递公司" => 'require',
            "logistic_code|快递单号" => 'require|unique:GoodOrder'
        ]);

        if(!$validate->check($data)){
            return result([],1,$validate->getError());
        }

        try {
            $order = GoodOrder::where("order_code",$data["order_code"])->find();
            if(!$order){
                return result([],1,"未找到相应的订单");
            }

            if($order["store_id"] != Session::get("store_info.id")) {
                return result([],1,"该订单不属于您");
            }

            if($order["pay_status"] != '已支付'){
                return result([],1,"订单状态不符合：该订单" . $order["pay_status"]);
            }

            $sender = StoreSender::get($data["sender_id"]);
            if(!$sender){
                return result([],1,"未找到发件地址");
            }

            $tData = [
                "pay_status" => 3, // 状态改成已发货
                "shipper_code" => $data["shipper_code"],
                "logistic_code" => $data["logistic_code"],
                "sender_name" => $sender->name,
                "sender_mobile" => $sender->mobile,
                "sender_province" => $sender->province_name,
                "sender_city" => $sender->city_name,
                "sender_area" => $sender->exp_area_name,
                "sender_address" => $sender->address,
                "ship_time" => date("Y-m-d H:i:s"),
            ];

            if($order->save($tData)){


                return result([],0,"发货成功");
            }else {
                return result([],1,"发货失败");
            }


        } catch (DbException $e) {
            return result([],1,$e->getMessage());
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

    /**
     * 物流信息
     */
    public function getOrderTraces(){
        if(!Request::has("order_code")){
            return "参数错误";
        }

        $order_code = Request::param("order_code");
        $order = GoodOrder::get($order_code);
        if(!$order){
            return "未找到相应的订单";
        }

        if($order->store_id !== Session::get("store_info.id")){
            return "该订单不属于您，不能查询物流信息";
        }

        try {
            // 获取快递公司
            $company = ExpressCompany::where("code",$order->shipper_code)->find();
            $this->assign("company",$company);

            // 调用快递鸟API函数
            KdNiao::search($order_code,$order->shipper_code,$order->logistic_code);

            // 获取物流轨迹信息
            $res = GoodOrderShipTraces::get($order_code);
            if(!$res || count(json_decode($res->Traces)) <= 0){
                $this->assign("traces",[]);
            }else {
                $this->assign("traces",json_decode($res->Traces));
            }
            return $this->fetch();

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * 退款接口
     * @return mixed
     */
    public function refund(){
        if(!Request::has("order_code")){
            return result([],1,"缺少参数");
        }
        try {
            if(WxPay::refund(Request::param("order_code"))){
                return result([],0,"退款成功");
            }else {
                return result([],1,"未知错误");
            }
        } catch (\Exception $e) {
            return result([],1,$e->getMessage());
        }

    }

    public function returnReject(){
        if(!Request::has("order_code")){
            return result([],1,"缺少参数");
        }
        try {
            $order = GoodOrder::get(Request::param("order_code"));
            if(!$order){
                return result([],1,"没有找到相应的订单");
            }

            if($order->getData("return_status") != 1){
                return result([],1,"用户没有发起退款请求，操作失败");
            }

            if($order->getData("pay_status") == 4){
                return result([],1,"用户已经确认收货,操作失败");
            }

            if($order->getData("settle_id") != null){
                return result([],1,"该订单已经进入结算流程,操作失败");
            }

            $order->return_status = 3;


            if($order->save()){
                return result([],0,"拒绝退款成功");
            }else {
                return result([],1,"拒绝退款失败");
            }
        } catch (\Exception $e) {
            return result([],1,$e->getMessage());
        }
    }

}