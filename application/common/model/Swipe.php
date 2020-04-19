<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-09
 * Time: 23:33
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;
class Swipe extends Model
{
    use SoftDelete;
    protected $table = 'admin_swipe';
    protected $deleteTime = 'delete_time';

}