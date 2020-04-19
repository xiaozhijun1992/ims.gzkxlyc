<?php /*a:2:{s:77:"C:\xampp\htdocs\ims.gzkxly.com\application\store\view\index\select_store.html";i:1562661711;s:65:"C:\xampp\htdocs\ims.gzkxly.com\application\store\view\window.html";i:1557288055;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>快享立赢商家后台</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8"/>
    <link rel="stylesheet" type="text/css" href="/static/css/font.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/xadmin.css" />
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/static/lib/layui/layui.js"></script>
    <script type="text/javascript" src="/static/js/xadmin.js"></script>
    <script type="text/javascript" src="/static/js/cookie.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="x-body">
    
<div style="width: 50%; margin: 0 auto;">
    <div style="text-align: center;font-size: 20px">请选择要登录的店铺</div>
    <table class="layui-table">
        <thead>
            <tr>
                <th>店铺ID</th>
                <th>店铺名称</th>
                <th>加入时间</th>
            </tr>
        </thead>
        <tbody>
        <?php if(is_array(app('session')->get('stores')) || app('session')->get('stores') instanceof \think\Collection || app('session')->get('stores') instanceof \think\Paginator): $i = 0; $__LIST__ = app('session')->get('stores');if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$store): $mod = ($i % 2 );++$i;?>
            <tr onclick="select(<?php echo htmlentities($store['id']); ?>)">
                <td><?php echo htmlentities($store['id']); ?></td>
                <td><?php echo htmlentities($store['name']); ?></td>
                <td><?php echo htmlentities($store['create_time']); ?></td>
            </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
</div>

<script>
    function select(id){
        window.location.href = "<?php echo url('store/index/selectStore'); ?>?store_id="+id;
    }
</script>


</div>
</body>
</html>