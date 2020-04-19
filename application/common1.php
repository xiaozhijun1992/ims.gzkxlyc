<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function result($data=[], $code=0,$message="",$count=0){
    $res = [ "code"=>$code,"data" => $data, "message" => $message, "count"=>$count];
    return json($res,200);
}

/**
 * @return string
 * 生成用户代码
 */
function newUserCode(){
    return strtoupper(uniqid("KXLY"));
}

/**
 * @return string
 * 生成短信验证码唯一编号
 */
function newVerificationCode(){
    return strtoupper(uniqid("DXYZ"));
}

/**
 * 随机产生短信验证码
 */
function newSmscode(){
    $key = '';
    $pattern = '1234567890';
    for ($i = 0; $i < 6; $i++) {
        $key .= $pattern[mt_rand(0, 9)];
    }
    return $key;
}

/**
 * @param $phone
 * @param $content
 * @return array|mixed|null|PDOStatement|string|\think\Model
 * @throws Exception
 */
function sendSms($phone,$content){
    $time = date("Y-m-d H:i:s",time()-60);
    try {

        // 检查验证码是否发送频繁
        $res = \app\common\model\SmsRecord::where("phone",$phone)->where("create_time","> time",$time)->find();
        if($res){
            exception("验证码发送频繁，请稍后重试");
        }
        $url = 'http://gateway.iems.net.cn/GsmsHttp?username=71484:admin&password=8699077&from=001&to=' . $phone .'&content=' . mb_convert_encoding($content,"GBK");
        $res = do_get($url,[]);
        return $res;
    } catch (\think\Exception $e) {
        exception($e->getMessage());
    }
}


/**
 * 生成唯一的微信授权状态码
 * @return string
 */
function newWeiXinState(){
    return sha1(uniqid());
}

/**
 * @param $value
 * @return mixed
 * 将反斜杠替换成斜杠，用于文件上传后修改路径
 */
function conventPath($value){
    return str_replace('\\','/',$value);
}

/**
 * 生成子订单号
 * @return string
 */
function createChildOrderCode(){
    $order_code = "C" . date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $order_code;
}

/**
 * 生成父订单号
 * @return string
 */
function createParentOrderCode(){
    $order_code = "P" . date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $order_code;
}


/**
 * 生成退款单号
 * @return string
 */
function createRefundCode(){
    $order_code = "R" . date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $order_code;
}

/**
 * 生成付款到零钱订单号
 * @return string
 */
function createTransfersOrderCode(){
    $order_code = "T" . date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $order_code;
}

/**
 * 获取登录用户的user_id
 * @return bool|mixed
 */
function getUid(){
    if(\think\facade\Session::has("uid")) {
        return \think\facade\Session::get("uid");
    }else {
        return false;
    }
}

/**
 * 获取已登录用户的文件目录(用户所有上传的文件、图片、涂鸦都存储在这个位置下)
 * @return string
 */
function getUserFilePath(){
    if(\think\facade\Session::has("uid")){
        return 'uploads/' .\think\facade\Session::get("uid") .'/';
    }else {
        return 'uploads';
    }
}

/**
 * 判断是否微信内置浏览器访问
 * @return bool
 */
function isWxClient()
{
    return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
}

/**
 * 判断是否支付宝内置浏览器访问
 * @return bool
 */
function isAliClient()
{
    return strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay') !== false;
}

/**
 * 将unicode字符转化成中文
 * @param $name
 * @return string
 */
function unicode_decode($name){
    $json = '{"str":"'.$name.'"}';
    $arr = json_decode($json,true);
    if(empty($arr)) return '';
    return $arr['str'];
}

/**
 * 通过wx.login换取code，在通过该函数获取用户的信息unionid
 * @param $code string
 * @return mixed
 */
function jscode2session($code){
    $url = 'https://api.weixin.qq.com/sns/jscode2session';
    $param = [
        "appid" => \think\facade\Config::get("wxapp.appid"),
        "secret" => \think\facade\Config::get("wxapp.appsecret"),
        "js_code" => $code,
        "grant_type" => "authorization_code"
    ];
    $result = do_get($url,$param);
    return $result;
}

/**
 * 根据微信unionid获取用户信息
 * @param $unionid
 * @param $openid
 * @return array|bool|null||string|\think\Model
 */
