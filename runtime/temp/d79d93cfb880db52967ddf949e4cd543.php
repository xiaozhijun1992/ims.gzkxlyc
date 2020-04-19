<?php /*a:2:{s:70:"C:\xampp\htdocs\ims.gzkxly.com\application\store\view\order\index.html";i:1568169292;s:63:"C:\xampp\htdocs\ims.gzkxly.com\application\store\view\base.html";i:1554859149;}*/ ?>
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
    
<div class="layui-card-body ">
    <form class="layui-form layui-col-space5">
        <div class="layui-input-inline layui-show-xs-block">
            <input class="layui-input" placeholder="开始日" name="start" id="start"></div>
        <div class="layui-input-inline layui-show-xs-block">
            <input class="layui-input" placeholder="截止日" name="end" id="end"></div>
        <div class="layui-input-inline layui-show-xs-block">
            <select name="pay_type" id="pay_type">
                <option value="">支付方式</option>
                <option value="1">线下扫码支付</option>
                <option value="2">线上订单支付</option>
            </select>
        </div>
        <div class="layui-input-inline layui-show-xs-block">
            <select name="pay_status" id="pay_status">
                <option value="">订单状态</option>
                <option value="0">未支付</option>
                <option value="1">已支付</option>
                <option value="2">已取消</option>
                <option value="3">已发货</option>
                <option value="4">确认收货</option>
            </select>
        </div>
        <div class="layui-input-inline layui-show-xs-block">
            <select name="admin_status" id="admin_status">
                <option value="">管理状态</option>
                <option value="0">已冻结</option>
                <option value="1">正常</option>
            </select>
        </div>
        <div class="layui-input-inline layui-show-xs-block">
            <input type="text" id="order_code" placeholder="请输入订单号" autocomplete="off" class="layui-input"></div>
        <div class="layui-input-inline layui-show-xs-block">
            <button class="layui-btn" title="搜索" id="search">
                <i class="layui-icon">&#xe615;</i>
            </button>
        </div>
    </form>
