<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/5/2
 * Time: 21:02
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class ShoppingSetting extends Model
{
    use SoftDelete;
    protected $table = 'shopping_setting';
    protected $deleteTime = 'delete_time';
    protected $pk = 'key';

}