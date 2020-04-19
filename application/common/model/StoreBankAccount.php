<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-07
 * Time: 18:05
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;


class StoreBankAccount extends Model
{
    use SoftDelete;
    protected $table = 'store_bank_account';
    protected $deleteTime = 'delete_time';

}