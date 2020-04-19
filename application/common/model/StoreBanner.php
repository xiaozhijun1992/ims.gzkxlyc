<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-07
 * Time: 21:29
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class StoreBanner extends Model
{
    use SoftDelete;
    protected $table = 'store_banner';
    protected $deleteTime = 'delete_time';

}