</div>
<table id="orderTable" lay-filter="orderTable"></table>
<!-- 操作栏 -->
<script type="text/html" id="barOrder">
    <div class="layui-btn-group">
    {{# if(d.admin_status == '已冻结'){ }}
        <a class="layui-btn layui-btn-xs" title="已冻结" lay-event="freeze">
            <i class="layui-icon layui-icon-snowflake"></i>
        </a>
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{# } else if(d.pay_type == '1' && d.pay_status == '4'){ }}
        <a type="button" class="layui-btn layui-btn-xs " title="线下扫码订单" lay-event="qrcodeOrder">
            <i class="icon iconfont">&#xe6a9;</i>
        </a>
    {{# } else if(d.pay_type == '1' && d.pay_status == '0'){ }}
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" title="删除订单">
            <i class="layui-icon layui-icon-delete"></i>
        </a>
        <a type="button" class="layui-btn layui-btn-xs " title="线下扫码订单" lay-event="qrcodeOrder">
            <i class="icon iconfont">&#xe6a9;</i>
        </a>
    {{# } else if(d.return_status == "1"){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="returnConfirm" title="退货">
            <i class="layui-icon layui-icon-ok-circle"></i>
        </a>
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="returnReject" title="拒绝退货">
            <i class="layui-icon layui-icon-close-fill"></i>
        </a>
        <a class="layui-btn layui-btn-xs" lay-event="returnReason" title="退货原因">
            <i class="layui-icon layui-icon-tips"></i>
        </a>
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{# } else if(d.return_status == "2"){ }}
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{# } else if(d.return_status == "3"){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="returnConfirm" title="退货">
            <i class="layui-icon layui-icon-ok-circle"></i>
        </a>
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{# } else if(d.return_status == "4"){ }}
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{# } else if(d.pay_status == '未支付'){ }}
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" title="删除订单">
            <i class="layui-icon layui-icon-delete"></i>
        </a>
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{# } else if(d.online_detail_count == 0){ }}
        <a type="button" class="layui-btn layui-btn-xs layui-btn-sm layui-btn-danger" title="线下核销订单" lay-event="offline">
            <i class="layui-icon layui-icon-down"></i>
        </a>
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{# } else if(d.pay_status == '已取消'){ }}
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" title="删除订单">
            <i class="layui-icon layui-icon-delete"></i>
        </a>
    {{# } else if(d.pay_status == '已支付'){ }}
        <a class="layui-btn layui-btn-xs" lay-event="shipConfirm" title="订单发货">
            <i class="layui-icon layui-icon-release"></i>
        </a>
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{# } else{ }}
        <a type="button" class="layui-btn layui-btn-xs" title="订单详情" lay-event="detail">
            <i class="layui-icon layui-icon-list"></i>
        </a>
    {{#  } }}
    {{# if(d.logistic_code){ }}
        <a type="button" class="layui-btn layui-btn-xs" title="查看物流" lay-event="shipRecord">
            <i class="layui-icon layui-icon-senior"></i>
        </a>
    {{#  } }}
    </div>
</script>

<!--支付方式-->
<script type="text/html" id="payTypeTpl">
    {{#  if(d.pay_type == "1"){ }}
    <span class="layui-badge">微信扫码支付</span>
    {{#  }else { }}
    <span class="layui-badge layui-bg-green">微信订单支付</span>
    {{#  } }}
</script>

<!--结算状态-->
<script type="text/html" id="settleStatusTpl">
    {{#  if(d.pay_status == '未支付' || d.pay_status == '已取消'){ }}
    <span>未支付</span>
    {{# } else if(d.settle_status == "1"){ }}
    <span class="layui-badge layui-bg-green">已结算</span>
    {{#  }else { }}
    <span class="layui-badge">未结算</span>
    {{#  } }}
</script>

<!--退货状态-->
<script type="text/html" id="returnStatusTpl">
    {{#  if(d.return_status == '0'){ }}
    <span>无</span>
    {{# } else if(d.return_status == "1"){ }}
    <span>申请中</span>
    {{# } else if(d.return_status == "2"){ }}
    <span>退货成功</span>
    {{# } else if(d.return_status == "3"){ }}
    <span>退货拒绝</span>
    {{# } else if(d.return_status == "4"){ }}
    <span>强制退货</span>
    {{#  }else { }}
    <span class="layui-badge">异常</span>
    {{#  } }}
</script>

<script>
    layui.use(['table','laydate'], function(){
        let table = layui.table;
        let laydate = layui.laydate;
        let $ = layui.$;
        laydate.render({
            elem: '#start' //指定元素
        });
        laydate.render({
            elem: '#end' //指定元素
        });

        let orderTableIns = table.render({
            elem: '#orderTable',
            cols: [[
                {field: 'order_code', title: "订单号",align: "center",width: 150},
                {field: 'amount', title: "订单金额",align: "center",width: 100,templet: "<div>￥{{d.amount}}</div>",style: 'color: red'},
                {field: 'get_amount', title: "可结算金额",align: "center",width: 100,templet: "<div>{{d.get_amount ? '￥' + d.get_amount : '未支付'}}</div>",style: 'color: green'},
                {field: 'user',title: '会员编码',templet: '<div>{{d.user.code || ""}}</div>',align: "center",width: 150},
                {field: 'pay_type', title: '支付方式',align: "center",width: 120,templet: "#payTypeTpl"},
                {field: 'pay_status', title: '订单状态',align: "center",width: 80},
                {field: 'ship_status', title: '物流状态',align: "center",width: 80},
                {field: 'logistic_code', title: '快递单号',align: "center",width: 200,templet: "<div>{{d.logistic_code||'无'}}</div>"},
                {field: 'return_status', title: '退货状态',align: "center",width: 80,templet: "#returnStatusTpl"},
                {field: 'settle_status', title: '结算状态',align: "center",width: 80,templet: "#settleStatusTpl"},
                {field: 'create_time', title: "下单时间",align: "center",width: 150},
                {field: 'pay_time', title: "支付时间",align: "center",width: 150},
                {field: 'receiver_name', title: "收件人（电话）",align: "center",width: 180,templet: "<div>{{d.receiver_name||'无'}}{{d.receiver_mobile ? '(' + d.receiver_mobile + ')' : ''}}</div>"},
                {field: 'receiver_address', title: "收件地址",align: "center",width: 200,templet:"<div>{{d.receiver_province||'无'}}{{d.receiver_city||''}}{{d.receiver_area||''}}{{d.receiver_address||''}}</div>"},
                {field:'admin_status', title:'管理状态', width:80,align: "center"},
                {fixed: 'right', title:'操作', toolbar: '#barOrder', width:200,align: "right"}
            ]],
            page: true,
            id: 'orderTable',
            url: '<?php echo url("store/order/get"); ?>'
        });


        table.on('tool(orderTable)', function(obj) {
            let layEvent = obj.event;
            if(layEvent === 'del'){
                layer.confirm("确定要删除该订单吗？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {order_code: obj.data.order_code},
                        url: '<?php echo url("store/order/delete"); ?>',
                        success: function (res) {
                            layer.alert(res.message,function(index){
                                layer.close(index);
                                if(res.code === 0) {
                                    table.reload("orderTable");
                                }
                            });

                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });
                })
            }else if(layEvent === 'shipConfirm'){
                x_admin_show("订单发货【订单号：" + obj.data.order_code + '】',"<?php echo url('store/order/shipConfirm'); ?>?order_code=" + obj.data.order_code,"","");
            }
            else if(layEvent === 'freeze'){
                layer.alert("该笔订单订单已被平台冻结");
            }else if(layEvent === 'shipRecord'){
                x_admin_show("物流信息【快递单号：" + obj.data.logistic_code + '】',"<?php echo url('store/order/getOrderTraces'); ?>?order_code=" + obj.data.order_code,"400","");
            }else if(layEvent === 'qrcodeOrder'){
                layer.alert("该笔订单是线下扫码订单");
            }else if(layEvent === 'detail'){
                x_admin_show("订单详情【订单号：" + obj.data.order_code + '】',"<?php echo url('store/order/detail'); ?>?order_code=" + obj.data.order_code,"","");
            }else if(layEvent === 'offline'){
                layer.alert("该笔订单的所有商品都需要线下核销");
            }else if(layEvent === 'returnConfirm'){
                layer.confirm("确定要对该订单进行退款吗？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {order_code: obj.data.order_code},
                        url: '<?php echo url("store/order/refund"); ?>',
                        success: function (res) {
                            layer.alert(res.message,function(index){
                                layer.close(index);
                                if(res.code === 0) {
                                    table.reload("orderTable");
                                }
                            });

                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });
                })
            }else if(layEvent === 'returnReject'){
                layer.confirm("确定要拒绝该订单的退款申请码？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {order_code: obj.data.order_code},
                        url: '<?php echo url("store/order/returnReject"); ?>',
                        success: function (res) {
                            layer.alert(res.message,function(index){
                                layer.close(index);
                                if(res.code === 0) {
                                    table.reload("orderTable");
                                }
                            });
                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });
                })
            }else if(layEvent === 'returnReason'){
                layer.open({
                    title: '退款原因'
                    ,content: obj.data.refund_reason
                });
                console.log(obj.data.refund_img);
            }



        });

        let serach = '#search';
        let start = '#start';
        let end = '#end';
        let pay_status = "#pay_status";
        let pay_type = "#pay_type";
        let admin_status = '#admin_status';
        let order_code = '#order_code';

        $(serach).on('click',function(e){
            console.log($(pay_type).val());
            e.preventDefault();
            orderTableIns.reload({
                page: {
                    curr: 1
                }
                ,where: {
                    key: {
                        start: $(start).val(),
                        end: $(end).val(),
                        pay_status: $(pay_status).val(),
                        pay_type: $(pay_type).val(),
                        order_code: $(order_code).val(),
                        admin_status: $(admin_status).val(),
                    }
                }
            });
        });

    });
</script>

</div>
</body>
</html>