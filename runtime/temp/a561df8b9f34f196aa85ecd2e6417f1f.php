<?php /*a:2:{s:75:"C:\xampp\htdocs\ims.gzkxly.com\application\admin\view\good_order\index.html";i:1568160865;s:63:"C:\xampp\htdocs\ims.gzkxly.com\application\admin\view\base.html";i:1558879011;}*/ ?>
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
    
<table id="goodOrderTable" lay-filter="goodOrderTable"></table>
<!-- 操作栏 -->
<script type="text/html" id="barGoodOrder">
    {{#  if(d.admin_status === '正常'){ }}
    <a class="layui-btn layui-btn-warm layui-btn-xs {{#  if(d.pay_type === '1'){ }}layui-btn-disabled {{#  } }}" lay-event="freeze">冻结订单</a>
    {{#  } }}
    {{#  if(d.admin_status === '已冻结'){ }}
    <a class="layui-btn layui-btn-green layui-btn-xs {{#  if(d.pay_type === '1'){ }}layui-btn-disabled {{#  } }}" lay-event="unfreeze">取消冻结</a>
    {{#  } }}
    <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
        <i class="layui-icon layui-icon-list"></i>
    </a>
</script>

<script type="text/html" id="payType">
    {{#  if(d.pay_type == '1'){ }}
    <span class="layui-badge layui-bg-green">微信扫码支付</span>
    {{#  } }}
    {{#  if(d.pay_type == '2'){ }}
    <span class="layui-badge layui-bg-blue">微信订单支付</span>
    {{#  } }}
</script>

<script type="text/html" id="payStatus">
    {{#  if(d.return_status == 1){ }}
    <span class="layui-badge">申请退货中</span>
    {{#  } else if(d.return_status == 2){ }}
    <span class="layui-badge layui-bg-gray">已退货</span>
    {{# } else if(d.return_status == 3){ }}
    <span class="layui-badge">拒绝退货</span>
    {{# } else if(d.pay_status == '未支付'){ }}
    <span class="layui-badge">{{d.pay_status}}</span>
    {{# } else if(d.pay_status == '已支付'){ }}
    <span class="layui-badge layui-bg-green">{{d.pay_status}}</span>
    {{# } else if(d.pay_status == '已取消'){ }}
    <span class="layui-badge layui-bg-gray">{{d.pay_status}}</span>
    {{# } else if(d.pay_status == '已发货'){ }}
    <span class="layui-badge layui-bg-blue">{{d.pay_status}}</span>
    {{# } else if(d.pay_status == '确认收货'){ }}
    <span class="layui-badge layui-bg-black">{{d.pay_status}}</span>
    {{#  } }}
</script>

<script type="text/html" id="settleStatus">
    {{#  if(d.settle_status == '0'){ }}
    <span class="layui-badge layui-bg-red">未结算</span>
    {{#  } }}
    {{#  if(d.settle_status == '1'){ }}
    <span class="layui-badge layui-bg-blue">已结算</span>
    {{#  } }}
</script>

<script>
    layui.use('table', function(){
        let table = layui.table,
            form = layui.form,
            layer = layui.layer,
            $ = layui.$;
        let orderTalbeIns = table.render({
            elem: '#goodOrderTable',
            cols: [[
                {field: 'order_code', title: '订单号',align: 'center',width: 200},
                {field: 'store', title: '店铺', templet: '<div>{{d.store.name}}</div>',align: 'center',width: 200},
                {field: 'user', title: '会员编码', templet: '<div>{{d.user.code}}</div>',align: 'center',width: 200},
                {field: 'amount',title: '订单金额',align: 'center',width: 120},
                {field: 'pay_type',title: '支付方式',templet:'#payType',align: 'center',width: 150},
                {field: "distribute_amount",title: "佣金",align: 'center',width: 120},
                {field: "get_amount",title: "结算金额",align: 'center',width: 120},
                {field: 'pay_status', title: '订单状态',templet: "#payStatus",align: 'center',width: 120},
                {field: 'ship_status', title: '物流状态',align: 'center',width: 120},
                {field: 'create_time', title: '下单时间',align: 'center',width: 200},
                {field: 'pay_time', title:'支付时间',align: 'center',width: 200},
                {field: 'settle_status', title:'结算状态',templet: "#settleStatus",align: 'center',width: 120},
                {field: 'settle_id', title:'结算ID',align: 'center',width: 120},
                {field: 'admin_status', title:'是否冻结',align: 'center',width: 120},
                {fixed: 'right', title:'操作', toolbar: '#barGoodOrder', width:160}
            ]],
            page: true,
            url: '<?php echo url("admin/good_order/get"); ?>'
        });
        //监听工具条
        table.on('tool(goodOrderTable)', function(obj){
            let layEvent = obj.event;

            if(layEvent === 'freeze'){ //冻结
                layer.prompt({title: '请输入操作口令，并确认', formType: 1}, function(pass, index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {order_code: obj.data.order_code,pass: pass},
                        url: "<?php echo url('admin/good_order/freezeOrder'); ?>",
                        success: function(res){
                            if(res.code === 0){
                                layer.alert(res.message,function(index){
                                    layer.close(index);
                                    orderTalbeIns.reload();
                                });
                            }else {
                                layer.alert(res.message);
                            }
                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });

                });
            }else if(layEvent === 'unfreeze'){ //取消冻结
                layer.prompt({title: '请输入操作口令，并确认', formType: 1}, function(pass, index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {order_code: obj.data.order_code,pass: pass},
                        url: "<?php echo url('admin/good_order/unFreezeOrder'); ?>",
                        success: function(res){
                            console.log(res);
                            if(res.code === 0){
                                layer.alert(res.message,function(index){
                                    layer.close(index);
                                    orderTalbeIns.reload();
                                });
                            }else {
                                layer.alert(res.message);
                            }
                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });

                });
            }else if(layEvent === 'detail'){
                x_admin_show("订单详情【订单号：" + obj.data.order_code + '】',"<?php echo url('admin/good_order/detail'); ?>?order_code=" + obj.data.order_code,"","");
            }
        });
    });
</script>

</div>
</body>
</html>