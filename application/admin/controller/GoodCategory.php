<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-26
 * Time: 15:23
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\AuthGroupAccess;
use think\Db;
use think\Exception;
use think\facade\Request;
use think\Image;

class GoodCategory extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','商城管理','分类管理']);
        return $this->fetch();
    }

    public function get(){
        $res = \app\common\model\GoodCategory::all();
        return result($res,0,"",$res->count());
    }

    public function edit(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $data = Request::param();
            $category = \app\common\model\GoodCategory::get($id);
            if($category){
                if($category->save($data)){
                    return result([],0,"修改成功");
                }else {
                    return result([],1,"修改失败");
                }
            }else {
                return result([],1,"未找到相应的数据");
            }
        }else if (Request::has("id")){
            $category = \app\common\model\GoodCategory::get(Request::param("id"));
            $category->upLevel;
            $this->assign("category",$category);
            return $this->fetch();
        }else {
            return "参数错误";
        }
    }

    public function add(){
        try {
            $oneLevelCategory = \app\common\model\GoodCategory::where("pid", 0)->field("id,name")->select();
            $this->assign("oneLevelCategory",$oneLevelCategory);
        }catch (Exception $e){
            return "系统错误";
        }
        return $this->fetch();
    }

    public function insert(){
        if(Request::isPost()){
            $category = new \app\common\model\GoodCategory();
            $res = $category->save(Request::param());
            if($res){
                return result([],0,"新增商品分类成功",0);
            }else {
                return result([],1,"新增失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $area = \app\common\model\GoodCategory::get($id);
            $area->status = $status;
            $count = $area->save();
            if($status == 0){
                \app\common\model\GoodCategory::where("pid",$id)->update(['status' => 0]);
            }
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }

    }
    public function upload(){
        $file = request()->file('image');
        $info = $file->validate(['size'=> 204800,'ext'=>'jpg,png'])->move(getUserFilePath());
        $image = Image::open($file);
        if($image->width() !== 120 || $image->height() !== 120){
            return result([],1,"图片尺寸必须是120*120像素");
        }
        if($info){
            return result(conventPath(getUserFilePath()  . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($file->getError(),1,"上传失败",0);
        }
    }

    public function del(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            if(checkHasGoodUnderCategory($id)){
                return result([],1,"该分类或其子分类下还有商品，不能删除");
            }
            $access = \app\common\model\GoodCategory::get($id);
            if(!$access){
                return result([],1,"未找到相应的记录");
            }else {
                Db::startTrans();
                try {
                    $access->delete();
                    \app\common\model\GoodCategory::where("pid",$id)->update(["delete_time" => time()]);
                    Db::commit();
                    return result([],0,"删除成功");
                } catch (Exception $e) {
                    Db::rollback();
                    return result([],1,"删除失败");
                }
            }
        }else {
            return result([],1,"参数错误");
        }
    }

}