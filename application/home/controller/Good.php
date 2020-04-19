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


class Good extends controller
{
    public function good()
    {
        return $this->fetch();
    }
	public function share()
    {
        return $this->fetch();
    }
    
	public function allcomments(){
		return $this->fetch();
	}
	public function search(){
		return $this->fetch();
	}
	public function SearchGoodList(){
		return $this->fetch();
	}
	public function getkeepgoodlist(){
		return $this->fetch();
	}
	
	
}
