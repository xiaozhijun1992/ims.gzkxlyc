<?php /*a:2:{s:72:"C:\xampp\htdocs\ims.gzkxly.com\application\admin\view\index\welcome.html";i:1557312738;s:63:"C:\xampp\htdocs\ims.gzkxly.com\application\admin\view\base.html";i:1558879011;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8"/>
    <link rel="stylesheet" type="text/css" href="/static/css/font.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/xadmin.css" />
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/static/lib/layui/layui.js"></script>
    <script type="text/javascript" src="/static/js/xadmin.js"></script>
    <script type="text/javascript" src="/static/js/cookie.js"></script>
    <link rel="stylesheet" type="text/css" href="/static/lib/plugin/table-filter/tableFilter.css" />
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="x-nav">
    
    <span class="layui-breadcrumb">
        <?php if(is_array($nav_list) || $nav_list instanceof \think\Collection || $nav_list instanceof \think\Paginator): $i = 0; $__LIST__ = $nav_list;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?>
        <a><?php echo htmlentities((isset($nav) && ($nav !== '')?$nav:"首页")); ?></a>
        <?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">&#xe669;</i>
    </a>
    
</div>
<div class="x-body">
    
<blockquote class="layui-elem-quote">
    欢迎您，<span class="x-red"><?php echo htmlentities(app('session')->get('admin_info.name')); ?></span>
</blockquote>
<fieldset class="layui-elem-field">
    <legend>数据统计</legend>
    <div class="layui-field-box">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                        <div carousel-item="">
                            <ul class="layui-row layui-col-space10 layui-this">
                                <li class="layui-col-xs2">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>商户数</h3>
                                        <p>
                                            <cite><?php echo htmlentities($store_count); ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs2">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>会员数</h3>
                                        <p>
                                            <cite><?php echo htmlentities($user_count); ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs2">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>订单数</h3>
                                        <p>
                                            <cite><?php echo htmlentities($order_count); ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs2">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>商品分类</h3>
                                        <p>
                                            <cite><?php echo htmlentities($good_category); ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs2">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>推广人员</h3>
                                        <p>
                                            <cite><?php echo htmlentities($salesman_count); ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs2">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>行业数</h3>
                                        <p>
                                            <cite><?php echo htmlentities($industry_count); ?></cite></p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset class="layui-elem-field">
    <legend>开发团队</legend>
    <div class="layui-field-box">
        <table class="layui-table">
            <tbody>
            <tr>
                <th>版权所有</th>
                <td>贵州同城共赢网络科技有限公司</td>
            </tr>
            <tr>
                <th>开发者</th>
                <td>Peter，Jon</td>
            </tr>
            </tbody>
        </table>
    </div>
</fieldset>
</div>


</div>
</body>
</html>