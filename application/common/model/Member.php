<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-22
 * Time: 23:22
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Member extends Model
{
    use SoftDelete;
    protected $table = 'member';
    protected $deleteTime = 'delete_time';

}