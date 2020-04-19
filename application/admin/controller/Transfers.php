<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-06
 * Time: 18:26
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\TransfersRecord;
use think\Exception;
use think\facade\Request;

class Transfers extends AdminBase
{
    public function index(){
        $this->assign("nav_list",["首页","财务管理","佣金提现"]);
        return $this->fetch();
    }

    public function get(){
        $page = 1;
        $limit = 10;
        if(Request::has("page")){
            $page = Request::param("page");
        }
        if(Request::has("limit")){
            $limit = Request::param("limit");
        }

        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        if($page && $limit){
            try {
                $transfersRecord = TransfersRecord::where($data)->limit(($page - 1) * $limit, $limit)->all();
                return result($transfersRecord,0,"",$transfersRecord->count());
            }catch (Exception $e){
                return result([],1,$e->getMessage(),0);
            }
        }else {
            return result([],1,"请求非法",0);
        }
    }

}