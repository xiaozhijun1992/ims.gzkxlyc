<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-22
 * Time: 11:43
 */

namespace app\common\validate;


use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        "name|规则标识" => 'require|unique:AuthRule,name',
        "title|规则名称" => 'require|unique:AuthRule,title',
        "status|状态" => 'in:0,1'
    ];

}