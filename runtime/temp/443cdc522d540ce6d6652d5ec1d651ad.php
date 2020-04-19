<?php /*a:1:{s:69:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\tabbar\cart.html";i:1586347640;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">

		<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
		<title></title>

		<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" /> <link rel="stylesheet" type="text/css" href="/static/css/app.css" /> <script type="text/javascript" src="/static/lib/layui/layui.js"></script> <link rel="stylesheet" type="text/css" href="/static/lib/layui/css/layui.css" /> <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> <script type="text/javascript" src="/static/js/jqset.js"></script>
	</head>
		<style type="text/css">
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
		    left: 50%;
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
	<body>
		<div class="head">
			<div class="jshook-ws-head" style="display: flex; background-color: #fa922f;color: #fff;">
				<div class="head-back jshook-ws-head-back" style="display: none;">

				</div>
				<div class="head-home jshook-ws-head-home" style="display: flex;">

				</div>
				<h3 class="head-title jshook-ws-head-title" style="color: black;">
   			 <i class="head-title-loading" style="display: none;"></i>
    		<span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">购物车</span>
  			</h3>
				<div class="head-option jshook-ws-head-option"></div>
			</div>
		</div>
		<div class="scrollable" style="bottom: 56px; top: 42px;">
			<div>
				<div>
					<div id='setgood' style="margin-bottom: 100px;">
						
					</div>
				</div>
			</div>
			
		</div>
		<view class="cu-tabbar-height" id="foots" ></view>
		    
		</view>
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
					<img class="tabbar-icon" src="/static/tabbar/cart_cur.png" icon="static/tabbar/cart.png" select-icon="static/tabbar/cart_cur.png" alt="">
					<p class="tabbar-label" style="color: rgb(250, 146, 47);">
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
	</body>

</html>
<script type="text/javascript">
gooddata();
window.onload=function(){
		parent.$('#loads').hide();
	}
function gooddata(){
	var odre_rc=encodeURIComponent(JSON.stringify(parent.odre_r));
	$.post('/api/index/getGoodData',{data:odre_rc},function(res){
		var html;
		if(res.data){
			var html;
			var new_sum=0;
			for (var i=0;i<res.data.length;i++) {
				var datahtml='<view class="cu-bar bg-white solid-bottom margin-top" bindtap="__e" onclick="store('+res.data[i].id+')"><view class="action" style="font-size: 16px;"><text class="cuIcon-shopfill text-orange "></text>'+res.data[i].name+'</view><view class="action"><text class="cuIcon-right"></text></view></view>';
				var marketprice=res.data[i].marketprice;
				
				for (var j=0;j<res.data[i].goodList.length;j++) {
					if(res.data[i].goodList[j].marketprice){
						marketprice=res.data[i].goodList[j].marketprice;
					}
					var title='无';
					var option_id;
					if(res.data[i].goodList[j].option){
						title=res.data[i].goodList[j].option.title;
						option_id=res.data[i].goodList[j].option.id;
					}
					if(res.data[i].goodList[j].order_type==0){
						res.data[i].goodList[j].order_type="线上";
						order_type='bg-green';
					}else{
						res.data[i].goodList[j].order_type="线下";
						order_type='bg-blue';
					}
					
					var datahtmlgoodList='<div><view class="flex padding-sm bg-white align-center solid-bottom"><text class="text-orange padding-right-sm cuIcon-radiobox cuIcon_one" data-id='+res.data[i].id+' style="width:50rpx;font-size:36rpx;" bindtap="__e"></text><view class="basis-xs" style="position:relative;"><image class="radius" style="width:85px;height:85px;" src="/'+res.data[i].goodList[j].getImage[0].img+'"></image><view class="cu-tag radius sm '+order_type+'" style="position:absolute;bottom:0;right:0;border-top-right-radius:0;border-bottom-right-radius:0;">'+res.data[i].goodList[j].order_type+'</view></view><view class="flex-sub padding-0 padding-left-sm"><view class="text-cut-2">'+res.data[i].goodList[j].name+'</view><view class="text-gray option_id"  data-id="'+option_id+'">规格: '+title+'</view><view class="flex justify-between align-center"><view class="text-red text-price">'+marketprice+'</view><view><div id="">	<view class="flex padding-sm" style="position: relative;border: 1px solid #ccc;padding: 0;margin: 8px;width: 80px;"><div class="jian jian_'+res.data[i].goodList[j].id+'" data-id='+res.data[i].goodList[j].id+' data-maxbuy='+res.data[i].goodList[j].maxbuy+'></div><input style="width: 30px;height: 20px;border: none;text-align: center;" type="text" name="c_res.data[i].goodList[j].id" id="count'+res.data[i].goodList[j].id+'" value="'+res.data[i].goodList[j].count+'" /><div class="jia blacks jia_'+res.data[i].goodList[j].id+'" data-id='+res.data[i].goodList[j].id+' data-maxbuy='+res.data[i].goodList[j].maxbuy+'></div></view>	</div></view></view>	</view></view></div>';
					datahtml+=datahtmlgoodList;
				
					new_sum=Math.floor(Number(new_sum*100)+Number(100*(marketprice)*res.data[i].goodList[j].count))/100;
				}
				if(!html){
					html=datahtml;
				}else{
					html+=datahtml;
				}
			}
			$('#setgood').append(html);
			$('#foots').append('<view class="cu-bar bg-white tabbar solid-top foot justify-between" style="position: fixed;bottom: 57px;"><view class="text-lg padding-left-sm"><text class="text-red padding-right-sm cuIcon-radiobox cuIcon_all"></text>全选</view><view class="flex align-center padding-right-sm"><view class="padding-right-sm">总计:<text class="text-red" id="sumAmount">'+new_sum+'</text></view><button class="cu-btn bg-red round shadow-blur" onclick="onclick_jia();">去结算</button></view></view>');
			$('.jia').on('click',function(){
				var good_idcountc=$(this).attr('data-id');
				var countc=$('#count'+good_idcountc).val();
				var maxbuy=parseInt($(this).attr('data-maxbuy'));
				if(parseInt(countc)<maxbuy){
					$('#count'+good_idcountc).val(parseInt(countc)+1);
					
					if(!$('.jian_'+good_idcountc).hasClass('blacks')){
						$('.jian_'+good_idcountc).addClass('blacks');
						
					}
					if((parseInt(countc)+1)==maxbuy){
						$(this).removeClass('blacks');
					}
					for (var i=0;i<parent.odre_r.length;i++) {
						for(var j=0;j<parent.odre_r[i].goods.length;j++){
							if(good_idcountc==parent.odre_r[i].goods[j].good_id){
								parent.odre_r[i].goods[j].count++;
							}
						}
					}
					var sum=$('#sumAmount').text();
					
					$('#sumAmount').text(Math.floor(Number(100*sum)+Number(100*$(this).parent().parent().parent().parent().find('.text-price').text()))/100);
					
					
				}else{
					alert('每人限量购买'+maxbuy);
				}

			})
			$('.jian').on('click',function(){
				var good_idcountc=$(this).attr('data-id');
				var countc=$('#count'+good_idcountc).val();
				if(parseInt(countc)>1){
					$('#count'+good_idcountc).val(parseInt(countc)-1);
					if(!$('.jia_'+good_idcountc).hasClass('blacks')){
						$('.jia_'+good_idcountc).addClass('blacks');
						
					}
					if((parseInt(countc)-1)==1){
						$(this).removeClass('blacks');
					}
					for (var i=0;i<parent.odre_r.length;i++) {
						for(var j=0;j<parent.odre_r[i].goods.length;j++){
							if(good_idcountc==parent.odre_r[i].goods[j].good_id){
								parent.odre_r[i].goods[j].count--;
							}
						}
					}
					var sum=$('#sumAmount').text();
					$('#sumAmount').text(Math.floor(Number(100*sum)-Number(100*$(this).parent().parent().parent().parent().find('.text-price').text()))/100);
					
				}
				
			})
			$('.cuIcon_one').on('click',function(){
				if(!$(this).hasClass('cuIcon-radiobox')){
					$(this).addClass('cuIcon-radiobox');
					$(this).removeClass('cuIcon-round');
				}else{
					$(this).addClass('cuIcon-round');
					$(this).removeClass('cuIcon-radiobox');
				}
				if($('.cuIcon_one').hasClass('cuIcon-round')){
					$('.cuIcon_all').removeClass('cuIcon-radiobox');
					$('.cuIcon_all').addClass('cuIcon-round');
				}else{
					$('.cuIcon_all').removeClass('cuIcon-round');
					$('.cuIcon_all').addClass('cuIcon-radiobox');
				}
				var sums=0;
				var sum_price=$('.cuIcon-radiobox').parent().find('.justify-between').find('.text-price');
				var sum_input=$('.cuIcon-radiobox').parent().find('.justify-between').find('input');
				for (var i=0;i<sum_price.length;i++) {
					sums=(Number(sums)+Number((sum_price[i].innerHTML*$(sum_input[i]).val()))).toFixed(2);
				}
				$('#sumAmount').text(sums);
			})
			var cset=1;
			$('.cuIcon_all').on('click',function(){
				if(cset==1){
					$('#setgood .cuIcon_one').removeClass('cuIcon-radiobox');
					$('#setgood .cuIcon_one').addClass('cuIcon-round');
					$('.cuIcon_all').removeClass('cuIcon-radiobox');
					$('.cuIcon_all').addClass('cuIcon-round');
					$('#sumAmount').text(0);
					cset=2;
				}else{
					var sum=$('#sumAmount').text();
					$('#setgood .cuIcon_one').removeClass('cuIcon-round');
					$('#setgood .cuIcon_one').addClass('cuIcon-radiobox');
					$('.cuIcon_all').removeClass('cuIcon-round');
					$('.cuIcon_all').addClass('cuIcon-radiobox');
					var sums=0;
					var sum_price=$('.cuIcon-radiobox').parent().find('.justify-between').find('.text-price');
					var sum_input=$('.cuIcon-radiobox').parent().find('.justify-between').find('input');
					for (var i=0;i<sum_price.length;i++) {
						sums=(Number(sums)+Number((sum_price[i].innerHTML*$(sum_input[i]).val()))).toFixed(2);
					}
					$('#sumAmount').text(sums)
					cset=1;
				}
				
			})
		}
	})
}
function onclick_jia(){
//	var c=$('.cuIcon-radiobox').parent().find('.justify-between').find('.jian')
	var c=$('#setgood .cuIcon-radiobox');
	
	parent.nodre_r=[];
	
	for (var i=0;i<c.length;i++) {
		var op_id=null;
		if($(c[i]).parent().find('.option_id').attr('data-id')!="undefined"){
			op_id=$(c[i]).parent().find('.option_id').attr('data-id');
		}
		if(parent.nodre_r.length==0){
			parent.nodre_r[parent.nodre_r.length]={store_id:$(c[i]).attr('data-id'),goods:[{good_id:$(c[i]).parent().find('.jian').attr('data-id'),option_id:op_id,count:$(c[i]).parent().find('input').val()}]};
		}else{
			var odc=0;
			for (var od=0;od<parent.nodre_r.length;od++) {
				if(parent.nodre_r[od].store_id==$(c[i]).attr('data-id')){
					odc=1;
					for (var odg=0;odg<parent.nodre_r[od].goods.length;odg++) {
						if(parent.nodre_r[od].goods[odg].good_id!=$(c[i]).parent().find('.jian').attr('data-id')){
							parent.nodre_r[od].goods[parent.nodre_r[od].goods.length]={good_id:$(c[i]).parent().find('.jian').attr('data-id'),option_id:op_id,count:$(c[i]).parent().find('input').val()};
							break;
						}
					}
				}
				
			}
			if(odc!=1){
				parent.nodre_r[parent.nodre_r.length]={store_id:$(c[i]).attr('data-id'),goods:[{good_id:$(c[i]).parent().find('.jian').attr('data-id'),option_id:op_id,count:$(c[i]).parent().find('input').val()}]};
				
			}
		}
	}
	var g=$('#setgood .cuIcon-round');
	parent.newodre_r=[];
	
	for (var i=0;i<g.length;i++) {
		var op_id=null;
		if($(g[i]).parent().find('.option_id').attr('data-id')!="undefined"){
			op_id=$(g[i]).parent().find('.option_id').attr('data-id');
		}
		if(parent.newodre_r.length==0){
			parent.newodre_r[parent.newodre_r.length]={store_id:$(g[i]).attr('data-id'),goods:[{good_id:$(g[i]).parent().find('.jian').attr('data-id'),option_id:op_id,count:$(g[i]).parent().find('input').val()}]};
		}else{
			var odc2=0;
			for (var od=0;od<parent.newodre_r.length;od++) {
				if(parent.newodre_r[od].store_id==$(g[i]).attr('data-id')){
					odc2=1;
					for (var odg=0;odg<parent.newodre_r[od].goods.length;odg++) {
						if(parent.newodre_r[od].goods[odg].good_id!=$(g[i]).parent().find('.jian').attr('data-id')){
							parent.newodre_r[od].goods[parent.newodre_r[od].goods.length]={good_id:$(g[i]).parent().find('.jian').attr('data-id'),option_id:op_id,count:$(g[i]).parent().find('input').val()};
							break;
						}
					}
				}
				
			}
			if(odc2!=1){
				parent.newodre_r[parent.newodre_r.length]={store_id:$(g[i]).attr('data-id'),goods:[{good_id:$(g[i]).parent().find('.jian').attr('data-id'),option_id:op_id,count:$(g[i]).parent().find('input').val()}]};
				
			}
		}
	}
	if(parent.nodre_r.length>0){
		var data=encodeURIComponent(JSON.stringify(parent.nodre_r));
		parent.onclicksetjia('/home/order/orderconfirm','data='+data);	
	}else{
		alert('你没有选择商品');
	}

//	新数据
//		console.log(parent.newodre_r);
	console.log(c.length);
//	老数据
//	console.log(parent.odre_r);

//	
//	parent.nodre_r
//	parent.newodre_r
}
function store(id){
	parent.onclicksetjia('<?php echo url("/home/stores/stores"); ?>','id='+id);
	
}
</script>