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


class Order extends controller
{
    public function orderconfirm()
    {
		$signature = Session::get('signature');
		$this->assign('signPackage',$signature);
        return $this->fetch();
    }
	public function orderlist()
    {
    	
        return $this->fetch();
    }
    public function ordercc()
    {
    	
        return $this->fetch();
    }


}
