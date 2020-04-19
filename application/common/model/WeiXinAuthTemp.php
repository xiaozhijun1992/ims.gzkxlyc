<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-26
 * Time: 13:58
 */

namespace app\common\model;


use think\Model;

class WeiXinAuthTemp extends Model
{
    protected $table = 'weixin_auth_temp';
    protected $pk = 'state';

}