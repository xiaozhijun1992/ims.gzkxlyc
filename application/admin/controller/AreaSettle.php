<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-26
 * Time: 08:49
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\AreaBankAccount;
use app\common\model\AreaSettleRecord;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Session;

class AreaSettle extends AdminBase
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
            $res = AreaSettleRecord::where($data)->limit(($page - 1) * $limit, $limit)->select();
            foreach ( $res as $record){
                $record->area;
            }
            $count = AreaSettleRecord::where($data)->count();
            return result($res,0,"",$count);
        }catch (DbException $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function del(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $settle = AreaSettleRecord::get($id);
            if($settle){
                if($settle->status != 1){
                    try {
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
            $settle = AreaSettleRecord::get($id);
            if($settle){
                if($settle->status != 1){
                    Db::startTrans();
                    try {
                        // 恢复订单的结算状态和结算ID
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
     * 自动生成区域结算单
     */
    public function autCreateAreaSettleRecord(){
        Db::startTrans();
        try {
            // 从视图中提取可以结算的区域ID
            $areas = Db::view("area_cansettle_distribute_record","area_id")->distinct(true)->select();
            // 记录生成结算单的数量
            $count = 0;
            foreach ($areas as $area){
                // 从视图中查询区域可提现金额
                $distributeAmount = Db::view("area_cansettle_distribute_record")->where("area_id",$area["area_id"])->sum("get_amount");

                // 获取区域已结算的总金额
                $hasSettle = AreaSettleRecord::sum("amount");

                $amount = $distributeAmount - $hasSettle;
                $bankAccount = AreaBankAccount::where("area_id",$area["area_id"])->find();
                if($amount > 0 && $bankAccount){
                    $settle = new AreaSettleRecord();
                    $res = $settle->save([
                        "amount" => $amount,
                        "area_id" => $area["area_id"],
                        "settle_user" => Session::get("admin_info.name"),
                        "status" => 0,
                        "bank" => $bankAccount->bank,
                        "name" => $bankAccount->name,
                        "account" => $bankAccount->account
                    ]);
                    if($res){
                        $count++;
                    }
                }
            }

            Db::commit();
            if($count > 0){
                return result([],0,"自动生成区域结算单成功");
            }else {
                return result([],1,"没有要生成的区域结算单");
            }

        } catch (Exception $e) {
            Db::rollback();
            return result([],1,$e->getMessage());
        }
    }
}