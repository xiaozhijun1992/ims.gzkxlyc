<?php /*a:1:{s:70:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\tabbar\store.html";i:1584879587;}*/ ?>
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
		<body>
			<div class="scrollable" style="margin-bottom: 57px;">
				
			
		<view>
			<div class="head">
				<div class="jshook-ws-head" style="display: flex; background-color: #fa922f;color: #fff;">
					<div class="head-back jshook-ws-head-back" style="display: none;">

					</div>
					<div class="head-home jshook-ws-head-home"  style="display: flex;font-size: 14px;">
						
					</div>
					<h3 class="head-title jshook-ws-head-title" style="color: black;">
    <i class="head-title-loading" style="display: none;"></i>
    <span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">店铺</span>
  </h3>
					<div class="head-option jshook-ws-head-option"></div>
				</div>
			</div>
		</view>
		<view class="cu-bar search bg-white" style="overflow-y: scroll;">
			<view class="search-form round">
				<text class="cuIcon-search"></text>
				<input class="inputs" type="text" placeholder="店铺搜索" confirm-type="search" value="" bindconfirm="__e" bindinput="__e" />
			</view>
			<view class="action">
				<button class="cu-btn bg-green shadow-blur round roundcc"  bindtap="__e">搜索</button>
			</view>
		</view>
		<wx-view class="cu-list grid col-5 no-border" id="getIndustryList">
							
		</wx-view>
		<scroll-view class="bg-white nav margin-top-xs" style="" scroll-x>
		    <view class="flex text-center" id="setstore" style="background: #fff;margin-top: 5px;">
		        <view class="cu-item flex-sub text-orange cur" onclick="setstore($(this))">附近</view>
		        <view class="cu-item flex-sub " onclick="setstore($(this))">推荐</view>
		        <view class="cu-item flex-sub " onclick="setstore($(this))">人气</view>
		        <view class="cu-item flex-sub " onclick="setstore($(this))">最新</view>   
		    </view>
  		</scroll-view>
  			<div id="">
  				<div id="getIndexRecommendStore" >
  			
  			</div>
  			<wx-view class="text-center text-sm text-gray padding" id="getindexGood_center"></wx-view>
  			</div>
  		
		<div class="tabbar-root ">
			<div class="tabbar jshook-ws-tabbar" style="display: flex; background-color: rgb(255, 255, 255); border-color: black; height: 56px;">
				<div class="tabbar-item jshook-ws-tabbar-item" key="0">
					<img class="tabbar-icon" src="/static/tabbar/index.png" icon="static/tabbar/index.png" select-icon="static/tabbar/index_cur.png" alt="">
					<p class="tabbar-label" style="color:#aaaaaa">
						首页
					</p>
				</div>
				<div class="tabbar-item jshook-ws-tabbar-item" key="1">
					<img class="tabbar-icon" src="/static/tabbar/near_cur.png" icon="static/tabbar/near.png" select-icon="static/tabbar/near_cur.png" alt="">
					<p class="tabbar-label" style="color: rgb(250, 146, 47);">
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
					<img class="tabbar-icon" src="/static/tabbar/my.png" icon="static/tabbar/my.png" select-icon="static/tabbar/my_cur.png" alt="">
					<p class="tabbar-label" style="color: rgb(170, 170, 170);">
						我的
					</p>
				</div>
			</div>
		</div>
			</div>
	</body>
