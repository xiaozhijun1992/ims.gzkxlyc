<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-28
 * Time: 10:43
 */

namespace app\api\controller;


use app\common\model\GoodOrder;
use app\common\model\GoodOrderShipTraces;
use think\Controller;
use think\facade\Config;
use think\facade\Log;

class KdNiao extends Controller
{
    /**
     * 即时查询函数
     * @param $OrderCode
     * @param $ShipperCode
     * @param $LogisticCode
     * @return mixed
     * @throws \Exception
     */
    static function search($OrderCode,$ShipperCode,$LogisticCode){
        // 1.查看是否有对应的订单存在，根据订单号和物流单号查询，如果没有找到，抛出异常
        $order = GoodOrder::get($OrderCode);
        if(!$order || $order->logistic_code != $LogisticCode){
            exception("未找到订单号和运单号对应的记录");
        }

        // 如果上次查询时间距离目前小于一小时或者订单确认收货或者物流状态为3（已签收），返回false;
        $traceTmp = GoodOrderShipTraces::get($OrderCode);
        if($traceTmp) {
            $hour = floor((strtotime(date("Y-m-d H:i:s")) - strtotime($traceTmp->push_time))/ 3600);
            if($hour < 1 || $order->getData("pay_status") == 4 || $order->getData("ship_status") == 3){
                return false;
            }
        }

        // 2. 查看是否配置快递鸟api参数
        $kdniaoApiParam = Config::get("kdniao.");
        if(!array_key_exists("EBusinessID",$kdniaoApiParam) || !array_key_exists("AppKey",$kdniaoApiParam)||!array_key_exists("OrderTraceURL",$kdniaoApiParam)){
            exception("平台未配置快递查询API参数，请联系0858-8699077");
        }

        //电商ID
        defined('EBusinessID') or define('EBusinessID', $kdniaoApiParam["EBusinessID"]);
        //电商加密私钥，快递鸟提供，注意保管，不要泄漏
        defined('AppKey') or define('AppKey', $kdniaoApiParam["AppKey"]);
        //请求url
        defined('ReqURL') or define('ReqURL', $kdniaoApiParam["OrderTraceURL"]);

        // 构造请求数据
        $requestData= "{'OrderCode':'" . $OrderCode . "','ShipperCode':'" . $ShipperCode . "','LogisticCode':'" . $LogisticCode ."'}";
        $datas = array(
            'EBusinessID' => EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = self::encrypt($requestData, AppKey);
        $result=self::sendPost(ReqURL, $datas);

        //根据公司业务处理返回的信息......
        $data = json_decode($result,true);

        $data["order_code"] = $OrderCode;
        Log::record($data);

        if(!$data["Success"] && array_key_exists("Reason",$data)){
            \exception($data["Reason"]);
        }

        // 如果查询失败，折返回false
        if(!$data["Success"] || count($data["Traces"]) <= 0){
            return false;
        }else {
            // 查询成功
            // 如果订单存在，则查询之前是否存在查询记录，如果存在就更新，如果不存在就插入
            $data["Traces"] = json_encode($data["Traces"],JSON_UNESCAPED_UNICODE);
            $data["push_time"] = date("Y-m-d H:i:s");
            if(GoodOrderShipTraces::get($OrderCode)) {
                GoodOrderShipTraces::update($data);
            }else {
                $trace = new GoodOrderShipTraces();
                $trace->save($data);
            }
            // 更新订单表ship_status（物流状态）字段
            $order->save([
                "ship_status" => $data["State"]
            ]);

            return true;
        }
    }


    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return string url响应返回的html
     */
    static function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param string data 内容
     * @param string appkey Appkey
     * @return string DataSign签名
     */
    static function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

}