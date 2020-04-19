<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-04
 * Time: 21:07
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodOrderRelationship extends Model
{
    use SoftDelete;
    protected $table = 'good_order_relationship';
    protected $deleteTime = 'delete_time';

    public function child(){
        return $this->hasMany("GoodOrder","order_code","order_code_child");
    }

}