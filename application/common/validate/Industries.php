<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-16
 * Time: 22:42
 */

namespace app\common\validate;


use think\Validate;

class Industries extends Validate
{
    protected $rule = [
        "name|行业名称" => 'require|chs|length:2,10',
        "platform|平台提点" => 'require|number|between:0,80',
        "shareholder|股东分红比例" => 'require|number|between:0,80',
        "area|区域代理佣金比例" => 'require|number|between:0,80',
        "manager|区域经理佣金比例" => 'require|number|between:0,80',
        "self|自购佣金比例" => 'require|number|between:0,80',
        "one|一级分销佣金比例" => 'require|number|between:0,500',
        "two|二级分销佣金比例" => 'require|number|between:0,100',
        "sort|排序" => 'require|number'
    ];

}