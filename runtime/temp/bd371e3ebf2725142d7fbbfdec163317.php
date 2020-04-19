<?php /*a:1:{s:74:"C:\xampp\htdocs\ims.gzkxly.com\application\home\view\users\newaddress.html";i:1584969482;}*/ ?>
<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, height=device-height,user-scalable=no, initial-scale=1.0" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="/static/css/weweb.min.css" /> <link rel="stylesheet" type="text/css" href="/static/css/app.css" /> <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> <script type="text/javascript" src="/static/lib/layui/layui.js"></script> <link rel="stylesheet" type="text/css" href="/static/lib/layui/css/layui.css" /> <script type="text/javascript" src="/static/js/render.js"></script>
		<script type="text/javascript" src="/static/city/city.js"></script>

		<script type="text/javascript" src="/static/city/picker.min.js"></script>
</head>
	<style type="text/css">
		form input{
			cursor: auto;
		    height: 1.4rem;
		    text-overflow: clip;
		    overflow: hidden;
		    white-space: nowrap;
		    font-family: UICTFontTextStyleBody;
		    min-height: 1.4rem;
		    border: none;
		}
		.cu-btns{
			position: relative;
		    border: 0.5px;
		    display: -webkit-inline-box;
		    display: -webkit-inline-flex;
		    display: -ms-inline-flexbox;
		    display: inline-flex;
		    -webkit-box-align: center;
		    -webkit-align-items: center;
		    -ms-flex-align: center;
		    align-items: center;
		    -webkit-box-pack: center;
		    -webkit-justify-content: center;
		    -ms-flex-pack: center;
		    justify-content: center;
		    -webkit-box-sizing: border-box;
		    box-sizing: border-box;
		    padding: 0 12px;
		    font-size: 11px;
		    height: 27px;
		    line-height: 1;
		    text-align: center;
		    text-decoration: none;
		    overflow: visible;
		    margin-left: initial;
		    -webkit-transform: translate(0.5px,0.5px);
		    -ms-transform: translate(0.5px,0.5px);
		    transform: translate(0.5px,0.5px);
		    margin-right: initial;
		}
		.arrow{
		  	width: 8px;
		  	height: 8px;
		  	border-top: 2px solid #999;
		  	border-right: 2px solid #999;
		  	right: 20px;
		  	transform: rotate(45deg);
		   	top:20px;
		}
		.cc{
			display: inline-flex;
		}
	</style>
	<body>
	
		<body>
			<div class="scrollable">
				<div id="storeshtml">

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
    <span style="color: #fff;font-size: 16px;width: 100%;font-weight: 100;"><?= $_GET['id']?'修改收货地址':'新增收货地址'?></span>
  </h3>
								<div class="head-option jshook-ws-head-option"></div>
							</div>
						</div>
					</view>
		<div class="">
			<form style="background: #fff;">
    <view class="cu-form-group cc">
      <view class="title" style="width: 48px;">收 件 人</view>
      <input placeholder="收件人姓名" name="input" id="name" value="" bindinput="__e" />
    </view>
    <view class="cu-form-group cc">
      <view class="title">手机号码</view>
      <input placeholder="收件人手机号码" name="input" id="phone" type=""  value="" bindinput="__e" />
    </view>
    <view class="cu-form-group " id="picker5">
      <view class="title" style="width: 48px;">省 市 县</view>
      <view class="pickers" style="position: absolute;left: 72px;"></view><view class="arrow"></view>
    </view>
    <view class="cu-form-group cc">
      <view class="title">详细地址</view>
      <input placeholder="详细地址" name="input" id="detail_address" value="" bindinput="__e" />
    </view>
  </form>
  <view class="cu-tabbar-height"></view>
	  <view  class="bg-red text-center cu-bar foot" bindtap="__e">
	    <view class="w-100 text-center ccbs">保存</view>
	  </view>
		</div>
		
	</body>
	
</html>
<script type="text/javascript">
		
	var id="<?=$_GET['id'];?>"
	window.onload=function(){
		parent.$('#loads').hide();
	}
	function onclickset(){
		parent.onclickset();
	}
	var province;
	var citys;
	var county;
