<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-20
 * Time: 22:43
 */

namespace app\common\validate;


use think\Validate;

class Area extends Validate
{
    protected $rule = [
        "province|省级代码" => 'require|number',
        "city|市级代码" => 'require|number',
        "manager_id|区域代理人员ID" => 'require|number',
        "country|县级代码" => 'require|number|unique:area,province^city^country'
    ];

}