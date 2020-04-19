<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-16
 * Time: 21:45
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\facade\Request;
use think\Exception;

class HotSearch extends  AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','平台管理','热门搜索']);
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function insert(){
        if(Request::isPost()){
            $validate = new \app\common\validate\HotSearch();
            if(!$validate->check(Request::param())){
                return result([],1,$validate->getError(),0);
            }
            $hotSearch = new \app\common\model\HotSearch();
            $res = $hotSearch->save(Request::param());
            if($res){
                return result([],0,"新增成功",0);
            }else {
                return result([],1,"新增失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            $res = \app\common\model\HotSearch::where($data)->limit(($page - 1) * $limit, $limit)->select();
            $count = \app\common\model\HotSearch::where($data)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $hotSearch = \app\common\model\HotSearch::get($id);
            $hotSearch->status = $status;
            $count = $hotSearch->save();
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }

    }

    public function changeSort(){
        if(Request::isPost()){
            $id = Request::param("id");
            $sort = Request::param("sort");
            $hotSearch = \app\common\model\HotSearch::get($id);
            $hotSearch->sort = $sort;
            $res = $hotSearch->save();
            if($res){
                return result([],0,"修改排序成功",0);
            }else {
                return result([],1,"修改培训失败",0);
            }
        }else {
            return result([],1,"非法提交",0);
        }
    }

    public function del(){
        if(Request::isPost()){
            $id = Request::param("id");
            $hotSearch = \app\common\model\HotSearch::get($id);
            $res = $hotSearch->delete();
            if($res){
                return result([],0,"删除成功",1);
            }else {
                return result([],1,"删除失败",0);
            }
        }else {
            return result([],1,"请勿非法提交",0);
        }
    }

}