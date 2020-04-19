<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-13
 * Time: 21:08
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodSpecItem extends Model
{
    use SoftDelete;
    protected $table = 'good_spec_item';
    protected $deleteTime = 'delete_time';

}