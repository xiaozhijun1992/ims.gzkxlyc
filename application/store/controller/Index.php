<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/4/30
 * Time: 20:53
 */

namespace app\store\controller;


use app\common\controller\StoreBase;
use app\common\model\GoodOrder;
use app\common\model\Industries;
use app\common\model\SmsRecord;
use app\common\model\Store;
use app\common\model\StoreBanner;
use app\common\model\StoreMaterial;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\facade\Request;
use think\facade\Config;
use think\facade\Session;
use app\api\controller\Weixin;
use app\common\model\User;
use app\common\controller\WxPay;
use think\Validate;

class Index extends StoreBase
{
    public function index(){
        try {
            $bankAccount = \app\common\model\StoreBankAccount::where("store_id",Session::get("store_info.id"))->find();
            if($bankAccount){
                $this->assign("hasBankAccount",true);
            }else {
                $this->assign("hasBankAccount",false);
            }
        } catch (DbException $e) {
            $this->error($e->getMessage());
        }
        return $this->fetch();
    }

    public function welcome(){
        $this->assign("nav_list",["商家平台","统计信息"]);
        $good_count = \app\common\model\Good::where("store_id",Session::get("store_info.id"))->count();
        $this->assign("good_count",$good_count);
        $order_count = GoodOrder::where("store_id",Session::get("store_info.id"))->count();
        $this->assign("order_count",$order_count);

        $today_order_count = GoodOrder::where("store_id", Session::get("store_info.id"))
            ->where('create_time', '> time', date("Y-m-d"))
            ->count();
        $this->assign("today_order_count",$today_order_count);
        $today_order_amount = GoodOrder::where("store_id", Session::get("store_info.id"))
            ->where('create_time', '> time', date("Y-m-d"))
            ->sum("amount");
        $this->assign("today_order_amount",$today_order_amount);

        try {
            $tradeData = Db::view("store_sale_data")->where("store_id",Session::get("store_info.id"))->select();
            $this->assign("tradeData",$tradeData);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $this->fetch();
    }

    public function login(){
		
        if(Request::isPost() && Request::has("phone") && Request::has("phoneCode") && Request::has("no")){
            // 短信验证码登录
            $data = Request::param();
            $validate = Validate::make([
                "phone|手机号" => 'require|mobile',
                "phoneCode|动态码" => 'require',
            ]);

            if(!$validate->check($data)){
                return result([],1,$validate->getError());
            }

            try {
                $record = SmsRecord::where(["no"=>$data["no"],"code"=>$data["phoneCode"],"phone"=>$data["phone"]])->find();
                if($record){
					if($user_song->user_song && $user_song->user_song_store){
			$user=User::where("id",$user_song->user_song)->find();
			$user->user_song=$user_song;		
		}else{
			$user=$user_song;
		}
        if($user){
        	if($user->user_song->user_song){
        		$store = Store::whereOr('user_id',$user->id)->whereOr("id",$user->user_song_store)->select();
        	}else{
        		$store = Store::where("user_id",$user->id)->select();
        	}
            if($store->count() > 0){
                //如果用户只有一个商家，就直接进入后台
                if($store->count() === 1) {
                    Session::set("store_info", $store[0]);
                    Session::set("uid",$user->id);
                    return result("/store/index/index",0,"登录成功");
                }else {
                    // 如果用户有多个商家，进入选择商家页面
                    Session::set("stores",$store);
                    return result("/store/index/selectStore",0,"登录成功");
                }
            }else {
                return result([],1,"您还没有店铺，不能登录");
            }
        }else {
            return result([],1,"没有找到您的信息，不能登录");
        }
                  //  $user = User::where("phone",$data["phone"])->find();
                 //   if($user){
                    //    $store = Store::where("user_id",$user->id)->select();
                    //    if($store->count() > 0){
                            //如果用户只有一个商家，就直接进入后台
                     //       if($store->count() === 1) {
                     //           Session::set("store_info", $store[0]);
                      //          Session::set("uid",$user->id);
                      //          return result("/store/index/index",0,"登录成功");
                     //       }else {
                                // 如果用户有多个商家，进入选择商家页面
                     //           Session::set("stores",$store);
                     //           return result("/store/index/selectStore",0,"登录成功");
                    //        }
                     //   }else {
                    //        return result([],1,"您还没有店铺，不能登录");
                    //    }
                    //}else {
                    //    return result([],1,"没有找到您的信息，不能登录");
                    //}
                }else {
                    return result([],1,"短信验证码不正确");
                }
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }
        }

        if(Request::param("code")){
            $res = Weixin::getOpenIdAndUnionIdByCode(Config::get("wxweb.appid"),Config::get("wxweb.secret"),Request::param("code"));
            $res = json_decode($res);
			//$resc = Weixin::getOpenIdAndUnionIdByCode(Config::get("wxapp.appid"),Config::get("wxapp.secret"),"KXLY5E4FBF63AB05B");
           // $resc = json_decode($resc);
			//$openid = WxPay::getOpenid();
			//echo "<pre>";
			//print_r($res);
			//exit;
			//$user->save();
            if ($res->unionid){
                try{
                    $user = User::where("wx_unionid",$res->unionid)->find();
					//$user->wx_openid=$res->openid;
					//$user->save();
                    if($user){
						if($user->user_song_store && $user->user_song){
							
							$store = Store::whereOr('user_id',$user->id)->whereOr("id",$user->user_song_store)->select();
						}else{
							$store = Store::where("user_id",$user->id)->select();
						}
						if($store->count() > 0){
                            //如果用户只有一个商家，就直接进入后台
							if($store->count() === 1) {
                                Session::set("store_info", $store[0]);
                                Session::set("uid",$user->id);
                                $this->success("登录成功", "/store/index/index");
                            }else {
                                // 如果用户有多个商家，进入选择商家页面
                                Session::set("stores",$store);
                                $this->redirect("/store/index/selectStore");
                            }
                       // $store = Store::where("user_id",$user->id)->select();
                       // if($store->count() > 0){
                            //如果用户只有一个商家，就直接进入后台
                         //   if($store->count() === 1) {
                         //       Session::set("store_info", $store[0]);
                         //       Session::set("uid",$user->id);
                          //      $this->success("登录成功", "/store/index/index");
                          //  }else {
                                // 如果用户有多个商家，进入选择商家页面
                         //       Session::set("stores",$store);
                        //        $this->redirect("/store/index/selectStore");
                         //   }
                        }else {
                            $this->error("您还不是店铺管理员，请联系区域添加",'/store/index/login',"",8);
                        }
                    }else {
                        $this->error("没有找到对应的用户信息",'/store/index/login',"",8);
                    }
                }catch (Exception $e){
                    $this->error($e->getMessage(),'/store/index/login',"",8);
                }
            }else {
                $this->error("登录失败",'/store/index/login');
            }
        }else {
            $this->assign("state",newWeiXinState());
            return $this->fetch();
        }
    }

    public function selectStore(){
        if(Request::param("store_id")) {
            $id = Request::param("store_id");
            foreach (Session::get('stores') as $store){
                if($store->id == $id){
                    Session::delete("stores");
                    Session::set("store_info",$store);
                    Session::set("uid",$store->user_id);
                    $this->redirect("/store/index");
                }
            }
        }
        return $this->fetch();
    }

    public function logout(){
        Session::clear();
        $this->success("已退出登录",'/store/index/login');
    }

    public function detail(){
        $store_id = Session::get("store_info.id");
        try {
            // 获取店铺信息
            $store_info = Store::get($store_id);
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
            return $this->fetch();
        }catch (Exception $e){
            $this->error($e->getMessage());
        }
    }
}