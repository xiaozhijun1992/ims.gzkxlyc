<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-23
 * Time: 21:05
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class ReceiveAddress extends Model
{
    use SoftDelete;
    protected $table = 'receive_address';
    protected $deleteTime = 'delete_time';

}