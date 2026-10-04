<?php
include "comm/comm.php";
require_once("API/qqConnectAPI.php");
$qc = new QC();
$callback = $qc->qq_callback();
$openid = $qc->get_openid();
$urlCookie = base64_decode($_COOKIE["Moleft_QQLogin_CallBack"]);
$token = $_COOKIE["token"];
$arr = get_userinfo($callback,$openid);
if($arr){
	$user['nickname'] = $arr['nickname'];
	$user['figureurl_qq'] = $arr['figureurl_qq'];
	$user['figureurl_qq_2'] = $arr['figureurl_qq_2'];
	$user['gender_type'] = $arr['gender_type'];
	$user['gender'] = $arr['gender'];
}

$time  = time();
//记录日志
write_loginlog($token,$openid,$urlCookie ,$user['nickname'],$callback,$time,real_ip1());

//销毁cookie
setcookie("Moleft_QQLogin_CallBack", "", time() - 3600);
setcookie("token", "", time() - 3600);

$go = $urlCookie . '?openid=' . $openid . '&callback=' . $callback . '&time='.$time.'&' . http_build_query($user);

//回调到用户页面
header('Refresh:1;url=' . $go);
	

function base_encode($str) {
	$src = array("/", "+", "=");
	$dist = array("_a", "_b", "_c");
	$old = base64_encode($str);
	$new = str_replace($src, $dist, $old);

	return $new;
}
function base_decode($str) {
	$src = array("_a", "_b", "_c");
	$dist = array("/", "+", "=");
	$old = str_replace($src, $dist, $str);
	$new = base64_decode($old);

	return $new;
}
?>