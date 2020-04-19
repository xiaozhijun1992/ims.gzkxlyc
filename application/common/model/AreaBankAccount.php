<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-26
 * Time: 12:32
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class AreaBankAccount extends Model
{
    use SoftDelete;
    protected $table = 'area_bank_account';
    protected $deleteTime = 'delete_time';

}