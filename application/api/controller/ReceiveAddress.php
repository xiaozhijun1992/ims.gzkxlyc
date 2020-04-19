<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-23
 * Time: 21:06
 */

namespace app\api\controller;


use app\common\controller\ApiBase;
use think\Exception;
use think\facade\Request;

class ReceiveAddress extends ApiBase
{
    /**
     * 获取收货地址
     * @return \think\response\Json
     */
    public function index(){
        if(Request::has("user_id")){
            $user_id = Request::param("user_id");
            try {
                $address = \app\common\model\ReceiveAddress::where("user_id",$user_id)->select();
                return result($address,0,"获取收货地址成功",$address->count());
            } catch (Exception $e) {
                return result([],1,$e->getMessage());
            }

        }else {
            return result([],1,"用户未登录");
        }

    }

    public function get(){
        if(Request::has("id")){
            $address = \app\common\model\ReceiveAddress::get(Request::param("id"));
            if($address){
                return result($address,0,"");
            }else {
                return result([],1,"为获取到信息");
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    public function edit(){
        if(!Request::has("id")){
            return result([],1,"参数错误");
        }
        $data = Request::param();
        $validate = new \app\common\validate\ReceiveAddress();
        if(!$validate->check($data)){
            return result($data,1,$validate->getError());
        }else {
            $address = \app\common\model\ReceiveAddress::get(Request::param("id"));
            if($address->save($data)){
                return result([],0,"修改成功");
            }else {
                return result([],1,"修改失败");
            }

        }
    }

    /**
     * 新增收货地址
     * @return \think\response\Json
     */
    public function add(){
        $data = Request::param();
        $validate = new \app\common\validate\ReceiveAddress();
        if(!$validate->check($data)){
            return result($data,1,$validate->getError());
        }else {
            $address = new \app\common\model\ReceiveAddress();
            if($address->save($data)){
                return result([],0,"新增成功");
            }else {
                return result([],1,"新增失败");
            }

        }
    }

}