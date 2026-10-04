<?php
include "../comm/comm.php";
if (!empty($_GET['action']) && $_GET['action'] == "exit") {
    $_SESSION['login'] = 0;
}
if (empty($_SESSION['login']) || $_SESSION['login'] == 0) {
    include "login.php";
    exit;
}

if (!empty($_GET['action']) && $_GET['action'] == "add") {
    if (empty($_POST['zhan_callback'])) {
        exit('<script>alert("回调地址不能为空");window.location.href="./manage.php"</script>');
    }
    $_POST['zhan_state'] = 1;
    $_POST['zhan_addtime'] = date("Y-m-d H:i:s");
    if (insert("qqlogin_zhan", $_POST)) {
        exit('<script>alert("新增成功");window.location.href="./manage.php"</script>');
    } else {
        exit('<script>window.location.href="./manage.php?error"</script>');
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../public/layui/css/layui.css" media="all">
</head>
<body>

<div class="layui-row" style="width: 80%; margin: 0 auto; margin-top: 20px;">
    <ul class="layui-tab-title">
        <li class="layui-this" onclick="window.location.href='manage.php'">站点设置</li>
        <li onclick="window.location.href='loginlog.php'">日志管理</li>
    </ul>
    <div class="layui-col-xs12">
        <div class="grid-demo grid-demo-bg1">
            <div class="layui-col-xs12">
                <div class="grid-demo">
                    <button class="layui-btn layui-btn-normal" id="addSiteBtn">添加站点</button>
                    <table class="layui-hide" id="demo" lay-filter="zhan_list"></table>
                </div>
            </div>
        </div>
        <script type="text/html" id="bar_btn">
            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        </script>

        <script src="../public/layui/layui.js" charset="utf-8"></script>
        <script>
            layui.use(['jquery', 'table', 'form', 'layer'], function() {
                var layer = layui.layer,
                    $ = layui.jquery,
                    table = layui.table;

                // 渲染表格
                table.render({
                    elem: '#demo',
                    height: 500,
                    url: './action.php?act=get_zhan',
                    page: true,
                    method: "POST",
                    totalRow: true,
                    toolbar: '#toolbar—list',
                    defaultToolbar: ['filter'],
                    cols: [[
                        {field: 'zhan_id', title: 'ID', width: 80, sort: true, fixed: 'left', totalRowText: '合计'},
                        {field: 'zhan_title', title: '站点标题', width: 150, edit: 'text'},
                        {field: 'zhan_url', title: '站点首页', width: 150, edit: 'text'},
                        {field: 'zhan_callback', title: '回调地址', width: 200, edit: 'text'},
                        {field: 'zhan_addtime', title: '添加时间', width: 200},
                        {fixed: 'right', title: '操作', toolbar: '#bar_btn', width: 150}
                    ]],
                    id: 'tab_Reload'
                });

                // 编辑事件
                table.on('edit(zhan_list)', function(obj) {
                    var value = obj.value //得到修改后的值
                        ,data = obj.data //得到所在行所有键值
                        ,field = obj.field; //得到字段
                    var ii = layer.load(2, {shade: [0.1, '#fff']});
                    $.ajax({
                        type: "POST",
                        url: "./action.php?act=zhan_update_byid",
                        data: {"zhan_id": data.zhan_id, "field": field, "value": value},
                        dataType: 'json',
                        success: function(data) {
                            layer.close(ii);
                            if (data.code == 1) {
                                var index = layer.alert(data.msg, {
                                    title: '提示',
                                    icon: 6
                                }, function() {
                                    layer.close(index);
                                    // window.location.reload();
                                })
                            } else {
                                layer.msg(data.msg);
                                return false;
                            }
                        },
                        error: function(data) {
                            layer.close(ii);
                            layer.msg('系统错误！');
                            return false;
                        }
                    });
                });

                // 删除事件
                table.on('tool(zhan_list)', function(obj) {
                    var data = obj.data;
                    if (obj.event === 'del') {
                        layer.confirm('确定要删除此站点吗', function(index) {
                            var zhan_id = data['zhan_id'];
                            var ii = layer.load(2, {shade: [0.1, '#fff']});
                            $.ajax({
                                type: "POST",
                                url: "./action.php?act=zhan_delete_byid",
                                data: {"zhan_id": zhan_id},
                                dataType: 'json',
                                success: function(data) {
                                    layer.close(ii);
                                    if (data.code == 1) {
                                        var index = layer.alert(data.msg, {
                                            title: '提示',
                                            icon: 6
                                        }, function() {
                                            obj.del();
                                            layer.close(index);
                                            // window.location.reload();
                                        })
                                    } else {
                                        layer.msg(data.msg);
                                        return false;
                                    }
                                },
                                error: function(data) {
                                    layer.close(ii);
                                    layer.msg('系统错误！');
                                    return false;
                                }
                            });
                        });
                    } else {
                        alert(obj.event);
                    }
                });

                // 添加站点按钮事件
                $('#addSiteBtn').on('click', function() {
                    layer.open({
                        type: 1,
                        title: '添加站点',
                        content: '<form class="layui-form" id="addSiteForm" style="padding: 20px;">' +
                            '<div class="layui-form-item">' +
                            '<label class="layui-form-label">站点标题</label>' +
                            '<div class="layui-input-block">' +
                            '<input type="text" name="zhan_title" required  lay-verify="required" placeholder="请输入站点标题" class="layui-input">' +
                            '</div></div>' +
                            '<div class="layui-form-item">' +
                            '<label class="layui-form-label">站点首页</label>' +
                            '<div class="layui-input-block">' +
                            '<input type="text" name="zhan_url" required lay-verify="required" placeholder="请输入站点首页" class="layui-input">' +
                            '</div></div>' +
                            '<div class="layui-form-item">' +
                            '<label class="layui-form-label">回调地址</label>' +
                            '<div class="layui-input-block">' +
                            '<input type="text" name="zhan_callback" required lay-verify="required" placeholder="请输入回调地址" class="layui-input">' +
                            '</div></div>' +
                            '</form>',
                        area: ['500px', '400px'],
                        btn: ['确定', '取消'],
                        yes: function(index, layero) {
                            var formData = $('#addSiteForm').serialize();
                            $.ajax({
                                type: "POST",
                                url: "./manage.php?action=add",
                                data: formData,
                                dataType: 'json',
                                success: function(response) {
                                    layer.msg(response.msg);
                                    if (response.code == 1) {
                                        table.reload('tab_Reload'); // 刷新表格
                                        layer.close(index); // 关闭弹出层
                                    }
                                },
                                error: function() {
                                    layer.msg('系统错误！');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    </div>
</div>

</body>
</html>
