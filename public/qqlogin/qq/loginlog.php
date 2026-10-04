<?php
include "../comm/comm.php";
if (!empty($_GET['action']) && $_GET['action'] == "exit") {
	$_SESSION['login'] = 0;
}
if (empty($_SESSION['login']) || $_SESSION['login'] == 0) {
	include "login.php";
exit;
}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>登陆日志</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../public/layui/css/layui.css"  media="all">
</head>
<body style="">

<div class="layui-row" style="width: 80%;margin:0 auto;margin-top: 20px; ">
  <ul class="layui-tab-title">
    <li  onclick="window.location.href='manage.php'">站点设置</li>
    <li class="layui-this" onclick="window.location.href='loginlog.php'">日志管理</li>
  </ul>
 
    <div class="layui-col-xs12">
      <div class="grid-demo">


      	<table class="layui-hide" id="demo" lay-filter="zhan_list"></table>
      </div>
    </div>

    </div>
 <script type="text/html" id="toolbar—list">
                    <div class="layui-btn-container">
                        <button class="layui-btn layui-btn-normal layui-btn-sm" style="background-color: #4876FF" onclick="javascript:window.location.reload() ;">刷新</button>
                        <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="all_delete">删除全部</button>
                    </div>
                </script>

<script src="../public/layui/layui.js" charset="utf-8"></script>
<script>
	  layui.use([ 'jquery', 'table', 'form','layer', 'laypage',   'element'], function() {
                    var  layer = layui.layer,
                        $ = layui.jquery;
                        var table = layui.table;
table.render({
			    elem: '#demo'
			    ,height: 500
			    ,url: './action.php?act=get_loginlog'
			    ,page: true
			    ,method:"POST"
			    ,toolbar:'#toolbar—list'
			
			    ,cols: [[
			      {field: 'log_id', title: 'ID', width:80, sort: true, fixed: 'left'}
			      ,{field: 'log_openid', title: 'openid', width:150,edit: 'text'}
			      ,{field: 'log_callback', title: '回调地址', width:150,edit: 'text'}
			       ,{field: 'log_nickname', title: 'QQ昵称', width:200,edit: 'text'}
			      ,{field: 'log_data', title: '登陆数据', width: 200}
            ,{field: 'log_time', title: '登陆时间', width: 200}
            ,{field: 'log_ip', title: '登陆IP', width: 200}
			     
			    ]]
			    ,id: 'tab_Reload'

			  });

          table.on('toolbar(zhan_list)', function(obj){
          switch(obj.event){
          case 'all_delete':
            var ii = layer.load(2, {shade:[0.1,'#fff']});
              $.ajax({
                 type : "POST",
                         url : "./action.php?act=all_delete",
                         data :{},
                         dataType : 'json',
                            success : function(data) {
                                     layer.close(ii);
                                    if(data.code == 1){
                                          var index = layer.alert(data.msg, {
                                            title: '提示'
                                                , icon:6},function(){
                                                  layer.close(index);
                                               window.location.reload();
                                       })
                                    }else{
                                            layer.msg(data.msg);
                                            return false;
                                    }
                            },
                            error:function(data){
                                     layer.close(ii);
                                    layer.msg('系统错误');
                                    return false;
                                    }
                    })
            break;
         
          };
        });
  });
</script>

</body>
</html>