<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-16
 * Time: 21:29
 */

namespace app\common\validate;


use think\Validate;

class Swipe extends Validate
{
    protected $rule = [
        "title|标题" => 'require|chs|length:2,10',
        "img|轮播图片" => 'require',
        "link_type|链接类型" => 'require|number|in:1,2',
        "link_id|链接项目ID" => 'require|number'
    ];

}