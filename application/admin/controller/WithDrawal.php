<?php
/**
 * 提现设置
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/4/30
 * Time: 20:37
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\facade\Request;

class WithDrawal extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','商城设置','提现设置']);
        $user_setting = \app\common\model\WithDrawal::get("user");
        $this->assign("user_setting",$user_setting);
        $store_setting = \app\common\model\WithDrawal::get("store");
        $this->assign("store_setting", $store_setting);
        $manager_setting = \app\common\model\WithDrawal::get('manager');
        $this->assign("manager_setting", $manager_setting);
        $area_setting = \app\common\model\WithDrawal::get("area");
        $this->assign("area_setting", $area_setting);
        return $this->fetch();
    }

    public function update(){
        if(Request::isPost()){
            $temp = \app\common\model\WithDrawal::get(Request::param("scene"));
            $data = Request::param();
            unset($data["scene"]);
            $res = $temp->save($data);
            if($res){
                return result([],0,"修改成功",0);
            }else {
                return result([],1,"修改失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

}