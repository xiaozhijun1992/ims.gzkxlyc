<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-23
 * Time: 00:15
 */

namespace app\admin\controller;

use app\common\controller\AdminBase;

class Sms extends AdminBase
{
    public function index(){
        $this->assign("nav_list",['首页','平台管理','短信配置']);
        return $this->fetch();
    }


}