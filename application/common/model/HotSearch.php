<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-16
 * Time: 21:52
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class HotSearch extends Model
{
    use SoftDelete;
    protected $table = 'admin_hot_search';
    protected $deleteTime = 'delete_time';

}