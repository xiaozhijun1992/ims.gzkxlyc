<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-25
 * Time: 00:14
 */
// 快递鸟平台参数配置

return [
    "EBusinessID"=> "1354790", // 用户ID
    "AppKey" => "37e5a18d-522a-4cee-8204-d139435c7724", //电商加密私钥
    "OrderTraceURL"=> "http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx", // 即使查询API接口地址
    "SubscribeURL" => 'http://api.kdniao.com/api/dist' // 快递跟踪API接口地址
];