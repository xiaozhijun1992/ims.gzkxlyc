<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-07
 * Time: 18:34
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\Exception;
use think\facade\Request;
use think\facade\Validate;

class BankList extends AdminBase
{
    public function index(){
        $this->assign("nav_list",["首页","财务管理","银行列表"]);
        return $this->fetch();
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        $data1 = array_filter($data,function($val){
            if($val == ""){
                return false;
            }else {
                return true;
            }
        });
        try {
            $res = \app\common\model\BankList::where($data1)->limit(($page - 1) * $limit, $limit)->select();
            $count = \app\common\model\BankList::where($data1)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function add(){
        if(Request::isPost()){
            $data = Request::param();
            $validate = Validate::rule([
                "name|银行名称" => 'require|unique:BankList',
            ]);

            if(!$validate->check($data)){
                return result([],1,$validate->getError());
            }

            $bank = new \app\common\model\BankList();
            $bank->save($data);

            return result([],0,"新增成功");
        }else {
            return result([],1,"非法提交");
        }
    }

    public function del(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $bank = \app\common\model\BankList::get($id);
            if($bank && $bank->delete()){
                return result([],0,"删除成功",0);
            }else {
                return result([],1,"操作失败，没有找到相应的记录");
            }
        }else {
            return result([],1,"非法提交");
        }
    }

}