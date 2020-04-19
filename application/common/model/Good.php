<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-13
 * Time: 20:22
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Good extends Model
{
    use SoftDelete;
    protected $table = 'good';
    protected $deleteTime = 'delete_time';

    public function getImage(){
        return $this->hasMany("GoodImage","good_id","id")->field("img")->order("id")->limit(1);
    }

    public function comments(){
        return $this->hasMany("GoodComment","good_id","id");
    }

    public function spec(){
        return $this->hasMany("GoodSpec","good_id","id");
    }

    public function goodOption(){
        return $this->hasMany("GoodOption","good_id","id");
    }

    public function param(){
        return $this->hasMany("GoodParam","good_id","id");
    }

    public function store(){
        return $this->hasOne("store","id","store_id");
    }

    public function getStatusAttr($value){
        $status = [
            0=>'审核中',
            1=>'已上架',
            2=>'已下架',
            3=>'审核未通过',
        ];
        return $status[$value];
    }

}