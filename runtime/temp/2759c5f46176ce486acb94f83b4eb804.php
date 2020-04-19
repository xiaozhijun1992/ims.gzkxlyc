<?php /*a:1:{s:65:"C:\xampp\htdocs\ims.gzkxly.com\application\common/view/error.html";i:1562662171;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>快享立赢商城</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />

    <link rel="stylesheet" type="text/css" href="/static/css/font.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/xadmin.css" />
</head>
<body>
<div class="layui-container">
    <div class="fly-panel">
        <div class="fly-none">
            <h2><i class="layui-icon layui-icon-404"></i></h2>
            <p><?php echo(strip_tags($msg));?></p>
            <p class="jump">
                页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function(){
        let wait = document.getElementById('wait'),
            href = document.getElementById('href').href;
        let interval = setInterval(function(){
            let time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            }
        }, 1000);
    })();
</script>
</body>
</html>