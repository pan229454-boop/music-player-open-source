<?php
error_reporting(0);
session_start();
date_default_timezone_set('Asia/Shanghai');
//设置系统的输出字符为utf-8
header('Content-Type:text/html;charset=utf-8');

//核心文件路径
define('AY_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

include AY_PATH . 'db.func.php';

$DB = init_db();

$check_Sql = "show tables like 'qqlogin_zhan'";
$check_tab = $DB->query($check_Sql)->fetch();
if (!$check_tab) {
	if (file_exists(AY_PATH . "qqlogin_install.sql")) {
		$se = 0;
		$sql = file_get_contents(AY_PATH . "qqlogin_install.sql");
		$sqllist = explode(";", $sql);
		foreach ($sqllist as $sql) {
			if ($DB->query($sql)) {
				$se++;
			}
		}
		exit("系统已为您自动安装数据库!成功执行" . $se . "条SQL,请刷新网站");
	}
}

?>