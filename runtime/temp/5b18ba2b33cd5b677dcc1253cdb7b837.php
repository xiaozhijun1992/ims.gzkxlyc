<?php /*a:2:{s:72:"C:\xampp\htdocs\ims.gzkxly.com\application\store\view\index\welcome.html";i:1568165891;s:63:"C:\xampp\htdocs\ims.gzkxly.com\application\store\view\base.html";i:1554859149;}*/ ?>
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
    <?php echo htmlentities(app('session')->get('store_info.name')); ?>,欢迎您！
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
                                <li class="layui-col-xs3">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>商品数量</h3>
                                        <p>
                                            <cite><?php echo htmlentities($good_count); ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>订单总数</h3>
                                        <p>
                                            <cite><?php echo htmlentities($order_count); ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>今日订单</h3>
                                        <p>
                                            <cite><?php echo htmlentities($today_order_count); ?></cite></p>
                                    </a>
                                </li>
                                <li class="layui-col-xs3">
                                    <a href="javascript:;" class="x-admin-backlog-body">
                                        <h3>今日销售</h3>
                                        <p>
                                            <cite><?php echo htmlentities($today_order_amount); ?></cite></p>
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
<div class="layui-fluid">
    <div class="layui-row">
        <div class="layui-col-md6">
            <!-- 订单走势图 -->
            <div id="order" style="width: 100%;height:400px;"></div>
        </div>
        <div class="layui-col-md6">
            <!-- 交易金额走势图 -->
            <div id="amount" style="width: 100%;height:400px;"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/static/js/echarts.min.js"></script>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    let order = echarts.init(document.getElementById('order'));
    let amount = echarts.init(document.getElementById('amount'));

    // 指定图表的配置项和数据
    let orderData = {
        title: {
            text: '订单量统计'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['订单量']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: [
                <?php foreach($tradeData as $key=>$vo): ?>
                    '<?php echo htmlentities($vo['date']); ?>',
                <?php endforeach; ?>
            ]
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name:'订单量',
                type:'line',
                stack: '总量',
                data:[
                    <?php foreach($tradeData as $key=>$vo): ?>
                        '<?php echo htmlentities($vo['count']); ?>',
                    <?php endforeach; ?>
                ]
            }
        ]
    };

    let amountData = {
        title: {
            text: '交易金额统计'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data:['交易金额']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: [
                <?php foreach($tradeData as $key=>$vo): ?>
                    '<?php echo htmlentities($vo['date']); ?>',
                <?php endforeach; ?>
            ]
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name:'交易金额',
                type:'line',
                stack: '总量',
                data:[
                    <?php foreach($tradeData as $key=>$vo): ?>
                        '<?php echo htmlentities($vo['amount']); ?>',
                    <?php endforeach; ?>
                ]
            }
        ]
    };


    // 使用刚指定的配置项和数据显示图表。
    order.setOption(orderData);
    amount.setOption(amountData);
</script>

</div>
</body>
</html>