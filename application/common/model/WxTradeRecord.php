<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-11
 * Time: 21:06
 */

namespace app\common\model;


use think\Model;

class WxTradeRecord extends Model
{
    protected $table = 'wx_trade_record';
    protected $pk = 'out_trade_no';

}