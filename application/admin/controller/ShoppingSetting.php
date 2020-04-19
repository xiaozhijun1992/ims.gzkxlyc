<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/4/30
 * Time: 20:29
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\facade\Request;

class ShoppingSetting extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','商城管理','购物设置']);
        return $this->fetch();
    }

    public function save(){
        if(Request::isPost()){
            $data = Request::param();
            // 设置自动收货时间
            if($data["key"] === 'auto_receive_time'){
                $set = \app\common\model\ShoppingSetting::get("auto_receive_time");
                $res = $set->save($data);
                if($res){
                    return result([],0,"修改自动收货时间成功",0);
                }else {
                    return result([],0,"修改是吧",0);
                }
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

}