<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-22
 * Time: 23:06
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\ExpressCompany;
use think\Exception;
use think\facade\Config;
use think\facade\Request;

class Express extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','平台管理','物流设置']);
        $this->assign("kdniao_param", Config::get("kdniao."));
        return $this->fetch();
    }

    public function getCompany(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        if($name = Request::param("name")){
            try{
                $count = ExpressCompany::where("name", 'like', '%' . $name . '%')->count();
                $res = ExpressCompany::where("name", 'like', '%' . $name . '%')
                    ->limit(($page - 1) * $limit, $limit)->order("status desc")->select();
            }catch (Exception $e){
                return result([],1,$e->getMessage(),0);
            }
        }else {
            try {
                $count = ExpressCompany::count(1);
                $res = ExpressCompany::limit(($page - 1) * $limit, $limit)
                    ->order("status desc")->select();
            } catch (Exception $e) {
                return result([], 1, $e->getMessage(), 0);
            }
        }
        return result($res,0,"",$count);
    }

    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $area = ExpressCompany::get($id);
            $area->status = $status;
            $count = $area->save();
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }

    }

}