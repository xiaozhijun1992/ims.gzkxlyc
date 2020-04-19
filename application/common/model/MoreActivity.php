<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-08-17
 * Time: 18:53
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class MoreActivity extends Model
{
    use SoftDelete;
    protected $table = 'more_activity';
    protected $deleteTime = 'delete_time';
    protected $pk = 'type';

}