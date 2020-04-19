<?php
namespace app\home\controller;

use app\common\model\SmsRecord;

use app\common\model\Store;
use app\common\model\StoreApplication;
use app\common\model\User;
use app\common\controller\Wechat;
use think\captcha\Captcha;
use think\Controller;
use think\exception\DbException;
use think\facade\Request;
use think\Validate;
use think\facade\Session;


class Index extends controller
{
    public function index()
    {
    	
		$Wechat = new Wechat();
		//$set=$Wechat->getUserOpenId();
		//
		//$this->assign('user',$user->id);
		
		if(!Session::get('user_open')){
			$cc=$Wechat->user_openid();
			Session::set('user_open',$cc);
		}else{
			$cc=Session::get('user_open');
		}
		
		$signature=$Wechat->getSignPackage();
		Session::set('signature',$signature);
		$users=User::field('id')->where('wx_unionid',$cc->unionid)->find();
		if(!$users){
			$add_user=new User;
			$add_user->wx_openid=$cc->openid;
			$add_user->avator=$cc->headimgurl;
			$add_user->nick_name=$cc->nickname;
			$add_user->status=1;
			$add_user->wx_unionid=$cc->unionid;
			$add_user->sex=$cc->sex;
			$add_user->city = $cc->city;
			$add_user->province = $cc->province;
			$add_user->country = $cc->country;
			$add_user->code=newUserCode();
			if($add_user->save()){
				$user=$add_user->id;
			}
		}else{
			if(!isset($users->wx_openid)){
				$users->wx_openid=$cc->openid;
				$users->save();
			}
			$user=$users->id;
		}
		
		$this->assign('user',$user);
		$this->assign('signPackage',$signature);
        return $this->fetch();
    }

    


}
