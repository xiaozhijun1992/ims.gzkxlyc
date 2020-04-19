<?php /*a:2:{s:68:"C:\xampp\htdocs\ims.gzkxly.com\application\area\view\store\edit.html";i:1562510550;s:64:"C:\xampp\htdocs\ims.gzkxly.com\application\area\view\window.html";i:1557288035;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>快享立赢区域代理平台</title>
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
    
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=SYt2oYMQf7qRhpu54agaOxQChLUfetme"></script>
<form class="layui-form" action="">
    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li class="layui-this">基本信息</li>
            <li>地理位置</li>
            <li>店铺轮播图</li>
            <li>店铺资料</li>
        </ul>
        <div class="layui-tab-content" style="height: 100px;">
            <div class="layui-tab-item layui-show">
                <input name="id" class="layui-input" value="<?php echo htmlentities($store_info['id']); ?>" type="hidden">
                <div class="layui-form-item">
                    <label class="layui-form-label">店铺名称</label>
                    <div class="layui-input-inline">
                        <input name="name" class="layui-input" value="<?php echo htmlentities($store_info['name']); ?>">
                    </div>
                    <label class="layui-form-label">行业</label>
                    <div class="layui-input-inline">
                        <select name="industry_id" lay-search="">
                            <option value=""></option>
                            <?php if(is_array($industry_list) || $industry_list instanceof \think\Collection || $industry_list instanceof \think\Paginator): $i = 0; $__LIST__ = $industry_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$industry): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo htmlentities($industry['id']); ?>" <?php if($industry['id'] == $store_info['industry_id']): ?> selected <?php endif; ?>><?php echo htmlentities($industry['name']); ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                    <label class="layui-form-label">店主</label>
                    <div class="layui-input-inline">
                        <input name="user_id" class="layui-input" id="user_id" type="hidden" value="<?php echo htmlentities($store_info['user_id']); ?>">
                        <input class="layui-input" id="user_name" value="<?php echo htmlentities($user['nick_name']); ?>">
                    </div>
                    <label class="layui-form-label">到期时间</label>
                    <div class="layui-input-inline">
                        <input name="expire_time" id="expire_time" class="layui-input" value="<?php echo htmlentities($store_info['expire_time']); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">联系人</label>
                    <div class="layui-input-inline">
                        <input name="contact_name" class="layui-input" value="<?php echo htmlentities($store_info['contact_name']); ?>">
                    </div>
                    <label class="layui-form-label">联系手机</label>
                    <div class="layui-input-inline">
                        <input name="contact_phone" class="layui-input" value="<?php echo htmlentities($store_info['contact_phone']); ?>">
                    </div>
                    <label class="layui-form-label">联系邮箱</label>
                    <div class="layui-input-inline">
                        <input name="contact_email" class="layui-input" value="<?php echo htmlentities($store_info['contact_email']); ?>">
                    </div>
                    <label class="layui-form-label">业务员</label>
                    <div class="layui-input-inline">
                        <select name="manager_id" lay-search="">
                            <option value=""></option>
                            <?php if(is_array($salesman_list) || $salesman_list instanceof \think\Collection || $salesman_list instanceof \think\Paginator): $i = 0; $__LIST__ = $salesman_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$salesman): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo htmlentities($salesman['id']); ?>" <?php if($salesman['id'] == $store_info['manager_id']): ?> selected <?php endif; ?>><?php echo htmlentities($salesman['name']); ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">店铺地址</label>
                    <div class="layui-input-block">
                        <input name="address" class="layui-input" value="<?php echo htmlentities($store_info['address']); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" for="business">主营业务</label>
                    <div class="layui-input-block">
                        <input name="business" class="layui-input" id="business" value="<?php echo htmlentities($store_info['business']); ?>">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label" for="introduce">店铺简介</label>
                    <div class="layui-input-block">
                        <textarea name="introduce" class="layui-textarea" id="introduce" rows="5"><?php echo htmlentities($store_info['business']); ?></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商品形象图</label>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="showImgUpload">上传图片</button>
                        </div>
                    </div>
                    <div class="layui-form-mid layui-word-aux">形象图大小：360px * 360px</div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <img src="/<?php echo htmlentities($store_info['show_img']); ?>" id="preview">
                    </div>
                </div>
            </div>
            <div class="layui-tab-item">
                <div class="layui-form-item">
                    <label class="layui-form-label">经度</label>
                    <div class="layui-input-inline">
                        <input name="lng" id="lng" class="layui-input" disabled title="经度" value="<?php echo htmlentities($store_info['lng']); ?>">
                    </div>
                    <label class="layui-form-label">纬度</label>
                    <div class="layui-input-inline">
                        <input name="lat" id="lat" class="layui-input" disabled title="纬度" value="<?php echo htmlentities($store_info['lat']); ?>">
                    </div>
                </div>
                <div class="layui-input-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <div id="map" style="width: 700px; height: 400px;"></div>
                    </div>
                </div>
            </div>
            <div class="layui-tab-item">
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-normal" id="testList">选择图片</button>
                    <div class="layui-upload-list">
                        <table class="layui-table">
                            <thead>
                            <tr><th>文件名</th>
                                <th>大小</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr></thead>
                            <?php foreach($store_banner as $banner): ?>
                            <tr>
                                <td><a href="/<?php echo htmlentities($banner['img']); ?>" target="_blank"><?php echo htmlentities($banner['img']); ?></a></td>
                                <td></td>
                                <td>已上传</td>
                                <td><i class="layui-btn layui-btn-xs layui-btn-danger delete-uploaded">删除</i></td>
                            </tr>
                            <?php endforeach; ?>
                            <tbody id="demoList"></tbody>
                        </table>
                    </div>
                    <button type="button" class="layui-btn" id="testListAction">开始上传</button>
                </div>
            </div>
            <div class="layui-tab-item">
                <div class="layui-form-item">
                    <label class="layui-form-label">营业执照</label>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn uploadMarial" lay-data="{name: 'yyzz'}">上传营业执照</button>
                        </div>
                    </div>
                    <?php foreach($store_material as $material): if($material['type'] == 1): $yyzz = $material['img']; ?>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="layui-form-mid layui-word-aux" id="yyzzDesc"><a target="_blank" href="/<?php echo htmlentities((isset($yyzz) && ($yyzz !== '')?$yyzz:'')); ?>"><?php echo htmlentities((isset($yyzz) && ($yyzz !== '')?$yyzz:'')); ?></a></div>

                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">协议书</label>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn uploadMarial" lay-data="{name: 'xys'}">上传协议书</button>
                        </div>
                    </div>
                    <?php foreach($store_material as $material): if($material['type'] == 2): $xys = $material['img']; ?>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="layui-form-mid layui-word-aux" id="xysDesc"><a target="_blank" href="/<?php echo htmlentities((isset($xys) && ($xys !== '')?$xys:'')); ?>"><?php echo htmlentities((isset($xys) && ($xys !== '')?$xys:'')); ?></a></div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">负责人身份证</label>
                    <div class="layui-input-inline">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn uploadMarial" lay-data="{name: 'sfz'}">上传身份证</button>
                        </div>
                    </div>
                    <?php foreach($store_material as $material): if($material['type'] == 3): $sfz = $material['img']; ?>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="layui-form-mid layui-word-aux" id="sfzDesc"><a target="_blank" href="/<?php echo htmlentities((isset($sfz) && ($sfz !== '')?$sfz:'')); ?>"><?php echo htmlentities((isset($sfz) && ($sfz !== '')?$sfz:'')); ?></a></div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-bg-red" lay-submit lay-filter="add">保存</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    layui.extend({'tableSelect': '{/}/static/lib/plugin/table-select/table-select'});
    layui.use(['form','layer','laydate','upload','tableSelect'], function(){
        let $ = layui.$,
            laydate = layui.laydate,
            form = layui.form,
            upload = layui.upload,
            show_img = null,
            imgs = [],
            xys='',
            yyzz='',
            sfz='',
            layer = layui.layer;
        let tableSelect = layui.tableSelect;
        laydate.render({
            elem: '#expire_time'
        });
        show_img = '<?php echo htmlentities($store_info['show_img']); ?>';
        <?php foreach($store_material as $material): if($material['type'] == 1): ?> yyzz = '<?php echo htmlentities($material['img']); ?>';<?php endif; if($material['type'] == 2): ?> xys = '<?php echo htmlentities($material['img']); ?>';<?php endif; if($material['type'] == 3): ?> sfz = '<?php echo htmlentities($material['img']); ?>';<?php endif; ?>
        <?php endforeach; foreach($store_banner as $banner): ?>
            imgs.push('<?php echo htmlentities($banner['img']); ?>');
        <?php endforeach; ?>
        $('.delete-uploaded').on('click', function(){
            let imgurl = $(this).parent().parent().find('a')[0].innerText;
            $(this).parent().parent().remove();
            for (var i = 0; i < imgs.length; i++) {
                if (imgs[i] === imgurl) imgs.splice(i, 1);
            }
        });


        // 店主选择
        tableSelect.render({
            elem: '#user_name',
            checkedKey: 'id',
            searchKey: 'phone',
            searchPlaceholder: '手机号搜索',
            table: {	//定义表格参数，与LAYUI的TABLE模块一致，只是无需再定义表格elem
                url:'<?php echo url("area/team/getUser"); ?>',
                cols: [[
                    {type: 'radio' },
                    {field: 'id', title: '用户ID'},
                    {field: 'code', title: '用户代码'},
                    {field: 'real_name',title: '真实姓名'},
                    {field: 'nick_name',title: '昵称'},
                    {field: 'phone',title: '手机号'},
                ]]
            },
            done: function (elem, data) {
                $("#user_id").val(data.data[0].id);
                $("#user_name").val(data.data[0].nick_name);
            }
        });

        //商家资料上传
        let metrialUpload = upload.render({
            elem: '.uploadMarial'
            ,url: '<?php echo url("area/store/uploadMetrial"); ?>'
            ,before: function(){
                layer.load("上传中");
            }
            ,done: function(res){
                let id = '#'+this.name + 'Desc';
                $(id)[0].innerHTML = res.message + '(' + res.data + ')';
                if(res.code === 0){
                    if(this.name === 'yyzz'){
                        yyzz = res.data;
                    }else if(this.name === 'xys'){
                        xys = res.data;
                    }else if(this.name === 'sfz'){
                        sfz = res.data;
                    }
                    layer.closeAll();
                }else {
                    layer.alert(res.msg, function () {
                        layer.closeAll()
                    });
                }
            }
            ,error: function(){
                layer.alert("上传失败",function () {
                    layer.closeAll();
                });
            }
        });

        //店铺形象图片上传
        let uploadInst = upload.render({
            elem: '#showImgUpload'
            ,url: '<?php echo url("area/store/uploadShowImg"); ?>'
            ,before: function(){
                layer.load("上传中");
            }
            ,done: function(res) {
                //如果上传失败
                if (res.code !== 0) {
                    layer.alert(res.message,function () {
                        layer.closeAll();
                    });
                } else if (res.code === 0){
                    //上传成功
                    show_img = res.data;
                    $("#preview").attr('src','/'+res.data);
                    layer.closeAll();
                }
            }
            ,error: function(){
                layer.alert("上传失败",function () {
                    layer.closeAll();
                });
            }
        });

        // 店铺轮播图上传
        let demoListView = $('#demoList');
        let uploadListIns = upload.render({
            elem: '#testList'
            ,url: '<?php echo url("area/store/uploadImg"); ?>'
            ,accept: 'file'
            ,multiple: true
            ,auto: false
            ,bindAction: '#testListAction'
            ,choose: function(obj){
                var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                //读取本地文件
                obj.preview(function(index, file, result){
                    var tr = $(['<tr id="upload-'+ index +'">'
                        ,'<td>'+ file.name +'</td>'
                        ,'<td>'+ (file.size/1014).toFixed(1) +'kb</td>'
                        ,'<td>等待上传</td>'
                        ,'<td>'
                        ,'<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                        ,'<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                        ,'</td>'
                        ,'</tr>'].join(''));

                    //单个重传
                    tr.find('.demo-reload').on('click', function(){
                        obj.upload(index, file);
                    });

                    //删除
                    tr.find('.demo-delete').on('click', function(){
                        delete files[index]; //删除对应的文件
                        tr.remove();
                        uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    });

                    demoListView.append(tr);
                });
            }
            ,done: function(res, index, upload){
                if(res.code === 0){ //上传成功
                    imgs.push(res.data);
                    console.log(imgs);
                    var tr = demoListView.find('tr#upload-'+ index)
                        ,tds = tr.children();
                    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                    tds.eq(3).html(''); //清空操作
                    return delete this.files[index]; //删除文件队列已经上传成功的文件
                }
                this.error(index, upload,res.message);
            }
            ,error: function(index, upload,message){
                let tr = demoListView.find('tr#upload-'+ index)
                    ,tds = tr.children();
                tds.eq(2).html('<span style="color: #FF5722;">'+message+'</span>');
                tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
            }
        });

        //监听提交
        form.on('submit(add)', function(data){
            console.log(data.field);
            $.ajax({
                type: 'POST',
                data: {data: data.field, show_img: show_img,imgs: imgs,yyzz: yyzz,xys: xys,sfz: sfz,id: data.field.id},
                url: "<?php echo url('area/store/editSave'); ?>",
                success: function(res){
                    if(res.code === 0) {
                        layer.alert(res.message, function(index){
                            layer.close(index);
                            x_admin_close();
                            x_admin_father_reload();
                        });
                    }else {
                        layer.alert(res.message);
                    }
                },
                error: function(){
                    layer.alert("提交失败");
                }
            });
            return false;
        });


        // 选择经纬度坐标
        var mp = new BMap.Map("map");

        mp.enableScrollWheelZoom();
        mp.enableInertialDragging();
        mp.enableContinuousZoom();

        var point = new BMap.Point(<?php echo htmlentities($store_info['lng']); ?>,<?php echo htmlentities($store_info['lat']); ?>);
        mp.centerAndZoom(point, 16);
        var mk = new BMap.Marker(point);
        mp.addOverlay(mk);

        mp.addEventListener("click",function(e){
            var mk = new BMap.Marker(e.point);
            mp.clearOverlays();
            mp.addOverlay(mk);
            mp.panTo(e.point);
            console.log(e.point.lng + "," + e.point.lat);
            $("#lng").val(e.point.lng);
            $("#lat").val(e.point.lat)
        });


    });
</script>

</div>
</body>
</html>