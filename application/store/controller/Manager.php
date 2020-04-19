<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-08
 * Time: 15:18
 */

namespace app\store\controller;


use app\common\controller\StoreBase;
use app\common\model\Area;
use app\common\model\AreaManager;
use app\common\model\Salesman;
use think\facade\Session;

class Manager extends StoreBase
{
    public function index(){
        $this->assign("nav_list",["店铺首页","我的信息","我的服务商"]);
        $saleman = Salesman::get(Session::get("store_info.manager_id"));
        $this->assign("saleman",$saleman);
        $area = Area::get(Session::get("store_info.area_id"));
        $manager = AreaManager::where("user_id",$area->manager_id)->find();
        $this->assign("area_manager",$manager);
        return $this->fetch();
    }

}