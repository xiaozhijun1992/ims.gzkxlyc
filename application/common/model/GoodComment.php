<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-23
 * Time: 00:34
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodComment extends Model
{
    use SoftDelete;
    protected $table = 'good_comment';
    protected $deleteTime = 'delete_time';

    public function user(){
        return $this->hasOne("User","id","user_id")->field("id,nick_name,avator");
    }
}