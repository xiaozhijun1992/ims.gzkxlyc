<?php /*a:1:{s:71:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\stores\stores.html";i:1584880351;}*/ ?>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
	<title></title>
	<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" /> <link rel="stylesheet" type="text/css" href="/static/css/app.css" /> <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> <script type="text/javascript" src="/static/lib/layui/layui.js"></script> <link rel="stylesheet" type="text/css" href="/static/lib/layui/css/layui.css" />
	<script type="text/javascript" src="/static/js/render.js"></script>
</head>

<body style='overflow-y: scroll;background: #fff;'>
	<div class="scrollable">
		
	
	<div id="storeshtml" style="background: #fff;">
		
	</div>
	<div >
		<block>
			<scroll-view style="width: 100%;display: block;box-sizing: border-box;overflow-x: scroll;" class="bg-white nav margin-top-sm" scroll-x scroll-with-animation>
			<div id="cu-item" >
				<view onclick="getGoodsByStoreIdAndCategoryId('null')" id="all" class="cu-item text-green cur"  bindtap="__e">全部分类</view>
				
			</div>
		</scroll-view>
	</block>
	</div>
	<div id="getGoodsByStoreIdAndCategoryId" class="grid col-2 padding-xs">
		
	</div>
	<wx-view class="text-center text-sm text-gray padding" id="getindexGood_center"></wx-view>
	
	<!--<view class="text-center text-sm text-gray padding" id="loadingText"></view>-->
</div>
</body>
<script id="stores2" type="text/x-jsrender">
	<block>
		<scroll-view class="bg-white nav margin-top-sm" scroll-x scroll-with-animation>
			<!--<view class="cu-item text-green cur"  bindtap="__e">全部分类</view>-->
			<block  >
				<view class="cu-item" bindtap="__e">{{>name}}</view>
			</block>
		</scroll-view>
	</block>
</script>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=RKOBZ-ROOKW-4F4R7-RPAEK-GGXYK-5CBY4&libraries=geometry"></script>
<script id="stores1" type="text/x-jsrender">
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
    			<span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">店铺主页</span>
  			</h3>
			<div class="head-option jshook-ws-head-option"></div>
		</div>
	</div>
	<wx-swiper  autoplay="" circular="" indicator-dots="" class="screen-swiper square-dot" style="height:153px;">
		<div class="layui-carousel" id="carousel">
			<div carousel-item id='imgs'>
				{{>html}}
			</div>
		</div>

	</wx-swiper>
	<view class="bg-white">
		<view class="text-lg padding solid-bottom"  style="    display: block;">
			<text class="cuIcon-shopfill text-orange padding-right-sm"></text><span id="store_name">
				{{>store_info.name}}
			</span></view>
		<view class="cu-list menu sm-border" style="width: 98%;">
				<view class="cu-item">
					<view class="content">
						<text class="cuIcon-goods text-blue"></text>
						<text  class="text-grey">{{>store_info.business}}</text>
					</view>
				</view>
			<view  class="cu-item arrow" bindtap="__e">
				<view class="content">
					<text class="cuIcon-phone text-blue"></text>
					<text class="text-grey"><a href="tel:{{>store_info.contact_phone}}">{{>store_info.contact_phone}}</a></text>
				</view>
			</view>
			<view  class="cu-item arrow" bindtap="__e">
				<view class="content">
					<text class="cuIcon-location text-blue"></text>
					<text class="text-grey" onclick="setaddress({{>store_info.lat}},{{>store_info.lng}},'{{>store_info.address}}','{{>store_info.address}}')">{{>store_info.address}}</text>
				</view>
			</view>
			<view  class="cu-item arrow modal_action" bindtap="__e">
				<view class="content">
					<text class="cuIcon-info text-blue"></text>
					<text class="text-grey text-cut">店铺简介</text>
				</view>
			</view>
		</view>
	</view>
	<view class="cu-modal">
		<view class="cu-dialog">
			<view class="cu-bar bg-white justify-end" style="height: 50px;">
				<view class="content" >店铺简介</view>
				<view  class="action" bindtap="__e">
					<text class="cuIcon-close text-red"></text>
				</view>
			</view>
			<scroll-view class="padding" style="max-height:400rpx;" scroll-y="true">{{:''+store_info.introduce+''}}</scroll-view>
		</view>
	</view>