var nameEl = document.getElementById('picker5');

var first = []; /* 省，直辖市 */
var second = []; /* 市 */
var third = []; /* 镇 */

var selectedIndex = [23, 1, 0]; /* 默认选中的地区 */
//默认选中city.js 中的地区
var checked = [0, 0, 0]; /* 已选选项 */

function creatList(obj, list){
  obj.forEach(function(item, index, arr){
  var temp = new Object();
  temp.text = item.name;
  temp.value = index;
  list.push(temp);
  })
}

creatList(city, first);

if (city[selectedIndex[0]].hasOwnProperty('sub')) {
  creatList(city[selectedIndex[0]].sub, second);
} else {
  second = [{text: '', value: 0}];
}

if (city[selectedIndex[0]].sub[selectedIndex[1]].hasOwnProperty('sub')) {
  creatList(city[selectedIndex[0]].sub[selectedIndex[1]].sub, third);
} else {
  third = [{text: '', value: 0}];
}

var picker = new Picker({
	data: [first, second, third],
  selectedIndex: selectedIndex,
	title: '地址选择'
});

picker.on('picker.select', function (selectedVal, selectedIndex) {
  province = first[selectedIndex[0]].text;
  citys = second[selectedIndex[1]].text;
  county = third[selectedIndex[2]] ? third[selectedIndex[2]].text : '';

	$('.pickers').text(province + ' ' + citys + ' ' + county);
});

picker.on('picker.change', function (index, selectedIndex) {
  if (index === 0){
    firstChange();
  } else if (index === 1) {
    secondChange();
  }

  function firstChange() {
    second = [];
    third = [];
    checked[0] = selectedIndex;
    var firstCity = city[selectedIndex];
    if (firstCity.hasOwnProperty('sub')) {
      creatList(firstCity.sub, second);

      var secondCity = city[selectedIndex].sub[0]
      if (secondCity.hasOwnProperty('sub')) {
        creatList(secondCity.sub, third);
      } else {
        third = [{text: '', value: 0}];
        checked[2] = 0;
      }
    } else {
      second = [{text: '', value: 0}];
      third = [{text: '', value: 0}];
      checked[1] = 0;
      checked[2] = 0;
    }

    picker.refillColumn(1, second);
    picker.refillColumn(2, third);
    picker.scrollColumn(1, 0)
    picker.scrollColumn(2, 0)
  }

  function secondChange() {
    third = [];
    checked[1] = selectedIndex;
    var first_index = checked[0];
    if (city[first_index].sub[selectedIndex].hasOwnProperty('sub')) {
      var secondCity = city[first_index].sub[selectedIndex];
      creatList(secondCity.sub, third);
      picker.refillColumn(2, third);
      picker.scrollColumn(2, 0)
    } else {
      third = [{text: '', value: 0}];
      checked[2] = 0;
      picker.refillColumn(2, third);
      picker.scrollColumn(2, 0)
    }
  }

});

picker.on('picker.valuechange', function (selectedVal, selectedIndex) {
  console.log(selectedVal);
  console.log(selectedIndex);
});

nameEl.addEventListener('click', function () {
	picker.show();
});
$('.ccbs').on('click',function(){
	var detail_address=$('#detail_address').val();
	var name=$('#name').val();
	var phone=$('#phone').val();
	parent.arr_id[1]=parent.getChildren().length;

	if(!detail_address){
		alert('详细地址不能为空');
		return false;
	}
	if(!name){
		alert('联系人不能为空');
		return false;
	}
	if(!phone){
		alert('联系电话不能为空');
		return false;
	}
	var data={
		user_id:parent.user_id,
		detail_address:detail_address,
		name:name,
		phone:phone,
		province:province,city:citys,county:county}
		$.post('/api/receive_address/add',data,function(res){
			if(res.data){
				alert(res.message);
//				回退到指定页面,在前进一个页面
				parent.onclicksets('/home/users/address',parent.arr_id);	
			}else{
				alert(res.message);
			}
		})
//	if(id){
//		
//	}else{
//		
//	}
})
</script>