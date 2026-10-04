-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: music_ovoeo_cn
-- ------------------------------------------------------
-- Server version	5.7.44-log
--
-- =====================================================
-- 默认管理员账号
-- 用户名: admin
-- 密码: 123456
-- =====================================================

DROP TABLE IF EXISTS `gui_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `qq` varchar(225) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time` varchar(225) NOT NULL,
  `sendtime` datetime NOT NULL,
  `sendip` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_configs` (
  `k` varchar(255) NOT NULL DEFAULT '',
  `v` text,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_ip` (
  `start_ip` varchar(15) NOT NULL COMMENT 'IP起始地址',
  `end_ip` varchar(15) NOT NULL COMMENT 'IP结束地址',
  `country` varchar(255) NOT NULL COMMENT '地址',
  PRIMARY KEY (`start_ip`,`end_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `gui_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '网站标题',
  `url` text COMMENT '网站链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_order` (
  `trade_no` varchar(64) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `orderid` varchar(64) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trade_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `gui_pays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_pays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `qq` char(20) DEFAULT NULL,
  `orderid` char(64) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `name` char(64) DEFAULT NULL,
  `money` decimal(6,2) NOT NULL DEFAULT '0.00',
  `type` varchar(10) DEFAULT NULL,
  `shop` varchar(225) DEFAULT NULL,
  `shopid` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `gui_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_player` (
  `id` varchar(100) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL COMMENT '播放器名称',
  `user_id` varchar(32) DEFAULT NULL COMMENT '关联用户id',
  `auto_player` int(11) DEFAULT '0' COMMENT '是否自动播放',
  `jquery` int(11) DEFAULT '0' COMMENT '是否加载jQuery插件',
  `phone_load` int(11) DEFAULT '0' COMMENT '手机端加载播放器',
  `random_player` int(11) DEFAULT '0' COMMENT '是否随机播放',
  `default_volume` int(11) DEFAULT '75' COMMENT '默认音量',
  `show_lrc` int(11) DEFAULT '1' COMMENT '是否显示歌词',
  `greeting` varchar(30) DEFAULT NULL COMMENT '欢迎语',
  `show_greeting` int(11) DEFAULT '1' COMMENT '是否显示欢迎语',
  `default_album` int(11) DEFAULT '1' COMMENT '默认专辑',
  `background` int(11) DEFAULT '1' COMMENT '模糊背景是否开启',
  `show_notes` int(11) DEFAULT '1' COMMENT '显示音符：0不显示1显示',
  `time` int(11) DEFAULT '1' COMMENT '几秒后弹出播放器',
  `switchopen` int(11) DEFAULT '1' COMMENT '是否弹出播放器',
  `showmsg` int(11) DEFAULT '0' COMMENT '桌面通知开关',
  `voice_msg` varchar(255) DEFAULT '你的域名没有通过授权,无法播放音乐' COMMENT '防盗提示语音文字',
  `plays` varchar(32) DEFAULT NULL COMMENT '总播放次数',
  `endtime` datetime NOT NULL COMMENT '最后播放时间',
  `theme` int(11) DEFAULT '1' COMMENT '播放器皮肤',
  `create_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_player_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_player_auth` (
  `player_id` varchar(32) DEFAULT NULL COMMENT '播放器id',
  `domain` varchar(32) DEFAULT NULL COMMENT '授权域名',
  `remark` varchar(32) DEFAULT NULL COMMENT '网站备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_player_song_sheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_player_song_sheet` (
  `player_id` varchar(32) DEFAULT NULL COMMENT '播放器id',
  `song_sheet_id` varchar(32) DEFAULT NULL COMMENT '歌单id',
  `taxis` int(11) DEFAULT NULL COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_plays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_plays` (
  `id` varchar(100) DEFAULT NULL,
  `player_id` varchar(32) DEFAULT NULL COMMENT '播放器id',
  `user_id` varchar(32) DEFAULT NULL COMMENT '关联用户id',
  `side` varchar(32) DEFAULT NULL COMMENT '播放客户端',
  `create_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_song`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_song` (
  `id` varchar(100) DEFAULT NULL,
  `song_id` varchar(32) DEFAULT NULL COMMENT '歌曲id',
  `song_sheet_id` varchar(32) DEFAULT NULL COMMENT '所属歌单',
  `name` varchar(100) DEFAULT NULL COMMENT '歌曲名称',
  `type` varchar(10) DEFAULT NULL COMMENT '歌曲类型',
  `album_name` varchar(100) DEFAULT NULL COMMENT '专辑名称',
  `artist_name` varchar(100) DEFAULT NULL COMMENT '歌手名称',
  `album_cover` varchar(100) DEFAULT NULL COMMENT '专辑图片',
  `location` varchar(150) DEFAULT NULL COMMENT '歌曲地址',
  `lyric` varchar(100) DEFAULT NULL COMMENT '歌词地址',
  `taxis` int(11) DEFAULT NULL COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_song_sheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_song_sheet` (
  `id` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `sheet_id` varchar(20) DEFAULT NULL,
  `user_id` varchar(32) DEFAULT NULL COMMENT '歌单所属用户',
  `status` int(11) DEFAULT '0' COMMENT '状态 1:开放 0:私密',
  `name` varchar(30) DEFAULT NULL COMMENT '歌单名称',
  `author` varchar(30) DEFAULT NULL COMMENT '歌单作者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `gui_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(225) DEFAULT NULL COMMENT '用户名',
  `password` varchar(225) DEFAULT NULL COMMENT '登陆密码',
  `qq` varchar(225) DEFAULT NULL COMMENT 'QQ号码',
  `mail` varchar(225) DEFAULT NULL COMMENT '邮箱',
  `power` int(11) DEFAULT NULL COMMENT '用户权限',
  `pie` int(11) DEFAULT '0' COMMENT '播放器额度',
  `skey` text COMMENT '登录验证密钥',
  `sid` text COMMENT '登录令牌',
  `token` text COMMENT 'QQ登录验证密钥',
  `limit2` varchar(25) NOT NULL DEFAULT '0',
  `token2` text,
  `dlip` varchar(20) DEFAULT NULL COMMENT '登录ip',
  `city` varchar(255) DEFAULT NULL COMMENT '城市',
  `time` varchar(255) DEFAULT NULL COMMENT '登录时间戳',
  `regtime` datetime DEFAULT NULL COMMENT '注册时间',
  `regip` varchar(32) DEFAULT NULL COMMENT '注册IP',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `qqlogin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qqlogin_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_token` varchar(100) NOT NULL,
  `log_openid` varchar(150) DEFAULT NULL,
  `log_callback` varchar(200) DEFAULT NULL,
  `log_nickname` varchar(50) DEFAULT NULL,
  `log_data` varchar(500) DEFAULT NULL,
  `log_time` varchar(100) DEFAULT NULL,
  `log_ip` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2523 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `qqlogin_zhan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qqlogin_zhan` (
  `zhan_id` int(11) NOT NULL AUTO_INCREMENT,
  `zhan_token` varchar(200) DEFAULT NULL,
  `zhan_userid` int(11) DEFAULT NULL,
  `zhan_qq` varchar(20) DEFAULT NULL,
  `zhan_url` varchar(100) DEFAULT NULL,
  `zhan_title` varchar(100) DEFAULT NULL,
  `zhan_callback` varchar(255) DEFAULT NULL,
  `zhan_addtime` datetime DEFAULT NULL,
  `zhan_state` int(11) DEFAULT NULL,
  PRIMARY KEY (`zhan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


-- =====================================================
-- 插入默认管理员账号
-- =====================================================
LOCK TABLES `gui_users` WRITE;
/*!40000 ALTER TABLE `gui_users` DISABLE KEYS */;
INSERT INTO `gui_users` VALUES (1,'admin','$2y$12$bsHd7f7Bih5EbvxRgIUceuyNbCAPwfbvVjlYCYlkOoSU4E0lVXliC','2963246343','2963246343@qq.com',0,99999,'7276944f1ad6e19696264e9b5ef81b25','208f376fa692c7ea00fc7d16266cf0b8','F313B6DE224765BCB32F381A907CA690','20000','543CE64052142EEBA1E6CDA4794200FC','116.171.247.89','未知地址','1728028800',NULL,'2024-09-12 19:19:36','116.171.247.89');
/*!40000 ALTER TABLE `gui_users` ENABLE KEYS */;
UNLOCK TABLES;

-- =====================================================
-- 插入默认网站配置
-- =====================================================
LOCK TABLES `gui_configs` WRITE;
/*!40000 ALTER TABLE `gui_configs` DISABLE KEYS */;
INSERT INTO `gui_configs` VALUES ('webname','笒鬼鬼音乐播放器'),('title','免费稳定的HTML悬浮播放器'),('keywords','笒鬼鬼音乐播放器,HTML5悬浮音乐播放器,网页音乐播放器,JQ音乐播放器'),('description','梨花带雨音乐播放器,HTML5悬浮音乐播放器,网页音乐播放器,JQ音乐播放器'),('regpie','5'),('piemoney','1'),('vipmoney','1'),('epay_url',''),('epay_id',''),('epay_key',''),('limit2money','1'),('reglimit2','100000'),('negativetime','90');
/*!40000 ALTER TABLE `gui_configs` ENABLE KEYS */;
UNLOCK TABLES;
