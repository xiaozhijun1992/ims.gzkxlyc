<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-21
 * Time: 21:39
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\AuthGroup;
use app\common\model\AuthGroupAccess;
use app\common\model\AuthRule;
use think\Db;
use think\Exception;
use think\facade\Request;
use think\Validate;


class Auth extends AdminBase
{
    public function index(){
        $this->assign('nav_list',['首页','平台管理','权限管理']);
        return $this->fetch();
    }

    public function getAuthRule(){
        $res = AuthRule::all();
        return result($res,0,"",$res->count());
    }

    public function getAuthGroup(){
        $sql = 'SELECT a.id,a.title,a.`status`,GROUP_CONCAT(b.title) as rules FROM `admin_auth_group` A, admin_auth_rule B where A.delete_time is  null and B.delete_time is null and FIND_IN_SET(b.id,a.rules) group by a.id,a.title,a.`status`';
        $res = Db::query($sql);
        if($res){
            return result($res,0,"",Db::table("admin_auth_group")->where("delete_time","=",null)->count());
        }else {
            return result([],1,"查询失败",0);
        }

    }

    public function getAuthGroupAccess(){
        $res = AuthGroupAccess::all();
        foreach ($res as $t){
            $t->member;
            $t->authGroup;
        }
        return result($res,0,"",$res->count());
    }

    public function addAuthRule(){
        return $this->fetch();
    }

    public function insertAuthRule(){
        if(Request::isPost()){
            $data = Request::param();
            $res = AuthRule::create($data);
            if($res){
                return result([],0,"新增成功",0);
            }else {
                return result([],1,"新增失败",0);
            }
        }else {
            return result([],1,"请勿非法提交");
        }
    }

    public function addGroup(){
        if(Request::isPost()){
            $data = Request::param();
            $group = new AuthGroup();
            if($group->save($data)){
                return result([],0,"新增成功");
            }else {
                return result([],1,"新增失败");
            }
        }else {
            return $this->fetch();
        }
    }

