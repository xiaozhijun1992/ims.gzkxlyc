<?php /*a:1:{s:67:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\good\good.html";i:1585051505;}*/ ?>
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
	</head>
	<style type="text/css">
		.cu-bar.tabbar.shop .action {
			width:80px;
			-webkit-box-flex:initial;
			-webkit-flex:initial;
			-ms-flex:initial;
			flex:initial;

		}
		.cu-bar.tabbar .action {
			font-size:10px;
			position:relative;
			-webkit-box-flex:1;
			-webkit-flex:1;
			-ms-flex:1;
			flex:1;
			text-align:center;
			padding:0;
			display:block;
			height:auto;
			line-height:1;
			margin:0;
			background-color:inherit;
			overflow:initial;

		}
		body img{
			width: 100%;
			box-sizing: border-box;	
		}
		.jian {
			  border: 1px solid;
			  width: 25px;
			  height: 20px;
			  color: #ccc;
			  transition: color .25s;
			  position: relative;
			}
		.jian::before{
		      content: '';
			    position: absolute;
			    left: 12%;
			    top: 50%;
			    width: 16px;
			    margin-left: px;
			    margin-right: px;
			    margin-top: -2px;
			    border-top: 4px solid;
		}
		.jia {
			  border: 1px solid;
			  width: 25px;
			  height: 20px;
			  color: #ccc;
			  transition: color .25s;
			  position: relative;
			}
		.jia::before{
		  	content: '';
			    position: absolute;
			      left: 16%;
    			top: 50%;
    			width: 16px;
			    margin-left: px;
			    margin-right: px;
			    margin-top: -2px;
			    border-top: 4px solid;
		}
		.jia::after {
		    content: '';
		    position: absolute;
		    top: 14%;
    		height: 15px;
    		margin-left: -1.4px;
		    border-left: 4px solid;
		}
		.blacks::before{
			color: #000;
		}
		.blacks::after{
			color: #000;
		}
	</style>
	<body style="overflow: scroll;font-size: 14px;">
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
  
  <view style="height: 42px;box-shadow: 6px 6px 6px 6px;border: none;display: block;">
  	</view>
  <swiper class="screen-swiper square-dot" style="height:750px;" >
    <block wx:for="" wx:for-item="item" wx:for-index="index" wx:key="id">
      <swiper-item data-event-opts="" bindtap="__e">
        <div class="layui-carousel" id="carousel">
		    <div carousel-item id='imgs'>
		        
		       
		    </div>
		</div>
      </swiper-item>
    </block>
  </swiper>
  <block>
    <view class="bg-white" style="width: 100%;">
   		<view style="width: 96%;padding: 0 2%;display: block;margin-top: 8px;">
      <view class="text-cut-2 text-lg" style="font-size: 14px;" id="name"></view>
      <view class="margin-tb flex justify-between">
        <view>
          <text class="text-price text-xxl text-red margin-right-xs" style="font-size: 20px;" id="marketprice"></text>
          <block>
            <text class="text-gray text-sm text-delete" style="font-size: 12px;" id="productprice"> </text>
          </block>
          <block>
            <text class="cu-tag bg-red radius margin-left sm" style="font-size: 12px;" id="discount"></text>
          </block>
        </view>
        <!--bg-green-->
        <view class="cu-tag radius bg-blue" style="font-size: 14px;" id="order_type"></view>
        </view>
      </view>
      <view class="flex justify-between text-gray" style="100%;padding: 10px 2%;box-sizing: border-box;" >
        <view style="font-size: 14px;">销量:<span id="sales"></span></view>
        <block>
          <view style="font-size: 14px;">库存:<span id="total"></span></view>
        </block>
        <view style="font-size: 14px;">浏览量:<span id="visits"></span></view>
      </view>
    </view>
  </block>
  <view class="bg-mauve light padding-sm flex justify-between" onclick="justify_between();">
    <view class="flex align-center">
      <view class="cu-capsule">
        <view class="cu-tag bg-red">
          <text class="cuIcon-share"></text>
        </view>
        <view class="cu-tag line-red">返</view>
      </view>
      <text class="padding-left-sm">分享此商品可获取佣金</text>
    </view>
    <view  class="text-red" bindtap="__e">立即分享
      <text class="cuIcon-right"></text>
    </view>
  </view>
  <block>
    <view onclick="store()"  class="cu-bar bg-white solid-bottom margin-top-sm" bindtap="__e">
      <view class="action" style="font-size: 14px;" >
        <text class="cuIcon-shopfill text-orange" style="font-size: 18px;" ></text>
        <span id="store_name" >
        	
        </span></view>
      <view class="action text-gray">进店逛逛
        <text class="cuIcon-right"></text>
      </view>
    </view>
  </block>
  <block>
    <view class="cu-list menu">
      <!--<block>
        <view class="cu-item arrow" data-target="specModal"  bindtap="__e">
          <view class="content">
            <text class="text-grey">选择规格</text>
          </view>
          <view class="action">
            <block wx:if="{{good_info.goodOption.length>0}}">
              <text><text class="text-grey">已选：</text>{{good_info.goodOption[selectedOptionIndex].title+","+count+good_info.unit}}</text>
            </block>
            <block wx:if="{{good_info.goodOption.length==0}}">
              <text><text class="text-grey">已选：</text>{{count+good_info.unit}}</text>
            </block>
          </view>
        </view>
      </block>-->
      <view class="cu-item arrow" data-target="paramModal" data-event-opts="{{[['tap',[['showModal',['$event']]]]]}}" bindtap="__e">
        <view class="content">
          <text class="text-grey" style="font-size: 14px;">商品参数</text>
        </view>
      </view>
    </view>
  </block>
  <block>
    <view class="cu-bar bg-white solid-bottom margin-top-sm">
      <view class="action" style="font-size: 14px;">
        <text class="cuIcon-commentfill text-orange" style="font-size: 18px;"></text>评价（<span id='comments'></span>）</view>
      <block>
        <view  class="action text-gray" onclick="allcomments(id)" bindtap="__e">全部评价
          <text class="cuIcon-right"></text>
        </view>
      </block>
    </view>
  </block>
  <view class="text-center bg-white padding margin-top-sm" style="width: 100%;display: block;padding: 10px 2%;box-sizing: border-box;color:#666666;font-size: 14px;">———— 商品详情 ————</view>
  <block>
    <view class="bg-white padding-xs" style="display: block;">
      <view style="font-weight: bolder;display: inline;width: 100%;padding: 10px 2%;display: block;font-size: 15px;user-select: text;line-height: 30px;box-sizing: border-box;    color: #333;" id="detail"></view>
      <view class="text-gray text-center padding-sm text-sm" style="width: 100%;display: block;padding: 10px 2%;box-sizing: border-box;font-size: 12px;">—— 我是有底线的 ——</view>
    </view>
  </block>
  <view class="cu-tabbar-height" style="display: block;"></view>
  <block >
    <view class="cu-bar bg-white tabbar border shop foot shadow" style="height: 42px;box-shadow: 6px 6px 6px 6px;border: none;">
      <a id='contact_phone' href=""><button data-event-opts="" class="action" bindtap="__e" style="border: none;">
        <view class="cuIcon-service text-green">
          <view class="cu-tag badge"></view>
        </view>电话</button></a>
      <view data-event-opts="" class="action text-orange text-gray shouchang" bindtap="__e">
        <!--cuIcon-favorfill-->
        <view class="cuIcon-favor"></view><cc>收藏</cc></view>
      <view data-event-opts="" class="action gouwuche" bindtap="__e">
        <view class="cuIcon-cart">
          <view class="cu-tag badge gouwu">0</view>
        </view>购物车</view>
      <block style="height: 100%;width: 23.5%;">
        <view style="height: 100%;" data-event-opts="" class="bg-orange submit good_buttcc" data-onclickset='1' bindtap="__e">加入购物车</view>
      </block>
      <block style="height: 100%;width: 23.5%;">
        <view  style="height: 100%;" data-event-opts="" class="bg-red submit good_buttcc" data-onclickset='2' bindtap="__e">立即购买</view>
      </block>
      <!--<block >
        <view class="bg-grey submit">已售罄</view>
      </block>-->
    </view>
  </block>
  <view  class="cu-modal bottom-modal params"  bindtap="__e">
    <view class="cu-dialog" style="position: absolute;bottom: 0;left: 0;padding: 10px;">
      <view class="cu-bar bg-white justify-center">商品参数</view>
      <block>
        <view class="padding-sm padding-bottom-lg">
          <block  id='param'>
            
          </block>
        </view>
      </block>
      <block >
        <view id="paramtow" class="text-gray text-xs padding-lg">暂无商品参数</view>
      </block>
    </view>
  </view>
  <view  class="cu-modal bottom-modal good_butt" bindtap="__e">
    <view  class="cu-dialog bg-white" style="position: absolute;bottom: 0;left: 0;" catchtap="__e">
      <view class="flex padding-sm">
        <image class="flex-sub w-100 h-0 radius" style="width: 76px;height: 76px;" id="img_one_good" mode="widthFix" src=""></image>
        <view class="flex-treble flex">
          <view class="flex-sub flex padding flex-direction align-start">
            	<view class="text-red text-lg text-price" id="text-price"></view>
              <view class="padding-tb">
                <text class="text-gray padding-right-xs">已选<span id='unit' style="padding: 0 5px;"></span></text></view>
          </view>
          <text data-event-opts="" class="text-gray cuIcon-close" bindtap="__e"></text>
        </view>
      </view>
      <div class="text-left padding" id="spec">
      	
      	
      </div>
      
      <div id="">
      	<view class="flex padding-sm" style="position: relative;left: 65%;border: 1px solid #ccc;padding: 0;margin: 8px;width: 80px;">
	      	<div class="jian"></div>
	      	<input style="width: 30px;height: 20px;border: none;text-align: center;" type="text" name="" id="count" value="1" />
	      	<div class="jia blacks"></div>
	     </view>
      </div>
      
      <view  style='margin: 10px;display: block;'data-event-opts="" class="bg-yellow padding text-lg onclickset" bindtap="__e">确认</view>
      
    </view>
  </view>
