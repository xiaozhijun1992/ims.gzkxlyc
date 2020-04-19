<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-16
 * Time: 22:41
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Industries extends Model
{
    use SoftDelete;
    protected $table = 'admin_industries';
    protected $deleteTime = 'delete_time';

}