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


class Tabbar extends controller
{
    public function store()
    {
    	
		$Wechat = new Wechat();
//		$set=$Wechat->getUserOpenId();
		
        return $this->fetch();
    }

    public function my(){
    	return $this->fetch();
    }
	public function cart(){
    	return $this->fetch();
    }
	public function distribute(){
    	return $this->fetch();
    }

}
