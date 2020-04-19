<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-08
 * Time: 17:16
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class StoreSender extends Model
{
    use SoftDelete;
    protected $table = 'store_sender';
    protected $deleteTime = 'delete_time';

}