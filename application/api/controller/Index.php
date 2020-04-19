<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-08
 * Time: 23:51
 */

namespace app\api\controller;


use app\common\controller\ApiBase;
use app\common\controller\WXBizDataCrypt;
use app\common\model\Good;
use app\common\model\GoodCategory;
use app\common\model\GoodComment;
use app\common\model\GoodImage;
use app\common\model\GoodKeep;
use app\common\model\GoodOption;
use app\common\model\HotSearch;
use app\common\model\Industries;
use app\common\model\SmsRecord;
use app\common\model\Store;
use app\common\model\StoreBanner;
use app\common\model\User;
use think\Db;
use think\Exception;
use think\exception\DbException;
use think\facade\Config;
use think\facade\Log;
use think\facade\Request;
use think\facade\Validate;


class Index extends ApiBase
{
    public function index(){
        echo "这是API接口地址";
    }

    /**
     * 获取首页轮播图
     * @return \think\response\Json
     */
    public function getSwipe(){
        try {
            $res = \app\common\model\Swipe::field("id,title,img,link_type,link_id,link_name,sort")
                ->where("status",1)
                ->order("sort desc")
                ->limit(6)->select();
            return result($res,0,"获取首页轮播图成功",$res->count());
        } catch (DbException $e) {
            return result([],1,$e->getMessage(),0);
        }
    }

    /**
     * 获取首页分类
     * @return \think\response\Json
     */
    public function getIndexCategory(){
        try {
            $res = GoodCategory::where("pid","<>",0)
                ->where("status",1)
                ->field("id,name,banner")
                ->limit(9)
                ->order("order")
                ->select();
            return result($res,0,"获取首页商品分类成功",$res->count());
        } catch (DbException $e) {
            return result([],1,$e->getMessage(),0);
        }
    }

