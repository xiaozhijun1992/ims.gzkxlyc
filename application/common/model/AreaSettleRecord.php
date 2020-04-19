<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-26
 * Time: 08:47
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class AreaSettleRecord extends Model
{
    use SoftDelete;
    protected $table = 'area_settle_record';
    protected $deleteTime = 'delete_time';

    public function area(){
        return $this->hasOne("Area","id","area_id");
    }

}