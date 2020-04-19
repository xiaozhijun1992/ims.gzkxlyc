<?php /*a:1:{s:69:"C:\xampp\htdocs\ims.gzkxly.com\application\area\view\index\index.html";i:1568185861;}*/ ?>
<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>快享立赢商城</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <meta name=renderer  content=webkit>
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" type="text/css" href="/static/css/font.css" />
    <link rel="stylesheet" type="text/css" href="/static/css/xadmin.css" />
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/blueimp-md5/2.10.0/js/md5.min.js"></script>
    <script type="text/javascript" src="/static/lib/layui/layui.js"></script>
    <script type="text/javascript" src="/static/js/xadmin.js"></script>
    <script type="text/javascript" src="/static/js/cookie.js"></script>
    <script>
        // 是否开启刷新记忆tab功能
        // var is_remember = false;
    </script>
</head>
<body>
    <!-- 顶部开始 -->
    <div class="container">
        <div class="logo"><a href="./index.html"><img src="/static/images/banner.png"></a></div>
        <div class="left_open">
            <i title="展开左侧栏" class="iconfont">&#xe699;</i>
        </div>
        <ul class="layui-nav left fast-add" lay-filter="">
          <li class="layui-nav-item">
            <a href="javascript:;">+新增</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
              <dd><a onclick="x_admin_show('资讯','https://www.baidu.com')"><i class="iconfont">&#xe6a2;</i>资讯</a></dd>
              <dd><a onclick="x_admin_show('图片','https://www.baidu.com')"><i class="iconfont">&#xe6a8;</i>图片</a></dd>
               <dd><a onclick="x_admin_show('用户 最大化','https://www.baidu.com','','',true)"><i class="iconfont">&#xe6b8;</i>用户最大化</a></dd>
               <dd><a onclick="x_admin_add_to_tab('在tab打开','https://www.baidu.com',true)"><i class="iconfont">&#xe6b8;</i>在tab打开</a></dd>
            </dl>
          </li>
        </ul>
        <ul class="layui-nav right" lay-filter="">
            <li class="layui-nav-item">
                <?php echo htmlentities(app('session')->get('area_info.desc')); ?>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;"><?php echo htmlentities(app('session')->get('area_info.manager_name')); ?></a>
                <dl class="layui-nav-child"> <!-- 二级菜单 -->
                  <dd><a onclick="x_admin_show('个人信息','http://www.baidu.com')">个人信息</a></dd>
                  <dd><a onclick="x_admin_show('切换帐号','http://www.baidu.com')">切换帐号</a></dd>
                  <dd><a href="<?php echo url('area/index/logout'); ?>">退出</a></dd>
                </dl>
            </li>
        </ul>
        
    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
     <!-- 左侧菜单开始 -->
    <div class="left-nav">
      <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <cite>我的信息</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li date-refresh="1">
                        <a _href="<?php echo url('area/profile/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>我的资料</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('area/team/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>我的团队</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a _href="javascript:;">
                    <cite>店铺管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('area/store/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>店铺列表</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('area/store/add'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>添加店铺</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <cite>我的财务</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo url('area/area_bank_account/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>结算信息</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('area/distribute/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>佣金明细</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="<?php echo url('area/settle/index'); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>佣金结算单</cite>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <cite>统计信息</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="city.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>销售量</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="city.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>经理销售量</cite>
                        </a>
                    </li>
                    <li>
                        <a _href="city.html">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>订单量</cite>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
      </div>
    </div>
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
          <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>我的桌面</li>
          </ul>
          <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
                <dl>
                    <dd data-type="this">关闭当前</dd>
                    <dd data-type="other">关闭其它</dd>
                    <dd data-type="all">关闭全部</dd>
                </dl>
          </div>
          <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='<?php echo url("area/index/welcome"); ?>' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
          </div>
          <div id="tab_show"></div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <!-- 右侧主体结束 -->
    <!-- 中部结束 -->
    <!-- 底部开始 -->
    <div class="footer">
        <div class="copyright" style="text-align: center">Copyright ©2019 贵州同城共赢网络科技有限公司</div>
    </div>
    <!-- 底部结束 -->
    <script>
        layui.use(['layer'], function() {
            let layer = layui.layer;
            layer.confirm('<?php echo $hasBankAccount==false ? "请填写结算账号" :  "结算账号确认"; ?>', {
                btn: ['<?php echo $hasBankAccount==false ? "去填写" :  "去看看"; ?>','取消'] //按钮
            }, function(index){
                layer.close(index);
                x_admin_show("结算信息","<?php echo url('area/area_bank_account/index'); ?>",600,400);
            }, function(index){
                layer.close(index);
            });
        });
    </script>
</body>
</html>