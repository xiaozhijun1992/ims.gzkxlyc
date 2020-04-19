<?php
/**
 * 店铺管理
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/4/30
 * Time: 20:43
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\model\StoreApplication;
use app\common\model\StoreBanner;
use app\common\model\StoreMaterial;
use think\Db;
use think\Exception;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;

class Store extends AdminBase
{
    public function index(){
        $this->assign("nav_list",["首页","商城管理","店铺管理"]);
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
        if($page && $limit){
            try {

                if(Request::has("name") && Request::param("name") !== ""){
                    $name = Request::param("name");
                    $stores = \app\common\model\Store::where("name","like",'%'.$name .'%')->limit(($page - 1) * $limit, $limit)->select();
                    $count = \app\common\model\Store::where("name","like",'%'.$name .'%')->count("id");
                }else {
                    $data = Request::param();
                    unset($data["limit"]);
                    unset($data["page"]);
                    Log::record($data);
                    $stores = \app\common\model\Store::where($data)->limit(($page - 1) * $limit, $limit)->select();
                    $count = \app\common\model\Store::where($data)->count("id");
                }

                foreach ($stores as $store){
                    $store->user;
                    $store->industry;
                    $store->salesman;
                    $store->area;
                }
                return result($stores,0,"",$count);
            }catch (Exception $e){
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"请求非法");
        }
    }

    /**
     * 店铺申请
     * @return mixed
     */
    public function application(){
        $this->assign("nav_list",["首页","商城管理","入驻申请"]);
        return $this->fetch();
    }

    /**
     * 获取店铺申请信息
     * @return \think\response\Json
     */
    public function getApplication(){
        $page = Request::param("page");
        $limit = Request::param("limit");
        $data = Request::param();
        unset($data["page"]);
        unset($data["limit"]);
        try {
            $res = StoreApplication::where($data)->order("create_time desc")->limit(($page - 1) * $limit, $limit)->select();
            $count = StoreApplication::where($data)->count();
            return result($res,0,"",$count);
        }catch (Exception $e){
            return result([],1,$e->getMessage(),0);
        }
    }

    /**
     * 更新店铺的联系状态
     * @return \think\response\Json
     */
    public function  onContact(){
        if(Request::has("id")){
            $application = StoreApplication::get(Request::param("id"));
            if($application){
                $application->status = 1;
                if($application->save()){
                    return result([],0,"更新成功");
                }else {
                    return result([],1,"更新失败");
                }
            }else {
                return result([],1,"没有找到相应的记录");
            }
        }else {
            return result([],1,"参数不正确");
        }
    }

    /**
     * 删除入驻申请
     * @return \think\response\Json
     */
    public function delApplication(){
        if(Request::has("id")){
            $application = StoreApplication::get(Request::param("id"));
            if($application){
                if($application->delete()){
                    return result([],0,"删除成功");
                }else {
                    return result([],1,"删除失败");
                }
            }else {
                return result([],1,"没有找到相应的记录");
            }
        }else {
            return result([],1,"参数不正确");
        }
    }

    /**
     * 审核通过
     * @return \think\response\Json
     */
    public function checkOn(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $store = \app\common\model\Store::get($id);
            if($store){
                if($store->status !== '申请中'){
                    return result([],1,"店铺状态不符合，请刷新重试");
                }else {
                    $store->status = 1;
                    if($store->save()){
                        // 给店主发送短息通知
                        try {
                            sendSms($store->contact_phone,"恭喜您，您的店铺已审核通过，欢迎的您加入");
                        } catch (\Exception $e) {
                            Log::record($e->getMessage());
                        }

                        return result([],0,"已审核通过");
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
     * 审核驳回
     * @return \think\response\Json
     */
    public function checkOff(){
        if(Request::isPost() && Request::has("id") && Request::has("remark")){
            $id = Request::param("id");
            $store = \app\common\model\Store::get($id);
            if($store){
                if(Request::param("remark") == ""){
                    return result([],1,"请输入驳回原因");
                }
                if($store->status !== '申请中'){
                    return result([],1,"店铺状态不符合，请刷新重试");
                }else {
                    $store->status = 2;
                    $store->remark = Request::param("remark");
                    if($store->save()){
                        try {
                            // 给店主发送短息通知
                            sendSms($store->contact_phone,"对不起，您的店铺已被驳回，请联系区域经理修改后再次提交");
                        } catch (\Exception $e) {
                            Log::record($e->getMessage());
                        }

                        return result([],0,"已驳回店铺申请");
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

    /*
     * 店铺详情
     */
    public function detail()
    {
        if (!Request::has("id")) {
            return "参数错误";
        }
        $store_id = Request::param("id");
        try {
            // 获取店铺信息
            $store_info = \app\common\model\Store::get($store_id);
            if (!$store_info) {
                return "未找到店铺信息，请刷新重试";
            }

            $this->assign("store_info", $store_info);
            // 获取店铺轮播图
            $store_banner = StoreBanner::where("store_id", $store_id)->select();
            $this->assign("store_banner", $store_banner);

            // 获取店铺资料
            $store_material = StoreMaterial::where("store_id", $store_id)->select();
            $this->assign("store_material", $store_material);

            // 获取店主信息
            $user = \app\common\model\User::get($store_info->user_id);
            $this->assign("user", $user);

            $industry_list = \app\common\model\Industries::where("status", 1)->field("id,name")->select();
            $this->assign("industry_list", $industry_list);
            $salesman_list = \app\common\model\Salesman::where(["status" => 1, "area_id" => Session::get("area_info.id")])->field("id,name")->select();
            $this->assign("salesman_list", $salesman_list);
            return $this->fetch();
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
        return "异常";
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

    // 展示店铺支付二维码
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
     * 推荐店铺到首页
     * @return \think\response\Json
     */
    public function recommend(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $store = \app\common\model\Store::get($id);
            if($store){
                if($store->status != '正常' && $store->recommend == 1){
                    return result([],1,"店铺状态不是正常状态，请刷新重试");
                }else {
                    $store->recommend = 1;
                    if($store->save()){
                        return result([],0,"已推荐到首页");
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
     * 取消店铺首页推荐
     * @return \think\response\Json
     */
    public function unrecommend(){
        if(Request::isPost() && Request::has("id")){
            $id = Request::param("id");
            $store = \app\common\model\Store::get($id);
            if($store){
                if($store->status != '正常' && $store->recommend == 0){
                    return result([],1,"店铺状态不是正常状态，请刷新重试");
                }else {
                    $store->recommend = 0;
                    if($store->save()){
                        return result([],0,"已推荐到首页");
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
     * 删除店铺
     */
    public function delete(){
        // 检查是否有店铺ID参数
        if(!Request::has("id")){
            return result([],1,"参数错误");
        }

        $store_id = Request::param("id");

        // 检查店铺是否有订单，如果有订单就不允许删除，只能停用
        $orders = \app\common\model\GoodOrder::where("store_id",$store_id)->count();
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
            \app\common\model\Good::where("store_id",$store_id)->update(["delete_time" => date('Y-m-d H:i:s', time())]);

            Db::commit();
            return result([],0,"删除店铺成功");
        } catch (\Exception $e) {
            Db::rollback();
            return result([],1,$e->getTraceAsString());
        }

    }

}