<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-07-01
 * Time: 20:25
 */

namespace app\api\controller;


use app\common\controller\ApiBase;
use app\common\model\GoodComment;
use app\common\model\GoodKeep;
use think\Exception;
use think\exception\DbException;
use think\facade\Request;

class Good extends ApiBase
{
    /**
     * 获取收藏商品列表
     */
    public function getKeepGoodList(){
        if(!Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");
        try {
            $res = GoodKeep::where(["user_id"=>$user_id,"keep"=>1])->select();
            if($res){
                foreach ($res as $keep) {
                    foreach ($keep->good as $good){
                        $good->getImage;
                    }

                }
                return result($res,0);
            }else {
                return result([],1,"收藏列表为空");
            }
        } catch (DbException $e) {
            return result([],0,$e->getMessage());
        }

    }

    /**
     * 获取收藏商品数量
     */
    public function getKeepCount(){
        if(!Request::has("user_id")){
            return result(0,1,"参数错误");
        }

        $user_id = Request::param("user_id");
        $res = GoodKeep::where(["user_id"=>$user_id,"keep"=>1])->count();
        if($res){
            return result($res,0);
        }else {
            return result(0,1,"收藏列表为空");
        }
    }

    public function getComments(){
        if(!Request::has("good_id") || !Request::has("page") || !Request::has("limit")){
            return result([],1,"参数错误");
        }
        $good_id = Request::param("good_id");
        $limit = Request::param("limit");
        $page = Request::param("page");

        try {
            $comments = GoodComment::where(["good_id" => $good_id,"status" => 1])
                ->order("create_time desc")
                ->limit(($page-1)*$limit,$limit)
                ->select();

            foreach ($comments as $comment){
                $comment->user;
            }
            for ($i = 0; $i< $comments->count();$i++){
                $comments[$i]->imgs = json_decode($comments[$i]->imgs);
            }
            return result($comments);
        } catch (Exception $e) {
            return result([],1,$e->getMessage());
        }

    }

    public function addVisits(){
        if(!Request::has("good_id")){
           return result([],1,"参数错误");
        }

        $good = \app\common\model\Good::get(Request::param("good_id"));
        if($good){
            $visits = $good->visits;
            $good->visits = $visits + 1;
            $good->save();
            return result([],0);
        }else {
            return result([],1,"商品不存在");
        }
    }
	public function user(){
		if(!Request::has("user_id")){
           return result([],1,"参数错误");
        }
		$user = \app\common\model\User::get(Request::param("user_id"));
        if($user){
            return result($user,0,'查询用户成功');
        }else {
            return result([],1,"用户不存在");
        }
	}

}