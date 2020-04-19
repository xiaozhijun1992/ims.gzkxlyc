<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-05
 * Time: 22:08
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\Exception;
use think\facade\Log;
use think\facade\Request;

class Salesman extends AdminBase
{
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
        Log::record($data1);
        try {
            $res = \app\common\model\Salesman::where($data1)->limit(($page - 1) * $limit, $limit)->select();
            foreach ($res as $r){
                $r->area;
            }
            $count = \app\common\model\Salesman::where($data1)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function disable(){
        if(Request::has("id")){
            $id = Request::param("id");
            $salesman = \app\common\model\Salesman::get($id);
            if($salesman){
                $salesman->status = 0;
                if($salesman->save()){
                    return result([],0,"已停用");
                }else {
                    return result([],1,"操作失败");
                }
            }else {
                return result([],1,"未找到相应的记录");
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    public function enable(){
        if(Request::has("id")){
            $id = Request::param("id");
            $salesman = \app\common\model\Salesman::get($id);
            if($salesman){
                $salesman->status = 1;
                if($salesman->save()){
                    return result([],0,"已停用");
                }else {
                    return result([],1,"操作失败");
                }
            }else {
                return result([],1,"未找到相应的记录");
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    public function reject(){
        if(Request::has("id")){
            $id = Request::param("id");
            $reason = Request::param("reason");
            $salesman = \app\common\model\Salesman::get($id);
            if($salesman){
                $salesman->status = 3;
                $salesman->reason = $reason;
                if($salesman->save()){
                    return result([],0,"已驳回");
                }else {
                    return result([],1,"操作失败");
                }
            }else {
                return result([],1,"未找到相应的记录");
            }
        }else {
            return result([],1,"参数错误");
        }
    }

}