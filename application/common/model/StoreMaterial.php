<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-07-07
 * Time: 11:52
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class StoreMaterial extends Model
{
    use SoftDelete;
    protected $table = 'store_material';
    protected $deleteTime = 'delete_time';

}