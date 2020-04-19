<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-16
 * Time: 22:04
 */

namespace app\common\validate;


use think\Validate;

class HotSearch extends Validate
{
    protected $rule = [
        "text|标题" => 'require|chs|length:2,8|unique:HotSearch',
        "sort|排序" => 'number'
    ];

}