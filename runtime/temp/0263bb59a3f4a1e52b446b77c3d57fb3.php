<?php /*a:1:{s:67:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\tabbar\my.html";i:1586378465;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		
		<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
		<title></title>
		
		<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" />
    	<link rel="stylesheet" type="text/css" href="/static/css/app.css" />
    	<script type="text/javascript" src="/static/lib/layui/layui.js"></script>
    	<link rel="stylesheet" type="text/css" href="/static/lib/layui/css/layui.css" />
    	<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript" src="/static/js/jqset.js"></script>
	</head>

	<body>
		<div class="head">
			<div class="jshook-ws-head" style="display: flex; background-color: #fa922f;color: #fff;">
				<div class="head-back jshook-ws-head-back" style="display: none;">
					
				</div>
				<div class="head-home jshook-ws-head-home" style="display: flex;">
					
				</div>
				<h3 class="head-title jshook-ws-head-title" style="color: black;">
    <i class="head-title-loading" style="display: none;"></i>
    <span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">个人中心</span>
  </h3>
				<div class="head-option jshook-ws-head-option"></div>
			</div>
		</div>
		<div class="scrollable" style="bottom: 56px; top: 42px;">
			<div id="weweb-view-4" view-id="4" style="height: 100%;">
				<wx-page>
					<wx-view>
						<wx-cu-custom>
							<wx-view></wx-view>
						</wx-cu-custom>
						<wx-view class="padding-sm bg-kxly flex flex-direction align-center">
							<wx-view class="flex justify-between align-center w-100">
								<wx-text><span style="display:none;">编码：undefined</span><span>编码：<span id='code'></span></span></wx-text>
							</wx-view>
							<wx-view class="padding-top-sm">
								<wx-image style="height:42px;width:42px;border-radius:50%;">
									<img id='avator' src="" style="background-size: 100% 100%; background-repeat: no-repeat;border-radius: 100%;text-align: center;margin: 0 auto;width: 42px;height: 42px;" />
								</wx-image>
							</wx-view>
							<wx-view class="text-xl padding-top-xs" id="nick_name">凌晨丨十点</wx-view>
							<wx-view class="solid-top flex justify-around margin-top padding text-lg" style="width:100%;">
								<wx-view class="text-center flex flex-direction justify-between">
									<wx-view class="text-xxl text-price">0.00</wx-view>
									<wx-view class="margin-top-xs text-white text-sm">我的余额</wx-view>
								</wx-view>
								<wx-view class="text-center flex flex-direction justify-between">
									<wx-view class="text-xxl">0
										<wx-label class="text-xs _span">分</wx-label>
									</wx-view>
									<wx-view class="margin-top-xs text-white text-sm">我的积分</wx-view>
								</wx-view>
								<wx-view class="text-center flex flex-direction justify-between" data-event-opts="tap,toKeepGood,$event">
									<wx-view class="text-xxl"><span id="shouc">
										0
									</span>
										<wx-label class="text-xs _span">条</wx-label>
									</wx-view>
									<wx-view class="margin-top-xs text-white text-sm">我的收藏</wx-view>
								</wx-view>
							</wx-view>
						</wx-view>
						<wx-view class="cu-bar bg-white solid-bottom margin-top" onclick="order_list(0)">
							<wx-view class="action">
								<wx-text class="cuIcon-shop" ><span style="display:none;"></span><span></span></wx-text>我的订单</wx-view>
							<wx-view class="action" data-event-opts="tap,toOrderList,0">
								<wx-text class="text-sm text-gray"><span style="display:none;">全部订单</span><span>全部订单</span></wx-text>
								<wx-text class="lg text-gray cuIcon-right"><span style="display:none;"></span><span></span></wx-text>
							</wx-view>
						</wx-view>
						<wx-view class="flex padding-top bg-white padding-bottom">
							<wx-view onclick="order_list(1)" class="flex-sub flex flex-direction justify-center align-center" data-event-opts="tap,toOrderList,1">
								<wx-image src="../../../static/images/order_status/dfk.png" style="width:34px;height:34px;">
									<div style="background-image: url(&quot;../../../static/images/order_status/dfk.png&quot;); background-size: 100% 100%; background-repeat: no-repeat;"></div>
									<virtual></virtual>
								</wx-image>
								<wx-view class="padding-top-xs">待付款</wx-view>
							</wx-view>
							<wx-view onclick="order_list(2)" class="flex-sub flex flex-direction justify-center align-center" data-event-opts="tap,toOrderList,2">
								<wx-image style="width:34px;height:34px;" src="../../../static/images/order_status/dfh.png">
									<div style="background-image: url(&quot;../../../static/images/order_status/dfh.png&quot;); background-size: 100% 100%; background-repeat: no-repeat;"></div>
									<virtual></virtual>
								</wx-image>
								<wx-view class="padding-top-xs">待发货</wx-view>
							</wx-view>
							<wx-view onclick="order_list(3)" class="flex-sub flex flex-direction justify-center align-center" data-event-opts="tap,toOrderList,3">
								<wx-image style="width:34px;height:34px;" src="../../../static/images/order_status/dsh.png">
									<div style="background-image: url(&quot;../../../static/images/order_status/dsh.png&quot;); background-size: 100% 100%; background-repeat: no-repeat;"></div>
									<virtual></virtual>
								</wx-image>
								<wx-view class="padding-top-xs">待收货</wx-view>
							</wx-view>
							<wx-view onclick="order_list(4)" class="flex-sub flex flex-direction justify-center align-center" data-event-opts="tap,toOrderList,4">
								<wx-image style="width:34px;height:34px;" src="../../../static/images/order_status/dhx.png">
									<div style="background-image: url(&quot;../../../static/images/order_status/dhx.png&quot;); background-size: 100% 100%; background-repeat: no-repeat;"></div>
									<virtual></virtual>
								</wx-image>
								<wx-view class="padding-top-xs">待核销</wx-view>
							</wx-view>
							<wx-view onclick="order_list(5)" class="flex-sub flex flex-direction justify-center align-center" data-event-opts="tap,toOrderList,5">
								<wx-image style="width:34px;height:34px;" src="../../../static/images/order_status/dtk.png">
									<div style="background-image: url(&quot;../../.../../../static/images/order_status/dtk.png&quot;); background-size: 100% 100%; background-repeat: no-repeat;"></div>
									<virtual></virtual>
								</wx-image>
								<wx-view class="padding-top-xs">待退货</wx-view>
							</wx-view>
						</wx-view>
						<wx-view class="cu-list menu sm-border margin-top phone_icon">
							<wx-view class="cu-item arrow ">
								<wx-view class="content padding-tb-sm" data-event-opts="tap,toBindPhone,$0,user_info.id">
									<wx-view>
										<wx-text class="cuIcon-phone text-blue margin-right-xs"><span style="display:none;"></span><span></span></wx-text><span id="phone">
											
										</span></wx-view>
									<wx-view class="text-gray text-sm">
										<wx-text class="cuIcon-infofill margin-right-xs"><span style="display:none;"></span><span></span></wx-text><span id="phone_s">
											
										</span></wx-view>
								</wx-view>
							</wx-view>
						</wx-view>
						<wx-view class="cu-list menu card-menu margin-top margin-bottom">
							<wx-view class="cu-item arrow address" data-event-opts="tap,toAddress,$event">
								<wx-view class="content">
									<wx-text class="cuIcon-locationfill text-olive"><span style="display:none;"></span><span></span></wx-text>
									<wx-text class="text-grey"><span style="display:none;">收货地址</span><span>收货地址</span></wx-text>
								</wx-view>
							</wx-view>
							<wx-view class="cu-item arrow">
								<wx-button class="cu-btn content">
									<wx-text class="cuIcon-servicefill text-olive"><span style="display:none;"></span><span></span></wx-text>
									<wx-text class="text-grey"><span style="display:none;">平台客服</span><span>平台客服</span></wx-text>
								</wx-button>
							</wx-view>
							<wx-view class="cu-item arrow">
								<wx-button class="cu-btn content">
									<wx-text class="cuIcon-markfill text-olive"><span style="display:none;"></span><span></span></wx-text>
									<wx-text class="text-grey"><span style="display:none;">意见建议</span><span>意见建议</span></wx-text>
								</wx-button>
							</wx-view>
							<wx-view class="cu-item arrow" data-event-opts="tap,toOrderVerification,$event">
								<wx-button class="cu-btn content">
									<wx-text class="cuIcon-qrcode text-olive"><span style="display:none;"></span><span></span></wx-text>
									<wx-text class="text-grey"><span style="display:none;">扫码核销</span><span>扫码核销</span></wx-text>
								</wx-button>
							</wx-view>
						</wx-view>
					</wx-view>
				</wx-page>
			</div>
		</div>
		<div class="tabbar-root ">
			<div class="tabbar jshook-ws-tabbar" style="display: flex; background-color: rgb(255, 255, 255); border-color: black; height: 56px;">
				<div class="tabbar-item jshook-ws-tabbar-item" key="0">
					<img class="tabbar-icon" src="/static/tabbar/index.png" icon="static/tabbar/index.png" select-icon="static/tabbar/index_cur.png" alt="">
					<p class="tabbar-label" style="color: rgb(170, 170, 170);">
						首页
					</p>
				</div>
				<div class="tabbar-item jshook-ws-tabbar-item" key="1">
					<img class="tabbar-icon" src="/static/tabbar/near.png" icon="static/tabbar/near.png" select-icon="static/tabbar/near_cur.png" alt="">
					<p class="tabbar-label" style="color:#aaaaaa">
						附近
					</p>
				</div>
				<div class="tabbar-item jshook-ws-tabbar-item" key="2">
					<img class="tabbar-icon" src="/static/tabbar/distribute.png" icon="static/tabbar/distribute.png" select-icon="static/tabbar/distribute_cur.png" alt="">
					<p class="tabbar-label" style="color:#aaaaaa">
						分销中心
					</p>
				</div>
				<div class="tabbar-item jshook-ws-tabbar-item" key="3">
					<img class="tabbar-icon" src="/static/tabbar/cart.png" icon="static/tabbar/cart.png" select-icon="static/tabbar/cart_cur.png" alt="">
					<p class="tabbar-label" style="color: rgb(170, 170, 170);">
						购物车
					</p>
				</div>
				<div class="tabbar-item jshook-ws-tabbar-item" key="4">
					<img class="tabbar-icon" src="/static/tabbar/my_cur.png" icon="static/tabbar/my.png" select-icon="static/tabbar/my_cur.png" alt="">
					<p class="tabbar-label" style="color: rgb(250, 146, 47);">
						我的
					</p>
				</div>
			</div>
		</div>

	</body>

</html>
<script type="text/javascript">
getKeepCount();
	window.onload=function(){
		parent.$('#loads').hide();
	}
	function getKeepCount(){
		$.get('/api/good/getKeepCount',{user_id:parent.user_id},function(res){
			$('#shouc').text(res.data);
		})
	}
	$('#shouc').on('click',function(){
		parent.onclicksetjia('/home/good/getkeepgoodlist');
	})
	user(parent.user_id)
	function user(user_id){
		$.get('/api/good/user',{user_id:parent.user_id},function(res){
			console.log(res);
			if(res.data){
				$('#code').text(res.data.code);
				$('#avator').attr('src',res.data.avator);
				$('#nick_name').text(res.data.nick_name);
				parent.phone=res.data.phone;
				if(res.data.phone){
					$('#phone').text('已绑定手机号');
					$('#phone_s').text('更换手机号');
				}else{
					$('#phone').text('绑定手机');
					$('#phone_s').text('请绑定手机');
				}
				
			}
		})
	}
	$('.phone_icon').on('click',function(){
		
		parent.onclicksetjia('home/users/bindPhone','phone='+parent.phone);
	})
	$('.address').on('click',function(){
		parent.onclicksetjia('home/users/address');
	})
	function order_list(type){
		parent.onclicksetjia('home/order/orderlist','type='+type);
		
	};
</script>