<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 08:13
 */

namespace app\common\controller;


class KdReceiver
{
    private $name = "";
    private $mobile = "";
    private $provinceName = "";
    private $cityName = "";
    private $expAreaName = "";
    private $address = "";

    /**
     * KdSender constructor.
     * @param $name string 收件人姓名
     * @param $mobile string 收件人手机号
     * @param $provinceName string 收件省
     * @param $cityName string 收件市
     * @param $expAreaName string 收件县/区
     * @param $address string 收件人详细地址
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

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

}