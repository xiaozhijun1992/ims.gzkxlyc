<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-16
 * Time: 22:40
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\Exception;
use think\facade\Request;
use think\facade\Validate;
use think\Image;

class Industries extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','平台管理','行业管理']);
        return $this->fetch();
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            $res = \app\common\model\Industries::where($data)->limit(($page - 1) * $limit, $limit)->select();
            $count = \app\common\model\Industries::where($data)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function insert(){
        if(Request::isPost()){
            $validate = new \app\common\validate\Industries();
            if(!$validate->check(Request::param())){
                return result([],1,$validate->getError(),0);
            }
            $swipe = new \app\common\model\Industries;
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

    public function add(){
        return $this->fetch();
    }

    public function change(){
        if(Request::isPost()){
            $id = Request::param("id");
            $field = Request::param("field");
            $value = Request::param("value");
            $validate = Validate::make([
                "name|行业名称" => 'chs|length:2,10',
                "platform|平台提点" => 'number|between:0,80',
                "shareholder|股东分红比例" => 'number|between:0,80',
                "area|区域代理佣金比例" => 'number|between:0,80',
                "manager|区域经理佣金比例" => 'number|between:0,80',
                "self|自购佣金比例" => 'number|between:0,80',
                "one|一级分销佣金比例" => 'number|between:0,500',
                "two|二级分销佣金比例" => 'number|between:0,100',
                "sort|排序" => 'number'
            ]);

            $data = [
                $field => $value
            ];

            if(!$validate->check($data)){
                return result([],1,$validate->getError());
            }

            $industries = \app\common\model\Industries::get($id);
            $industries->$field = $value;
            $res = $industries->save();
            if($res){
                return result([],0,"修改成功",0);
            }else {
                return result([],1,"修改失败",0);
            }
        }else {
            return result([],1,"非法提交",0);
        }
    }

    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $industries = \app\common\model\Industries::get($id);
            $industries->status = $status;
            $count = $industries->save();
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }

    }

    public function del(){
        if(Request::isPost()){
            $id = Request::param("id");
            $industries = \app\common\model\Industries::get($id);
            if(checkHasStoreInIndustry($industries->id)){
                return result([],1,"该行业下还有店铺，请先删除店铺");
            }
            $res = $industries->delete();
            if($res){
                return result([],0,"删除成功",1);
            }else {
                return result([],1,"删除失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    public function upload(){
        $file = request()->file('image');
        $info = $file->validate(['size'=> 204800,'ext'=>'jpg,png'])->move( getUserFilePath());
        $image = Image::open($file);
        if($image->width() !== 100 || $image->height() !== 100){
            return result("",1,"图片尺寸必须为100*100，请修改后重新上传");
        }
        if($info){
            return result(conventPath(getUserFilePath()  . $info->getSaveName()),0,"上传成功",0);
        }else{
            echo $file->getError();
            return result($file->getError(),1,"上传失败",0);
        }
    }

}