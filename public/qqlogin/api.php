<?php
header("Content-type: text/html; charset=utf-8"); 
if(empty($_GET['callback']) || $_GET['callback'] == ""){
	exit('{"code":-1,"msg":"请输入有效的callback"}');
}
include "comm/comm.php";
$callback = $_GET['callback'] ;
if(!empty($_GET['check'])){
	$openid = $_GET['openid'];
	$time = $_GET['time'];
	if(check_login($callback,$openid,$time)){
		exit('{"code":1,"msg":"验证成功"}');
	}else{
		exit('{"code":-1,"msg":"验证失败"}');
	}
}
?>
<html>
<head>
<title>QQ登录</title>
</head>
<?php
if($callback != ""){
$urlCookie = base64_encode($callback);
setcookie("Moleft_QQLogin_CallBack",$urlCookie);
setcookie("token",$token);
require_once("API/qqConnectAPI.php");
$qc = new QC();
$qc->qq_login();
}
else{
exit('{"code":-1,"msg":"您还未填写回调地址"}');
}
?>
</html>