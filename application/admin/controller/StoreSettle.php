<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-06
 * Time: 19:05
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\StoreBankAccount;
use app\common\model\StoreSettleRecord;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Session;

class StoreSettle extends AdminBase
{
    public function index(){
        $this->assign("nav_list",["首页","财务管理","店铺付款"]);
        return $this->fetch();
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
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

    public function del(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $settle = StoreSettleRecord::get($id);
            if($settle){
                if($settle->status != 1){
                    Db::startTrans();
                    try {
                        // 恢复订单的结算状态和结算ID
                        \app\common\model\GoodOrder::where(["settle_id"=>$id,"store_id" => $settle->store_id])->update(["settle_id" => null,"settle_status" => 0]);
                        $settle->delete();
                        // 提交事务
                        Db::commit();
                        return result([],0,"删除成功");
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                        return result([],1,"操作失败");
                    }

                }else {
                    return result([],1,"记录的状态不符合要求，不能删除，请刷新重试");
                }
            }else {
                return result([],1,"未找到要操作的记录");
            }
        }else {
            return result([],1,"参数不正确");
        }
    }

    public function settle(){
        if(Request::isPost() && Request::has("id") && Request::has("settle_no")){
            $id = Request::param("id");
            $settle_no = Request::param("settle_no");
            $settle = StoreSettleRecord::get($id);
            if($settle){
                if($settle->status != 1){
                    Db::startTrans();
                    try {
                        // 修改订单的结算状态和结算ID
                        \app\common\model\GoodOrder::where(["settle_id"=>$id,"store_id" => $settle->store_id])->update(["settle_status" => 1]);
                        $settle->save([
                            "status" => 1,
                            "settle_no" => $settle_no,
                            "settle_date" => date("Y-m-d H:i:s")
                        ]);
                        // 提交事务
                        Db::commit();
                        return result([],0,"结算成功");
                    } catch (\Exception $e) {
                        // 回滚事务
                        Db::rollback();
                        return result([],1,"操作失败");
                    }

                }else {
                    return result([],1,"记录的状态不符合要求，不能删除，请刷新重试");
                }
            }else {
                return result([],1,"未找到要操作的记录");
            }
        }else {
            return result([],1,"参数不正确");
        }
    }

    /**
     * 自动生成店铺结算单
     */
    public function autCreateStoreSettleRecord(){
        try {
            // 提取7天前交易订单为支付状态为已完成，物流状态为已签收，管理状态为正常的订单中的店铺ID（store_id）
            $stores =  Db::table("good_order")->where([
                "pay_status" => 4,
                "ship_status" => 3,
                "admin_status" => 1,
                "settle_status" => 0,
                "return_status" => 0,
                "settle_id" => null,
            ])->where("delivery_date","<=",date('Y-m-d',strtotime("-7 day")) . " 23:59:59")
                ->whereNotIn("order_code", function($query){
                    $query->table("good_order_detail")->where(["offline" => 1,"is_verification" => 0])->field("order_code");
                })->distinct("true")->field("store_id")
                ->select();
            if(count($stores) <= 0){
                return result([],1,"没有要生成的店铺结算单");
            }
            foreach ($stores as $store){
                // 插入店铺结算单记录表
                $amount = \app\common\model\GoodOrder::where([
                    "pay_status" => 4,
                    "ship_status" => 3,
                    "admin_status" => 1,
                    "return_status" => 0,
                    "store_id" => $store["store_id"],
                    "settle_status" => 0,
                    "settle_id" => null,
                ])->where("delivery_date","<=",date('Y-m-d',strtotime("-7 day")) . " 23:59:59")
                    ->whereNotIn("order_code", function($query){
                        $query->table("good_order_detail")->where(["offline" => 1,"is_verification" => 0])->field("order_code");
                    })->sum("get_amount");

                $bankAccount = StoreBankAccount::where("store_id",$store["store_id"])->find();
                // 若果有结算账号则生成结算单
                if($bankAccount){
                    $settle = StoreSettleRecord::create([
                        "amount" => $amount,
                        "store_id" => $store["store_id"],
                        "settle_user" => Session::get("admin_info.name"),
                        "status" => 0,
                        "bank" => $bankAccount->bank,
                        "name" => $bankAccount->name,
                        "account" => $bankAccount->account
                    ]);
                    $settle_id = $settle->id;
                    // 更新订单结算状态
                    Db::startTrans();
                    Db::execute("UPDATE good_order  SET settle_id = :settle_id  WHERE  pay_status = 4 AND return_status = 0  AND ship_status = 3  AND admin_status = 1  AND store_id = :store_id  AND settle_status = 0  AND delivery_date <= :delivery_date and order_code not in (select order_code from good_order_detail where offline = 1 and is_verification = 0)",
                        [
                            'settle_id' => $settle_id,
                            'store_id' => $store["store_id"],
                            "delivery_date" => date('Y-m-d',strtotime("-7 day")) . " 23:59:59"
                        ]
                    );
                    Db::commit();
                }

            }
            return result($stores,0,"自动生成店铺收款单成功");
        } catch (Exception $e) {
            Db::rollback();
            return result([],1,$e->getMessage());
        }

    }

    /**
     * 店铺结算单明细
     */

    public function detail(){
        if(!Request::has("id")){
            return "参数错误";
        }

        $settle_id = Request::param("id");
        try {
            $orders = \app\common\model\GoodOrder::where("settle_id",$settle_id)->select();
            $this->assign("orders",$orders);
            return $this->fetch();
        } catch (DbException $e) {
            return $e->getMessage();
        }
    }

}