<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-21
 * Time: 11:08
 */

namespace app\common\validate;


use think\Validate;

class AreaManager extends Validate
{
    protected $rule = [
        "user_id|用户ID" => 'require|number|unique:areaManager,user_id',
        "real_name|真实姓名" => 'require|chs|length:2,10',
        "sex|性别" => 'require|in:0,1',
        "phone|手机号码" => 'require|mobile',
        "id_no|身份证号" => 'require|idCard|unique:areaManager,id_no',
        "address|地址" => 'require|length: 0,255',
        "agreement|协议书" => 'require',
        "id_photo|身份证扫描件" => 'require'
    ];


}