<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-21
 * Time: 09:25
 */

namespace app\common\validate;


use think\Validate;

class Good extends Validate
{
    protected $rule = [
        "name|商品名称" => 'require|length:15,50',
        "unit|商品单位" => 'require|length:1,2',
        "category_id|商品分类" => 'require|number',
        "order|排序" => 'number',
        "order_type|订单类型" => 'require|number',
        "marketprice|商品售价" => 'require|float',
        "productprice|商品原价" => 'float',
        "costprice|商品成本价" => 'float',
        "sales|销量" => 'between:0,500',
        "total|库存" => 'lt:999999999',
        "detail|详情" => 'require',
        "maxbuy|最多购买" => 'number',
        "minbuy|最低购买" => 'number'
    ];

}