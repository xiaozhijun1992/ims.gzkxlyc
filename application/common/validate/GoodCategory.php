<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 11:10
 */

namespace app\common\validate;


use think\Validate;

class GoodCategory extends Validate
{
    protected $rule = [
        "name|分类名称" => 'require:chs|length:2,5'
    ];

}