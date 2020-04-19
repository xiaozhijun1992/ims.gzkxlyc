<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 11:09
 * 商品分类表模型
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodCategory extends Model
{
    use SoftDelete;
    protected $table = 'good_category';
    protected $deleteTime = 'delete_time';

    public function upLevel(){
        return $this->hasOne("GoodCategory","id","pid");
    }

    public function child(){
        return $this->hasMany("GoodCategory","pid","id");
    }
}