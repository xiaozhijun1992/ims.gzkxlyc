<?php
namespace app\home\controller;

use app\common\model\SmsRecord;

use app\common\model\Store;
use app\common\model\StoreApplication;
use app\common\model\User;
use think\captcha\Captcha;
use think\Controller;
use think\exception\DbException;
use think\facade\Request;
use think\Validate;


class Stores extends controller
{
    public function stores()
    {
        return $this->fetch();
    }
	public function industrystorelist()
    {
        return $this->fetch();
    }
	

}
?>