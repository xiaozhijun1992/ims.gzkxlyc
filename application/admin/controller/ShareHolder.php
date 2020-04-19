<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-05
 * Time: 08:20
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\facade\Request;
use think\Validate;

class ShareHolder extends AdminBase
{
    public function index(){
        $this->assign("nav_list",["首页","平台管理","股东管理"]);
        return $this->fetch();
    }

    public function get(){
        $data = \app\common\model\ShareHolder::all();
        return result($data,0,"",$data->count());
    }

    public function del(){
        $id =  Request::param("id");
        $shareHolder = \app\common\model\ShareHolder::get($id);
        $res = $shareHolder->delete();
        if($res){
            return result([],0,"删除股东成功",0);
        }else {
            return result([],1,"删除股东失败",0);
        }
    }

    public function add(){
        if(Request::isPost()){
            $data = Request::param();
            $validate = \think\facade\Validate::make([
                "user_id|会员ID" => 'require|number|unique:ShareHolder',
                "phone|手机号" => 'require|number|unique:ShareHolder',
                "name|姓名" => 'require|chs',
                "id_no|身份证号" => 'require|idCard|unique:ShareHolder',
            ]);
            $check = $validate->check($data);
            if(!$check){
                return result([],1,$validate->getError());
            }
            $shareHolder = new \app\common\model\ShareHolder();
            $res = $shareHolder->save($data);
            if($res){
                return result([],0,"新增股东成功",0);
            }else {
                return result([],1,"新增股东失败",0);
            }
        }else {
            return $this->fetch();
        }
    }

    public function change(){
        if(Request::isPost()){
            $id = Request::param("id");
            $field = Request::param("field");
            $value = Request::param("value");
            $shareHolder = \app\common\model\ShareHolder::get($id);
            $shareHolder->$field = $value;
            $res = $shareHolder->save();
            if($res){
                return result([],0,"修改成功",0);
            }else {
                return result([],1,"修改失败",0);
            }
        }else {
            return result([],1,"非法提交",0);
        }
    }

}