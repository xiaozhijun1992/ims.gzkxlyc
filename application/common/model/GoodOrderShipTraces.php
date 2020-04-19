<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-07-16
 * Time: 22:09
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class GoodOrderShipTraces extends Model
{
    use SoftDelete;
    protected $pk = 'order_code';
    protected $table = 'good_order_ship_traces';
    protected $deleteTime = 'delete_time';


}