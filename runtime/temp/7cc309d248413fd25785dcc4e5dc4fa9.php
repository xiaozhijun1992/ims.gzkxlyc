<?php /*a:1:{s:76:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\order\orderconfirm.html";i:1586713774;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
    .buttons{
        width: 80%;
        height: 33px;
        background: #179B16;
        border: none;
        border-radius: 5%;
        color: #fff;
    }
    .button{
        width: 100%;
        display: block;
        text-align: center;
    }
    .padd{
        padding: 8px;
    }
    .pob{
        position: absolute;
        left: 0;
        bottom: 0;
    }
    #acce .text-gray .flex-sub view{
        font-size: 14px;
        padding: 5px 0;
    }
    #acce .text-gray .flex-sub{
        padding: 5px 0;
    }
    .order_pay .justify-between .cu-tag{
        margin-right: 8px;
    }
    #acce .text-gray input{
        width: 26px;
        height: 26px;
    }
    input[type=checkbox]{
        visibility: hidden;
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
                <span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">确认订单</span>
            </h3>
            <div class="head-option jshook-ws-head-option"></div>
        </div>
    </div>
    <div class="back">
        <div id="addressListone">

        </div>
        <div class="order_pay">

        </div>
        <div class="">
            <div class="padd margin-top-sm bg-white padding-bottom-sm">
                <view class="text-df padding-sm solid-bottom">
                    <view class="flex justify-between">
                        <view>商品金额</view>
                        <view class="text-price text-red sumAmount"></view>
                    </view>
                    <view class="flex justify-between">
                        <view>优惠券</view>
                        <view class="text-red">-
                            <text class="text-price ">0.00</text>
                        </view>
                    </view>
                </view>
                <view class="button w-100">
                    <button type="button" class="buttons margin text-lg " onclick="setbuttons()" type="primary" lang="zh_CN"  bindtap="__e">微信支付</button>
                </view>
            </div>
        </div>
</view>
</div>
<view class=" cu-modal bottom-modal" bindtap="__e">
    <view  class="pob cu-dialog" catchtap="__e">
        <view class="cu-bar bg-white">
            <view class="action text-green text-center" bindtap="__e">选择收货地址</view>
        </view>
        <scroll-view class="padding-xs" style="max-height:400rpx;" scroll-y="true">
            <radio-group id="acce"  class="w-100" bindchange="__e">

                <!--<view class="text-gray padding-sm flex align-center">
                  <view class="padding-right-sm">
                    <input type="radio" value="{{addresIndex}}" checked="{{addresIndex==currentAddressIndex}}"></input>
                  </view>
                  <view class="flex-sub text-left text-sm">
                    <view class="text-black">{{address.province+address.city+address.county+address.detail_address}}</view>
                    <view>{{address.name+" - "+address.phone}}</view>
                  </view>
                </view>-->
            </radio-group>
        </scroll-view>
    </view>
</body>
</html>
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
    gooddata('<?= $_GET["data"]?>');
    getAddress();
    window.onload=function(){
        parent.$('#loads').hide();
    }
    function onclickset(){
        parent.onclickset();
    }
    var datscc;
    var datac;
    var setobjk=1;
    function gooddata(data){
        var new_sum=0;
        $.post('/api/index/getGoodData',{data:data},function(res){
            if(res.data){

                var html;
                for (var i=0;i<res.data.length;i++) {
                    var hcnew='<div class="padd margin-top-sm bg-white"><view class="padding solid-bottom text-lg"><text class="cuIcon-shopfill text-red margin-right-sm"></text>'+res.data[i].name+'</view>';
                    for (var j=0;j<res.data[i].goodList.length;j++) {
                        var order_type='';
                        var order_good;
                        if(res.data[i].goodList[j].order_type==0){
                            res.data[i].goodList[j].order_type="线上发货";
                            order_type='bg-green';
                        }else{
                            res.data[i].goodList[j].order_type="线下核销";
                            order_type='bg-blue';
                        }
                        var title='无';
                        var marketprice=res.data[i].goodList[j].marketprice;
                        if(res.data[i].goodList[j].option){
                            title=res.data[i].goodList[j].option.title;
                            marketprice=res.data[i].goodList[j].option.marketprice;
                        }
                        new_sum=Math.floor(Number(new_sum*100)+Number(100*(marketprice)*res.data[i].goodList[j].count))/100;
                        order_good='<view class="w-100 flex padding-sm bg-white align-center solid-bottom solid-bottom"><view class="basis-xs"><image class="radius" style="width:68px;height:68px;" src="/'+res.data[i].goodList[j].getImage[0].img+'"></image></view><view class="flex-sub padding-left-sm flex justify-between flex-direction" style="height:160rpx;"><view class="text-cut-2">'+res.data[i].goodList[j].name+'</view><view><text class="text-gray">规格:</text>'+title+'</view><view class="flex justify-between align-center"><view class="flex"><view class="text-red text-price margin-right">'+marketprice+'</view><view> <text class="cuIcon-close"></text>'+res.data[i].goodList[j].count+res.data[i].goodList[j].unit+'</view></view><view class="cu-tag '+order_type+'" >'+res.data[i].goodList[j].order_type+'</view></view></view></view>';
                        hcnew+=order_good;
                    }
                    var hcnewc='</div>';
                    if(!html){
                        html=hcnew+hcnewc;
                    }else{
                        html+=hcnew+hcnewc;
                    }


                }
                $('.order_pay').append(html);
                $('.sumAmount').text(new_sum);
            }
        })
    }
    function getAddress(){

        $.get('/api/order/getAddress',{user_id:parent.user_id},function(res){
            if(res.data){
                datac=res.data;
                var one_array;
                var ardd;
                for (var i=0;i<res.data.length;i++) {
                    var arddc;
                    var backs='background: url(../../../../static/images/001.png) no-repeat;'
                    if(i==0){
                        backs='background: url(../../../../static/images/12345600.png) no-repeat;';
                        one_array='<view style="padding-left: 16px;" id="receiver_id" data-index="0" data-id="'+res.data[i].id+'" onclick="show_addr()" class="padding-sm bg-white flex justify-between align-center fixed" bindtap="__e"><view><view class="text-lg text-red margin-bottom-xs">'+res.data[i].name+' '+res.data[i].phone+'</view><view class="text-cut-2 text-justify">'+res.data[i].province+res.data[i].city+res.data[i].county+res.data[i].detail_address+'</view></view><view><text class="cuIcon-right text-gray text-lg margin-left" style="font-size:50rpx;"></text></view></view>';
                    }
                    arddc='<view class="objkc text-gray padding-sm flex align-center"><view class="padding-right-sm ccb" style="'+backs+'" onclick="objk(this)"><input  style="visibility: hidden;" type="radio" value="'+[i]+'" ></input></view><view class="flex-sub text-left text-sm"><view class="text-black" style="display: block;">'+res.data[i].province+res.data[i].city+res.data[i].county+res.data[i].detail_address+'</view><view>'+res.data[i].name+' '+res.data[i].phone+'</view></view></view>';
                    if(!ardd){
                        ardd=arddc;
                    }else{
                        ardd+=arddc;
                    }
                }
                $('#addressListone').append(one_array);
                $('#acce').append(ardd);
                $('.action').on('click',function(){

                    $('.cu-modal').removeClass('show');
                    if(setobjk==1){
                        return false;
                    }
                    var i=$('.receiver input').val();
                    var one_arrayc='<view style="padding-left: 16px;height: 30px;" id="receiver_id" data-index="0" data-id="'+datac[i].id+'" onclick="show_addr()" class="padding-sm bg-white flex justify-between align-center fixed" bindtap="__e"><view><view class="text-lg text-red margin-bottom-xs">'+datac[i].name+' '+datac[i].phone+'</view><view class="text-cut-2 text-justify">'+datac[i].province+datac[i].city+datac[i].county+datac[i].detail_address+'</view></view><view><text class="cuIcon-right text-gray text-lg margin-left" style="font-size:50rpx;"></text></view></view>';

                    $('#receiver_id').remove();
                    $('#addressListone').append(one_arrayc);
                })
            }else{
				var backs='background: url(../../../../static/images/12345600.png) no-repeat;';
					one_array='<view  style="padding-left: 16px;" id="receiver_id" data-index="0" onclick="new_address()" class="padding-sm bg-white flex justify-between align-center fixed" bindtap="__e"><view><view class="text-lg text-red margin-bottom-xs">请添加收货地址</view><view class="text-cut-2 text-justify"></view></view><view><text class="cuIcon-right text-gray text-lg margin-left" style="font-size:50rpx;"></text></view></view>';
				 $('#addressListone').append(one_array);
			}
        })
    }
	function new_address(){
		parent.onclicksetjia('/home/users/newaddress','id=');
	}
    function show_addr(){
        $('.cu-modal').addClass('show');
    }
    function objk(obj){
        setobjk=0;
        $('#acce').find('.ccb').attr('style','background: url(../../../../static/images/001.png) no-repeat;');
        $('#acce').find('.ccb').removeClass('receiver');
        obj.style='background: url(../../../../static/images/12345600.png) no-repeat;';
        obj.setAttribute('class','padding-right-sm ccb receiver');
    }
    function setbuttons(){
        var receiver_id=$('#receiver_id').attr('data-id');
		if(!receiver_id){
			alert('请选择收货地址');
			return false;
		}
        var user_id=parent.user_id;
        var datac='<?= $_GET["data"]?>';
        $.get('/api/order/createOrder2',{data:datac,receiver_id:receiver_id,user_id:user_id},function(res){
            if(res.data){
                datscc=res.data;
				callpay();
            }
        })
    }
    function jsApiCall()
	{
		parent.WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			datscc,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				if(res.err_msg == "get_brand_wcpay_request:ok" ){
					parent.onclicksetjia('home/order/orderlist',type=2);
				}else{ 
					parent.onclicksetjia('home/order/orderlist',type=1);
				}
			}
		);
		
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall(), false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall()); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall());
		    }
		}else{
		    jsApiCall();
		}
	}
	
    
</script>
