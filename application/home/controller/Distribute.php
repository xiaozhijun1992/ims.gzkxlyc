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


class Distribute extends controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function distributerecord(){
    	return $this->fetch();
    }
	public function team(){
    	return $this->fetch();
    }

}
