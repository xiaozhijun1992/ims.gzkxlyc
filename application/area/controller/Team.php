<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-05
 * Time: 16:03
 */

namespace app\area\controller;


use app\common\controller\AreaBase;
use app\common\model\Salesman;
use app\common\model\User;
use think\Exception;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;

class Team extends AreaBase
{
    public function index(){
        $this->assign("nav_list",["区域代理","我的信息","我的团队"]);
        return $this->fetch();
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        try {
            $res = Salesman::where("area_id",Session::get("area_info.id"))->limit(($page - 1)* $limit,$limit)->select();
            $count = Salesman::where("area_id",Session::get("area_info.id"))->count();
            return result($res,0,"",$count);
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }

    public function add(){
        if(Request::isPost()){
            $data = Request::param();
            $salesman = new Salesman();
            $data["area_id"] = Session::get("area_info.id");

            $validate = Validate::make([
                "user_id|会员ID" => 'require|number',
                "name|姓名" => 'require|chs',
                "area_id|区域ID" => 'require|number',
                "area_id|区域ID和会员ID" => 'unique:Salesman,user_id^area_id',
                "phone|手机号码" => 'require|mobile',
                "id_no|身份证号" => 'require|idCard',
                "address|详细地址" => 'require',
                "agreement|协议书" => 'require',
                "id_photo|身份证扫描件" => 'require',
                "sex|性别" => 'require'
            ]);

            if(!$validate->check($data)){
                return result([],1,$validate->getError());
            }

            $res = $salesman->save($data);
            if($res){
                return result([],0,"添加成功",0);
            }else {
                return result([],1,"添加失败",0);
            }
        }else {
            return $this->fetch();
        }
    }

    public function getUser(){
        if(Request::param("phone")){
            $phone = Request::param("phone");
            try {
                $user = User::where("phone", $phone)->select();
                return result($user,0,"",$user->count());

            }catch (Exception $e){
                return result([],1,"",0);
            }
        }else {
            return result([],0,"",0);
        }
    }

    public function upload(){
        $file = request()->file('file');
        $info = $file->validate(['ext'=>'jpg,png'])->move(getUserFilePath());
        if($info){
            return result(conventPath(getUserFilePath() . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($info->getError(),0,"上传失败",0);
        }
    }

    public function del(){
        if(Request::has("id")){
            $id = Request::param("id");
            // 检查该业务员下是否还有店铺
            try {
                if(checkHasStoreBySalesmanId($id)){
                    return result([],1,"该业务员下还有店铺，不能删除");
                }
                $salesman = Salesman::get($id);
                if($salesman->delete()){
                    return result([],0,"删除成功");
                }

            } catch (\Exception $e) {
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    /**
     * 启用业务员
     */
    public function enable(){
        if(Request::has("id")){
            $salesman = Salesman::get(Request::param("id"));
            if(!$salesman){
                return result([],1,"业务员不存在，刷新重试");
            }

            if($salesman->getData("status") != 0){
                return result([],1,"业务员状态不正确，刷新重试");
            }

            $salesman->status = 1;
            if($salesman->save()){
                return result([],0,"已启用");
            }else {
                return result([],1,"操作失败");
            }

        }else {
            return result([],1,"参数错误");
        }

    }

    /**
     * 停用业务员
     */
    public function disable(){
        if(Request::has("id")){
            $salesman = Salesman::get(Request::param("id"));
            if(!$salesman){
                return result([],1,"业务员不存在，刷新重试");
            }

            if($salesman->getData("status") != 1){
                return result([],1,"业务员状态不正确，刷新重试");
            }

            $salesman->status = 0;
            if($salesman->save()){
                return result([],0,"已停用");
            }else {
                return result([],1,"操作失败");
            }

        }else {
            return result([],1,"参数错误");
        }
    }

    public function edit(){
        if(Request::isPost()){
            // 保存提交
            $id = Request::param("id");
            $data = Request::param();
            $salesman = Salesman::get($id);
            unset($data["id"]);
            $res = $salesman->save($data);
            if($res){
                return result([],0,"修改成功",0);
            }else {
                return result([],1,"修改失败",0);
            }

        }else if(Request::has("id")){
            $salesman = Salesman::get(Request::param("id"));
            if(!$salesman){
                return "业务员不存在，请刷新重新";
            }

            $this->assign("salesman",$salesman);
            return $this->fetch();

        }else {
            return "参数错误";
        }
    }


}