</html>
<script type="text/javascript">
getIndexCategory();
	window.onload=function(){
		parent.$('#loads').hide();
	}
	function getIndexCategory(){
			var html;
			
			$.post("/api/index/getIndustryList",'',function(res){
				if((res.data).length>0){
					var resc;
					for (var i=0;i<(res.data).length;i++) {
						
						resc='<wx-view class="cu-item" data-event-opts="tap,toCategoryList,$event" ><wx-view class="padding-0 padding-lr" onclick="goodCategory('+res.data[i].id+')"><wx-image mode="widthFix" style="height: 38px;" src="/static/tabbar/all_category.png"><div style="background-image: url(/'+res.data[i].img+'); background-size: 100% 100%; background-repeat: no-repeat;"></div><virtual></virtual></wx-image></wx-view><wx-text><span style="display:none;">'+res.data[i].name+'</span><span>'+res.data[i].name+'</span></wx-text></wx-view>';
						if(!html){
							html=resc;
						}else{
							html+=resc;
						}
					}
//					html+='<wx-view class="cu-item" data-event-opts="tap,toCategoryList,$event" >	<wx-view class="padding-0 padding-lr" onclick="goodCategorys(all)"><wx-image mode="widthFix" style="height: 38px;" src="/static/tabbar/all_category.png"><div style="background-image: url(/static/tabbar/all_category.png); background-size: 100% 100%; background-repeat: no-repeat;"></div><virtual></virtual></wx-image>	</wx-view><wx-text><span style="display:none;">所有分类</span><span>所有分类</span></wx-text></wx-view>'
					$('#getIndustryList').append(html);
				}else{
					return false;
				}
			})	
		}
	var urlcc='/api/index/getStoreListOrderByDistance';
	var page=0;
	var limit=9;
	var set=1;
	var goodonttop=0;
	var lat="<?= $_GET['lat'];?>";
	var lng="<?= $_GET['lng'];?>";
	function getIndexRecommendStore(url,lat,lng,page,limit){
			set=0;
			if(goodonttop==1){
				return false;
			}
			$.get(url,{lat,lng,page,limit},function(res){
				var html;
//				var htmlc='<block ><view class="cu-bar justify-center bg-white"><view class="cu-bar bg-white"><view class="action title-style-3"><text class="text-xl text-bold">优质商家</text><text class="text-Abc text-gray self-end margin-left-sm">Good Store</text></view></view></view>	</block>';
					      
				if(res.data.length>0){
					var resc;
					for (var i=0;i<res.data.length;i++) {
						resc='<view class="cu-card case"><block ><view onclick="jumpToCaroucel('+res.data[i].id+')" class="flex margin-top-sm bg-white margin radius" bindtap="__e"><view class="flex-twice flex justify-center align-center"><image class="margin-xs radius" style="width:100%;" src="/'+res.data[i].show_img+'" mode="widthFix"></image></view><view class="flex-treble padding"><view><view class="text-cut-2">'+res.data[i].name+'</view></view><view class="flex justify-between align-center"><view><uni-rate  bind:__l="__l"></uni-rate></view><view class="text-xs">'+fomatFloat(res.data[i].distance,2)+'Km</view></view><view class="padding-top-xs text-xs text-grey text-cut-2"><text class="cu-tag bg-red radius margin-right-xs text-xs">主营业务</text>'+res.data[i].business+'</view><view class="padding-top-xs text-xs text-grey text-cut-2"><text class="cu-tag bg-red radius margin-right-xs text-xs">店铺地址</text>'+res.data[i].address+'</view></view></view>'
						if(!html){
							html=resc;
						}else{
							html+=resc;
						}
					}
					$('#getIndexRecommendStore').append(html);
					set=1
				}else{
					$('#getindexGood_center').text('没有更多了');
					goodonttop=1;
					return false;
				
				}
			})
		}
	var arraysetorder=new Array();
	arraysetorder['附近']='getStoreListOrderByDistance';
	arraysetorder['推荐']='getStoreListOrderByRecommend';
	arraysetorder['人气']='getStoreListOrderByVisits';
	arraysetorder['最新']='getStoreListOrderByCreate';
	getIndexRecommendStore(urlcc,lat,lng,page+1,limit);
	function setstore(obj){
//		/api/index/
		page=0;
		limit=9;
		goodonttop=0;
		if(arraysetorder[obj.text()]){
			ulr='/api/index/'+arraysetorder[obj.text()];
			$('#setstore view').removeClass('text-orange cur');
			obj.addClass('text-orange cur');
			page++;
			$('#getIndexRecommendStore view').remove();
			getIndexRecommendStore(urlcc,lat,lng,page,limit);
		}else{
			return false;
		}

	}
	$('.scrollable').on('scroll',function(){
		if ($('.scrollable').height() + $('.scrollable').scrollTop() >= $('#getIndexRecommendStore').height()) {
	    //dosomething
	    if(set==0){
	    	return false;
	    }
	    page++;
	    getIndexRecommendStore(urlcc,lat,lng,page,limit);
	    return false;
	  }
	})
	function fomatFloat(src,pos){   //src是要保留小数的值，pos是要保留几位小数；
   		return Math.round(src*Math.pow(10, pos))/Math.pow(10, pos);   
	} 
	function jumpToCaroucel(text){
		parent.jumpToCaroucel(text);
	}
	function goodCategory(id){
		parent.onclicksetjia('<?php echo url("/home/stores/industrystorelist"); ?>','id='+id);
	}
	$('.roundcc').on('click',function(){
		var texts=$('.inputs').val();
		parent.onclicksetjia('<?php echo url("/home/stores/industrystorelist"); ?>','keyword='+texts);
	})

</script>