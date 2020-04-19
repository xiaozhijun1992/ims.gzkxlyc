<?php /*a:1:{s:69:"C:\xampp\htdocs\ims.gzkxly.com\application\index\view\index\join.html";i:1561283326;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>快享立赢商城-商家入驻</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <script type="text/javascript" src="/static/lib/layui/layui.js"></script>
    <link rel="stylesheet" type="text/css" href="/static/lib/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/static/css/indexMain.css">
    <!--加载meta IE兼容文件-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<!-- header -->
<div class="header_box">
    <div class="header">
        <ul class="app-header">
            <li class="app-header-menuicon">
                <i class="layui-icon layui-icon-more-vertical"></i>
            </li>
        </ul>
        <h1 class="logo">
            <a href="#"><img src="/static/images/banner.png" /></a>
        </h1>
        <div class="nav"  style="visibility: visible">
            <a href="http://pc.gzkxly.com/" target="_top">首页</a>
            <a href="<?php echo url('index/index/join'); ?>" class="active">商家入驻</a>
            <a href="<?php echo url('store/index/index'); ?>" target="_blank">商家登录</a>
        </div>
        <ul class="layui-nav header-down-nav">
            <li class="layui-nav-item"><a href="/">首页</a></li>
            <li class="layui-nav-item"><a href="<?php echo url('index/index/join'); ?>" class="active">商家入驻</a></li>
            <li class="layui-nav-item"><a href="<?php echo url('index/index/service'); ?>">服务</a></li>
            <li class="layui-nav-item"><a href="<?php echo url('index/index/about'); ?>">关于</a></li>
            <li class="layui-nav-item"><a href="<?php echo url('store/index/index'); ?>" target="_blank">商家登录</a></li>
        </ul>
    </div>
</div>
<!-- end-header -->


<!-- content -->
<div class="content">
    <div class="layui-container">
        <div class="form">
            <h1 class="layui-field-title">商家入驻</h1>
            <form class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">联系人</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" autocomplete="off" class="layui-input" id="name">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">联系电话</label>
                    <div class="layui-input-inline">
                        <input type="tel" name="phone" autocomplete="off" class="layui-input" id="phone">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商家名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" autocomplete="off" class="layui-input" id="title">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">联系地址</label>
                    <div class="layui-input-inline">
                        <input type="text" name="address" autocomplete="off" class="layui-input" id="address">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-inline">
                        <i class="layui-btn" lay-filter="add" lay-submit="">提交</i>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end-content -->
<!-- footer -->
<div class="footer">
    <div class="line"></div>
    <p class="copyright">
        Copyright 2014-2019  gzkxly.com  版权所有<br />
        地址:贵州省六盘水市钟山区中路金泰大厦1802室<br />
        TEL:0858-8699077<br />
        <a href="http://beian.miit.gov.cn" style="color: white;" target="_blank">黔ICP备15016690号-6</a>
    </p>
</div>
<!-- end-footer -->
<script>
    layui.use(['layer','form'],function(){
        let $ = layui.$,form = layui.form;
        let appHeaderMenuion = '.app-header-menuicon';
        let headerDownNav = '.header-down-nav';
        $(appHeaderMenuion).on('click',function(){
            $(headerDownNav).toggleClass('down-nav')
        });
        form.on('submit(add)',function(data){
            $.ajax({
                type: 'POST',
                data: data.field,
                url: "<?php echo url('index/index/join'); ?>",
                success: function(res){
                    if(res.code === 0) {
                        layer.alert(res.message, {icon: 6}, function (index) {
                            layer.close(index);
                            $("#name").val("");
                            $("#phone").val("");
                            $("#title").val("");
                            $("#address").val("");
                        });
                    }else {
                        layer.alert(res.message);
                    }
                },
                fail: function(){
                    layer.alert("网络连接失败");
                }
            });
            return false;
        });
    });
</script>
</body>
</html>