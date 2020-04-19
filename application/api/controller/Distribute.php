<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-07-01
 * Time: 21:06
 */

namespace app\api\controller;


use app\common\controller\ApiBase;
use app\common\model\User;
use think\Db;
use think\exception\DbException;
use think\facade\Request;

class Distribute extends ApiBase
{
    /**
     * 获取团队用户列表
     */
    public function getTeam(){
        if(!Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");
        try {
            $res = User::where(["one_level_user_id"=>$user_id,"status" => 1])->select();
            if($res){
                return result($res,0);
            }else {
                return result([],1,"您还没有团队成员");
            }
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }

    /**
     * 获取团队用户数量
     */
    public function getTeamCount(){
        if(!Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");
        $res = User::where(["one_level_user_id"=>$user_id,"status" => 1])->count();
        if($res){
            return result($res,0);
        }else {
            return result([],1,"您还没有团队成员");
        }
    }

    /**
     * 获取佣金明细
     */
    public function getDistributeRecord(){
        if(!Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");
        try {
            $res = Db::view("user_distribute_record")->where(["user_id"=>$user_id])->order("create_time desc")->select();
            if($res){
                return result($res,0);
            }else {
                return result([],1,"您还没有获得佣金");
            }
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }

    /**
     * 获取总佣金和已提现佣金
     */
    public function getAllAmountAndTransferAmountByUserId(){
        if(!Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");
        try {
            $data = Db::view("user_distribute_amount")->where("id",$user_id)->find();
            $data1 = Db::view("user_transfers_amount")->where("id",$user_id)->find();
            $res = [
                "all_amount" => $data ? $data["sum_amount"]: 0.00,
                "transfer_amount" => $data1 ? $data1["sum_amount"]: 0.00,
            ];
            return result($res,0);
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }

    }

    /**
     * 根据用户ID获取商家
     */
    public function getStoreByUserId(){
        if(!Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");

    }



}