</script>
<script type="text/javascript">
	var id = "<?= $_GET['id']?>";
	getSwipe(id);
	window.onload=function(){
		parent.$('#loads').hide();
	}
	function onclickset() {
		parent.onclickset();
	}

	function setSwipe() {
		var ins;
		layui.use(['carousel', 'element', 'layer'], function() {
			var carousel = layui.carousel;
			layer = layui.layer;
			var element = layui.element; //导航的hover效果、二级菜单等功能，需要依赖element模块
			//建造实例
			ins = carousel.render({
				elem: '#carousel',
				width: '100%' //设置容器宽度
					,
				arrow: 'none' //始终显示箭头
				,
				height: '153px'
					//,anim: 'updown' //切换动画方式
			});
		});
		$("#carousel").on("touchstart", function(e) {
			var startX = e.originalEvent.targetTouches[0].pageX; //开始坐标X
			$(this).on('touchmove', function(e) {
				arguments[0].preventDefault(); //阻止手机浏览器默认事件
			});
			$(this).on('touchend', function(e) {
				var endX = e.originalEvent.changedTouches[0].pageX; //结束坐标X
				e.stopPropagation(); //停止DOM事件逐层往上传播
				if (endX - startX > 30) {
					ins.slide("sub");
				} else if (startX - endX > 30) {
					ins.slide("add");
				}
				$(this).off('touchmove touchend');
			});
		})
	}

	function getSwipe(id) {
		var html;
		
		$.post("/api/index/getStoreInfo", {
			id
		}, function(res) {
			if (res.data) {
				for (var i=0;i<(res.data.store_imgs).length;i++) {
					resc='<img onclick="jumpToCaroucel('+res.data.store_imgs[i].id+')" src="/'+res.data.store_imgs[i].img+'"/>';
					if(!html){
						html=resc;
					}else{
						html+=resc;
					}
				}
				var template = $.templates("#stores1");
				var htmlOutput = template.render(res.data)
				$("#storeshtml").html(htmlOutput);
				$('#imgs').append(html);
				$('.action').on('click',function(){
					$('.cu-modal').removeClass('show');
				})
				$('.modal_action').on('click',function(){
					$('.cu-modal').addClass('show');
				})
				setSwipe();
				getStoreCategory(res.data.store_info.id);
			} else {
				return false;
			}
			
		})
	}

	function getStoreCategory(store_id) {
		var html;
		
		$.post("/api/index/getStoreCategory", {
			store_id
		}, function(res) {
			if (res.data) {
				for (var i=0;i<res.data.length;i++) {
					var sethtml='<view onclick="getGoodsByStoreIdAndCategoryId('+res.data[i].id+')" id='+res.data[i].id+' class="cu-item" bindtap="__e">'+res.data[i].name+'</view>'
					if(!html){
						html=sethtml;
					}else{
						html+=sethtml;
					}
				}
				$('#cu-item').append(html);
//				var template = $.templates("#stores2");
//				var htmlOutput = template.render(res.data)
//				$("#getGoodsByStoreIdAndCategoryId").html(htmlOutput);
			} else {
				return false;
			}
			
		})
	}
	function setaddress(lat,lng,addr,title){
		var divs='<div class="head"><div class="jshook-ws-head" style="display: flex; background-color: #fa922f;color: #fff;"><div class="head-back jshook-ws-head-back" style="display: none;"></div><div class="head-home jshook-ws-head-home" onclick="onclickset()" style="display: flex;font-size: 14px;"><div class="cuIcon-back cuIcon-back"></div>返回</div><h3 class="head-title jshook-ws-head-title" style="color: black;"><i class="head-title-loading" style="display: none;"></i><span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">选择位置</span></h3>	<div class="head-option jshook-ws-head-option"></div></div></div>		';
//		alert('https://apis.map.qq.com/uri/v1/geocoder?coord='+lat+','+lng+'&addr='+addr+'&title='+title+'&referer=RKOBZ-ROOKW-4F4R7-RPAEK-GGXYK-5CBY4');
		parent.onclicksetjia('https://apis.map.qq.com/uri/v1/geocoder?coord='+lat+','+lng+';addr:'+addr+'&referer=RKOBZ-ROOKW-4F4R7-RPAEK-GGXYK-5CBY4','',divs);
		parent.$('#loads').hide();
	}
	var page=0;
	var limit=9;
	var set=1;
	var goodonttop=0;
	function getIndexGood(page,limit,category_id,store_id){
		set=0;
		
		$.post("/api/index/getGoodsByStoreIdAndCategoryId",{page,limit,category_id,store_id},function(res){
			var html;
				if(res.data.length>0){
					var resc;
					
					for (var i=0;i<res.data.length;i++) {
						var order_type='';
						if(res.data[i].order_type==0){
							res.data[i].order_type="线上";
							order_type='bg-green';
						}else{
							res.data[i].order_type="线下";
							order_type='bg-blue';
						}
						resc='<wx-view onclick="good('+res.data[i].id+')" bindtap="__e" class="cu-card case"><wx-view class="cu-item shadow" style="margin:4px;"><wx-view class="image"><img style="height: 148px;width: 100%;" src="/'+res.data[i].getImage[0].img+'"/><wx-view class="cu-tag radius sm '+order_type+' " style="bottom:0;top:auto;border-top-right-radius:0;border-bottom-right-radius:0;">'+res.data[i].order_type+'</wx-view></wx-view>	<view class="cu-list"><view class="cu-item"><view class="content flex-sub"><view class="text-grey text-sm padding-xs text-cut-2">'+res.data[i].name+'</view><view class="text-gray text-sm flex justify-between padding-xs"><view class="flex"><view class="text-red text-sm">￥'+res.data[i].marketprice+'</view><view class="text-xs" style="text-decoration:line-through;"></view></view><view style="text-decoration:line-through;">￥'+res.data[i].productprice+'</view></view></view></view></view></wx-view></wx-view>'
						if(!html){
							html=resc;
						}else{
							html+=resc;
						}
					}
					$('#getGoodsByStoreIdAndCategoryId').append(html);
					set=1;
				}else{
					$('#getindexGood_center').text('没有更多了');
					goodonttop=1;
					return false;
				}
		})
	}
	
	var category_id='null';
	getGoodsByStoreIdAndCategoryId(category_id);
	function getGoodsByStoreIdAndCategoryId(cid){
		page=0;
		limit=9;
		goodonttop=0;
		category_id=cid;
		if(goodonttop==1){
			return false;
		}
		$('#cu-item view').removeClass('cur text-green');
		if(category_id=='null'){	
			$('#all').addClass('cur text-green');
		}else{
			$('#'+category_id).addClass('cur text-green');
		}
		page++;
		$('#getGoodsByStoreIdAndCategoryId wx-view').remove();
		getIndexGood(page,limit,category_id,id);
	}
	$('.scrollable').on('scroll',function(){
		if (90+ $('.scrollable').scrollTop() > $('#getGoodsByStoreIdAndCategoryId').height()) {
	    //dosomething
	    if(goodonttop==1){
			return false;
		}
	    page++;
	    getIndexGood(page,limit,category_id,id);
	    return false;
	  }
	})
	function good(good_id){
		parent.good(good_id);
	}
</script>