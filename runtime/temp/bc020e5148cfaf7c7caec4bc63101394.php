<?php /*a:2:{s:77:"C:\xampp\htdocs\ims.gzkxly.com\application\store\view\order\ship_confirm.html";i:1563194885;s:65:"C:\xampp\htdocs\ims.gzkxly.com\application\store\view\window.html";i:1557288055;}*/ ?>
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
    
<blockquote class="layui-elem-quote">
    订单明细
</blockquote>
<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
        <col>
        <col>
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>类型</th>
        <th>商品名称</th>
        <th>商品规格</th>
        <th>单价</th>
        <th>数量</th>
        <th>总价</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($details as $detail): ?>
    <tr>
        <td><?php if(($detail->offline == 1)): ?><span class="layui-badge  layui-bg-cyan">线下核销</span><?php else: ?><span class="layui-badge">线上发货</span> <?php endif; ?></td>
        <td><?php echo htmlentities($detail->good_name); ?></td>
        <td><?php echo htmlentities((isset($detail->option_name) && ($detail->option_name !== '')?$detail->option_name:"无")); ?></td>
        <td><?php echo htmlentities($detail->price); ?></td>
        <td><?php echo htmlentities($detail->number); ?></td>
        <td><?php echo htmlentities($detail->amount); ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<blockquote class="layui-elem-quote">
    发货信息
</blockquote>
<form class="layui-form">
    <input name="order_code"  value="<?php echo htmlentities($order['order_code']); ?>" type="hidden">
    <div class="layui-form-item">
        <label class="layui-form-label">发货地址</label>
        <div class="layui-input-inline">
            <select name="sender_id">
                <option value=""></option>
                <?php foreach($senders as $sender): ?>
                <option value="<?php echo htmlentities($sender['id']); ?>" data-address="<?php echo htmlentities($sender); ?>"><?php echo htmlentities($sender['name']); ?>(<?php echo htmlentities($sender['mobile']); ?>)-<?php echo htmlentities($sender['province_name']); ?>-<?php echo htmlentities($sender['city_name']); ?>-<?php echo htmlentities($sender['exp_area_name']); ?>-<?php echo htmlentities($sender['address']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">快递公司</label>
        <div class="layui-input-inline">
            <select name="shipper_code">
                <option value=""></option>
                <?php foreach($companys as $company): ?>
                <option value="<?php echo htmlentities($company['code']); ?>"><?php echo htmlentities($company['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="layui-form-mid layui-word-aux">请选择快递公司</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">快递单号</label>
        <div class="layui-input-inline">
            <input name="logistic_code"  placeholder="快递单号" autocomplete="off" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">请输入快递单号</div>
    </div>
    <div class="layui-form-item">
        <label for="L_add" class="layui-form-label">
        </label>
        <button id="L_add" class="layui-btn" lay-filter="ship" lay-submit="">
            确认发货
        </button>
    </div>
</form>
<script>
    layui.use(['form','layer',], function(){
        let $ = layui.$,
            form = layui.form,
            layer = layui.layer;

        //监听提交
        form.on('submit(ship)', function(data){
            layer.confirm("确定发货吗？确认后将不能不能修改发货信息", function(index){
                layer.close(index);
                $.ajax({
                    type: 'POST',
                    data: data.field,
                    url: '<?php echo url("store/order/orderShip"); ?>',
                    success: function (res) {
                        layer.alert(res.message,function(index){
                            layer.close(index);
                            if(res.code === 0) {
                                x_admin_father_reload();
                            }
                        });

                    },
                    error: function(){
                        layer.alert("请求失败！");
                    }
                });
            });
            return false;
        });
    });
</script>

</div>
</body>
</html>