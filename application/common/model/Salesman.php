<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-05
 * Time: 22:08
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Salesman extends Model
{
    use SoftDelete;
    protected $table = 'salesman';
    protected $deleteTime = 'delete_time';

    public function getSexAttr($value){
        $data = [0=>'男',1=>'女'];
        return $data[$value];
    }

    public function getStatusAttr($value){
        $data = [0=>'停用',1=>'正常',2=>'申请中',3=>'驳回'];
        return $data[$value];
    }

    public function area(){
        return $this->hasOne("area","id","area_id");
    }

}