function getUserInfoByUnionid($unionid,$openid){
    try {
        $res = null;
        $res = \app\common\model\User::where("wx_unionid",$unionid)->find();
        if(!$res){
            $res = \app\common\model\User::where("wxapp_openid",$openid)->find();
        }

        if(!$res){
            return false;
        }
        $user = \app\common\model\User::get($res->id);
        // 如果用户没有code，则创建一个code赋予用户
        if($user->code == null || $user->code == ""){
            $code = newUserCode();
            \think\facade\Log::record($code);
            $user->save(["code"=>$code]);
        }
        \think\facade\Log::record("用户有code：". $user->id);
        $user->save(["wxapp_openid"=>$openid]);
        return $user;
    } catch (\think\exception\DbException $e) {
        return false;
    }

}

/**
 * 发起Get请求
 * @param $url string
 * @param $params array
 * @return mixed
 */
function do_get($url, $params) {
    $url = "{$url}?" . http_build_query ( $params );
    $ch = curl_init();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );
    $result = curl_exec ( $ch );
    curl_close ( $ch );
    return $result;
}

/**
 * 发送POST请求
 * @param $url
 * @param array $param
 * @return mixed
 */
function do_post($url, $param=array()){
    $ch =curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
    $result=curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * 检查商品$good_id(商品ID)是否属于当前操作会话的店铺
 * @param $good_id
 * @return bool
 */
function checkGoodIsBlongToStore($good_id){
    if(\think\facade\Session::has("store_info.id")){
        // 从Session中获取店铺ID
        $store_id = \think\facade\Session::get("store_info.id");
        $good = \app\common\model\Good::get($good_id);
        if($store_id === $good->store_id){
            return true;
        }else {
            return false;
        }
    }else {
        return false;
    }

}

/**
 * 检查业务员下是否还有店铺
 * @param $salemanId
 * @return bool
 * @throws Exception
 */
function checkHasStoreBySalesmanId($salemanId){
    try {
        $res = \app\common\model\Store::where("manager_id",$salemanId)->select();
        if($res->count() > 0){
            return true;
        }else {
            return false;
        }
    } catch (\think\exception\DbException $e) {
        exception($e->getMessage());
    }

}

/**
 * 微信小程序获取接口调用凭证
 * @param string $grant_type
 * @return mixed
 */
function wxapptoken($grant_type="client_credential"){
    $url = "https://api.weixin.qq.com/cgi-bin/token";
    $param = [
        "grant_type" => $grant_type,
        "appid" => \think\facade\Config::get("wxapp.appid"),
        "secret" => \think\facade\Config::get("wxapp.appsecret")
    ];
    $result = do_get($url,$param);
    return json_decode($result);
}

/**
 * 微信公众号获取接口调用凭证
 * @param string $grant_type
 * @return mixed
 */
function getwxmptoken($grant_type="client_credential"){
    $url = "https://api.weixin.qq.com/cgi-bin/token";
    $param = [
        "grant_type" => $grant_type,
        "appid" => \think\facade\Config::get("wxmp.appid"),
        "secret" => \think\facade\Config::get("wxmp.appsecret")
    ];
    $result = do_get($url,$param);
    return json_decode($result);
}

/**
 * 发送公众号模板消息
 * @param $template string 对应wxmp_template配置文件中的模板消息ID
 * @param $openid string 接收用户的openid（公众号对应）
 * @param $data array 数据数组，需要和模板消息对应
 * @return mixed
 */
function sendMpMessage($template,$openid,$data){
    $token = getwxmptoken();
    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='. $token->access_token;
    $message = [
        "touser" => $openid,
        "template_id" => \think\facade\Config::get("wxmp_template." . $template),
        "miniprogram"=>[
            "appid"=>"wx8e388427d88811b5",
        ],
        "data" => $data
    ];

    $result = do_post($url,$message);
    \think\facade\Log::record("发送微信公众号消息");
    \think\facade\Log::record($message);
    \think\facade\Log::record($result);
    return $result;
}

/**
 * 生成订单通知（发给店铺）
 * @param $first string
 * @param $orderMount string
 * @param $orderDetail string
 * @param $orderCode string
 * @param $buyName string
 * @param $remark string
 * @return array
 */
function newOrderNotificationData($first,$orderMount,$orderDetail,$orderCode,$buyName,$remark){
    $data = [
        "first" => [
            "value" => $first,
            "color" => "#FF0000"
        ],
        "keyword1" => [
            "value" => $orderMount,
            "color" => "#173177"
        ],
        "keyword2" => [
            "value" => $orderDetail,
        ],
        "keyword3" => [
            "value" => $orderCode,
            "color" => "#173177"
        ],
        "keyword4" => [
            "value" => $buyName,
        ],
        "remark" => [
            "value" => $remark,
        ]
    ];
    return $data;
}

/**
 * 扫码支付成功通知（发给商户）
 * @param $first string
 * @param $orderMoneySum string
 * @param $orderProductName string
 * @param $Remark string
 * @return array
 */
function scanPayNotificationData($first,$orderMoneySum,$orderProductName,$Remark){
    $data = [
        "first" => [
            "value" => $first,
            "color" => "#FF0000"
        ],
        "orderMoneySum" => [
            "value" => $orderMoneySum,
            "color" => "#173177"
        ],
        "orderProductName" => [
            "value" => $orderProductName,
        ],
        "Remark" => [
            "value" => $Remark,
        ]
    ];
    return $data;
}

/**
 * 发送佣金提醒
 * @param $first
 * @param $amount
 * @param $time
 * @param $remark
 * @return array
 */
function commissionNotificationData($first,$amount,$time,$remark){
    \think\facade\Log::record("佣金金额:",$amount);
    $data = [
        "first" => [
            "value" =>$first,
            "color" => "#FF0000",
        ],
        "keyword1" => [
            "value" =>$amount
        ],
        "keyword2" => [
            "value" =>$time
        ],
        "remark" => [
            "value" =>$remark
        ]
    ];
    return $data;
}

/**
 * 发送订单支付成功通知
 * @param $first
 * @param $amount
 * @param $orderCode
 * @param $remark
 * @return array
 */
function orderPaySuccessData($first,$amount,$orderCode,$remark){
    $data = [
        "first" => [
            "value" =>$first,
            "color" => "#FF0000",
        ],
        "keyword1" => [
            "value" =>$amount
        ],
        "keyword2" => [
            "value" =>$orderCode
        ],
        "remark" => [
            "value" =>$remark
        ]
    ];
    return $data;
}


/**
 * 获取微信小程序二维码图片
 * @param $access_token string
 * @param $scene string
 * @param $page string
 * @param $width integer
 * @param $line_color array
 * @param $is_hyaline boolean
 * @return mixed
 */
function getwxacodeunlimit($access_token,$scene,$page,$width,$line_color,$is_hyaline){
    $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
    $param = [
        "scene" => $scene,
        "page" => $page,
        "width" => $width,
        "line_color" => $line_color,
        "is_hyaline" => $is_hyaline
    ];
    $result = do_post($url,$param);
    return $result;
}

/**
 * 检查行业下面是否还有的商户(不管状态),有或者报错就返回true，否则返回false；
 * @param $industry_id
 * @return bool
 */
function checkHasStoreInIndustry($industry_id){
    try {
        $store = \app\common\model\Store::where("industry_id",$industry_id)->select();
        if($store->count() !== 0){
            return true;
        }else {
            return false;
        }
    } catch (Exception $e) {
        return true;
    }
}

function checkHasStoreInArea($area_id){
    try {
        $store = \app\common\model\Store::where("area_id",$area_id)->select();
        if($store->count() !== 0){
            return true;
        }else {
            return false;
        }
    } catch (Exception $e) {
        return true;
    }
}

/**
 * 检查区域下面是否还有业务员
 * @param $area_id
 * @return bool
 */
function checkHasSalemanInArea($area_id){
    try {
        $store = \app\common\model\Salesman::where("area_id",$area_id)->select();
        if($store->count() !== 0){
            return true;
        }else {
            return false;
        }
    } catch (Exception $e) {
        return true;
    }
}

/**
 * 检查商品分类下是否还有商品
 * @param $category_id
 * @return bool
 */
function checkHasGoodUnderCategory($category_id){
    $goodCount = \app\common\model\Good::where("category_id",$category_id)->count();
    if($goodCount > 0){
        return true;
    }else {
        try {
            $childCategory = \app\common\model\GoodCategory::where("pid",$category_id)->select();
            foreach ($childCategory as $category){
                $childGoodCount = \app\common\model\Good::where("category_id",$category->id)->count();
                if($childGoodCount > 0){
                    return true;
                }
            }
            return false;
        } catch (\think\Exception $e) {
            return true;
        }

    }
}

/**
 * 插入分销记录,必须是订单完成以后（扫码支付不受限制）
 * @param $data array
 */
function insertDistributeRecode($data){
    $recode = new \app\common\model\DistributeRecord();
    $recode->save($data);
    if($data["user_id"] !== null &&  $user = \app\common\model\User::get($data["user_id"])){
        $amount = $user->distribute_amount ? 0: $user->distribute_amount;
        $amount = $amount + $data["get_amount"];
        $user->distribute_amount = $amount;
        $user->save();
    }
}
