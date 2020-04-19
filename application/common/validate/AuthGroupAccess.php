<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-28
 * Time: 17:39
 */

namespace app\common\validate;


use think\Validate;

class AuthGroupAccess extends Validate
{
    protected $rule = [
        "uid|用户ID" => 'require|number|unique:AuthGroupAccess',
        "group_id|权限组" => 'require|number'
    ];

}