<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-21
 * Time: 21:31
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class AuthGroup extends Model
{
    use SoftDelete;
    protected $table = 'admin_auth_group';
    protected $deleteTime = 'delete_time';
}