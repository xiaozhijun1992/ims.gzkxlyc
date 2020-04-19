<?php /*a:1:{s:67:"C:\xampp\htdocs\ims.gzkxlyc\application\store\view\index\login.html";i:1568167091;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>快享立赢商城</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <script type="text/javascript" src="//res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
    <link rel="stylesheet" type="text/css" href="/static/css/font.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/xadmin.css" />
    <script type="text/javascript" src="/static/lib/layui/layui.js"></script>
    <style>
        .layui-form-label {
            width: 40px;
        }
        .layui-input-block {
            margin-left: 70px;
            margin-right: 20px;
        }
        .layui-input {
            height: 38px !important;
        }
        .layui-form {
            margin-top: 20px;
        }
        .captcha img {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="layui-header" style="background-color: #24262F;font-size: 14px;">
    <div class="layui-container">
        <a style="position: absolute;left: 0;top: 5px;">
            <img src="/static/images/banner.png" alt="layui"><span style="color: white"> | 商家登录</span>
        </a>
        <ul class="layui-nav" style="position:absolute;top: 0;right: 0;background: none;">
            <li class="layui-nav-item"><a href="http://www.gztcgy.com" target="_blank">同城共赢官网</a></li>
            <li class="layui-nav-item"><a href="https://ims.gzkxly.com/admin/index/login">总部运营中心登录</a></li>
            <li class="layui-nav-item"><a href="https://ims.gzkxly.com/area/index/login">区域代理登录</a></li>
            <li class="layui-nav-item  layui-this"><a href="https://ims.gzkxly.com/store/index/login">商家登录</a></li>
        </ul>
    </div>
</div>



<div class="layui-carousel" id="carousel">
    <div carousel-item>
        <img src="/static/images/login/1.jpg" />
        <img src="/static/images/login/1.jpg" />
    </div>
</div>

<div style="position: absolute;background-color: rgba(255,255,255,.8);height: 400px; overflow: hidden;top: 130px;right: 100px; width: 320px;">
    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li class="layui-this">微信扫码登录</li>
            <li>短信验证码登录</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <p style="text-align: center; font-size: 18px; margin-top: 10px">微信扫码登录</p>
                <div id="qrcode"></div>
            </div>
            <div class="layui-tab-item">
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">手机号</label>
                        <div class="layui-input-block">
                            <input type="text" name="phone" lay-verify="required|phone" lay-reqtext="手机号不能为空？" placeholder="请输入手机号" class="layui-input" id="phone">
                            <input type="hidden" id="no" name="no" />
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">验证码</label>
                        <div class="layui-input-block">
                            <input type="text" name="captchaCode" lay-verify="required" lay-reqtext="验证码不能为空？" placeholder="请输入下面的验证码" autocomplete="off" class="layui-input" id="captchaCode">
                            <div class="captcha">
                                <img src="<?php echo url('index/index/verify'); ?>" alt="验证码加载中" id="captchaImg"/>
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">动态码</label>
                        <div class="layui-input-block">
                            <input type="text" name="phoneCode" id="phoneCode" lay-verify="required" lay-reqtext="短信动态码不能为空？" placeholder="手机动态码" autocomplete="off" class="layui-input" style="width: 45%;display: inline-block; margin-right: 10px;">
                            <button type="button" class="layui-btn layui-btn-radius" style="width: 99px" id="getCode">获取验证码</button>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 50px;">
                        <button type="button" class="layui-btn layui-btn-danger" lay-submit="" lay-filter="login">立即登录</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="layui-footer" style="margin-top: 80px; padding: 30px 0; color: #888888; text-align: center">
    <p>Copyright 2014-2019 gzkxly.com 版权所有</p>
</div>

<script>
    layui.use(['carousel','element','form'], function(){
        let carousel = layui.carousel;
        let element = layui.element;
        let form = layui.form;
        let $ = layui.$;
        //建造实例
        carousel.render({
            elem: '#carousel'
            ,width: '100%' //设置容器宽度
            ,arrow: 'always' //始终显示箭头
            ,interval: 6000
            ,height: '500px'
        });
        $("#captchaImg").on('click',function() {
            this.src = "<?php echo url('index/index/verify'); ?>?"+Math.random();
        });

        $("#getCode").on('click',function(){
            // 获取手机号和验证码
            let phone = $("#phone").val();
            let captchaCode = $("#captchaCode").val();
            $.ajax({
                type: 'POST',
                data: {phone,captchaCode},
                url: "<?php echo url('index/index/getCode'); ?>",
                success: function(res){
                    console.log(res);
                    if(res.code === 0) {
                        $("#no").val(res.data);
                        layer.msg("手机验证码发送成功");
                        $("#getCode").addClass("layui-btn-disabled"); // 禁用获取验证码按钮
                        let time = 60; // 设置重新获取短信验证码的时间
                        let inst = setInterval(function(){
                            --time;
                            if(time === 0){
                                clearInterval(inst);
                                $("#getCode").text("获取验证码");
                                $("#getCode").removeClass("layui-btn-disabled");
                            }else {
                                $("#getCode").text(time + "s后重新获取")
                            }
                        },1000)

                    }else {
                        layer.alert(res.message);
                    }
                }
            });
        });
        form.on('submit(login)', function(data){
            console.log(data.field);
            $.ajax({
                type: 'POST',
                data: data.field,
                url: "<?php echo url('store/index/login'); ?>",
                success: function(res){
                    console.log(res);
                    if(res.code === 0) {
                        layer.alert(res.message, {icon: 6}, function () {
                            location.replace(res.data);
                        });
                    }else {
                        layer.alert(res.message);
                    }
                }
            });
            return false;
        });
    });
    new WxLogin({
        self_redirect:false,
        id:"qrcode",
        appid: "<?php echo htmlentities(app('config')->get('wxweb.appid')); ?>",
        scope: "snsapi_login",
        redirect_uri: "https://ims.gzkxly.com/store/index/login",
        state: "<?php echo htmlentities($state); ?>",
        style: "black",
        href: "https://ims.gzkxly.com/static/css/wxlogin.css"
    });
</script>
</body>
</html>