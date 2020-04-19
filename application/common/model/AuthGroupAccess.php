<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-21
 * Time: 21:34
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class AuthGroupAccess extends Model
{
    use SoftDelete;
    protected $table = 'admin_auth_group_access';
    protected $deleteTime = 'delete_time';

    public function member(){
        return $this->hasOne("member","id","uid");
    }

    public function authGroup(){
        return $this->hasOne("AuthGroup","id","group_id");
    }

}