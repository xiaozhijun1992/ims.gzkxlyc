<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-09-10
 * Time: 08:00
 */

namespace app\api\controller;



class ApiError
{
    public function error(){
        return result([],1,"非法访问");
    }

}