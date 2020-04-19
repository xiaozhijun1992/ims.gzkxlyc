<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-16
 * Time: 10:41
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodKeep extends Model
{
    use SoftDelete;
    protected $table = 'good_keep';
    protected $deleteTime = 'delete_time';

    public function good(){
        return $this->hasMany("Good","id","good_id");
    }


}