</view>
	</body>
</html>
<script type="text/javascript">
//	var urlc = location.pathname;
//	if(urlc!="/home"){ 
//		window.location.href="/home";
//	}
	var id="<?= $_GET['id'];?>";
	if(!id){
		window.history.back(-1);
	}
	window.onload=function(){
		parent.$('#loads').hide();
	}
	getGoodImg(id);
	var img;
	var marketprice;
	var name;
	var store_id;
	var keep;
	var user_id=parent.user_id;
	$('.gouwu').text(parent.gouwu_c);
	function getGoodImg(id){
			var html;
			$.post("/api/index/getGoodImg",{id},function(res){
				if(res.count>0){
					var resc;
					for (var i=0;i<res.count;i++) {
						resc='<img onclick="jumpToCaroucel('+res.data[i].id+')" src="/'+res.data[i].img+'"/>';
						if(!html){
							html=resc;
						}else{
							html+=resc;
						}
					}
					img=res.data[0].img;
					$('#imgs').append(html);
					setSwipe();
					$('#img_one_good').attr('src','/'+img);
				}else{
					return false;
				}
			})	
		}
	function setSwipe(){
	 		var ins;
	layui.use(['carousel','element','layer'], function(){
        var carousel = layui.carousel;
        layer = layui.layer;
        var element = layui.element; //导航的hover效果、二级菜单等功能，需要依赖element模块
        //建造实例
        ins=carousel.render({
            elem: '#carousel'
            ,width: '100%' //设置容器宽度
            ,arrow: 'none' //始终显示箭头
            
            ,height: '320px'
            //,anim: 'updown' //切换动画方式
        });

    });


	$("#carousel").on("touchstart", function (e) {
        var startX = e.originalEvent.targetTouches[0].pageX;//开始坐标X
        $(this).on('touchmove', function (e) {
            arguments[0].preventDefault();//阻止手机浏览器默认事件
        });
        $(this).on('touchend', function (e) {
            var endX = e.originalEvent.changedTouches[0].pageX;//结束坐标X
            e.stopPropagation();//停止DOM事件逐层往上传播
            if (endX - startX > 30) {
                ins.slide("sub");
            }
            else if (startX - endX > 30) {
                ins.slide("add");
            }
            $(this).off('touchmove touchend');
        });
    })
	 }
	getGoodInfo(id);
	var goodOption;
	var maxbuy;
	var unit;
	function getGoodInfo(good_id){
		var paramhtmls;
			$.post("/api/index/getGoodInfo",{good_id},function(res){
				if(res.data){
					var resc;
					marketprice=res.data.marketprice;
					name=res.data.name;
					$('#order_type').text(res.data.order_type==0?'线上发货':"线下核销");
					$('#comments').text(res.data.comments.length);
					$('#total').text(res.data.total+res.data.unit);
					$('#sales').text(res.data.sales+res.data.unit);
					$('#visits').text(res.data.visits);
					$('#name').text(res.data.name);
					$('#marketprice').text(res.data.marketprice);
					$('#productprice').text(res.data.productprice);
					$('#detail').append(res.data.detail);
					$('#discount').text((parseInt((res.data.marketprice/res.data.productprice)*100))/10+'折');
					$('#store_name').text(res.data.store.name);
					$('#unit').text(1+res.data.unit);
					unit=res.data.unit;
					$('#text-price').text(res.data.marketprice);
					store_id=res.data.store.id;
					maxbuy=res.data.maxbuy;
					$('#contact_phone').attr('href','tel:'+res.data.store.contact_phone);
					if(res.data.param){
						for (var j=0;j<(res.data.param).length;j++) {
							 var paramhtml='<view class="flex bg-white dashed-bottom"><view class="basis-xs bg-kxly padding-xs">'+res.data.param[j].title+'</view><view class="flex-sub padding-xs ">'+res.data.param[j].value+'</view></view>';
							if(!paramhtmls){
								paramhtmls=paramhtml
							}else{
								paramhtmls+=paramhtml;
							}
						}
						$('#paramtow').remove();
						$('#param').append(paramhtmls);
					}else{
						$('#param').remove();
					}
					$.post('/api/index/getKeep',{user_id,good_id},function(res){
						if(res.data){
							keep=false;
							$('.shouchang').addClass('text-orange');
							$('.shouchang').removeClass('text-gray');
							$('.shouchang view').addClass('cuIcon-favorfill');
							$('.shouchang view').removeClass('cuIcon-favor');
							$('.shouchang cc').text('已收藏');
						}else{
							keep=true;
							$('.shouchang').addClass('text-gray');
							$('.shouchang').removeClass('text-orange');
							$('.shouchang view').addClass('cuIcon-favor');
							$('.shouchang view').removeClass('cuIcon-favorfill');
							$('.shouchang cc').text('收藏');
						}
					})
//					split(”|”)
					goodOption=res.data.goodOption;
					var spec_item=res.data.spec;
					if(goodOption){
						var spec_itemhtml;
						var c=0;
						var check_spec;
						var spec_id;
						for (var s=0;s<spec_item.length;s++) {
							var spec_itemhtml1='<div class="margin-bottom text-gray">'+spec_item[s].spec_title+'</div><div class="flex flex-wrap">';
							for (var si=0;si<spec_item[s].item.length;si++) {
								var addclass='';
								if(si==0){
									var addclass='bg-yellow';
								}
								var spec_itemhtml2='<button data-id="'+spec_item[s].item[si].id+'" class="cu-btn radius margin-bottom margin-right '+addclass+'">'+spec_item[s].item[si].title+'</button>'
								spec_itemhtml1+=spec_itemhtml2;
								if(si+1==spec_item[s].item.length){
									spec_itemhtml1+='</div>';
								}
							}
							if(!spec_itemhtml){
								spec_itemhtml=spec_itemhtml1;
							}else{
								spec_itemhtml+=spec_itemhtml1;
							}
						}
					}
					$('#spec').append(spec_itemhtml);
					$('#spec button').on('click',function(){
						$(this).parent('div').find('button').removeClass('bg-yellow');
						$(this).addClass('bg-yellow');
					});
					$('.arrow').on('click',function(){
						$('.params').addClass('show');
						$('body').attr('style','overflow: hidden');
					})
					$('.params ').on('click',function(){
						$('.params').removeClass('show');
						$('body').attr('style','overflow: scroll;font-size: 14px;');
					})
					$('.jia').on('click',function(){
						var countc=$('#count').val();
						if(parseInt(countc)<maxbuy){
							$('#count').val(parseInt(countc)+1);
							$('#unit').text(parseInt(countc)+1+unit);
							if(!$('.jian').hasClass('blacks')){
								$('.jian').addClass('blacks');
								
							}
							if((parseInt(countc)+1)==maxbuy){
								$('.jia').removeClass('blacks');
							}
						}else{
							alert('每人限量购买'+maxbuy+unit);
						}

					})
					$('.jian').on('click',function(){
						var countc=$('#count').val();
						if(parseInt(countc)>1){
							$('#count').val(parseInt(countc)-1);
							$('#unit').text(parseInt(countc)-1+unit);
							if(!$('.jia').hasClass('blacks')){
								$('.jia').addClass('blacks');
								
							}
							if((parseInt(countc)-1)==1){
								$('.jian').removeClass('blacks');
							}
						}
						
					})
					$('.shouchang').on('click',function(){
						
						
						$.post('/api/index/goodKeep',{user_id,good_id,keep},function(res){
							if(res.message){
								if(keep){
									keep=false;
									$('.shouchang').addClass('text-orange');
									$('.shouchang').removeClass('text-gray');
									$('.shouchang view').addClass('cuIcon-favorfill');
									$('.shouchang view').removeClass('cuIcon-favor');
									$('.shouchang cc').text('已收藏');
								}else{
									keep=true;
									$('.shouchang').addClass('text-gray');
									$('.shouchang').removeClass('text-orange');
									$('.shouchang view').addClass('cuIcon-favor');
									$('.shouchang view').removeClass('cuIcon-favorfill');
									$('.shouchang cc').text('收藏');
								}
								alert(res.message);
							}
						});
					})
				}else{
					return false;
				}
			})
	}
	function onclickset(){
		parent.onclickset();
	}
	function justify_between(){
		parent.onclicksetjia('<?php echo url("/home/good/share"); ?>','id='+id+'&img='+img+'&name='+name+'&money='+marketprice);

	}
	function store(){
		parent.onclicksetjia('<?php echo url("/home/stores/stores"); ?>','id='+store_id);
	
	}
	function allcomments(){
		parent.onclicksetjia('<?php echo url("/home/good/allcomments"); ?>','id='+id);
	
	}
	$('.gouwuche').on('click',function(){
		parent.remove_jia=parent.getChildren().length;
		parent.$('.tabbar-root .tabbar-item:eq(3)').trigger('click');
		
	})
	
	$('.cuIcon-close').on('click',function(){
		$('.good_butt').removeClass('show');
		$('body').attr('style','overflow: scroll;font-size: 14px;');
	})
	$('.good_buttcc').on('click',function(){
		$('.good_butt').addClass('show');
		$('.good_butt').attr('data-onclickset',$(this).attr('data-onclickset'))
		$('body').attr('style','overflow: hidden');
	})
	var tags = [];
	$('.onclickset').on('click',function(){
		var option_specs;
		$("#spec .bg-yellow").each(function(i,e){
         	 tags[i]= $(this).attr('data-id');
         	 if(!option_specs){
         	 	option_specs=tags[i];
         	 }else{
         	 	option_specs=option_specs+'_'+tags[i];
         	 }
         	 
        });
        var op_idc=null;
        if(option_specs){
	        for (var op_id=0;op_id<goodOption.length;op_id++) {
	        	if(option_specs==goodOption[op_id].specs){
	        		op_idc=goodOption[op_id].id;
	        		break;
	        	}
	        }
        }
		var count=$('#count').val();
		var sarray =new Array();
		var goods=new Array();
		var data=[{store_id:store_id,goods:[{good_id:id,option_id:op_idc,count:count}]}];
		data=encodeURIComponent(JSON.stringify(data));
		
		var odc=0;
		var odgc=0;
		var odb;
		if($('.good_butt').attr('data-onclickset')==1){
			gouwu_c=$('.gouwu').text();
			odgc=gouwu_c;
			if(parent.odre_r.length==0){
				parent.odre_r[parent.odre_r.length]={store_id:store_id,goods:[{good_id:id,option_id:op_idc,count:count}]};
			}else{
				for (var od=0;od<parent.odre_r.length;od++) {
					
					if(parent.odre_r[od].store_id==store_id){
						odc=1;
						for (var odg=0;odg<parent.odre_r[od].goods.length;odg++) {
							if(parent.odre_r[od].goods[odg].good_id!=id){
								parent.odre_r[od].goods[parent.odre_r[od].goods.length]={good_id:id,option_id:op_idc,count:count};
								break;
							}
						}
					}
					
				}
				if(odc!=1){
					if(odgc==gouwu_c){
						parent.odre_r[parent.odre_r.length]={store_id:store_id,goods:[{good_id:id,option_id:op_idc,count:count}]};	
					}
				}
				
			}
			
//			gouwu_c=0;
			var gouwu_c0=0;
			for (var c=0;c<parent.odre_r.length;c++) {
				var dis=new Array();
				var c1=new Array();
				var cr=0;
				
				for (var cb=0;cb<parent.odre_r[c].goods.length;cb++) {
					if(c1[parent.odre_r[c].goods[cb].good_id]!=1){
						c1[parent.odre_r[c].goods[cb].good_id]=1;
						dis[cr]=parent.odre_r[c].goods[cb];
						cr++;
					}
				}
				parent.odre_r[c].goods=dis;
				gouwu_c0=gouwu_c0+dis.length;
				
//				parent.odre_r[c].goods=unique2(parent.odre_r[c].goods);
//			console.log(parent.odre_r[c]);//	
//			console.log(unique3(parent.odre_r[c].goods));
//				gouwu_c=gouwu_c+parent.odre_r[c].goods.length;
			}
			parent.gouwu_c=gouwu_c0;
			console.log(parent.gouwu_c);
			$('.gouwu').text(parent.gouwu_c);
			$('.good_butt').attr('data-onclickset','2');
			
		}else{
			parent.onclicksetjia('/home/order/orderconfirm','data='+data);
		}
		$('.good_butt').removeClass('show');
		$('body').attr('style','overflow: scroll;font-size: 14px;');
	})
	addVisits();
	function addVisits(){
		$.get('/api/good/addVisits',{good_id:id},function(res){})
	}
</script>