<?php /*a:2:{s:70:"C:\xampp\htdocs\ims.gzkxly.com\application\admin\view\store\index.html";i:1566964193;s:63:"C:\xampp\htdocs\ims.gzkxly.com\application\admin\view\base.html";i:1558879011;}*/ ?>
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
    
<div class="demoTable">
    店铺名称：
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" autocomplete="off">
    </div>
    <button class="layui-btn" data-type="reload" id="searchName">搜索</button>
</div>
<div id="storeDiv">
    <table id="storeTable" lay-filter="storeTable"></table>
</div>
<script type="text/html" id="barStore">
    <div class="layui-btn-group">
    {{#  if(d.status == '申请中'){ }}
    <a class="layui-btn layui-btn-xs" lay-event="check" title="审核店铺">审核</a>
    {{#  } }}
    {{#  if(d.status == '驳回'){ }}
    <a class="layui-btn layui-btn-xs" lay-event="reason" title="审核店铺">驳回原因</a>
    {{#  } }}
    {{#  if(d.status == '禁用'){ }}
    <a class="layui-btn layui-btn-xs" lay-event="enable" title="启用">启用</a>
    {{#  } }}
    {{#  if(d.status == '正常'){ }}
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="disable">停用</a>
    <a class="layui-btn layui-btn-xs" lay-event="qrcodePayment" title="收款二维码">收款二维码</a>
    {{#  } }}
    {{#  if(d.recommend == '1'){ }}
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="unrecommend" title="启用">取消推荐</a>
    {{#  } }}
    {{#  if(d.recommend == '0'){ }}
    <a class="layui-btn layui-btn-xs" lay-event="recommend" title="启用">首页推荐</a>
    {{#  } }}
    <a class="layui-btn layui-btn-xs" lay-event="detail">详情</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </div>
</script>
<script>
    layui.extend({tableFilter: '{/}/static/lib/plugin/table-filter/tableFilter'});
    layui.use(['table','layer','tableFilter'],function(){
        let table = layui.table,
            $ = layui.$,
            tableFilter = layui.tableFilter,
            layer = layui.layer;

        table.render({
            elem: '#storeTable',
            cols: [[
                {field: 'id', title: "店铺ID",width: 70,align: 'center'},
                {field:'status', title:'状态',width: 120,align: "center"},
                {field: 'recommend', title: '是否推荐', templet: function(d){
                    if(d.recommend === 1){
                        return "是";
                    }else {
                        return "否";
                    }
                    },width: 160,align: "center"},
                {field: 'name',title: '店铺名称',width: 200, align: 'center'},
                {field: 'user_id', title: '店主ID',width: 70,align: 'center'},
                {field: 'user', title: '店主编码', templet: '<div>{{d.user.code}}</div>',width: 160,align: "center"},
                {field: 'user', title: '店主昵称', templet: '<div>{{d.user.nick_name}}</div>',width: 120,align: "center"},
                {field: 'industry',title: '行业',templet: '<div>{{d.industry.name}}</div>',width: 120,align: "center"},
                {field: 'area',title: '区域',templet: '<div>{{d.area.desc}}</div>',width: 200,align: "center"},
                {field: 'salesman',title: '业务员',templet: '<div>{{d.salesman.name}}</div>',width: 70,align: "center"},
                {field: 'address',title: '详细地址',width: 200,align: "center"},
                {field: 'contact_name',title: '联系人姓名',width: 100,align: "center"},
                {field: 'contact_phone',title: '联系人手机',width: 120,align: "center"},
                {field: 'contact_email',title: '联系人邮箱',width: 150,align: "center"},

                {fixed: 'right', title:'操作', toolbar: '#barStore', width:320}
            ]],
            page: true,
            id: "storeTable",
            url: '<?php echo url("admin/store/get"); ?>',
            done: function(){
                //非常重要！如果使table.reload()后依然使用过滤，就必须将过滤组件也reload()一下
                tableFilterIns.reload()
            }
        });

        let tableFilterIns = tableFilter.render({
            'elem' : '#storeTable',//table的选择器
            'mode' : 'api',//过滤模式
            'parent': '#storeDiv',
            'filters' : [
                {field: 'recommend',type: 'radio',data: [{ "key":"0", "value":"否"},{ "key":"1", "value":"是"}]},
                {field: 'status',type: 'radio',data: [{ "key":"0", "value":"申请中"},{ "key":"1", "value":"正常"},{ "key":"2", "value":"驳回"},{ "key":"3", "value":"禁用"}]},
            ],//过滤项配置
            'done': function(filters){

            }
        });

        $("#searchName").on('click',function(){
            table.reload('storeTable', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    name: $("#name").val()
                }
            }, 'data');
        });

        table.on('tool(storeTable)', function(obj){
            let layEvent = obj.event;
            if(layEvent === 'check'){ //删除
                layer.msg('店铺审核操作', {
                    time: 0 //不自动关闭
                    ,btn: ['审核通过', '不通过']
                    ,yes: function(index){
                        layer.close(index);
                        $.ajax({
                            type: 'POST',
                            data: {id: obj.data.id},
                            url: '<?php echo url("admin/store/checkOn"); ?>',
                            success: function (res) {
                                layer.alert(res.message,function(index){
                                    layer.close(index);
                                    if(res.code === 0) {
                                        table.reload("storeTable");
                                    }
                                });

                            },
                            error: function(){
                                layer.alert("请求失败！");
                            }
                        });
                    }
                    ,btn2: function(index){
                        layer.close(index);
                        layer.prompt({title: '不通过的原因', formType: 2}, function(text, index){
                            layer.close(index);
                            $.ajax({
                                type: 'POST',
                                data: {id: obj.data.id,remark: text},
                                url: '<?php echo url("admin/store/checkOff"); ?>',
                                success: function (res) {
                                    layer.alert(res.message,function(index){
                                        layer.close(index);
                                        if(res.code === 0) {
                                            table.reload("storeTable");
                                        }
                                    });
                                },
                                error: function(){
                                    layer.alert("请求失败！");
                                }
                            });
                        });
                    }
                });
            }else if(layEvent === 'reason'){ //删除
                layer.alert(obj.data.remark);
            }else if(layEvent === 'enable'){
                layer.confirm("确定要启用该店铺吗？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {id: obj.data.id},
                        url: '<?php echo url("admin/store/enable"); ?>',
                        success: function (res) {
                            layer.alert(res.message,function(index){
                                layer.close(index);
                                if(res.code === 0) {
                                    table.reload("storeTable");
                                }
                            });

                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });
                })
            }else if(layEvent === 'disable'){
                layer.confirm("确定要禁用该店铺吗？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {id: obj.data.id},
                        url: '<?php echo url("admin/store/disable"); ?>',
                        success: function (res) {
                            layer.alert(res.message,function(index){
                                layer.close(index);
                                if(res.code === 0) {
                                    table.reload("storeTable");
                                }
                            });
                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });
                })
            }else if(layEvent === 'qrcodePayment'){
                layer.open({
                    title: '店铺收款二维码',
                    type: 2,
                    fixed: false, //不固定
                    maxmin: false,
                    area: ['330px', '300px'],
                    content: '<?php echo url("admin/store/qrcodePayment"); ?>?store_id=' + obj.data.id
                });
            }else if(layEvent === 'detail'){
                x_admin_show("店铺详情【" + obj.data.name + '】',"<?php echo url('admin/store/detail'); ?>?id=" + obj.data.id,"","",true);
            }else if(layEvent === 'recommend'){
                layer.confirm("确定要将该店铺推荐到首页吗？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {id: obj.data.id},
                        url: '<?php echo url("admin/store/recommend"); ?>',
                        success: function (res) {
                            layer.alert(res.message,function(index){
                                layer.close(index);
                                if(res.code === 0) {
                                    table.reload("storeTable");
                                }
                            });

                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });
                })
            }else if(layEvent === 'unrecommend'){
                layer.confirm("确定要将该店铺推荐到首页吗？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {id: obj.data.id},
                        url: '<?php echo url("admin/store/unrecommend"); ?>',
                        success: function (res) {
                            layer.alert(res.message,function(index){
                                layer.close(index);
                                if(res.code === 0) {
                                    table.reload("storeTable");
                                }
                            });

                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });
                })
            }else if(layEvent === 'del'){ //删除
                layer.confirm("确定要删除该店铺吗？改操作将会一并删除店铺下的所有商品", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {id: obj.data.id},
                        url: '<?php echo url("admin/store/delete"); ?>',
                        success: function (res) {
                            layer.alert(res.message,function(index){
                                layer.close(index);
                                if(res.code === 0) {
                                    table.reload("storeTable");
                                }
                            });

                        },
                        error: function(){
                            layer.alert("请求失败！");
                        }
                    });
                })
            }
        });
    })
</script>

</div>
</body>
</html>