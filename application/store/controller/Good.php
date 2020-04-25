<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-08
 * Time: 20:53
 */

namespace app\store\controller;


use app\common\controller\StoreBase;
use app\common\model\GoodCategory;
use app\common\model\GoodComment;
use app\common\model\GoodImage;
use app\common\model\GoodKeep;
use app\common\model\GoodOption;
use app\common\model\GoodParam;
use app\common\model\GoodSpec;
use app\common\model\GoodSpecItem;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;
use think\Image;

class Good extends StoreBase
{
    public function index(){
        $this->assign("nav_list",["店铺首页","我的店铺","商品管理"]);
        $this->assign("state",newWeiXinState());
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
        $data1["store_id"] = Session::get("store_info.id");
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

    /**
     * 添加商品
     * @return mixed|string|\think\response\Json
     */
    public function add(){
        if(Request::isPost()){
            $data = Request::param();

            Db::startTrans();
            try {
                // 保存商品信息
                $data_good = [
                    "name" => array_key_exists("name",$data) ? $data["name"] : null,
                    "unit" => array_key_exists("unit",$data) ? $data["unit"] : null,
                    "category_id" => array_key_exists("category",$data) ? $data["category"] : null,
                    "keyword" => array_key_exists("keyword",$data) ? $data["keyword"] : null,
                    "order" => array_key_exists("order",$data) ? $data["order"] : null,
                    "order_type" => array_key_exists("order_type",$data) ? $data["order_type"] : null,
                    "marketprice" => array_key_exists("marketprice",$data) ? $data["marketprice"] : null,
                    "productprice" => array_key_exists("productprice",$data) ? $data["productprice"] : null,
                    "costprice" => array_key_exists("costprice",$data) ? $data["costprice"] : null,
                    "sales" => array_key_exists("sales",$data) ? $data["sales"] : null,
                    "total" => array_key_exists("total",$data) ? $data["total"] : null,
                    "showtotal" => (array_key_exists("showtotal",$data) && $data["showtotal"]) == "on" ? 1:0,
                    "totalcnf" => array_key_exists("totalcnf",$data) ? $data["totalcnf"] : 1,
                    "detail" => array_key_exists("editorValue",$data) ? $data["editorValue"] : null,
                    "maxbuy" => array_key_exists("maxbuy",$data) ? $data["maxbuy"] : null,
                    "minbuy" => array_key_exists("minbuy",$data) ? $data["minbuy"] : null,
                    "status" => 1,
                    "store_id" => Session::get("store_info.id")
                ];
                $validateGood = new \app\common\validate\Good();
                if(!$validateGood->check($data_good)){
                    Db::rollback();
                    return result([],1,$validateGood->getError());
                }
                $good = new \app\common\model\Good();
                $good->save($data_good);

                // 保存商品图片
                $good_id = $good->id;
                if(array_key_exists("imgs",$data) && count($data["imgs"]) > 0){
                    foreach ($data["imgs"] as $img){
                        $data_image = [
                            "good_id" => $good_id,
                            "img" => $img
                        ];
                        $goodImage = new GoodImage();
                        $goodImage->save($data_image);
                    }
                }else {
                    Db::rollback();
                    return result([],1,"请上传商品图片");
                }

                // 保存商品参数
                if(array_key_exists("param_title",$data)){
                    foreach ($data["param_title"] as $key => $value){
                        $data_param = [
                            "title" => $value,
                            "value" => $data["param_value"][$key],
                            "good_id" => $good_id
                        ];
                        $goodParam = new GoodParam();
                        $goodParam->save($data_param);
                    }
                }


                // 保存商品规格
                $goodOption = [];
                // 用于保存所有规格项名称，用于计算MD5匹配
                $specItemList = [];
                if(array_key_exists("spec_title",$data)){
                    foreach ($data["spec_title"] as $title){
                        $data_spec = [
                            "spec_title" => $title,
                            "store_id" => Session::get("store_info.id"),
                            "good_id" => $good_id
                        ];
                        $goodSpec = new GoodSpec();
                        $goodSpec->save($data_spec);
                        $specID = $goodSpec->id;
                        $key = "spec_item_title_" . $title;
                        if(array_key_exists($key, $data)){
                            foreach ($data[$key] as $itemTitle){
                                $dataSpecItem = [
                                    "spec_id" => $specID,
                                    "title" => $itemTitle,
                                    "good_id" => $good_id
                                ];
                                array_push($specItemList,$itemTitle);
                                $goodSpecItem = new GoodSpecItem();
                                $goodSpecItem->save($dataSpecItem);
                                $goodOption[$itemTitle] = $goodSpecItem->id;
                            }
                        }
                    }
                }
                $dataOption = [];
                foreach ($data as $key => $value){
                    if(strpos($key,"option_") === 0){
                        $dataOption[$key] = $value;
                    }
                }

                foreach ( $dataOption as $key => $value){
                    $keyArr = explode("_", $key);
                    $title = "";
                    $specs = "";
                    for($i=1; $i< count($keyArr);$i++){
                        for($t=0;$t < count($specItemList);$t++){
                            if(md5($specItemList[$t]) == $keyArr[$i]){
                                $title .= $specItemList[$t] . '+';
                                $specs .= $goodOption[$specItemList[$t]] . '_';
                                break;
                            }
                        }
                    }

                    $dataParam = [
                        "store_id" => Session::get("store_info.id"),
                        "good_id" => $good_id,
                        "title" => substr($title,0,strlen($title)-1),
                        "specs" => substr($specs,0,strlen($specs)-1),
                        "stock" => $value["stock"],
                        "marketprice" => $value["marketprice"],
                        "productprice" => $value["productprice"],
                        "costprice" => $value["costprice"]
                    ];

                    $goodOptionM = new GoodOption();
                    $goodOptionM->save($dataParam);
                }
                Db::commit();
                return result([],0,"新增商品成功",0);
            } catch (\Exception $e) {
                Db::rollback();
                return result([],1,$e->getMessage());
            }

        }
        try {
            $good_category = GoodCategory::where("status",1)->where("pid","<>",0)->select();
            $this->assign("good_category",$good_category);
        } catch (DbException $e) {
            return $e->getMessage();
        }
        return $this->fetch();
    }


    // 编辑商品
    public function edit(){

        if(Request::isPost()){
            $data = Request::param();
            $good_id = Request::param("good_id");
            Db::startTrans();
            try {
                // 保存商品信息
                $data_good = [
                    "name" => array_key_exists("name",$data) ? $data["name"] : null,
                    "unit" => array_key_exists("unit",$data) ? $data["unit"] : null,
                    "category_id" => array_key_exists("category",$data) ? $data["category"] : null,
                    "keyword" => array_key_exists("keyword",$data) ? $data["keyword"] : null,
                    "order" => array_key_exists("order",$data) ? $data["order"] : null,
                    "order_type" => array_key_exists("order_type",$data) ? $data["order_type"] : null,
                    "marketprice" => array_key_exists("marketprice",$data) ? $data["marketprice"] : null,
                    "productprice" => array_key_exists("productprice",$data) ? $data["productprice"] : null,
                    "costprice" => array_key_exists("costprice",$data) ? $data["costprice"] : null,
                    "sales" => array_key_exists("sales",$data) ? $data["sales"] : null,
                    "total" => array_key_exists("total",$data) ? $data["total"] : null,
                    "showtotal" => (array_key_exists("showtotal",$data) && $data["showtotal"]) == "on" ? 1:0,
                    "totalcnf" => array_key_exists("totalcnf",$data) ? $data["totalcnf"] : 1,
                    "detail" => array_key_exists("editorValue",$data) ? $data["editorValue"] : null,
                    "maxbuy" => array_key_exists("maxbuy",$data) ? $data["maxbuy"] : null,
                    "minbuy" => array_key_exists("minbuy",$data) ? $data["minbuy"] : null,
                    "status" => 0,
                    "store_id" => Session::get("store_info.id")
                ];

                $validate = new \app\common\validate\Good();
                if(!$validate->check($data_good)){
                    Db::rollback();
                    return result([],1,$validate->getError());
                }
                $good = \app\common\model\Good::get($good_id);
                $good->save($data_good);

                // 保存商品图片
                if(array_key_exists("imgs",$data) && count($data["imgs"]) > 0){
                    GoodImage::where("good_id",$good_id)->delete();
                    foreach ($data["imgs"] as $img){
                        $data_image = [
                            "good_id" => $good_id,
                            "img" => $img
                        ];
                        $goodImage = new GoodImage();
                        $goodImage->save($data_image);
                    }
                }else {
                    Db::rollback();
                    return result([],1,"请上传商品图片");
                }

                // 保存商品参数
                if(array_key_exists("param_title",$data)){
                    try {
                        GoodParam::where("good_id",$good_id)->delete();
                    } catch (Exception $e) {
                        return result([],1,$e->getMessage(),0);
                    }
                    foreach ($data["param_title"] as $key => $value){
                        $data_param = [
                            "title" => $value,
                            "value" => $data["param_value"][$key],
                            "good_id" => $good_id
                        ];
                        $goodParam = new GoodParam();
                        $goodParam->save($data_param);
                    }
                }


                GoodSpec::where("good_id",$good_id)->delete();
                GoodSpecItem::where("good_id",$good_id)->delete();
                GoodOption::where("good_id",$good_id)->delete();

                // 保存商品规格
                $goodOption = [];

                // 用于保存所有规格项名称，用于计算MD5匹配
                $specItemList = [];
				
                if(array_key_exists("spec_title",$data)){
                    foreach ($data["spec_title"] as $title){
                        $data_spec = [
                            "spec_title" => $title,
                            "store_id" => Session::get("store_info.id"),
                            "good_id" => $good_id
                        ];
                        $goodSpec = new GoodSpec();
                        $goodSpec->save($data_spec);
                        $specID = $goodSpec->id;
                        $key = "spec_item_title_" . $title;
                        if(array_key_exists($key, $data)){
                            foreach ($data[$key] as $itemTitle){
                                $dataSpecItem = [
                                    "spec_id" => $specID,
                                    "title" => $itemTitle,
                                    "good_id" => $good_id
                                ];
								
                                array_push($specItemList,$itemTitle);
                                $goodSpecItem = new GoodSpecItem();
								
                                $goodSpecItem->save($dataSpecItem);
                                $goodOption[$itemTitle] = $goodSpecItem->id;
                            }
                        }
                    }
                }
                $dataOption = [];
                foreach ($data as $key => $value){
                    if(strpos($key,"option_") === 0){
                        $dataOption[$key] = $value;
                    }
                }
                Log::record($goodOption);
                Log::record($specItemList);
                foreach ( $dataOption as $key => $value){
                    $keyArr = explode("_", $key);
                    $title = "";
                    $specs = "";
                    for($i=1; $i< count($keyArr);$i++){
                        for($t=0;$t < count($specItemList);$t++){
                            if(md5($specItemList[$t]) == $keyArr[$i]){
                                $title .= $specItemList[$t] . '+';
                                
                                $specs .= $goodOption[$specItemList[$t]] . '_';
								
                                break;
                            }
                        }
                    }

                    $dataParam = [
                        "store_id" => Session::get("store_info.id"),
                        "good_id" => $good_id,
                        "title" => substr($title,0,strlen($title)-1),
                        "specs" => substr($specs,0,strlen($specs)-1),
                        "stock" => $value["stock"],
                        "marketprice" => $value["marketprice"],
                        "productprice" => $value["productprice"],
                        "costprice" => $value["costprice"]
                    ];
					
                    $goodOptionM = new GoodOption();
                    $goodOptionM->save($dataParam);
                }
                Db::commit();
                return result([],0,"修改成功",0);
            } catch (\Exception $e) {
                Db::rollback();
                return result([],1,$e->getMessage());
            }
        }

        if(!Request::has("id")) {
            return "参数错误";
        }
        $good_id = Request::param("id");
        if(!checkGoodIsBlongToStore($good_id)){
            return "该商品不属于您的店铺";
        }
        try {
            // 获取商品分类
            $good_category = GoodCategory::where("status",1)->where("pid","<>",0)->select();
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

    /**
     * 获取商品图片
     * @return \think\response\Json
     */
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

    /**
     * 获取商品参数
     * @return \think\response\Json
     */
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

    /**
     * 获取商品规格
     * @return \think\response\Json
     */
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

    /**
     * 获取商品规格项
     * @return \think\response\Json
     */
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
     * 商品图片上传
     * @return \think\response\Json
     */
    public function uploadImg(){
        $file = request()->file("file");
        $image = Image::open($file);
        if($image->height() !== 500 || $image->width() !== 500){
            return result([],1,"图片尺寸必须是500*500像素");
        }
        $info = $file->validate(['size'=> 512000,'ext'=>'jpg,png'])->move(getUserFilePath());
        if($info){
            return result(conventPath(getUserFilePath() . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($file->getError(),1,$file->getError(),0);
        }
    }

    /**
     * 删除商品
     * @return \think\response\Json
     */
    public function del(){
        if(Request::isPost() && Request::has("id")){
            // 验证要删除的商品是不是属于该店铺
            if(checkGoodIsBlongToStore(Request::param("id"))){
                $good_id = Request::param("id");
                try {
                    // 删除商品评论
                    GoodComment::where("good_id",$good_id)->delete();
                    // 删除商品图片
                    GoodImage::where("good_id",$good_id)->delete();
                    // 删除商品收藏
                    GoodKeep::where("good_id",$good_id)->delete();
                    // 删除商品参数
                    GoodParam::where("good_id",$good_id)->delete();
                    // 删除商品规格
                    GoodOption::where("good_id",$good_id)->delete();
                    GoodSpec::where("good_id",$good_id)->delete();
                    GoodSpecItem::where("good_id",$good_id)->delete();
                    // 删除商品
                    \app\common\model\Good::destroy($good_id);
                    return result([],0,"删除商品成功",0);
                } catch (Exception $e) {
                    return result([],1,$e->getMessage(),0);
                }

            }else {
                return result([],1,"删除商品失败，该商品不属于您的店铺",0);
            }
        }
    }

    /**
     * 商品下架
     * @return \think\response\Json
     */
    public function pullOff(){
        if(Request::isPost() && Request::has("id")){
            $good_id = Request::param("id");
            if(checkGoodIsBlongToStore($good_id)){
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
                return result([],1,"操作失败，该商品不属于您的店铺",0);
            }
        }
    }

    /**
     * 商品上架
     * @return \think\response\Json
     */
    public function pullOn(){
        if(Request::isPost() && Request::has("id")){
            $good_id = Request::param("id");
            if(checkGoodIsBlongToStore($good_id)){
                $good = \app\common\model\Good::get($good_id);
                if($good){
                    if($good->getData("status") !== 2){
                        return result([],1,"商品正在审核中或已上架，不能执行该操作");
                    }else {
                        $good->status = 1;
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
                return result([],1,"操作失败，该商品不属于您的店铺",0);
            }
        }
    }
	public function user_song(){
		if(Request::isPost() && Request::has("id")){
            $user_id = Request::param("id");
			if($user_id){
				$user = \app\common\model\User::get($user_id);
				if($user){
					if(!$user->user_song && !$user->user_song_store){
						$user->user_song_store=Session::get("store_info.id");
						$user->user_song=Session::get("uid");
						if($user->save()){
                            return result([],0,'操作成功');
                        }else {
                            return result([],1,"操作失败");
                        }
					}else{
						return result([],1,"该员工已经绑定其他店铺了",0);
					}
				}else{
					return result([],1,"操作失败，无法查询到该员工",0);
				}
			}else{
				return result([],1,"操作失败，无法查询到该员工",0);
			}
        }
	}

}