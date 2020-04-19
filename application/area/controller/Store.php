<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-05
 * Time: 22:22
 */

namespace app\area\controller;


use app\common\controller\AreaBase;
use app\common\model\Good;
use app\common\model\GoodOrder;
use app\common\model\Industries;
use app\common\model\Salesman;
use app\common\model\StoreBanner;
use app\common\model\StoreMaterial;
use app\common\model\User;
use app\common\model\WeiXinAuthTemp;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Session;
use think\facade\Validate;
use think\Image;

class Store extends AreaBase
{
    public function index(){
        $this->assign("nav_list",["区域代理","店铺管理","店铺列表"]);
        return $this->fetch();
    }

    public function get(){
        if(!Request::has("page") || !Request::has("limit")){
            return result([],1,"参数错误");
        }
        try {
            $page = Request::param("page");
            $limit =Request::param("limit");
            $res = \app\common\model\Store::where("area_id",Session::get("area_info.id"))
                ->limit(($page- 1) * $limit,$limit)->select();
            foreach ($res as $store){
                $store->user;
                $store->industry;
                $store->salesman;
            }
            $count = \app\common\model\Store::where("area_id",Session::get("area_info.id"))->count();
            return result($res,0,"",$count);
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }

    public function edit(){
        if(!Request::has("id")){
            return "参数错误";
        }
        $store_id = Request::param("id");
        try {
            // 获取店铺信息
            $store_info = \app\common\model\Store::get($store_id);
            if(!$store_info){
                return "未找到店铺信息，请刷新重试";
            }

            $this->assign("store_info",$store_info);
            // 获取店铺轮播图
            $store_banner = StoreBanner::where("store_id",$store_id)->select();
            $this->assign("store_banner",$store_banner);

            // 获取店铺资料
            $store_material = StoreMaterial::where("store_id",$store_id)->select();
            $this->assign("store_material",$store_material);

            // 获取店主信息
            $user = User::get($store_info->user_id);
            $this->assign("user",$user);

            $industry_list = Industries::where("status",1)->field("id,name")->select();
            $this->assign("industry_list",$industry_list);
            $salesman_list = Salesman::where(["status"=>1,"area_id"=>Session::get("area_info.id")])->field("id,name")->select();
            $this->assign("salesman_list",$salesman_list);
            $area = explode('/',Session::get("area_info.desc"))[2];
            $this->assign("area",$area);
            return $this->fetch();
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }

    public function detail(){
        if(!Request::has("id")){
            return "参数错误";
        }
        $store_id = Request::param("id");
        try {
            // 获取店铺信息
            $store_info = \app\common\model\Store::get($store_id);
            if(!$store_info){
                return "未找到店铺信息，请刷新重试";
            }

            $this->assign("store_info",$store_info);
            // 获取店铺轮播图
            $store_banner = StoreBanner::where("store_id",$store_id)->select();
            $this->assign("store_banner",$store_banner);

            // 获取店铺资料
            $store_material = StoreMaterial::where("store_id",$store_id)->select();
            $this->assign("store_material",$store_material);

            // 获取店主信息
            $user = User::get($store_info->user_id);
            $this->assign("user",$user);

            $industry_list = Industries::where("status",1)->field("id,name")->select();
            $this->assign("industry_list",$industry_list);
            $salesman_list = Salesman::where(["status"=>1,"area_id"=>Session::get("area_info.id")])->field("id,name")->select();
            $this->assign("salesman_list",$salesman_list);
            $area = explode('/',Session::get("area_info.desc"))[2];
            $this->assign("area",$area);
            return $this->fetch();
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }

    /**
     * 添加店铺
     * @return mixed
     */
    public function add(){
        $this->assign("nav_list",["区域代理","店铺管理","添加店铺"]);
        try {
            $industry_list = Industries::where("status",1)->field("id,name")->select();
            $this->assign("industry_list",$industry_list);
            $salesman_list = Salesman::where(["status"=>1,"area_id"=>Session::get("area_info.id")])->field("id,name")->select();
            $this->assign("salesman_list",$salesman_list);
            $area = explode('/',Session::get("area_info.desc"))[2];
            $this->assign("area",$area);
            return $this->fetch();
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }

    public function save(){
        $data = Request::param("data");
        // 店铺形象图片
        $data["show_img"] = Request::param("show_img");
        $data["area_id"] = Session::get("area_info.id");
        $validate = Validate::make([
            "user_id|店主" => 'require|number',
            "name|店铺名称" => 'require|length:2,50|unique:store',
            "industry_id|行业" => 'require|number',
            "business|主营业务" => 'require',
            "address|店铺地址" => 'require',
            "expire_time|到期时间" => 'require',
            "contact_name|联系人" => 'require',
            "contact_phone|联系电话" => 'require',
            "contact_email|联系邮箱" => 'email',
            "manager_id|业务员" => "require|number",
            "lng|经度" => 'require',
            "lat|纬度" => 'require',
            "show_img|店铺形象图" => 'require',
            "area_id|区域ID" => 'require|number',
            "introduce|店铺简介" => 'require'
        ]);
        if(!$validate->check($data)){
            return result([],1,$validate->getError());
        }

        // 店铺轮播图
        if(!Request::param("imgs") || count(Request::param("imgs")) < 1){
            return result([],1,"请上传店铺轮播图");
        }
        // 营业执照
        if(!Request::param("yyzz") || Request::param("yyzz") == ''){
            return result([],1,"请上传店铺营业执照");
        }
        // 协议书
        if(!Request::param("xys") || Request::param("xys") == ''){
            return result([],1,"请上传店铺协议书");
        }
        // 身份证
        if(!Request::param("sfz") || Request::param("sfz") == ''){
            return result([],1,"请上传店铺负责人身份证");
        }

        $imgs = Request::param("imgs");

        Db::startTrans();
        try {
            // 插入店铺信息
            $store = new \app\common\model\Store();
            $store->save($data);
            $store_id = $store->id;

            // 插入店铺轮播图
            foreach ($imgs as $img){
                $temp = [];
                $temp["store_id"] = $store_id;
                $temp["img"] = $img;
                $banner = new StoreBanner();
                $banner->save($temp);
            }

            // 插入店铺资料
            $yyzz = Request::param("yyzz");
            $xys = Request::param("xys");
            $sfz = Request::param("sfz");
            $storeMateral = new StoreMaterial();
            $list = [
                ["store_id" => $store_id,"type"=>1,"title" => '营业执照',"img"=>$yyzz],
                ["store_id" => $store_id,"type"=>2,"title" => '协议书',"img"=>$xys],
                ["store_id" => $store_id,"type"=>3,"title" => '负责人身份证',"img"=>$sfz],
            ];

            $storeMateral->saveAll($list);

            // 给店主发送短息通知
            sendSms($data["contact_phone"],"您的店铺开通申请已提交成功，我们会在1-3个工作日完成审核");

            Db::commit();
            return result([],0,"新增店铺成功");
        } catch (\Exception $e) {
            Db::rollback();
            return result($e->getMessage());
        }
    }

    public function editSave(){
        $data = Request::param("data");
        // 店铺形象图片
        $data["show_img"] = Request::param("show_img");
        $data["area_id"] = Session::get("area_info.id");
        $validate = Validate::make([
            "id|店铺ID" => 'require|number',
            "user_id|店主" => 'require|number',
            "name|店铺名称" => 'require|length:2,50|unique:store',
            "industry_id|行业" => 'require|number',
            "business|主营业务" => 'require',
            "address|店铺地址" => 'require',
            "expire_time|到期时间" => 'require',
            "contact_name|联系人" => 'require',
            "contact_phone|联系电话" => 'require',
            "contact_email|联系邮箱" => 'email',
            "manager_id|业务员" => "require|number",
            "lng|经度" => 'require',
            "lat|纬度" => 'require',
            "show_img|店铺形象图" => 'require',
            "area_id|区域ID" => 'require|number',
            "introduce|店铺简介" => 'require'
        ]);
        if(!$validate->check($data)){
            return result([],1,$validate->getError());
        }

        // 店铺轮播图
        if(!Request::param("imgs") || count(Request::param("imgs")) < 1){
            return result([],1,"请上传店铺轮播图");
        }
        // 营业执照
        if(!Request::param("yyzz") || Request::param("yyzz") == ''){
            return result([],1,"请上传店铺营业执照");
        }
        // 协议书
        if(!Request::param("xys") || Request::param("xys") == ''){
            return result([],1,"请上传店铺协议书");
        }
        // 身份证
        if(!Request::param("sfz") || Request::param("sfz") == ''){
            return result([],1,"请上传店铺负责人身份证");
        }

        $imgs = Request::param("imgs");

        Db::startTrans();
        try {
            // 修改店铺信息
            $data["status"] = 0;
            $store = \app\common\model\Store::get($data["id"]);
            $store->save($data);
            $store_id = $data["id"];

            // 插入店铺轮播图
            StoreBanner::where("store_id",$store_id)->update(["delete_time" => date('Y-m-d H:i:s', time())]);
            foreach ($imgs as $img){
                $temp = [];
                $temp["store_id"] = $store_id;
                $temp["img"] = $img;
                $banner = new StoreBanner();
                $banner->save($temp);
            }

            // 插入店铺资料
            StoreMaterial::where("store_id",$store_id)->update(["delete_time" => date('Y-m-d H:i:s', time())]);

            $yyzz = Request::param("yyzz");
            $xys = Request::param("xys");
            $sfz = Request::param("sfz");
            $storeMateral = new StoreMaterial();
            $list = [
                ["store_id" => $store_id,"type"=>1,"title" => '营业执照',"img"=>$yyzz],
                ["store_id" => $store_id,"type"=>2,"title" => '协议书',"img"=>$xys],
                ["store_id" => $store_id,"type"=>3,"title" => '负责人身份证',"img"=>$sfz],
            ];

            $storeMateral->saveAll($list);

            // 给店主发送短息通知
            sendSms($data["contact_phone"],"您的店铺资料修改申请已提交成功，我们会在1-3个工作日完成审核");
            Db::commit();
            return result([],0,"修改店铺成功");
        } catch (\Exception $e) {
            Db::rollback();
            return result($e->getMessage());
        }
    }


    /**
     * 上传商品轮播图
     * @return \think\response\Json
     */
    public function uploadImg(){
        $file = request()->file("file");
        $image = Image::open($file);
        if($image->width() !== 980 || $image->height() !== 500){
            return result([],1,"图片尺寸为：980*500，请重新上传",0);
        }
        $info = $file->validate(['size'=> 504800,'ext'=>'jpg,png'])->move(getUserFilePath());
        if($info){
            return result(conventPath(getUserFilePath() . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($file->getError(),1,$file->getError(),0);
        }
    }

    /**
     * 上传商品形象图
     * @return \think\response\Json
     */
    public function uploadShowImg(){
        $file = request()->file("file");
        $image = Image::open($file);
        if($image->width() !== 360 || $image->height() !== 360){
            return result([],1,"图片尺寸为：360*360，请重新上传");
        }
        $info = $file->validate(['size'=> 504800,'ext'=>'jpg,png'])->move(getUserFilePath());
        if($info){
            return result(conventPath(getUserFilePath() . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($file->getError(),1,$file->getError(),0);
        }
    }

    /**
     * 店铺资料上传
     * @return \think\response\Json
     */
    public function uploadMetrial(){
        $file = request()->file("file");
        $info = $file->move(getUserFilePath());
        if($info){
            return result(conventPath(getUserFilePath() . $info->getSaveName()),0,"上传成功",0);
        }else{
            return result($file->getError(),1,$file->getError(),0);
        }
    }

    /**
     * 展示店铺支付二维码
     * @return mixed|string
     */
    public function qrcodePayment(){
        if(Request::has("store_id")){
            $url = Request::domain() . '/qrcode/index?store_id=' . Request::param("store_id");
            $this->assign("url",$url);
            return $this->fetch();
        }else {
            return "参数不正确";
        }
    }

    /**
     * 删除店铺
     */
    public function delete(){
        // 检查是否有店铺ID参数
        if(!Request::has("id")){
            return result([],1,"参数错误");
        }

        $store_id = Request::param("id");

        // 检查店铺是否有订单，如果有订单就不允许删除，只能停用
        $orders = GoodOrder::where("store_id",$store_id)->count();
        if($orders && $orders > 0){
            return result([],1,"该店铺已有订单，不允许删除，但可以停用");
        }

        // 检查店铺


        Db::startTrans();
        try {

            // 删除店铺
            $store = \app\common\model\Store::get($store_id);
            $store->delete();

            // 删除店铺下的商品
            Good::where("store_id",$store_id)->update(["delete_time" => date('Y-m-d H:i:s', time())]);

            Db::commit();
            return result([],0,"删除店铺成功");
        } catch (\Exception $e) {
            Db::rollback();
            return result([],1,$e->getTraceAsString());
        }

    }

    /**
     * 停用店铺
     * @return \think\response\Json
     */
    public function disable(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $store = \app\common\model\Store::get($id);
            if($store){
                if($store->status !== '正常'){
                    return result([],1,"店铺状态不符合，请刷新重试");
                }else {
                    $store->status = 3;
                    if($store->save()){
                        return result([],0,"已禁用");
                    }else {
                        return result([],1,"操作失败");
                    }
                }
            }else {
                return result([],1,"找到要操作的店铺，请刷新重试");
            }
        }else {
            return result([],1,"参数不正确");
        }
    }

    /**
     * 启用店铺
     * @return \think\response\Json
     */
    public function enable(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $store = \app\common\model\Store::get($id);
            if($store){
                if($store->status !== '禁用'){
                    return result([],1,"店铺状态不符合，请刷新重试");
                }else {
                    $store->status = 1;
                    if($store->save()){
                        return result([],0,"已启用");
                    }else {
                        return result([],1,"操作失败");
                    }
                }
            }else {
                return result([],1,"找到要操作的店铺，请刷新重试");
            }
        }else {
            return result([],1,"参数不正确");
        }
    }

    public function selectLocation(){
        $area = Request::param("area");
        $this->assign("area",$area);
        return $this->fetch();
    }
}