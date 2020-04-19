<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信支付样例-付款到零钱</title>
</head>
<?php
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/
require_once "../lib/WxPay.Api.php";
require_once 'log.php';
require_once "WxPay.Config.php";

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#f00;'>$key</font> : ".htmlspecialchars($value, ENT_QUOTES)." <br/>";
    }
}

if(isset($_REQUEST["partner_trade_no"]) && $_REQUEST["partner_trade_no"] != ""  && isset($_REQUEST["openid"]) && $_REQUEST["openid"] != "" && isset($_REQUEST["amount"]) && $_REQUEST["amount"] != "" && isset($_REQUEST["desc"]) && $_REQUEST["desc"] != "" && isset($_REQUEST["spbill_create_ip"]) && $_REQUEST["spbill_create_ip"] != ""){
	try{
		$partner_trade_no = $_REQUEST["partner_trade_no"];
		$openid = $_REQUEST["openid"];
		$amount = $_REQUEST["amount"];
		$desc = $_REQUEST["desc"];
		$spbill_create_ip = $_REQUEST["spbill_create_ip"];
		$input = new WxPayTransfers();
		$input->SetPartner_trade_no($partner_trade_no);
		$input->SetOpenid($openid);
		$input->SetAmount($amount);
		$input->SetDesc($desc);
		$input->SetSpbill_create_ip($spbill_create_ip);

		$config = new WxPayConfig();
		printf_info(WxPayApi::transfers($config, $input));
	} catch(Exception $e) {
		Log::ERROR(json_encode($e));
		echo var_dump($e);
	}
	exit(222);
}
?>
<body>  
	<form action="#" method="post">
        <div style="margin-left:2%;color:#f00">商户订单号需保持唯一性：</div><br/>
        <div style="margin-left:2%;">商户订单号：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="partner_trade_no" /><br /><br />
        <div style="margin-left:2%;">用户openID：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="openid" /><br /><br />
        <div style="margin-left:2%;">金额（分）：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="amount" /><br /><br />
        <div style="margin-left:2%;">描述：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="desc" /><br /><br />
		<div style="margin-left:2%;">IP：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="spbill_create_ip" /><br /><br />
		<div align="center">
			<input type="submit" value="提交退款" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" />
		</div>
	</form>
</body>
</html>
