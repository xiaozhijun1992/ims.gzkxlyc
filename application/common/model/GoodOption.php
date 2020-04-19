<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-13
 * Time: 22:05
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodOption extends Model
{
    use SoftDelete;
    protected $table = 'good_option';
    protected $deleteTime = 'delete_time';

}