<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-03
 * Time: 09:10
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class StoreApplication extends Model
{
    use SoftDelete;
    protected $table = 'store_application';
    protected $deleteTime = 'delete_time';

}