<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-20
 * Time: 17:18
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\AreaManager;
use think\Exception;
use think\facade\Request;

class Area extends AdminBase
{
    /**
     * 展示首页
     */
    public function index(){
        $this->assign("nav_list",['首页','平台管理','区域管理']);
        return $this->fetch();
    }

    /**
     * 获取区域代理数据
     */
    public function get(){
        $res = \app\common\model\Area::all();
        return result($res,0,"",$res->count());
    }

    /**
     * 获取区域代理人数据
     */
    public function getManger(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            $res = AreaManager::where($data)->limit(($page - 1) * $limit, $limit)->select();
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
        return result($res,0,"",$res->count());
    }


    public function editManager(){
        if(Request::param("id")){
            $id = Request::param("id");
            $data = Request::param();
            $manager = AreaManager::get($id);
            unset($data["id"]);
            $res = $manager->save($data);
            if($res){
                return result([],0,"修改成功",0);
            }else {
                return result([],1,"修改失败",0);
            }
        }else {
            return result([],1,"非法提交",0);
        }
    }
    /**
     * 展示区域代理人新增窗口
     */
    public function addAreaManager(){
        return $this->fetch();
    }

    /**
     * 上传文件，代理协议书、身份证
     */
    public function upload(){
        $file = request()->file('file');
        $info = $file->validate(['ext'=>'jpg,png'])->move(getUserFilePath());
        if($info){

            return result(conventPath(getUserFilePath() . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($info->getError(),0,"上传失败",0);
        }
    }

    /**
     * 区域代理人新增插入数据库
     */
    public function insertManager(){
        if(Request::isPost()){
            $validate = new \app\common\validate\AreaManager;
            $data = Request::param();
            $data["status"] = 1;
            $check = $validate->check($data);
            if($check){
                $res = AreaManager::create($data);
                if($res){
                    return result([],0,"新增成功",0);
                }else {
                    return result([],1,"新增失败",0);
                }
            }else {
                return result([],1,$validate->getError(),0);
            }
        }
    }

    public function editAreaManager(){
        $id = Request::param("id");
        if($id){
            $res = AreaManager::get($id);
            if($res){
                $this->assign("manager",$res);
                return $this->fetch();
            }else {
                return '<div style="font-size: 14px; text-align: center">未找到要编辑的代理人</div>';
            }
        }else{
            return '<div style="font-size: 14px; text-align: center">非法提交</div>';
        }
    }

    /**
     * 删除区域代理人
     */
    public function delAreaManager(){
        $id = Request::param("id");
        $res = AreaManager::get($id);
        if( $res->delete()){
            return result([],0,"删除成功",0);
        }else {
            return result([],1,'删除失败',0);
        }
    }

    /**
     * 根据区域代理人的user_id获取是否存在代理区域
     */
    public function getAreaByUserID(){
        try {
            $res = \app\common\model\Area::where("manager_id", Request::param("user_id"))->find();
            if($res){
                return result([],1,"该代理人存在代理区域，请先删除区域代理数据",$res->count("id"));
            }else {
                return result([],0,"",0);
            }
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    /**
     * @return mixed
     * 展示新增区域代理窗口
     */
    public function add(){
        return $this->fetch();
    }

    /**
     * @return \think\response\Json
     * 插入区域代理数据到数据库
     */
    public function insert(){
        if(Request::isPost()){
            $validate = new \app\common\validate\Area();
            if(!$validate->check(Request::param())){
                return result([],1,$validate->getError(),0);
            }
            $area = new \app\common\model\Area();
            $res = $area->allowField(true)->save(Request::param());
            if($res){
                return result([],0,"新增成功",0);
            }else {
                return result([],1,"新增失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    /**
     * @return mixed|string
     * 展示区域代理编辑窗口
     */
    public function edit(){
        if(Request::param("id")){
            $id = Request::param("id");
            $area = \app\common\model\Area::get($id);
            $this->assign("area", $area);
            return $this->fetch();
        }else {
            return '<div style="text-align: center;font-size: 14px; color:red;">参数非法</div>';
        }
    }

    /**
     * @return \think\response\Json
     * 更新区域代理数据
     */
    public function update(){
        if(!Request::param("id")){
            return result([],1,"参数不合法",0);
        }else {
            $data = Request::param();
            $validate = new \app\common\validate\Area;
            $check = $validate->check($data);
            if(!$check){
                return result([],1,$validate->getError(),0);
            }

            $area = \app\common\model\Area::get($data["id"]);
            if(!$area){
                return result([],0,"未找到要修改的记录",0);
            }else {
                unset($data["id"]);
                $area->save($data);
                return result([],0,"修改成功",0);
            }
        }
    }

    /**
     * @return \think\response\Json
     * 切换区域代理数据状态
     */
    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $area = \app\common\model\Area::get($id);
            $area->status = $status;
            $count = $area->save();
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }

    }

    /**
     * @return \think\response\Json
     * 删除区域代理数据
     */
    public function del(){
        if(Request::isPost()){
            $id = Request::param("id");
            if(checkHasSalemanInArea($id)){
                return result([],1,"该区域下还有业务员，不能删除",0);
            }
            if(checkHasStoreInArea($id)){
                return result([],1,"该区域下还有店铺，不能删除",0);
            }
            $area = \app\common\model\Area::get($id);
            $res = $area->delete();
            if($res){
                return result([],0,"删除成功",1);
            }else {
                return result([],1,"删除失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    /**
     * 查看业务员详细信息
     * @return mixed
     */
    public function salesmanView(){
        if(Request::has("id")){
            $id = Request::param("id");
            $salesman = \app\common\model\Salesman::get($id);
            $salesman->area;
            $this->assign("salesman",$salesman);
            return $this->fetch();
        }else {
            return "缺少参数";
        }

    }

}