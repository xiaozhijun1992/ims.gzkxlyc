<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/4/30
 * Time: 20:46
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\GoodImage;
use app\common\model\GoodOption;
use app\common\model\GoodParam;
use think\Exception;
use think\exception\DbException;
use app\common\model\GoodSpec;
use think\facade\Request;

class Good extends AdminBase
{
    public function index(){
        $this->assign("nav_list",["首页","商城管理","商品管理"]);
        return $this->fetch();
    }

    /**
     * 获取商品列表
     * @return \think\response\Json
     */
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
            $res = \app\common\model\Good::where($data1)->limit(($page - 1) * $limit, $limit)->select();
            foreach ($res as $r){
                $r->store;
            }
            $count = \app\common\model\Good::where($data1)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    public function detail(){
        try {
            if(!Request::has("id")) {
                return "参数错误";
            }
            $good_id = Request::param("id");
            // 获取商品分类
            $good_category = \app\common\model\GoodCategory::where("status",1)->where("pid","<>",0)->select();
            $this->assign("good_category",$good_category);

            // 获取商品信息
            $good = \app\common\model\Good::get($good_id);
            $this->assign("good",$good);

            // 获取规格
            $specs = GoodSpec::where("good_id",$good_id);
            $this->assign("good_specs",$specs);
            return $this->fetch();

        } catch (DbException $e) {
            return $e->getMessage();
        }
    }

    public function getGoodImage(){
        if(Request::has("id")&& Request::isPost()){
            try {
                $image = GoodImage::where("good_id",Request::param("id"))->select();
                return result($image,0,"",$image->count());
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }

        }else {
            return result([],1,"参数错误");
        }
    }

    public function getGoodParam(){
        if(Request::has("id")&& Request::isPost()){
            try {
                $image = GoodParam::where("good_id",Request::param("id"))->select();
                return result($image,0,"",$image->count());
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }

        }else {
            return result([],1,"参数错误");
        }
    }

    public function getGoodSpec(){
        if(Request::has("id")&& Request::isPost()){
            try {
                $specs = GoodSpec::where("good_id",Request::param("id"))->select();
                foreach ($specs as $spec){
                    $spec->item;
                }
                return result($specs,0,"",$specs->count());
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }

        }else {
            return result([],1,"参数错误");
        }
    }

    public function getGoodOption(){
        if(Request::has("id")&& Request::isPost()){
            try {
                $options = GoodOption::where("good_id",Request::param("id"))->select();
                return result($options,0,"",$options->count());
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }

        }else {
            return result([],1,"参数错误");
        }
    }
    /**
     * 商品下架
     * @return \think\response\Json
     */
    public function pullOff(){
        if(Request::isPost() && Request::has("id")){
            $good_id = Request::param("id");
            $good = \app\common\model\Good::get($good_id);
            if($good){
                if($good->getData("status") !== 1){
                    return result([],1,"商品正在审核中或已下架，不能执行该操作");
                }else {
                    $good->status = 2;
                    if($good->save()){
                        return result([],0,'操作成功');
                    }else {
                        return result([],1,"操作失败");
                    }

                }
            }else {
                return result([],1,"操作失败，未找到商品信息",0);
            }
        }else {
            return result([],1,"操作失败，参数错误",0);
        }

    }

    /**
     * 审核不通过
     * @return \think\response\Json
     */
    public function checkOff(){
        if(Request::isPost() && Request::has("id") && Request::has("remark")){
            $id = Request::param("id");
            $remark = Request::param("remark");
            $good = \app\common\model\Good::get($id);
            if($good){
                $good->remark = $remark;
                $good->status = 3;
                if($good->save()){
                    return result([],0,"操作成功");
                }else {
                    return result([],1,"操作失败");
                }
            }else {
                return result([],1,"未找到相应的商品，请检查");
            }
        }else if(!Request::has("remark")){
            return result([],1,"审核意见不能为空");
        }else {
            return result([],1,"参数错误");
        }
    }

    /**
     * 审核通过
     * @return \think\response\Json
     */
    public function checkOn(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $good = \app\common\model\Good::get($id);
            if($good){
                $good->remark = null;
                $good->status = 1;
                if($good->save()){
                    return result([],0,"已通过");
                }else {
                    return result([],1,"操作失败");
                }
            }else {
                return result([],1,"未找到相应的商品，请检查");
            }
        }else {
            return result([],1,"参数错误");
        }
    }


    public function recommend(){
        if(Request::isPost() && Request::has("id")){
            $good_id = Request::param("id");
            $good = \app\common\model\Good::get($good_id);
            if($good){
                if($good->getData("recommend") !== 0){
                    return result([],1,"商品已经推荐，不能执行该操作");
                }else {
                    $good->recommend = 1;
                    if($good->save()){
                        return result([],0,'操作成功');
                    }else {
                        return result([],1,"操作失败");
                    }

                }
            }else {
                return result([],1,"操作失败，未找到商品信息",0);
            }
        }else {
            return result([],1,"操作失败，参数错误",0);
        }

    }

    public function unrecommend(){
        if(Request::isPost() && Request::has("id")){
            $good_id = Request::param("id");
            $good = \app\common\model\Good::get($good_id);
            if($good){
                if($good->getData("recommend") !== 1){
                    return result([],1,"商品没有推荐，不能执行该操作");
                }else {
                    $good->recommend = 0;
                    if($good->save()){
                        return result([],0,'操作成功');
                    }else {
                        return result([],1,"操作失败");
                    }

                }
            }else {
                return result([],1,"操作失败，未找到商品信息",0);
            }
        }else {
            return result([],1,"操作失败，参数错误",0);
        }

    }

}