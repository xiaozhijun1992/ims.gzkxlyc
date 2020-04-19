<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-08-17
 * Time: 17:33
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\MoreActivity;
use think\facade\Request;
use think\facade\Validate;
use think\Image;

class Activity extends AdminBase
{
    public function singleActivity(){
        $this->assign("nav_list",['首页','活动管理','单活动位管理']);
        return $this->fetch();
    }

    public function twoActivity(){
        $this->assign("nav_list",['首页','活动管理','双活动位管理']);
        return $this->fetch();
    }

    public function threeActivity(){
        $this->assign("nav_list",['首页','活动管理','三活动位管理']);
        $left = MoreActivity::get("left");
        $this->assign("left",$left);
        $right_bottom = MoreActivity::get("right_bottom");
        $this->assign("right_bottom",$right_bottom);
        $right_top = MoreActivity::get("right_top");
        $this->assign("right_top",$right_top);
        return $this->fetch();
    }

    public function fourActivity(){
        $this->assign("nav_list",['首页','活动管理','四活动位管理']);
        return $this->fetch();
    }

    public function threeActivityUpdate(){
        $data = Request::param();
        $validate = Validate::make([
            "type|活动位置" => "require",
            "img|活动图片" => 'require|url',
            "url|活动URL地址" => 'require|url'
        ]);

        if(!$validate->check($data)){
            return result([],1,$validate->getError());
        }
        $type = $data["type"];

        $result = MoreActivity::get($type);
        if($result){
            $res = $result->save($data);
        }else {
            $res = MoreActivity::create($data);
        }

        if($res){
            return result([],0,"修改成功");
        }else {
            return result([],1,"修改失败");
        }
    }


    // 图片上传
    public function upload(){
        $file = request()->file('image');
        $type = Request::param("type");
        $image = Image::open($file);

        $imgSize = [
            "left" => [374,240], // 多图活动左边图大小
            "right_top" => [374,120], // 多图活动右边上部图片大小
            "right_bottom" => [374,120], // 多图活动右边下部图片大小
        ];

        if($image->width()!== $imgSize[$type][0] || $image->height() !== $imgSize[$type][1]){
            return result([],1,"图片上传失败，图片大小必须是" .$imgSize[$type][0] . '*' . $imgSize[$type][1] .  "像素",0);
        }
        $info = $file->validate(['size'=> 512000,'ext'=>'jpg,png'])->move(getUserFilePath());
        if($info){
            return result(Request::domain() .'/' . conventPath(getUserFilePath()  . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($file->getError(),1,"上传失败",0);
        }
    }

}