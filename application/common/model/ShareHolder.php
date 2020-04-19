<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-05
 * Time: 09:56
 */

namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class ShareHolder extends Model
{
    use SoftDelete;
    protected $table = 'share_holder';
    protected $deleteTime = 'delete_time';

}