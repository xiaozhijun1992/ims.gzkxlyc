<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 16:41
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;


class GoodOrder extends Model
{
    use SoftDelete;
    protected $table = 'good_order';
    protected $deleteTime = 'delete_time';
    protected $pk = 'order_code';

    public function store(){
        return $this->hasOne("store","id","store_id");
    }

    public function user(){
        return $this->hasOne("user","id","user_id");
    }

    public function ship(){
        return $this->hasOne("ExpressCompany","code","shipper_code");
    }

    public function getPayStatusAttr($value){
        $status = [
            '0' => '未支付',
            "1" => "已支付",
            "2" => "已取消",
            "3" => "已发货",
            "4" => "确认收货"
        ];
        return $status[$value];
    }

    public function getShipStatusAttr($value){
        $status = [
            null=>'无轨迹',
            '0' => '无轨迹',
            "1" => "已揽收",
            "2" => "在途中",
            "3" => "签收",
            "4" => "问题件"
        ];
        return $status[$value];
    }

    public function getAdminStatusAttr($value){
        $status = [
            "0" => "已冻结",
            "1" => "正常",
        ];
        return $status[$value];
    }

    public function detail(){
        return $this->hasMany("OrderDetail","order_code","order_code");
    }

    // 用于通过withCount查询订单中有多少线上发货的商品，如果数量大于0，表示需要发货，则需要在商家后台显示发货按钮。
    public function onlineDetail(){
        return $this->hasMany("OrderDetail","order_code","order_code")->where("offline",0);
    }

}