<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-20
 * Time: 19:39
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Area extends Model
{
    use SoftDelete;
    protected $table = 'admin_area';
    protected $deleteTime = 'delete_time';

}