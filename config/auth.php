<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-21
 * Time: 21:39
 */

return [
    'auth_on'           => 1, // 权限开关
    'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
    'auth_group'        => 'admin_auth_group', // 用户组数据不带前缀表名
    'auth_group_access' => 'admin_auth_group_access', // 用户-用户组关系不带前缀表名
    'auth_rule'         => 'admin_auth_rule', // 权限规则不带前缀表名
    'auth_user'         => 'user', // 用户信息不带前缀表名
];