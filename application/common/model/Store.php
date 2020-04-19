<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/5/2
 * Time: 22:01
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Store extends Model
{
    use SoftDelete;
    protected $table = 'store';
    protected $deleteTime = 'delete_time';

    public function getStatusAttr($value){
        $result = [
            0 => '申请中',
            1 => '正常',
            2 => '驳回',
            3 => '禁用'
        ];
        return $result[$value];
    }

    public function user(){
        return $this->hasOne("user","id","user_id");
    }

    public function industry(){
        return $this->hasOne("Industries","id","industry_id");
    }

    public function salesman(){
        return $this->hasOne("Salesman","id","manager_id");
    }

    public function area(){
        return $this->hasOne("Area","id","area_id");
    }

}