    public function toggleRuleStatus(){
        if(Request::has("id") && Request::has("status")){
            $id = Request::param("id");
            $status = Request::param("status");

            $rule = AuthRule::get($id);
            if(!$rule){
                return result([],1,"未找到相应的记录");
            }
            $rule->status = $status;
            if($rule->save()){
                return result([],0,"切换状态成功");
            }else {
                return result([],1,"切换状态失败");
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    public function toggleGroupStatus(){
        if(Request::has("id") && Request::has("status")){
            $id = Request::param("id");
            $status = Request::param("status");
            $rule = AuthGroup::get($id);
            if(!$rule){
                return result([],1,"未找到相应的记录");
            }
            $rule->status = $status;
            if($rule->save()){
                return result([],0,"切换状态成功");
            }else {
                return result([],1,"切换状态失败");
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    public function ruleDelete(){
        if(Request::has("id")){
            $id = Request::param("id");
            $rule = AuthRule::get($id);
            if(!$rule){
                return result([],1,"为找到相应的记录");
            }
            if($rule->delete()){
                return result([],0,"删除成功");
            }else {
                return result([],1,"删除失败");
            }

        }else {
            return result([],1,"参数错误");
        }
    }

    public function groupDelete(){
        if(Request::has("id")){
            $id = Request::param("id");
            $rule = AuthGroup::get($id);
            if(!$rule){
                return result([],1,"为找到相应的记录");
            }
            if($rule->delete()){
                return result([],0,"删除成功");
            }else {
                return result([],1,"删除失败");
            }

        }else {
            return result([],1,"参数错误");
        }
    }

    public function accessDelete(){
        if(Request::has("id")){
            $id = Request::param("id");
            $rule = AuthGroupAccess::get($id);
            if(!$rule){
                return result([],1,"为找到相应的记录");
            }
            if($rule->delete()){
                return result([],0,"删除成功");
            }else {
                return result([],1,"删除失败");
            }

        }else {
            return result([],1,"参数错误");
        }
    }

    public function editRule(){
        if(Request::isPost()){
            if(Request::has("id") && $id = Request::param("id")){
                $rule = AuthRule::get($id);
                if(!$rule){
                    return result([],1,"未找到相应的记录");
                }else {
                    if($rule->save(Request::param())){
                        return result([],0,"修改成功");
                    }else {
                        return result([],1,"修改失败");
                    }
                }
            }else {
                return result([],1,"参数错误");
            }
        }else {
            if(!Request::has("id")){
                return "参数错误";
            }
            $id = Request::param("id");
            $rule = AuthRule::get($id);
            if($rule){
                $this->assign("rule",$rule);
                return $this->fetch();
            }else {
                return "未找到相应的记录";
            }

        }
    }

    public function editAccess(){
        if(Request::isPost()){
            if(Request::has("id") && $id = Request::param("id")){
                $rule = AuthGroupAccess::get($id);
                if(!$rule){
                    return result([],1,"未找到相应的记录");
                }else {
                    if($rule->save(Request::param())){
                        return result([],0,"修改成功");
                    }else {
                        return result([],1,"修改失败");
                    }
                }
            }else {
                return result([],1,"参数错误");
            }
        }else {
            if(!Request::has("id")){
                return "参数错误";
            }
            $id = Request::param("id");
            $rule = AuthGroupAccess::get($id);
            $rule->member;
            if($rule){
                $this->assign("rule",$rule);

                return $this->fetch();
            }else {
                return "未找到相应的记录";
            }

        }
    }

    public function getRuleFormTable(){
        $rules = AuthRule::select();
        $data = [];
        foreach ($rules as $rule){
            $tmp = [
                "name" => $rule->title,
                "value" => $rule->id,
                "selected"=>"",
                "disabled"=> $rule->status == 0 ? "disable":""
            ];
            $data[] = $tmp;
        }
        return result($data,0,"success");
    }

    public function getRuleFormTableByID(){
        $id = Request::param("id");
        $rules = AuthRule::select();
        $group = AuthGroup::get($id);
        $groupRules = explode(',',$group->rules);
        $data = [];
        foreach ($rules as $rule){
            $tmp = [
                "name" => $rule->title,
                "value" => $rule->id,
                "selected"=> in_array($rule->id,$groupRules) ? "selected": "",
                "disabled"=> $rule->status == 0 ? "disable":""
            ];
            $data[] = $tmp;
        }
        return result($data,0,"success");
    }

    public function getGroupFormTable(){
        $rules = AuthGroup::select();
        $data = [];
        foreach ($rules as $rule){
            $tmp = [
                "name" => $rule->title,
                "value" => $rule->id,
                "selected"=>"",
                "disabled"=> $rule->status == 0 ? "disable":""
            ];
            $data[] = $tmp;
        }
        return result($data,0,"success");
    }

    public function getMemberFormTable(){
        $rules = \app\common\model\Member::select();
        $data = [];
        foreach ($rules as $rule){
            $tmp = [
                "name" => $rule->name,
                "value" => $rule->id,
                "selected"=>"",
                "disabled"=> $rule->status == 0 ? "disable":""
            ];
            $data[] = $tmp;
        }
        return result($data,0,"success");
    }

    public function editGroup(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $data = Request::param();
            $group = AuthGroup::get($id);
            if($group->save($data)){
                return result([],0,"保存成功");
            }else {
                return result([],1,"保存失败");
            }
        }else {
            $id = Request::param("id");
            $group = AuthGroup::get($id);
            $this->assign("group",$group);
            return $this->fetch();
        }
    }

    public function addAuthGroupAccess(){
        if(Request::isPost()){
            $data = Request::param();
            $validate = new \app\common\validate\AuthGroupAccess();
            if(!$validate->check($data)){
                return result([],1,$validate->getError());
            }
            $group = new AuthGroupAccess();
            if($group->save($data)){
                return result([],0,"新增成功");
            }else {
                return result([],1,"新增失败");
            }
        }else {
            return $this->fetch();
        }
    }

}