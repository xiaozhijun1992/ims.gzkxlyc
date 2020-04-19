<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 08:13
 */

namespace app\common\controller;


class KdSender
{
    private $name = "";
    private $mobile = "";
    private $provinceName = "";
    private $cityName = "";
    private $expAreaName = "";
    private $address = "";

    /**
     * KdSender constructor.
     * @param $name string 发件人姓名
     * @param $mobile string 发件人手机号
     * @param $provinceName string 发件省
     * @param $cityName string 发件市
     * @param $expAreaName string 发件县/区
     * @param $address string 发件人详细地址
     */

    public function __construct($name,$mobile,$provinceName,$cityName,$expAreaName,$address)
    {
        $this->name = $name;
        $this->mobile = $mobile;
        $this->provinceName = $provinceName;
        $this->cityName = $cityName;
        $this->expAreaName = $expAreaName;
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @return string
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * @return string
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * @return string
     */
    public function getExpAreaName()
    {
        return $this->expAreaName;
    }



}