    /**
     * 根据分类ID获取商品列表
     * @return \think\response\Json
     */
    public function getGoodListByCategory(){
        $category_id = Request::param("id");
        $order = Request::param("order");
        $page = Request::param("page");
        $limit = Request::param("limit");
        try {
            $res = Good::where("category_id",$category_id)
                ->where("status",1)
                ->field("id,name,sales,marketprice,productprice,order_type")
                ->order($order)
                ->limit(($page-1)*$limit,$limit)
                ->select();
            foreach ($res as $good) {
                $good->getImage;
            }
            return result($res,0,"获取商品列表成功",$res->count());
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }


    public function getGoodImg(){
        $id = Request::param("id");
        try {
            $img = GoodImage::where("good_id",$id)->field("id,img")->select();
            return result($img,0,"获取商品轮播图成功",$img->count());
        } catch (DbException $e) {
            return result([],1,$e->getMessage(),0);
        }
    }

    public function getGoodShareImage(){
        if(!Request::has("good_id") || !Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");
        $good_id = Request::param("good_id");
        $qrcode_good_path = 'uploads/qrcodeGood/' . $user_id . '-' . $good_id . '.jpg';
        if(file_exists($qrcode_good_path)){
            return result($qrcode_good_path);
        }
      	$access_token = wxapptoken()->access_token;
        $line_color = [
            "r"=> '0',
            "g" => '0',
            "b" => '0'
        ];
        $result = getwxacodeunlimit(
            $access_token,
            "user_id:" . $user_id . ",good_id:" . $good_id,
            'pages/good/good',
            430,
            $line_color,
            false
        );

        if(isset(json_decode($result)->errcode)){
            return result([],1,json_decode($result)->errmsg);
        }
        file_put_contents($qrcode_good_path, $result);
        return result($qrcode_good_path);
    }
	public function getGoodShareImageweb(){
        if(!Request::has("good_id") || !Request::has("user_id")){
            return result([],1,"参数错误");
        }

        $user_id = Request::param("user_id");
        $good_id = Request::param("good_id");
        $qrcode_good_path = 'uploads/qrcodeGood/' . $user_id . '-' . $good_id . '.jpg';
        if(file_exists($qrcode_good_path)){
            return result($qrcode_good_path);
        }
      	$access_token = webapptoken()->access_token;
        $line_color = [
            "r"=> '0',
            "g" => '0',
            "b" => '0'
        ];
        $result = getwxacodeunlimit(
            $access_token,
            "user_id:" . $user_id . ",good_id:" . $good_id,
            'pages/good/good',
            430,
            $line_color,
            false
        );

        if(isset(json_decode($result)->errcode)){
            return result([],1,json_decode($result)->errmsg);
        }
        file_put_contents($qrcode_good_path, $result);
        return result($qrcode_good_path);
    }
    public function getUserInfoByWxcode(){
    	
        if(Request::has("code")){
        	
            $code = Request::param("code");
            $res = json_decode(jscode2session($code));
            Log::record($res);

            $user = getUserInfoByUnionid(array_key_exists("unionid",$res)?$res->unionid:'AAAAAAAAAAA',$res->openid);

            if($user) {
                return result($user, 0, "获取用户信息成功", 0);
            }else {
                return result([], 1, "获取用户信息失败", 0);
            }
        }else {
            return result(false,1,"缺少code参数",0);
        }
    }

    public function getWxAppUnionidAndOpenid(){
        if(Request::has("code")){
            $code = Request::param("code");
            $res = json_decode(jscode2session($code));
            return result($res,0,"获取用户信息成功",0);
        }else {
            return result([],1,"缺少code参数",0);
        }
    }

    public function newUser(){
        $data = json_decode(Request::param("data"));
        Log::record($data);
        // 判断是否存在unionid
        if(!isset($data->wx_unionid) && isset($data->sessionKey) && isset($data->encryptedData) && isset($data->iv)){
            $appid = Config::get("wxapp.appid");
            $sessionKey = $data->sessionKey;
            $encryptedData = $data->encryptedData;
            $iv = $data->iv;
            $pc = new WXBizDataCrypt($appid, $sessionKey);
            $errCode = $pc->decryptData($encryptedData, $iv, $dataTmp );
            if ($errCode == 0) {
                $dataTmp = json_decode($dataTmp);
                Log::record($dataTmp);
                $data->wxapp_openid = $dataTmp->openId;
                $data->wx_unionid = $dataTmp->unionId;
            } else {
                return result([],1,$errCode);
            }
        }

        if(!isset($data->wx_unionid)){
            return result([],1,"获取信息失败");
        }else {
            try {
                $user = User::where("wx_unionid",$data->wx_unionid)->find();
                if($user){
					if(!$user->wxapp_openid){
						$user->wxapp_openid=$data->wxapp_openid;
						$user->save();
					}
					
                    return result([],1,"信息已存在");
                }
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }
        }

        $data1 = [];
        $data1["nick_name"] = $data->nickName;
        $data1["avator"] = $data->avatarUrl;
        $data1["sex"] = $data->gender;
        $data1["city"] = $data->city;
        $data1["province"] = $data->province;
        $data1["country"] = $data->country;
        $data1["wxapp_openid"] = $data->wxapp_openid;
        $data1["wx_unionid"] = $data->wx_unionid;
        $data1["code"] = newUserCode();

        // 如果存在存在推荐人ID
        if(isset($data->recommend_id) && $data->recommend_id){
            $one_level_user_id = $data->recommend_id;
            $recommendUser = User::get($one_level_user_id);
            if($recommendUser){
                $two_level_user_id = $recommendUser->one_level_user_id;
                $data1["one_level_user_id"] = $one_level_user_id;
                $data1["two_level_user_id"] = $two_level_user_id;
            }
        }
        $res = User::create($data1);
        if($res){
            return result($res,0,"欢迎加入快享立赢大家庭，记得分享赚取佣金噢！！");
        }else {
            return result([],1,"登陆失败");
        }
    }

    public function goodKeep(){
        $good_id = Request::param("good_id");
        $user_id = Request::param("user_id");
        $keep = Request::param("keep") == "true"?1:0;
        try {
            $goodKeep = GoodKeep::where("good_id",$good_id)->where("user_id",$user_id)->find();
            if($goodKeep){
                $goodKeep->keep = $keep;
                $goodKeep->save();
            }else {
                $goodKeep = new GoodKeep();
                $goodKeep->save([
                    "user_id" => $user_id,
                    "good_id" => $good_id,
                    "keep" => $keep
                ]);
            }
            if($keep){
                return result([],0,"收藏成功",0);
            }else {
                return result([],0,"取消收藏成功",0);
            }

        } catch (Exception $e) {
            return result([],1,$e->getMessage());
        }
    }

    /**
     * 获取商品信息
     * @return \think\response\Json
     */
    public function getGoodInfo(){
        $good_id = Request::param("good_id");
        $good = Good::get($good_id);
        if(!$good){
            return result([],1,"商品信息不存在或已失效");
        }
        $good["comments"] = $good->comments()->where("status",1)->select();
        foreach ($good["comments"] as $comment){
            $comment->user;
        }
        $good->spec;
        $good->param;
        foreach ($good["spec"] as $spec){
            $spec->item;
        }
        $good->goodOption;
        $good->store;
        return result($good,0,"");
    }

    /**
     * 获取是否已收藏
     */
    public function getKeep(){
        try {
            $keep = GoodKeep::where(Request::param())->find();
            if($keep) {
                return result($keep->keep === 0 ? false : true, 0, "");
            }else {
                return result(false,0,"");
            }
        } catch (Exception $e) {
            return result(false,0,"");
        }
    }

    public function getComment(){
        $good_id = Request::param("good_id");
        $count = GoodComment::where("status",1)->where("good_id",$good_id)->count();
        try {
            $res = GoodComment::where("status",1)->where("good_id",$good_id)->order("update_time desc")->limit(3)->select();
        } catch (Exception $e) {
            return result([],1,$e->getMessage(),0);
        }

        foreach ($res as $comment){
            $comment->user;
        }
        return result($res,0,"",$count);
    }

    /**
     * 获取推荐商品
     * @return \think\response\Json
     */
    public function getRecommendGood(){
        try {
            $res = Good::where(["status" => 1,"recommend" => 1])->limit(10)->orderRand()->select();
            foreach ($res as $good){
                $good->getImage;
            }
            return result($res,0);
        } catch (Exception $e) {
            return result([],1,$e->getMessage());
        }
    }

    public function getIndexRecommendStore(){
        if(!Request::has("lat") || !Request::has("lng")){
            return result([],1,"为获取到地址信息");
        }
        $lat = Request::param("lat");
        $lng = Request::param("lng");
        $sql = 'select a.id,a.name,a.business,show_img, FORMAT((6371 * acos (cos ( radians(' . $lat .') ) * cos( radians( lat ) )* cos( radians( lng ) - radians(' . $lng. ') ) + sin ( radians(' . $lat.') ) * sin( radians( lat ) ))),2) AS distance,b.name as industry_name FROM store a,admin_industries b where a.recommend = 1 and a.industry_id = b.id ORDER BY distance LIMIT 0 , 5';
        $recommendStores = Db::query($sql);
        return result($recommendStores,0);
    }

    /**
     * 获取首页商品
     * @return \think\response\Json
     */
    public function getIndexGood(){
        if(Request::has("page") && Request::has("limit")) {
            $page = Request::param("page");
            $limit = Request::param("limit");
            try {
                $res = Good::where("status",1)->field("id,RPAD(name,50,' ') as name,marketprice,productprice,order_type")->limit(($page - 1) * $limit, $limit)->orderRand()->select();
                if($res->count() > 0){
                    foreach ($res as $good){
                        $good->getImage;
                    }
                    return result($res,0);
                }else {
                    return result([],1,"没有更多了...");
                }
            } catch (Exception $e) {
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    /**
     * 获取分类数据
     */

    public function getCategory(){
        try {
            $res = GoodCategory::where("pid",0)
                ->where("status",1)
                ->field("id,name,banner")
                ->order("order desc")
                ->select();
            foreach ($res as $category){
                $category["child"] = $category->child()->where("status",1)->field("id,name,banner,desc")->select();
            }
            return result($res,0,"",$res->count());
        } catch (DbException $e) {
            return result([],1,$e->getMessage(),0);
        }
    }

    /**
     * 根据店铺ID获取店铺信息
     */
    public function getStoreInfo(){
        if(Request::has("id")){
            $id = Request::param("id");
            $store = Store::get($id);
            if($store){
                try {
                    $imgs = StoreBanner::where("store_id",$id)->select();
                    $data = [
                        "store_info" => $store,
                        "store_imgs" => $imgs
                    ];

                    return result($data,0);
                } catch (Exception $e) {
                    return result([],1,$e->getMessage());
                }
            }else {
                return result([],1,"未找到店铺信息");
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    /**
     * 获取店铺商品列表
     */
    public function getGoodListByStoreID(){
        if(Request::has("page") && Request::has("limit") && Request::has("id")) {
            $page = Request::param("page");
            $limit = Request::param("limit");
            $store_id = Request::param("id");
            try {
                $res = Good::where(["status"=>1,"store_id" => $store_id])->field("id,RPAD(name,50,' ') as name,marketprice,productprice")->limit(($page - 1) * $limit, $limit)->select();
                if($res->count() > 0){
                    foreach ($res as $good){
                        $good->getImage;
                    }
                    return result($res,0);
                }else {
                    return result([],1,"商品列表为空");
                }
            } catch (Exception $e) {
                return result([],1,$e->getMessage());
            }
        }else {
            return result([],1,"参数错误");
        }
    }

    /**
     * 获取行业列表
     */
    public function getIndustryList(){
        try {
            $result = Industries::where("status",1)->order("sort")->limit(0,10)->select();
            return result($result,0);
        } catch (Exception $e) {
            return result([],1,$e->getMessage());
        }
    }

    /**
     * 获取店铺列表(根据距离排序)
     */
    public function getStoreListOrderByDistance(){
        if(Request::has("lat") && Request::has("lng") && Request::has("page") && Request::has("limit")){
            $lat = Request::param("lat");
            $lng = Request::param("lng");
            $page = Request::param("page");
            $limt = Request::param("limit");
            $sql = 'select a.id,a.name,a.business,show_img,a.address, FORMAT((6371 * acos (cos ( radians(' . $lat .') ) * cos( radians( lat ) )* cos( radians( lng ) - radians(' . $lng. ') ) + sin ( radians(' . $lat.') ) * sin( radians( lat ) ))),2) AS distance,b.name as industry_name FROM store a,admin_industries b where a.industry_id = b.id and a.delete_time is null and a.status = 1 ORDER BY distance LIMIT ' . ($page - 1)*$limt .',' . $limt;
            $nearStores = Db::query($sql);
            return result($nearStores,0);
        }else {
            return result([],1,"参数不正确");
        }
    }

    /**
     * 获取店铺列表(根据是否推荐排序)
     */
    public function getStoreListOrderByRecommend(){
        if(Request::has("lat") && Request::has("lng") && Request::has("page") && Request::has("limit")){
            $lat = Request::param("lat");
            $lng = Request::param("lng");
            $page = Request::param("page");
            $limt = Request::param("limit");
            $sql = 'select a.id,a.name,a.business,show_img,a.address, FORMAT((6371 * acos (cos ( radians(' . $lat .') ) * cos( radians( lat ) )* cos( radians( lng ) - radians(' . $lng. ') ) + sin ( radians(' . $lat.') ) * sin( radians( lat ) ))),2) AS distance,b.name as industry_name FROM store a,admin_industries b where a.industry_id = b.id and a.delete_time is null and a.status = 1 ORDER BY recommend desc,a.update_time desc LIMIT ' . ($page - 1)*$limt .',' . $limt;
            $nearStores = Db::query($sql);
            return result($nearStores,0);
        }else {
            return result([],1,"参数不正确");
        }
    }


    /**
     * 获取店铺列表(根据访问量排序)
     */
    public function getStoreListOrderByVisits(){
        if(Request::has("lat") && Request::has("lng") && Request::has("page") && Request::has("limit")){
            $lat = Request::param("lat");
            $lng = Request::param("lng");
            $page = Request::param("page");
            $limt = Request::param("limit");
            $sql = 'select a.id,a.name,a.business,show_img,a.address, FORMAT((6371 * acos (cos ( radians(' . $lat .') ) * cos( radians( lat ) )* cos( radians( lng ) - radians(' . $lng. ') ) + sin ( radians(' . $lat.') ) * sin( radians( lat ) ))),2) AS distance,b.name as industry_name FROM store a,admin_industries b where a.industry_id = b.id and a.delete_time is null and a.status = 1 ORDER BY visits desc,a.update_time desc LIMIT ' . ($page - 1)*$limt .',' . $limt;
            $nearStores = Db::query($sql);
            return result($nearStores,0);
        }else {
            return result([],1,"参数不正确");
        }
    }

    /**
     * 获取店铺列表(根据访问量排序)
     */
    public function getStoreListOrderByCreate(){
        if(Request::has("lat") && Request::has("lng") && Request::has("page") && Request::has("limit")){
            $lat = Request::param("lat");
            $lng = Request::param("lng");
            $page = Request::param("page");
            $limt = Request::param("limit");
            $sql = 'select a.id,a.name,a.business,show_img,a.address, FORMAT((6371 * acos (cos ( radians(' . $lat .') ) * cos( radians( lat ) )* cos( radians( lng ) - radians(' . $lng. ') ) + sin ( radians(' . $lat.') ) * sin( radians( lat ) ))),2) AS distance,b.name as industry_name FROM store a,admin_industries b where a.industry_id = b.id and a.delete_time is null and a.status = 1 ORDER BY a.create_time desc,visits desc LIMIT ' . ($page - 1)*$limt .',' . $limt;
            $nearStores = Db::query($sql);
            return result($nearStores,0);
        }else {
            return result([],1,"参数不正确");
        }
    }

    /**
     * 获取店铺的商品分类
     */

    public function getStoreCategory(){
        if(Request::has("store_id")){
            $store_id = Request::param("store_id");
            $sql = 'select * from good_category where id in (select distinct category_id from good where store_id = ' . $store_id . ' and status = 1 and delete_time is null) and status = 1';
            $storeCategory = Db::query($sql);
            return result($storeCategory,0);
        }else {
            return result([],1,"参数不正确");
        }
    }

    /**
     * 根据店铺和分类获取商品列表
     */
    public function getGoodsByStoreIdAndCategoryId(){
        $data = Request::param();
        $validate = Validate::make([
            "store_id|店铺ID" => 'require|number',
            "page|页数" => "require|number",
            "limit" => "require|number",
        ]);

        if($validate->check($data)){
            $page = Request::param("page");
            $limit = Request::param("limit");

            unset($data["page"]);
            unset($data["limit"]);
            $data["status"] = 1;

            if($data["category_id"] == 'null'){
                unset($data["category_id"]);
            }

            try {
                $res = Good::where($data)->limit(($page-1)*$limit,$limit)->select();
                if($res->count() > 0){
                    foreach ($res as $good){
                        $good->getImage;
                    }
                    return result($res,0);
                }else {
                    return result([],1,"商品列表为空");
                }
            } catch (Exception $e) {
                return result([],1,$e->getMessage());
            }

        }else {
            return result([],1,$validate->getError());
        }
    }

    /**
     * 获取短信验证码
     */
    public function getSmsCode(){
        $data = Request::param();
        $validate = Validate::make([
            "phone|手机号" => 'require|mobile'
        ]);

        if($validate->check($data)){
            // 检查新手机号是否已绑定
            try {
                $phone = Request::param("phone");
                $user = User::where("phone",$phone)->find();
                if($user){
                    return result([],1,"该手机号已被使用，不能绑定");
                }
            } catch (DbException $e) {
                return result([],1,$e->getMessage());
            }

            $code = newSmscode();
            $content = "您的验证码" . $code .",该验证码5分钟内有效，请勿泄露于他人";
            try {
                $res = sendSms($phone,$content);
            } catch (\Exception $e) {
                return result([],1,$e->getMessage());
            }

            $no = newVerificationCode();
            $data = [
                "code" => $code,
                "phone" => $phone,
                "no" => $no,
                "content" => $content,
                "status" => $res,
            ];

            $smsRecord = new SmsRecord();
            $smsRecord->save($data);
            // 发送成功
            if(strpos($res,"OK:") === 0){
                return result($no,0,"短信发送成功");
            }else {
                return result([],1,"短信发送失败");
            }
        }else {
            return result([],1,$validate->getError());
        }
    }

    /**
     * 绑定用户手机号
     */
    public function bindPhone(){
        $data = Request::param();
        $validate = Validate::make([
            "user_id|用户ID" => 'require|number',
            "phone|手机号" => 'require|mobile',
        ]);

        if(!$validate->check($data)){
            return result([],1,$validate->getError());
        }

        try {
            $record = SmsRecord::where(["no"=>$data["no"],"code"=>$data["code"]])->find();
            if($record){
                $user = User::get($data["user_id"]);
                if($user){
                    $user->phone = $data["phone"];
                    $user->save();
                    return result([],0,"绑定成功");
                }else {
                    return result([],1,"未找到用户信息");
                }
            }else {
                return result([],1,"验证码和手机号不匹配");
            }
        } catch (Exception $e) {
            return result([],1,$e->getMessage());
        }

    }

    /**
     * 根据上传的good_id,option_id,count获取详细信息，用于展示在前端
     */
    public function getGoodData(){
        if(Request::has("data")){
            $data = json_decode(urldecode(Request::param("data")));

            $res = [];
            foreach ($data as $store){
                try {
                    $store_info = Store::where(["id"=>$store->store_id,"status" => 1])->find();
                    if($store_info){
                        $goodList = [];
                        foreach($store->goods as $good){
                            $good_info = Good::where(["id"=>$good->good_id,"status"=>1])->find();
                            if($good_info){
                                $good_info->getImage;
                                $option_info = GoodOption::get($good->option_id);
                                $temp_good = $good_info->toArray();
                                $temp_good["option"] = $option_info;
                                $temp_good["count"] = $good->count;
                                $temp_good["selected"] = true;

                                array_push($goodList,$temp_good);
                            }else {
                                return result([],1,"商品信息有误");
                            }
                        }

                        $temp_store = $store_info->toArray();
                        $temp_store["goodList"] = $goodList;
                        array_push($res,$temp_store);
                    }else {
                        return result([],1,"店铺已停用");
                    }
                } catch (Exception $e) {
                    return result([],1,$e->getMessage());
                }
            }
            return result($res,0);
        }else {
            return result([],1,"参数错误");
        }
    }

    public function getHotSearch(){
        try {
            $res = HotSearch::where("status",1)->field("text,id")->select();
            return result($res);
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }

    }

    public function getGoodListByKeyword(){
        if(!Request::has("keyword")){
            return result([],1,"参数错误");
        }

        $keyword = Request::param("keyword");
        $order = Request::param("order");
        $page = Request::param("page");
        $limit = Request::param("limit");
        try {
            $res = Good::where("name",'like', '%'.$keyword . '%')
                ->where("status",1)
                ->order($order)
                ->limit(($page -1)*$limit,$limit)
                ->select();
            foreach ($res as $good) {
                $good->getImage;
            }

            return result($res,0);
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }

    /**
     * 获取推荐商品
     * @return \think\response\Json
     */
    public function getRecommendGoodList(){
        try {
            $order = Request::param("order");
            $page = Request::param("page");
            $limit = Request::param("limit");
            $res = Good::where(["status" => 1,"recommend" => 1])
                ->order($order)
                ->limit(($page-1)*$limit,$limit)
                ->select();
            foreach ($res as $good){
                $good->getImage;
            }
            return result($res,0);
        } catch (Exception $e) {
            return result([],1,$e->getMessage());
        }
    }


    /**
     * 店铺搜索
     * @return \think\response\Json
     */
    public function getStoreListByKeyword(){
        if(!Request::has("keyword")){
            return result([],1,"参数错误");
        }

        $keyword = Request::param("keyword");
        $page = Request::param("page");
        $limit = Request::param("limit");
        try {
            $res = Store::where("name",'like', '%'.$keyword . '%')
                ->where("status",1)
                ->limit(($page -1)*$limit,$limit)
                ->select();
            return result($res,0);
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }

    /**
     * 根据行业获取店铺
     * @return \think\response\Json
     */
    public function getStoreListByIndustryId(){
        if(!Request::has("industry_id")){
            return result([],1,"参数错误");
        }

        $industry_id = Request::param("industry_id");
        $page = Request::param("page");
        $limit = Request::param("limit");
        try {
            $res = Store::where("industry_id",$industry_id)
                ->where("status",1)
                ->limit(($page -1)*$limit,$limit)
                ->select();
            return result($res,0);
        } catch (DbException $e) {
            return result([],1,$e->getMessage());
        }
    }

    /**
     * 增加店铺访问量
     */
    public function addStoreVisit(){
        if(!Request::has("store_id")){
            return result([],1,"参数错误");
        }

        $store_id = Request::param("store_id");
        $store = Store::get($store_id);
        $visits = $store->visits + 1;
        $store->visits = $visits;
        $store->save();
        return result();
    }



}