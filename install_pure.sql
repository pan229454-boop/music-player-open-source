-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: music_player
-- ------------------------------------------------------
-- Server version	5.7.44-log
--
-- 创建数据库并使用
CREATE DATABASE IF NOT EXISTS `music_player` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `music_player`;

-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: music_ovoeo_cn
-- ------------------------------------------------------
-- Server version	5.7.44-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `gui_chat`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_chat`
--

/*!40000 ALTER TABLE `gui_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_chat` ENABLE KEYS */;

--
-- Table structure for table `gui_configs`
--

DROP TABLE IF EXISTS `gui_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_configs` (
  `k` varchar(255) NOT NULL DEFAULT '',
  `v` text,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_configs`
--

/*!40000 ALTER TABLE `gui_configs` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_configs` ENABLE KEYS */;

--
-- Table structure for table `gui_ip`
--

DROP TABLE IF EXISTS `gui_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_ip` (
  `start_ip` varchar(15) NOT NULL COMMENT 'IP起始地址',
  `end_ip` varchar(15) NOT NULL COMMENT 'IP结束地址',
  `country` varchar(255) NOT NULL COMMENT '地址',
  PRIMARY KEY (`start_ip`,`end_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_ip`
--

/*!40000 ALTER TABLE `gui_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_ip` ENABLE KEYS */;

--
-- Table structure for table `gui_links`
--

DROP TABLE IF EXISTS `gui_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '网站标题',
  `url` text COMMENT '网站链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_links`
--

/*!40000 ALTER TABLE `gui_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_links` ENABLE KEYS */;

--
-- Table structure for table `gui_order`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_order`
--

/*!40000 ALTER TABLE `gui_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_order` ENABLE KEYS */;

--
-- Table structure for table `gui_pays`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_pays`
--

/*!40000 ALTER TABLE `gui_pays` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_pays` ENABLE KEYS */;

--
-- Table structure for table `gui_player`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_player`
--

/*!40000 ALTER TABLE `gui_player` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_player` ENABLE KEYS */;

--
-- Table structure for table `gui_player_auth`
--

DROP TABLE IF EXISTS `gui_player_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_player_auth` (
  `player_id` varchar(32) DEFAULT NULL COMMENT '播放器id',
  `domain` varchar(32) DEFAULT NULL COMMENT '授权域名',
  `remark` varchar(32) DEFAULT NULL COMMENT '网站备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_player_auth`
--

/*!40000 ALTER TABLE `gui_player_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_player_auth` ENABLE KEYS */;

--
-- Table structure for table `gui_player_song_sheet`
--

DROP TABLE IF EXISTS `gui_player_song_sheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_player_song_sheet` (
  `player_id` varchar(32) DEFAULT NULL COMMENT '播放器id',
  `song_sheet_id` varchar(32) DEFAULT NULL COMMENT '歌单id',
  `taxis` int(11) DEFAULT NULL COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_player_song_sheet`
--

/*!40000 ALTER TABLE `gui_player_song_sheet` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_player_song_sheet` ENABLE KEYS */;

--
-- Table structure for table `gui_plays`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_plays`
--

/*!40000 ALTER TABLE `gui_plays` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_plays` ENABLE KEYS */;

--
-- Table structure for table `gui_song`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_song`
--

/*!40000 ALTER TABLE `gui_song` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_song` ENABLE KEYS */;

--
-- Table structure for table `gui_song_sheet`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_song_sheet`
--

/*!40000 ALTER TABLE `gui_song_sheet` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_song_sheet` ENABLE KEYS */;

--
-- Table structure for table `gui_users`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gui_users`
--

/*!40000 ALTER TABLE `gui_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_users` ENABLE KEYS */;

--
-- Table structure for table `qqlogin_log`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qqlogin_log`
--

/*!40000 ALTER TABLE `qqlogin_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `qqlogin_log` ENABLE KEYS */;

--
-- Table structure for table `qqlogin_zhan`
--

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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qqlogin_zhan`
--

/*!40000 ALTER TABLE `qqlogin_zhan` DISABLE KEYS */;
/*!40000 ALTER TABLE `qqlogin_zhan` ENABLE KEYS */;

--
-- Dumping events for database 'music_ovoeo_cn'
--

--
-- Dumping routines for database 'music_ovoeo_cn'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-14 11:33:44
