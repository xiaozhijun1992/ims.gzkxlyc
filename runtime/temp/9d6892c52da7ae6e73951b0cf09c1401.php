<?php /*a:1:{s:69:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\good\search.html";i:1584879334;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" /> <link rel="stylesheet" type="text/css" href="/static/css/app.css" />
		<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
		<style type="text/css">
			.inputs {
				-webkit-box-flex: 1;
				-webkit-flex: 1;
				-ms-flex: 1;
				flex: 1;
				padding-right: 10px;
				height: 14px;
				line-height: 14px;
				font-size: 14px;
				background-color: transparent;
				border: none;
			}
		</style>
	</head>

	<body style="background: #fff;">
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
    <span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">商品详情</span>
  </h3>
					<div class="head-option jshook-ws-head-option"></div>
				</div>
			</div>
		</view>
		<view class="cu-bar search bg-white" style="overflow-y: scroll;margin-top: 42px;">
			<view class="search-form round">
				<text class="cuIcon-search"></text>
				<input class="inputs" type="text" placeholder="搜索商品" confirm-type="search" value="" bindconfirm="__e" bindinput="__e" />
			</view>
			<view class="action">
				<button class="cu-btn bg-green shadow-blur round roundcc"  bindtap="__e">搜索</button>
			</view>
		</view>
		<view class="cu-bar bg-white">
			<view class="action">
				<text class="cuIcon-hotfill text-red"></text>
				<text>热门搜索</text>
			</view>
		</view>
		<view class="padding-lr-sm">
			<block id="getHotSearch">
				
			</block>
		</view>
	</body>

</html>
<script type="text/javascript">
	window.onload=function(){
		parent.$('#loads').hide();
	}
	getHotSearch();
	function getHotSearch(){
		
		$.post('/api/index/getHotSearch','',function(res){
			var html;
			for (var i=0;i<res.data.length;i++) {
				var cc="'"+res.data[i].text+"'";
				var setc='<view onclick="SearchGoodList('+cc+')" class="cu-tag round margin-bottom-sm" bindtap="__e">'+res.data[i].text+'</view>';
				if(!html){
					html=setc;
				}else{
					html+=setc
				}
				
			}
			$('#getHotSearch').append(html);
		})
	}
	function onclickset(){
		parent.onclickset();
	}
	function SearchGoodList(text){
		parent.onclicksetjia('<?php echo url("/home/good/SearchGoodList"); ?>','keyword='+text);
	}
	$('.roundcc').on('click',function(){
		var texts=$('.inputs').val();
		SearchGoodList(texts);
	})
	
</script>