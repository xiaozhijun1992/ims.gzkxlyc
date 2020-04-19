<?php /*a:1:{s:73:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\order\orderlist.html";i:1586378447;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" /> <link rel="stylesheet" type="text/css" href="/static/css/app.css" />
		<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<style type="text/css">
		.back{
			margin-top: 42px;
    		width: 100%;
   			 box-sizing: border-box;
		}
	</style>
	<body>
		<view>
			<div class="head">
				<div class="jshook-ws-head" style="display: flex; background-color: #fa922f;color: #fff;">
					<div class="head-back jshook-ws-head-back" style="display: none;">

					</div>
					<div class="head-home jshook-ws-head-home" onclick="onclickset()" style="display: flex;font-size: 14px;">
						<div class="cuIcon-back cuIcon-back">

						</div>返回
					</div>
					<h3 class="head-title jshook-ws-head-title" style="color: black;">
    				<i class="head-title-loading" style="display: none;"></i>
    				<span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">我的订单</span>
  					</h3>
					<div class="head-option jshook-ws-head-option"></div>
				</div>
			</div>
			<div class="back"> 
				<div id="addressListone">
					
				</div>
			</div>
	</body>
</html>
<script type="text/javascript">
window.onload=function(){
		parent.$('#loads').hide();
	}
	function onclickset(){
		parent.onclickset();
	}
</script>