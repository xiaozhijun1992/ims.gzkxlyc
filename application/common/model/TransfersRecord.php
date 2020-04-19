<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-06
 * Time: 17:47
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class TransfersRecord extends Model
{
    use  SoftDelete;
    protected $table = 'transfers_record';
    protected $deleteTime = 'delete_time';

}