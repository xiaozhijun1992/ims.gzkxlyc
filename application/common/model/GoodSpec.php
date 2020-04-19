<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-13
 * Time: 20:57
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodSpec extends Model
{
    use SoftDelete;
    protected $table = 'good_spec';
    protected $deleteTime = 'delete_time';

    public function item(){
        return $this->hasMany("GoodSpecItem","spec_id","id");
    }

}