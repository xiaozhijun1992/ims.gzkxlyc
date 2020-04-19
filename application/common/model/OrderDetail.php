<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-25
 * Time: 12:22
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class OrderDetail extends Model
{
    use SoftDelete;
    protected $table = 'good_order_detail';
    protected $deleteTime = 'delete_time';

    public function img(){
        return $this->hasOne("goodImage","good_id","good_id");
    }
}