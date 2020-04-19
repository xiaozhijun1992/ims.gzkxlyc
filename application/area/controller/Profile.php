<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/5/3
 * Time: 13:59
 */

namespace app\area\controller;


use app\common\controller\AreaBase;
use app\common\model\Area;
use app\common\model\AreaManager;
use think\Exception;
use think\facade\Session;

class Profile extends AreaBase
{
    public function index(){
        $this->assign("nav_list",["区域代理","我的信息","我的资料"]);
        try {
            $area = AreaManager::where("user_id",Session::get("area_info.manager_id"))->find();
            $area->user;
            $this->assign("area",$area);
            return $this->fetch();
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

}