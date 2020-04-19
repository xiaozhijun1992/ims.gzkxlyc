<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-09
 * Time: 23:11
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\exception\DbException;
use think\facade\Request;
use think\Image;

class Swipe extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','平台管理','首页轮播图']);
        return $this->fetch();
    }

    public function get(){
        $res = \app\common\model\Swipe::all();
        return result($res,0,"",$res->count());
    }

    public function chooseItem(){
        $type =Request::param("type");
        $page = Request::param("page");
        $limit = Request::param("limit");
        $name = Request::has("name")?Request::param("name"):"";
        try {
            // 店铺
            if($type == 1){
                $res = \app\common\model\Store::where("status",1)->where("name","like","%".$name."%")->field("id,name")->limit(($page-1)*$limit,$limit)->select();
                return result($res,0,"",$res->count());
            }elseif ($type == 2){
                // 商品
                $res = \app\common\model\Good::where("status",1)->where("name","like","%".$name."%")->field("id,name")->limit(($page-1)*$limit,$limit)->select();
                return result($res,0,"",$res->count());
            }else {
                return result([],1,"参数不正确",0);
            }
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }

    }

    public function add(){
        return $this->fetch();
    }

    public function insert(){
        if(Request::isPost()){
            $validate = new \app\common\validate\Swipe;
            if(!$validate->check(Request::param())){
                return result([],1,$validate->getError(),0);
            }
            $swipe = new \app\common\model\Swipe;
            $res = $swipe->save(Request::param());
            if($res){
                return result([],0,"新增成功",0);
            }else {
                return result([],1,"新增失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    public function choose(){
        $type = Request::param("type");
        $this->assign("type",$type);
        return $this->fetch();
    }

    public function upload(){
        $file = request()->file('image');
        $image = Image::open($file);
        if($image->width()!== 750 || $image->height() !== 360){
            return result([],1,"图片上传失败，图片大小必须是750*360像素",0);
        }
        $info = $file->validate(['size'=> 512000,'ext'=>'jpg,png'])->move(getUserFilePath());
        if($info){
            return result(conventPath(getUserFilePath()  . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($file->getError(),1,"上传失败",0);
        }
    }

    public function del(){
        if(Request::isPost()){
            $id = Request::param("id");
            $swipe = \app\common\model\Swipe::get($id);
            $res = $swipe->delete();
            if($res){
                return result([],0,"删除成功",1);
            }else {
                return result([],1,"删除失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    public function changeSort(){
        if(Request::isPost()){
            $id = Request::param("id");
            $sort = Request::param("sort");
            $swipe = \app\common\model\Swipe::get($id);
            $swipe->sort = $sort;
            $res = $swipe->save();
            if($res){
                return result([],0,"修改排序成功",0);
            }else {
                return result([],1,"修改培训失败",0);
            }
        }else {
            return result([],1,"非法提交",0);
        }
    }

    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $swipe = \app\common\model\Swipe::get($id);
            $swipe->status = $status;
            $count = $swipe->save();
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }

    }

}