<?php
/**
 * 发件人信息表
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-08
 * Time: 17:18
 */

namespace app\store\controller;


use app\common\controller\StoreBase;
use app\common\model\StoreSender;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Session;
use think\Validate;

class Sender extends StoreBase
{
    public function index(){
        $this->assign("nav_list",["店铺首页","我的商城","发货地址管理"  ]);
        return $this->fetch();
    }

    public function get(){
        try {
            $res = StoreSender::where("store_id",Session::get("store_info.id"))->select();
            return result($res,0,"",$res->count());
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }

    }

    public function add(){
        if(Request::isPost()){
            $data = Request::param();

            $validate = Validate::make([
                "name|发件人" => 'require',
                "mobile|发件人手机" => 'require|mobile',
                "province_name|发件省" => 'require',
                "city_name|发件市" => 'require',
                "exp_area_name|发件县区" => 'require',
                "address|详细地址" => 'require',
            ]);

            if(!$validate->check($data)){
                return result([],1,$validate->getError());
            }

            $data["store_id"] = Session::get("store_info.id");
            $sender = new StoreSender();
            $res = $sender->save($data);
            if($res){
                return result([],0,"添加成功",0);
            }else {
                return result([],1,"添加失败",0);
            }
        }else {
            return $this->fetch();
        }
    }

    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $sender = StoreSender::get($id);
            $sender->status = $status;
            $res = $sender->save();
            if($res) {
                return result([], 0, "修改状态成功", 0);
            }else {
                return result([], 1,'修改状态失败',0);
            }
        }else {
            return result([],1,'请求不合法',0);
        }

    }

    public function del(){
        if(Request::isPost()){
            $id = Request::param("id");
            $sender = StoreSender::get($id);
            $res = $sender->delete();
            if($res){
                return result([],0,"删除成功",1);
            }else {
                return result([],1,"删除失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    public function change(){
        if(Request::isPost()){
            $sender = StoreSender::get(Request::param("id"));
            $data[Request::param("field")] = Request::param("value");
            $res = $sender->save($data);
            if($res){
                return result([],0,"修改成功",1);
            }else{
                return result([],1,"修改失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }
}