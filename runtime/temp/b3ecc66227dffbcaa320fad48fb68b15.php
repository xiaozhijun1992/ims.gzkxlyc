<?php /*a:1:{s:68:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\good\share.html";i:1584879337;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" /> <link rel="stylesheet" type="text/css" href="/static/css/app.css" />
		<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
	</head>

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
    			<span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">商品详情</span>
  		</h3>
					<div class="head-option jshook-ws-head-option"></div>
				</div>
			</div>
			<canvas id="canvas" width="256" height="410" style="display:none;border: 1px solid #000000;"></canvas>
			<img id="avatar" style="background: #fff;margin-left: 30px;margin-top: 60px;box-shadow: 0 0 0 1px #ddd;display: block;padding-bottom: 20px;" src="" />
			<view onclick="savePic()" style="margin-top: 50px;margin-bottom: 20px;margin: 0 auto;display: block;text-align: center;width: 30%;" class="round bg-red shadow padding-lr padding-tb-sm" bindtap="__e">保存到相册</view>
		
	</body>

</html>
<script type="text/javascript">
var good_id='<?= $_GET["id"];?>';
var img='<?= $_GET["img"];?>';
var strc='<?= $_GET["name"];?>';
var money='<?= $_GET["money"];?>';
getGoodShareImage(5947,good_id);
window.onload=function(){
		parent.$('#loads').hide();
	}
function getGoodShareImage(user_id,good_id){
	$.get('/api/index/getGoodShareImageweb',{user_id,good_id},function(res){
		if(res.data){
//			var myImage1='http://xzjhds.com/static/images/banner.png';
			canvasset('/'+img,'/'+res.data,strc,money);
		}else{
			return false;
		}
	})
}



//https://ims.gzkxly.com/api/index/getGoodShareImage?user_id=5947&good_id=42
	function  canvasset(myImage2,myImage3,strc,money){
		var c = document.getElementById("canvas");
		var ctx = c.getContext("2d");
		ctx.font = "14px Georgia";
		ctx.fillText("丨自购省钱，分享赚钱", 110, 30);
		var myImage1 = new Image();
		myImage1.src = "/static/images/banner.png"; //背景图片  你自己本地的图片或者在线图片
		myImage1.crossOrigin = 'Anonymous';
		myImage1.onload =function (){
		ctx.drawImage(myImage1, 10, 0, 100, 40);
	
		var myImage = new Image();
		myImage.src = myImage2; //背景图片  你自己本地的图片或者在线图片
		myImage.crossOrigin = 'Anonymous';
		myImage.onload =function (){
		ctx.drawImage(myImage, 0, canvas.height - (canvas.height * 0.8 + 40), canvas.width, canvas.height * 0.6);
	
		var str = strc;
		var myImage2 = new Image();
		myImage2.src = myImage3; //你自己本地的图片或者在线图片
		myImage2.crossOrigin = 'Anonymous';
		myImage2.onload = function() {
			ctx.drawImage(myImage2, canvas.width - 70, canvas.height - 115, 70, 70);
			if (str.trim().replace(/[^\x00-\xff]/g, "aa").length > 30) {
				var str_length = 15;
				var accct = 10;
				for (var i = 0; i < Math.ceil((str.trim().replace(/[^\x00-\xff]/g, "aa").length) / 30); i++) {
					strsub = str.substring(str_length - 15, str_length);
					ctx.setTextBaseline = "middle";
					ctx.setFillStyle = 'black';
					ctx.font = "11px normal";
					ctx.strokeStyle = '#aaa';
					ctx.fillText(strsub, 10, canvas.height - 110 + accct);
					accct += 20;
					str_length += 15;
				}
			} else {
				ctx.fillText(str, 10, canvas.height - 110);
			}
			ctx.fillStyle = '#e54d42';
			ctx.font = "normal 16px 微软雅黑";
			ctx.fillText('￥'+money, 10, canvas.height - 50);
			ctx.font = "normal 11px 微软雅黑";
			ctx.fillStyle = 'black';
			ctx.fillText('长按二维码进入商城', 80, canvas.height - 10);
			var base64 = c.toDataURL("image/png"); //"image/png" 这里注意一下
			//var img = document.getElementById('avatar');
			document.getElementById('avatar').src = base64;
			
		}
		}
		}
	}
function savePic(){         
    var picurl= $("#avatar").attr("src");
    //alert(picurl);
    savePicture(picurl);
}
function savePicture(Url){
    var blob=new Blob([''], {type:'application/octet-stream'});
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = Url;
    a.download = Url.replace(/(.*\/)*([^.]+.*)/ig,"$2").split("?")[0];
    var e = document.createEvent('MouseEvents');
    e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
    a.dispatchEvent(e);
    URL.revokeObjectURL(url);
}
function onclickset(){
	parent.onclickset();
}
</script>