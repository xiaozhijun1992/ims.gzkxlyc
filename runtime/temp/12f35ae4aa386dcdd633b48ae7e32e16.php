<?php /*a:1:{s:79:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\good\search_good_list.html";i:1584879321;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" /> <link rel="stylesheet" type="text/css" href="/static/css/app.css" /> <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> <script type="text/javascript" src="/static/lib/layui/layui.js"></script> <link rel="stylesheet" type="text/css" href="/static/lib/layui/css/layui.css" />
		<script type="text/javascript" src="/static/js/render.js"></script>
	</head>
	<body>
		<body >
			<div class="scrollable">
				<div id="storeshtml" >
					
				
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
    <span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">商品搜索</span>
  </h3>
					<div class="head-option jshook-ws-head-option"></div>
				</div>
			</div>
		</view>
		<scroll-view class="bg-white nav" scroll-x>
	    <view class="flex text-center" style="display: block;width: 80%;box-sizing: border-box">
	      <block  id='view_item'>
	        <view style="width: 31%;" id='recommenddes' onclick="getGoodsByStoreIdAndCategoryId('recommend des')" class="cu-item flex-sub text-orange cur" >推荐</view>
	      	<view style="width: 31%;" id='salesdesc' onclick="getGoodsByStoreIdAndCategoryId('sales desc')" class="cu-item flex-sub " >销量</view>
	      	<view style="width: 31%;" id='marketprice' onclick="getGoodsByStoreIdAndCategoryId('marketprice')" class="cu-item flex-sub " >价格</view>
	      </block>
	    </view>
	   
	  </scroll-view>
	 	<wx-view  class="grid col-3 padding-xs" id="getindexGood"></wx-view>
		<wx-view class="text-center text-sm text-gray padding" id="getindexGood_center"></wx-view>
	  </div>
	</div>
	</body>
</html>

<script type="text/javascript">
	var page=0;
	var limit=9;
	var set=1;
	var goodonttop=0;
	var order='recommend des';
	var keyword;
	var id;
window.onload=function(){
		parent.$('#loads').hide();
	}

	//获取到Url并且解析Url编码
	var url = decodeURI(location.search);  
	if (url.indexOf("?") != -1) { 
							
	var str = url.substr(1); 
		strs = str.split("&");
		for(var i = 0; i < strs.length; i ++) {
			if(strs[i].split("=")[0]=="keyword"){
    			keyword=unescape(strs[i].split("=")[1]);
    			getIndexGood(page+1,limit,keyword,order);
    			
    		}
    		if(strs[i].split("=")[0]=="id"){
    			id=unescape(strs[i].split("=")[1]);
    			getIndexGoodtow(page+1,limit,id,order);
    		}
		} 
	} 	 


	
	function getIndexGood(page,limit,keyword,order){
		set=0;
		if(goodonttop==1){
			return false;
		}
		$.post("/api/index/getGoodListByKeyword",{page,limit,keyword,order},function(res){
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
						resc='<wx-view onclick="good('+res.data[i].id+')" bindtap="__e" class="cu-card case"><wx-view class="cu-item shadow" style="margin:4px;"><wx-view class="image"><img style="height: 96px;width: 100%;" src="/'+res.data[i].getImage[0].img+'"/><wx-view class="cu-tag radius sm '+order_type+' " style="bottom:0;top:auto;border-top-right-radius:0;border-bottom-right-radius:0;">'+res.data[i].order_type+'</wx-view></wx-view>	<view class="cu-list"><view class="cu-item"><view class="content flex-sub"><view class="text-grey text-sm padding-xs text-cut-2">'+res.data[i].name+'</view><view class="text-gray text-sm flex justify-between padding-xs"><view class="flex"><view class="text-red text-sm">￥'+res.data[i].marketprice+'</view><view class="text-xs" style="text-decoration:line-through;"></view></view><view style="text-decoration:line-through;font-size: 8px;">￥'+res.data[i].productprice+'</view></view></view></view></view></wx-view></wx-view>'
						if(!html){
							html=resc;
						}else{
							html+=resc;
						}
					}
					$('#getindexGood').append(html);
					
					set=1;
				}else{
					$('#getindexGood_center').text('没有更多了');
					goodonttop=1;
					return false;
				}
		})
	}
	function getIndexGoodtow(page,limit,id,order){
		set=0;
		if(goodonttop==1){
			return false;
		}
		$.post("/api/index/getGoodListByCategory",{page,limit,id,order},function(res){
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
						resc='<wx-view onclick="good('+res.data[i].id+')" bindtap="__e" class="cu-card case"><wx-view class="cu-item shadow" style="margin:4px;"><wx-view class="image"><img style="height: 96px;width: 100%;" src="/'+res.data[i].getImage[0].img+'"/><wx-view class="cu-tag radius sm '+order_type+' " style="bottom:0;top:auto;border-top-right-radius:0;border-bottom-right-radius:0;">'+res.data[i].order_type+'</wx-view></wx-view>	<view class="cu-list"><view class="cu-item"><view class="content flex-sub"><view class="text-grey text-sm padding-xs text-cut-2">'+res.data[i].name+'</view><view class="text-gray text-sm flex justify-between padding-xs"><view class="flex"><view class="text-red text-sm">￥'+res.data[i].marketprice+'</view><view class="text-xs" style="text-decoration:line-through;"></view></view><view style="text-decoration:line-through;font-size: 8px;">￥'+res.data[i].productprice+'</view></view></view></view></view></wx-view></wx-view>'
						if(!html){
							html=resc;
						}else{
							html+=resc;
						}
					}
					$('#getindexGood').append(html);
					
					set=1;
				}else{
					$('#getindexGood_center').text('没有更多了');
					goodonttop=1;
					return false;
				}
		})
	}
	//绑定事件
	
	$('.scrollable').on('scroll',function(){
		if ($('.scrollable').height() + $('.scrollable').scrollTop() >= $('#getindexGood').height()) {
	    //dosomething
	    page++;
	    if(!keyword){
			getIndexGoodtow(page,limit,id,order);
		}
		if(!id){
			getIndexGood(page,limit,keyword,order);
		}
	    return false;
	  }
	})
	function getGoodsByStoreIdAndCategoryId(cid){
		page=0;
		limit=9;
		goodonttop=0;
		order=cid;
		if(goodonttop==1){
			return false;
		}
		
		$('#view_item view').removeClass('text-orange cur');
		$('#'+cid.replace(/\s*/g,"")).addClass('text-orange cur');
		page++;
		$('#getindexGood wx-view').remove();
		if(!keyword){
			getIndexGoodtow(page,limit,id,order);
		}
		if(!id){
			getIndexGood(page,limit,keyword,order);
		}
		
	}
	function good(good_id){
		parent.good(good_id);
	}
	function onclickset(){
		parent.onclickset();
	}
</script>