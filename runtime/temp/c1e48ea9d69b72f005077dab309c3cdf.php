<?php /*a:1:{s:70:"C:\xampp\htdocs\ims.gzkxly.com\application\admin\view\index\login.html";i:1562661454;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>快享立赢商城运营中心</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <script type="text/javascript" src="//res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
    <link rel="stylesheet" type="text/css" href="/static/css/font.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/xadmin.css" />
    <script type="text/javascript" src="/static/lib/layui/layui.js"></script>
</head>
<body>
<div class="layui-header" style="background-color: #24262F;font-size: 14px;">
    <div class="layui-container">
        <a style="position: absolute;left: 0;top: 5px;">
            <img src="/static/images/banner.png" alt="layui"><span style="color: white"> | 总部运营中心</span>
        </a>
        <ul class="layui-nav" style="position:absolute;top: 0;right: 0;background: none;">
            <li class="layui-nav-item"><a href="http://www.gztcgy.com" target="_blank">同城共赢官网</a></li>
            <li class="layui-nav-item layui-this"><a href="https://ims.gzkxly.com/admin/index/login">总部运营中心登录</a></li>
            <li class="layui-nav-item"><a href="https://ims.gzkxly.com/area/index/login">区域代理登录</a></li>
            <li class="layui-nav-item"><a href="https://ims.gzkxly.com/store/index/login">商家登录</a></li>
        </ul>
    </div>
</div>



<div class="layui-carousel" id="carousel">
    <div carousel-item>
        <img src="/static/images/login/1.jpg" />
        <img src="/static/images/login/1.jpg" />
    </div>
</div>
<div style="position: absolute;background-color: rgba(0,0,0,.4);height: 360px; overflow: hidden;top: 130px;right: 100px">
    <p style="text-align: center; font-size: 18px; color: white; margin-top: 10px">微信扫码登录</p>
    <div id="qrcode"></div>
</div>

<div class="layui-footer" style="margin-top: 80px; padding: 30px 0; color: #888888; text-align: center">
    <p>Copyright 2014-2019 gzkxly.com 版权所有</p>
</div>

<script>
    layui.use('carousel', function(){
        let carousel = layui.carousel;
        //建造实例
        carousel.render({
            elem: '#carousel'
            ,width: '100%' //设置容器宽度
            ,arrow: 'always' //始终显示箭头
            ,interval: 6000
            ,height: '500px'
        });
    });
    new WxLogin({
        self_redirect:false,
        id:"qrcode",
        appid: "<?php echo htmlentities(app('config')->get('wxweb.appid')); ?>",
        scope: "snsapi_login",
        redirect_uri: "https://ims.gzkxly.com/admin/index/login",
        state: "<?php echo htmlentities($state); ?>",
        style: "white",
        href: "https://ims.gzkxly.com/static/css/wxlogin.css"
    });
</script>
</body>
</html>
