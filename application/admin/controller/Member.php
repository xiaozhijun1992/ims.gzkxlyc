<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-22
 * Time: 23:23
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\AuthGroupAccess;
use think\Exception;
use think\facade\Request;

class Member extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','平台管理','用户管理']);
        return $this->fetch();
    }

    public function insert(){
        if(Request::isPost()){
            $validate = new \app\common\validate\Member();
            if(!$validate->check(Request::param())){
                return result([],1,$validate->getError(),0);
            }
            $member = new \app\common\model\Member();
            $res = $member->save(Request::param());
            if($res){
                return result([],0,"新增成功",0);
            }else {
                return result([],1,"新增失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    public function resetPwd(){
        if(Request::isPost()){
            $id = Request::param("id");
            $resetPassword = '8699077';
            $member = \app\common\model\Member::get($id);
            $member->password = sha1($resetPassword);
            $count = $member->save();
            return result([],0,"重置密码成功，重置后的密码为: " . $resetPassword ,$count);
        }else {
            return result([],1,'请求不合法',0);
        }
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            $res = \app\common\model\Member::where($data)->limit(($page-1)*$limit,$limit)->select();
            return result($res,0,"",$res->count());
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function add(){
        return $this->fetch();
    }

    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $member = \app\common\model\Member::get($id);
            $member->status = $status;
            $count = $member->save();
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }

    }

    public function del(){
        if(Request::isPost()){
            $id = Request::param("id");
            $access = AuthGroupAccess::get($id);
            $res1 = $access->delete();
            $member = \app\common\model\Member::get($id);
            $res = $member->delete();
            if($res && $res1){
                return result([],0,"删除成功",1);
            }else {
                return result([],1,"删除失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

}