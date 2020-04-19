<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-22
 * Time: 23:52
 */

namespace app\common\validate;


use think\Validate;

class Member extends Validate
{
    protected $rule = [
        "user_id|用户" => 'require|number|unique:member',
        "name|员工姓名" => 'require|chs|length:2,5|unique:member',
        "phone|手机号" => 'require|mobile|unique:member',
    ];

}