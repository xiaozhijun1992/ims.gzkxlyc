<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/6
 * Time: 22:41
 */

namespace app\common\controller;
use think\Controller;

class Wechat extends Controller
{
    protected $accessTokenUrl = 'https://api.weixin.qq.com/cgi-bin/token';
    protected $wechatAuthCodeUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    protected $userOpenIdUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    protected $appId='wx8d22286d1cfd6a86';
    protected $secret='412c59e2bc126a783aae9e578bc1c361';
    protected $code;
    protected $openId;
	protected $access_token;

    /**
     * 加载微信配置
     */
//  protected function _initialize(){
//      $this->appId = 'wx8d22286d1cfd6a86';
//      $this->secret = '412c59e2bc126a783aae9e578bc1c361';
//		
//  }
    
    /**
     * 作用：格式化参数，签名过程需要使用
     * @param $paraMap
     * @param $urlencode
     * @return bool|string
     */
    protected function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    /**
     * 网页授权获取用户openId -- 1.获取授权code url
     */
    public function getWechatAuthCode(){
        // 获取来源地址
        $url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

        // 获取code
        $urlObj["appid"] = $this->appId;
        $urlObj["redirect_uri"] = "$url";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_userinfo";
        $urlObj["state"] = "STATE"."#wechat_redirect";

        $bizString = $this->formatBizQueryParaMap($urlObj, false);
        $codeUrl =  $this->wechatAuthCodeUrl.'?'.$bizString;
		
        return $codeUrl;
    }

    /**
     * 网页授权获取用户openId -- 2.获取openid
     * @return mixed
     */
    public function getUserOpenId(){
    	
        if (!isset($_GET['code']))
        {
            $codeUrl = $this->getWechatAuthCode();
            Header("Location: $codeUrl");
            die;
        }else{
            $code = $_GET['code'];
            $this->code = $code;

            // 请求openid
            $param = [
                'appid'     =>  $this->appId,
                'secret'    =>  $this->secret,
                'code'      =>  $this->code,
                'grant_type'=>  "authorization_code",
            ];

          	$data = file_get_contents($this->userOpenIdUrl.'?appid='.$this->appId. '&secret='.$this->secret.'&code='.$this->code.'&grant_type=authorization_code');
            //取出openid
			
            $this->openId = json_decode($data)->openid;
			$this->access_token = json_decode($data)->access_token;
            return $this->openId;
        }
    }
	function user_openid(){
		
		$this->openId=$this->getUserOpenId();
		
		$info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$this->access_token.'&openid='.$this->openId.'&lang=zh_CN';
		$info = json_decode(file_get_contents($info_url));
		
		return $info;
	}
	function user_jsapi(){
		$this->access_token=
		$userjsapi='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$this->access_token.'&type=jsapi';
		$info = json_decode(file_get_contents($userjsapi));
		print_r($userjsapi);
		exit;
	}
	function getSignPackage() {
	$appId=$this->appId;
	$secret=$this->secret;
	
    $jsapiTicket = $this->getJsApiTicket($appId,$secret);
    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();
    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";

    $signature = sha1($string);
    // var_dump($signature);die;
    $signPackage = array(
      "appId"     => $appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}

function getJsApiTicket($appId,$appSecret) {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
	
	$data = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."\jsapi_ticket.json"));
     //var_dump($data->expire_time);die;
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken($appId,$appSecret);
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$accessToken}";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen($_SERVER['DOCUMENT_ROOT']."\jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }
    return $ticket;
  }

function getAccessToken($appId,$appSecret) {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."\access_token.json"));
    if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
	  
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $fp = fopen($_SERVER['DOCUMENT_ROOT']."\jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
}

	function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}


}