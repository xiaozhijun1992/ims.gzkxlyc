<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-06-07
 * Time: 18:32
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class BankList extends Model
{
    use SoftDelete;
    protected $table = 'bank_list';
    protected $deleteTime = 'delete_time';

}