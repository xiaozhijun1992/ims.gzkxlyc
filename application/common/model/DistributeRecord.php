<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-03
 * Time: 20:27
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class DistributeRecord extends Model
{
    use SoftDelete;
    protected $table = 'distribute_record';
    protected $deleteTime = 'delete_time';

    public function store(){
        return $this->hasOne("Store","id","store_id")->field("id,name");
    }

}