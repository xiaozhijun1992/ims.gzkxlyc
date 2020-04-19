<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-13
 * Time: 20:43
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodParam extends Model
{
    use SoftDelete;
    protected $table = 'good_param';
    protected $deleteTime = 'delete_time';

}