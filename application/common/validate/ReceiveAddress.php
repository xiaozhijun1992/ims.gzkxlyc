<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-23
 * Time: 21:07
 */

namespace app\common\validate;


use think\Validate;

class ReceiveAddress extends Validate
{
    protected $rule = [
        "user_id|用户ID" => 'require|number',
        "name|姓名" => "require|length:2,10",
        "phone|手机号" => 'require|mobile',
        "province|省" => 'require',
        "city|市" => "require",
        "county|县" => 'require',
        "detail_address|详细地址" => 'require'
    ];

}