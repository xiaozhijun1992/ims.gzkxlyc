<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2019/4/30
 * Time: 22:47
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class WithDrawal extends Model
{
    use SoftDelete;
    protected $table = 'with_drawal';
    protected $deleteTime = 'delete_time';
    protected $pk = 'scene';

}