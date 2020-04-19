<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-21
 * Time: 11:07
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class AreaManager extends Model
{
    use SoftDelete;
    protected $table = 'area_manager';
    protected $deleteTime = 'delete_time';

    public function getStatusAttr($value){
        $result = [0=>'禁用',1=>'正常',2=>'申请中',3=>'驳回'];
        return $result[$value];
    }

    public function getSexAttr($value){
        $result = [0=>'男',1=>'女'];
        return $result[$value];
    }

    public function user(){
        return $this->hasOne("user","id","user_id");
    }

}