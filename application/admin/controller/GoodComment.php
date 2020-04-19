<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-23
 * Time: 00:35
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use think\Exception;
use think\facade\Request;

class GoodComment extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','商城管理','商品评价']);
        return $this->fetch();
    }

    public function get(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            if(isset($data["comment"])){
                $res = \app\common\model\GoodComment::where("comment",'like','%'.$data['comment'].'%')
                    ->limit(($page-1)*$limit,$limit)->select();
            }else {
                $res = \app\common\model\GoodComment::where($data)->limit(($page - 1) * $limit, $limit)->select();
            }
            return result($res,0,"",$res->count());
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function toggleStatus(){
        if(Request::isPost()){
            $id = Request::param("id");
            $status = Request::param("status");
            $goodComment = \app\common\model\GoodComment::get($id);
            $goodComment->status = $status;
            $count = $goodComment->save();
            return result([],0,"修改状态成功",$count);
        }else {
            return result([],1,'请求不合法',0);
        }

    }

    public function del(){
        if(Request::isPost() && Request::has("id")){
            if(\app\common\model\GoodComment::destroy(Request::param("id"))){
                return result([],0,"删除成功");
            }else {
                return result([],1,"删除失败");
            }

        }else {
            return result([],1,"参数错误");
        }
    }

}