<?php /*a:2:{s:69:"C:\xampp\htdocs\ims.gzkxly.com\application\area\view\store\index.html";i:1562512305;s:62:"C:\xampp\htdocs\ims.gzkxly.com\application\area\view\base.html";i:1558879011;}*/ ?>
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
    <script type="text/javascript" src="https://cdn.bootcss.com/blueimp-md5/2.10.0/js/md5.min.js"></script>
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
    
<table id="storeTable" lay-filter="storeTable"></table>
<script type="text/html" id="barStore">
    <div class="layui-btn-group">
        {{#  if(d.status == '正常'){ }}
        <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="disable" title="禁用"><i class="layui-icon layui-icon-face-cry"></i></a>
        <a class="layui-btn layui-btn-xs" lay-event="qrcodePayment" title="收款二维码"><i class="icon iconfont">&#xe6a9;</i></a>
        {{#  } }}
        {{#  if(d.status == '禁用'){ }}
        <a class="layui-btn layui-btn-xs" lay-event="enable" title="启用"><i class="layui-icon layui-icon-face-smile"></i></a>
        {{#  } }}
        {{#  if(d.status == '驳回'){ }}
        <a class="layui-btn layui-btn-xs" lay-event="reason" title="驳回原因"><i class="layui-icon layui-icon-survey"></i></a>
        {{#  } }}
        <a class="layui-btn layui-btn-xs" lay-event="edit" title="修改"><i class="layui-icon layui-icon-edit"></i></a>
        <a class="layui-btn layui-btn-xs" lay-event="view" title="查看"><i class="layui-icon layui-icon-search"></i></a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del" title="删除"><i class="layui-icon layui-icon-delete"></i></a>
    </div>
</script>
<script>
    layui.use(['table','layer'],function(){
        let table = layui.table,
            layer = layui.layer;

        table.render({
            elem: '#storeTable',
            cellMinWidth: 120,
            cols: [[
                {field: 'id', title: "店铺ID",width: 70, align: 'center'},
                {field: 'name',title: '店铺名称',width: 200, align: 'center'},
                {field: 'user', title: '店主编码',templet: '<div>{{d.user.code}}</div>',width: 140,align: "center"},
                {field: 'industry',title: '行业',templet: '<div>{{d.industry.name}}</div>',width: 70,align: 'center'},
                {field: 'address',title: '详细地址'},
                {field: 'contact_name',title: '联系人姓名',width: 100,align: 'center'},
                {field: 'contact_phone',title: '联系人手机',width: 110,align: 'center'},
                {field: 'contact_email',title: '联系人邮箱',width: 150,align: 'center'},
                {field: 'salesman', title: '区域经理',templet:'<div>{{d.salesman.name}}</div>',width: 80,align: 'center'},
                {field:'create_time', title:'创建时间',width: 150,align: 'center'},
                {field:'status', title:'状态',width: 70,align: 'center'},
                {fixed: 'right', title:'操作', toolbar: '#barStore', width:220}
            ]],
            page: true,
            url: '<?php echo url("area/store/get"); ?>'
        });
        table.on('tool(storeTable)', function(obj) {
            let layEvent = obj.event;
            if (layEvent === 'edit') { //删除
                x_admin_show("修改店铺【" + obj.data.name + '】',"<?php echo url('area/store/edit'); ?>?id=" + obj.data.id,"","",true);
            }else if(layEvent === 'reason'){
                layer.alert(obj.data.remark);
            }else if(layEvent === 'view'){
                x_admin_show("店铺详情【" + obj.data.name + '】',"<?php echo url('area/store/detail'); ?>?id=" + obj.data.id,"","",true);
            }else if(layEvent === 'qrcodePayment'){
                layer.open({
                    title: obj.data.name + '的收款二维码',
                    type: 2,
                    fixed: false, //不固定
                    maxmin: false,
                    area: ['330px', '300px'],
                    content: '<?php echo url("area/store/qrcodePayment"); ?>?store_id=' + obj.data.id
                });
            }else if(layEvent === 'del'){
                layer.confirm("确定要删除该店铺吗？改操作将会一并删除店铺下的所有商品", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {id: obj.data.id},
                        url: '<?php echo url("area/store/delete"); ?>',
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
                layer.confirm("确定要停用该店铺吗？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {id: obj.data.id},
                        url: '<?php echo url("area/store/disable"); ?>',
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
            }else if(layEvent === 'enable'){
                layer.confirm("确定要停用该店铺吗？", function(index){
                    layer.close(index);
                    $.ajax({
                        type: 'POST',
                        data: {id: obj.data.id},
                        url: '<?php echo url("area/store/enable"); ?>',
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