<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-06
 * Time: 20:00
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class StoreSettleRecord extends Model
{
    use SoftDelete;
    protected $table = 'store_settle_record';
    protected $deleteTime = 'delete_time';

    public function store(){
        return $this->hasOne("Store","id","store_id");
    }

}