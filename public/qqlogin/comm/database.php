<?php
return array(
	// ========= 数据库配置 =========
	'host' => '127.0.0.1', // *数据库服务器
	'port' => 3306, // *数据库端口
	'user' => 'player', // *数据库用户名
	'pwd' => 'player', // *数据库密码
	'dbname' => 'player', // *数据库名

	// ========= APP配置 =========
	'appid'=>'102109526', // *应用ID
	'appkey'=>'40iEWeXGk381B9tR', // *应用KEY
	'callback'=>'https://music.guiwl.cn/qqlogin/callback.php', //  *回调地址 与QQ互联平台一致
	
	
	// ========= 后台登陆配置 =========
	'loginpass' => '111333', //* 后台登陆地址  

	// ========= 系统其他参数 / 无需配置 =========
	'scope'=>'get_user_info', //无需修改
	'errorReport'=>true, //无需修改
	'storageType'=>'file', //无需修改
);