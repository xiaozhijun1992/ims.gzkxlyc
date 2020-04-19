<?php /*a:1:{s:75:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\tabbar\distribute.html";i:1586194035;}*/ ?>
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
<style type="text/css">
	.grid  .cu-item{
		width: 25%;
	}
	.grid>.cu-item text {
		margin-top:5px;
		color:#888;
		font-size:12px;
		line-height:20px;
	
	}

</style>
	<body>
		<div class="head">
			<div class="jshook-ws-head" style="display: flex; background-color: #fa922f;color: #fff;">
				<div class="head-back jshook-ws-head-back" style="display: none;">
					
				</div>
				<div class="head-home jshook-ws-head-home" style="display: flex;">
					
				</div>
				<h3 class="head-title jshook-ws-head-title" style="color: black;">
    <i class="head-title-loading" style="display: none;"></i>
    <span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">分销中心</span>
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
							<wx-view class="text-xl padding-top-xs" id="nick_name"></wx-view>
							<wx-view class="solid-top flex justify-around margin-top padding text-lg" style="width:100%;">
								<wx-view data-id='0' class="text-center flex flex-direction justify-between onclicksetdis" >
									<wx-view class="text-xxl">我的团队</wx-view>
									<wx-view class="margin-top-xs text-white text-sm objc_price" id="shouc">0人</wx-view>
								</wx-view>
								<wx-view data-id='1' class="text-center flex flex-direction justify-between onclicksetdis">
									<wx-view class="text-xxl ">我的佣金</wx-view>
									<wx-view class="margin-top-xs text-white text-sm obj_price text-price" id="sum_amount">0</wx-view>
								</wx-view>
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
						 <view class="cu-list grid col-4">
    <view data-id='0'  class="cu-item onclicksetdis" >
      <view>
        <image style="height:34px;width:34px;" src="../../static/tabbar/distribute/yongjin.png"></image>
      </view>
      <text>佣金明细</text>
    </view>
    <view data-id='1' class="cu-item onclicksetdis" >
      <view>
        <image style="height:34px;width:34px;" src="../../static/tabbar/distribute/team.png"></image>
      </view>
      <text>我的团队</text>
    </view>
    <view data-id='2'  class="cu-item onclicksetdis" >
      <view>
        <image style="height:34px;width:34px;" src="../../static/tabbar/distribute/area.png"></image>
      </view>
      <text>区域经理</text>
    </view>
    <view  data-id='3' class="cu-item onclicksetdis" >
      <view>
        <image style="height:34px;width:34px;" src="../../static/tabbar/distribute/join.png"></image>
      </view>
      <text>我要入驻</text>
    </view>
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
					<img class="tabbar-icon" src="/static/tabbar/distribute_cur.png" icon="static/tabbar/distribute.png" select-icon="static/tabbar/distribute_cur.png" alt="">
					<p class="tabbar-label"  style="color: rgb(250, 146, 47);">
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
					<img class="tabbar-icon" src="/static/tabbar/my.png" icon="static/tabbar/my.png" select-icon="static/tabbar/my_cur.png" alt="">
					<p class="tabbar-label" style="color:#aaaaaa">
						我的
					</p>
				</div>
			</div>
		</div>

	</body>

</html>
<script type="text/javascript">
getTeamCount();
	window.onload=function(){
		parent.$('#loads').hide();
	}
	function getTeamCount(){
		$.get('/api/Distribute/getTeamCount',{user_id:parent.user_id},function(res){
			$('#shouc').text(res.data+'人');
		})
	}
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
	getUserDistributeAmount();
	function getUserDistributeAmount(){
		$.get('/api/Transfers/getUserDistributeAmount',{user_id:parent.user_id},function(res){
			$('#sum_amount').text(res.data.sum_amount);
		})
		
	}
	$('.phone_icon').on('click',function(){
		
		parent.onclicksetjia('home/users/bindPhone','phone='+parent.phone);
	})
	$('.address').on('click',function(){
		parent.onclicksetjia('home/users/address');
	})
	$('.onclicksetdis').on('click',function(){
		var dis_id=$(this).attr('data-id')
		var arrays=new Array();
		arrays[0]='/home/distribute/distributerecord';
		arrays[1]='/home/distribute/team';
		if(arrays[dis_id]){
			parent.onclicksetjia(arrays[dis_id]);
		}else{
			alert('功能正在开发中....');
		}
//		arrays[2]='';
//		arrays[3]='';
	})
</script>