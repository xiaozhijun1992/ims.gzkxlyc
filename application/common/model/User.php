<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-21
 * Time: 00:02
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class User extends Model
{
    use SoftDelete;
    protected $table = 'user';
    protected $deleteTime = 'delete_time';

    public function money(){
        return $this->hasMany("DistributeRecord","user_id","id");
    }

}