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


class Users extends controller
{
    public function bindphone()
    {
    	
		$Wechat = new Wechat();
//		$set=$Wechat->getUserOpenId();
		
        return $this->fetch();
    }
	public function address()
    {
    	return $this->fetch();
    }
	public function newaddress()
    {
    	return $this->fetch();
    }

}
