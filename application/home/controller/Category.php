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


class Category extends controller
{
    public function categorylist()
    {
    	
//		$Wechat = new Wechat();
//		$set=$Wechat->getUserOpenId();
		
        return $this->fetch();
    }

    


}
