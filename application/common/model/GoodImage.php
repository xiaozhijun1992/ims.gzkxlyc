<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-13
 * Time: 20:34
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodImage extends Model
{
    use SoftDelete;
    protected $table = 'good_image';
    protected $deleteTime = 'delete_time';

}