<?php
if (!empty($_GET['action']) && $_GET['action'] == "login") {
		if ($_POST['admin_login'] === LOGINPASS) {
			$_SESSION['login'] = 1;
			exit('<script>alert("成功");window.location.href="./manage.php"</script>');
		} else {
			$_SESSION['login'] = 0;
			exit('<script>alert("错误");window.location.href="./manage.php"</script>');
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="../public/layui/css/layui.css"  media="all">
</head>
<body style="">


<div style="padding: 20px; background-color: #F2F2F2;">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-body">
                  <form class="layui-form"  action="?action=login" method="POST">
                  <div class="layui-form-item">
                    <div class="layui-input-block">
                      <input type="text" name="admin_login" lay-verify="title" autocomplete="off" placeholder="" class="layui-input">
                    </div>
                  </div>
                   <div class="layui-form-item">
    <div class="layui-input-block">
      <button type="submit" class="layui-btn layui-btn-normal" style="width: 100%;" lay-submit="" >提交</button>
    </div>
  </div>


</body>
</html>