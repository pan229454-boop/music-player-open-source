<?php
function insert($table, $data) {
	global $DB;
	foreach ($data as $k => $v) {
		$fields[] = $v;
		$keys[] = $k;
	}
	$values = "('" . implode("','", $fields) . "')";
	$column = "(`" . implode("`,`", $keys) . "`)";
	$sql = "insert into {$table} {$column} values {$values}";
	//exit(htmlentities($sql));
	$cid = $DB->exec($sql);
	return $cid;
}
function aysafe_replace($string) {
	$string = str_replace('shell', '', $string);
	$string = str_replace('php', '', $string);
	$string = str_replace('bak', '', $string);
	$string = str_replace('$', '', $string);
	$string = str_replace('echo', '', $string);
	$string = str_replace('cookie', '', $string);
	$string = str_replace('eval', '', $string);
	$string = str_replace('encode', '', $string);
	$string = str_replace('_post', '', $string);
	$string = str_replace('decode', '', $string);
	$string = str_replace('_POST', '', $string);
	$string = str_replace('_get', '', $string);
	$string = str_replace('_GET', '', $string);
	$string = str_replace(' ', '', $string);
	$string = str_replace('%20', '', $string);
	$string = str_replace('%27', '', $string);
	$string = str_replace('%2527', '', $string);
	$string = str_replace('*', '', $string);
	$string = str_replace('"', '', $string);
	$string = str_replace("'", '', $string);
	$string = str_replace(';', '', $string);
	$string = str_replace('<', '&lt;', $string);
	$string = str_replace('>', '&gt;', $string);
	$string = str_replace("{", '', $string);
	$string = str_replace('}', '', $string);
	$string = str_replace('\\', '', $string);
	return $string;
}

function init_db() {
	@header('Content-Type: text/html; charset=UTF-8');
	if (!file_exists(AY_PATH . "database.php")) {
		exit("您还未安装AY-QQLOGIN!");
	}
	$dbconfig = include AY_PATH . "database.php";
	if ($dbconfig['user'] == "" || $dbconfig['pwd'] == "" || $dbconfig['dbname'] == "") {
		exit("您还未安装AY-QQLOGIN!");
	}
	try {
		define("LOGINPASS", $dbconfig['loginpass']);
		$DB = new PDO("mysql:host={$dbconfig['host']};dbname={$dbconfig['dbname']};port={$dbconfig['port']}", $dbconfig['user'], $dbconfig['pwd']);
	} catch (Exception $e) {
		exit('链接数据库失败:' . $e->getMessage());
	}
	$DB->exec("set names utf8");
	return $DB;
}
function get_userinfo($callback,$openid){
	$config = include  "comm/database.php";
	$arr = file_get_contents("https://graph.qq.com/user/get_user_info?oauth_consumer_key=".$config["appid"]."&access_token=".$callback ."&openid=".$openid);
	$arr = (array)json_decode($arr);
	if($arr){
		return $arr;
	}else{
		return null;
	}
}
function write_loginlog($token,$openid,$callback,$nickname,$log_data,$time,$ip){
	global $DB;
	$sql = "insert into qqlogin_log(`log_token`,`log_openid`,`log_callback`,`log_nickname`,`log_data`,`log_time`,`log_ip`) 
	value('$token','$openid','$callback','$nickname','$log_data','$time','$ip')";
	if($DB->query($sql)){
		return 1;
	}else{
		return 0;
	}
}
function check_login($log_token,$log_openid,$log_time){
	if($log_time < time()-180){
		exit('{"code":-1,"msg":"验证失败,建议您重新登陆!"}');
	}
	global $DB;
	$sql = "select log_id from qqlogin_log where log_token = '$log_token' and log_openid = '$log_openid' and log_time = '$log_time' limit 1";
	if($DB->query($sql)->fetch()){
		return 1;
	}else{
		return 0;
	}
}

/*
 * 获取真实ip地址
 */
function real_ip1() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
}
?>