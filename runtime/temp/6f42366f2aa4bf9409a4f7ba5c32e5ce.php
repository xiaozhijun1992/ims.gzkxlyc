<?php /*a:1:{s:74:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\good\allcomments.html";i:1584879313;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" /> <link rel="stylesheet" type="text/css" href="/static/css/app.css" /> <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> <script type="text/javascript" src="/static/lib/layui/layui.js"></script> <link rel="stylesheet" type="text/css" href="/static/lib/layui/css/layui.css" /> <script type="text/javascript" src="/static/js/render.js"></script>
		<style type="text/css">
			.imgsc{
				background-size: 100% 100%;
			    background-repeat: no-repeat;
			    border-radius: 100%;
			    text-align: center;
			    margin: 0 auto;
			    width: 29px;
			    height: 29px;
			}
		</style>
	</head>
			
	<body style="overflow-y: scroll;" id="scrollable">
	
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
    				<span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;">商品评价</span>
  				</h3>
			<div class="head-option jshook-ws-head-option"></div>
		</div>
	</div>
	<view>
		<div id="storeshtml" style="display: block;background: #fff;margin-top: 42px;">
			<block >
				<view class="text-gray text-center padding-tb">暂无评价</view>
			</block>
		</div>
	</body>
		<script id="stores1" type="text/x-jsrender">
			<div style="display: block;">
					<block>
				<view class="uni-comment bg-white">
					<block>
						<view class="uni-comment-list padding-sm">
							<view class="uni-comment-face">
								<image class="imgsc" src="{{>user.avator}}" mode="widthFix"></image>
							</view>
							<view class="uni-comment-body">
								<view class="uni-comment-top">
									<text>{{>user.nick_name}}</text>
								</view>
								<view class="uni-comment-date text-gray">
									<text>{{>create_time}}</text>
								</view>
								<view class="uni-comment-content text-justify text-cut-3">{{>comment}}</view>
								<view class="grid col-4 grid-square">
									<block id="img{{>id}}">
										
									</block>
								</view>
							</view>
						</view>
					</block>
				</view>
			</block>
			</div>
			
			
	</script>

</html>
<script type="text/javascript">
	id="<?= $_GET['id'];?>";
	
	var page=1;
	var limit=20;
	window.onload=function(){
		parent.$('#loads').hide();
	}
	getComments(page,limit,id);
	function getComments(page,limit,good_id){
	$.post("/api/good/getComments",{good_id,limit,page},function(res) {
			if (res.data) {

				
				
				
				
				var template = $.templates("#stores1");
				var htmlOutput = template.render(res.data)
				$("#storeshtml").html(htmlOutput);
				for (var j=0;j<(res.data).length;j++) {
					var htmls='';
						if((res.data[j].imgs).length>0){
							for (var i=0;i<(res.data[j].imgs).length;i++) {
								setimgs='<img style="width: 25%;" src="/'+res.data[j].imgs[i]+'"/>'
								$('#img'+res.data[j].id).append(setimgs);
						}
					}	
				}
				page++;
			}
		})
	}
//	console.log($('#scrollable').height())
//	console.log($('#storeshtml').height());
//	$('body').on('scroll',function(){
//		alert(1);
//		
//		console.log($('#scrollable').height())
////		if (90+ $('#storeshtml').scrollTop() > $('#scrollable').height()) {
////	    //dosomething
////		
//////	    page++;
////	    getIndexGood(page,limit,category_id,id);
////	    return false;
////	  }
//	})
	function onclickset(){
		parent.onclickset();
	}
</script>