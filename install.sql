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

LOCK TABLES `gui_chat` WRITE;
/*!40000 ALTER TABLE `gui_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_chat` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_configs` WRITE;
/*!40000 ALTER TABLE `gui_configs` DISABLE KEYS */;
INSERT INTO `gui_configs` VALUES ('webname','笒鬼鬼音乐播放器'),('title','免费稳定的HTML悬浮播放器'),('keywords','笒鬼鬼音乐播放器,HTML5悬浮音乐播放器,网页音乐播放器,JQ音乐播放器'),('description','梨花带雨音乐播放器,HTML5悬浮音乐播放器,网页音乐播放器,JQ音乐播放器'),('regpie','5'),('piemoney','1'),('vipmoney','1'),('epay_url',''),('epay_id',''),('epay_key',''),('limit2money','1'),('reglimit2','100000'),('negativetime','90');
/*!40000 ALTER TABLE `gui_configs` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_ip` WRITE;
/*!40000 ALTER TABLE `gui_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_ip` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_links` WRITE;
/*!40000 ALTER TABLE `gui_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_links` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_order` WRITE;
/*!40000 ALTER TABLE `gui_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_order` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_pays` WRITE;
/*!40000 ALTER TABLE `gui_pays` DISABLE KEYS */;
INSERT INTO `gui_pays` VALUES (1,1,'2963246343','20240912191936681','2024-09-12 19:19:36',NULL,'1个播放器额度',10.00,'alipay','pie',1,0),(2,1,'2963246343','20240912193613578','2024-09-12 19:36:13',NULL,'1个播放器额度',0.10,'alipay','pie',1,0),(3,1,'2963246343','20240912194107850','2024-09-12 19:41:07',NULL,'1个播放器额度',0.10,'alipay','pie',1,0),(4,1,'2963246343','20240912194122214','2024-09-12 19:41:22',NULL,'1个播放器额度',0.50,'qqpay','pie',1,0),(5,1,'2963246343','20240912194519453','2024-09-12 19:45:19','2024-09-12 19:48:32','1个播放器额度',0.50,'alipay','pie',1,2),(6,1,'2963246343','20240913080824552','2024-09-13 08:08:24',NULL,'1个播放器额度',0.50,'alipay','pie',1,0),(7,3,'2705633921','20240913224446368','2024-09-13 22:44:46',NULL,'1个播放器额度',0.50,'alipay','pie',1,0),(8,3,'2705633921','20240913224458469','2024-09-13 22:44:58',NULL,'永久付费版',88.00,'alipay','vip',1,0),(9,2,'2952250494','20240914171450273','2024-09-14 17:14:50',NULL,'永久付费版',88.00,'alipay','vip',1,0),(10,5,'2181838758','20240916160004722','2024-09-16 16:00:04',NULL,'1个播放器额度',0.50,'alipay','pie',1,0),(11,1,'2963246343','20240916190734365','2024-09-16 19:07:34',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(12,1,'2963246343','20240916191105232','2024-09-16 19:11:05',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(13,1,'2963246343','20240916191125371','2024-09-16 19:11:25',NULL,'1个播放器额度',0.50,'alipay','pie',1,0),(14,1,'2963246343','20240916191145765','2024-09-16 19:11:45',NULL,'1千接口调用额度',10.00,'alipay','limit2',1,0),(15,1,'2963246343','20240916191234388','2024-09-16 19:12:34',NULL,'1个播放器额度',0.50,'alipay','pie',1,0),(16,6,'2181838759','20240916191317202','2024-09-16 19:13:17',NULL,'1千接口调用额度',10.00,'alipay','limit2',1,0),(17,1,'2963246343','20240916193401426','2024-09-16 19:34:01',NULL,'1千接口调用额度',10.00,'alipay','limit2',1,0),(18,6,'2181838759','20240916193510270','2024-09-16 19:35:10',NULL,'永久付费版',88.00,'alipay','vip',1,0),(19,6,'2181838759','20240916193527719','2024-09-16 19:35:27',NULL,'永久付费版',88.00,'wxpay','vip',1,0),(20,1,'2963246343','20240916193758498','2024-09-16 19:37:58',NULL,'1千接口调用额度',0.10,'wxpay','limit2',1,0),(21,1,'2963246343','20240916194757586','2024-09-16 19:47:57',NULL,'1千接口调用额度',0.10,'qqpay','limit2',1,0),(22,1,'2963246343','20240916195449776','2024-09-16 19:54:49','2024-09-16 19:55:24','1千接口调用额度',0.10,'qqpay','limit2',1,2),(23,1,'2963246343','20240916195703195','2024-09-16 19:57:03','2024-09-16 19:57:20','1千接口调用额度',0.10,'qqpay','limit2',1,2),(24,1,'2963246343','20240916232519602','2024-09-16 23:25:19',NULL,'1个播放器额度',0.50,'alipay','pie',1,0),(25,1,'2963246343','20240916232524674','2024-09-16 23:25:24',NULL,'1个播放器额度',0.50,'wxpay','pie',1,0),(26,1,'2963246343','20240916232527352','2024-09-16 23:25:27','2024-09-16 23:25:46','1个播放器额度',0.50,'qqpay','pie',1,2),(27,1,'2963246343','20240917095142449','2024-09-17 09:51:42','2024-09-17 09:52:03','1千接口调用额度',0.10,'qqpay','limit2',1,2),(28,1,'2963246343','20240919114146331','2024-09-19 11:41:46',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(29,1,'2963246343','20240919114753245','2024-09-19 11:47:53',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(30,1,'2963246343','20240919115104692','2024-09-19 11:51:04',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(31,1,'2963246343','20240919115116303','2024-09-19 11:51:16',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(32,1,'2963246343','20240919154058221','2024-09-19 15:40:58',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(33,1,'2963246343','20240919154102852','2024-09-19 15:41:02',NULL,'1个播放器额度',5.00,'alipay','pie',1,0),(34,1,'2963246343','20240919154106519','2024-09-19 15:41:06',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(35,1,'2963246343','20240919154652199','2024-09-19 15:46:52',NULL,'1千接口调用额度',1.00,'qqpay','limit2',1,0),(36,1,'2963246343','20240919154803629','2024-09-19 15:48:03',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(37,1,'2963246343','20240919154808566','2024-09-19 15:48:08',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(38,1,'2963246343','20240919155121826','2024-09-19 15:51:21',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(39,1,'2963246343','20240919155127225','2024-09-19 15:51:27',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(40,1,'2963246343','20240919225434476','2024-09-19 22:54:34',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(41,1,'2963246343','20240919225444147','2024-09-19 22:54:44',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(42,1,'2963246343','20240920003850848','2024-09-20 00:38:50',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(43,1,'2963246343','20240920004823325','2024-09-20 00:48:23',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(44,1,'2963246343','20240920005106334','2024-09-20 00:51:06',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(45,1,'2963246343','20240920012150850','2024-09-20 01:21:50',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(46,1,'2963246343','20240920012242154','2024-09-20 01:22:42',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(47,1,'2963246343','20240920163330304','2024-09-20 16:33:30',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(48,1,'2963246343','20240920164009137','2024-09-20 16:40:09',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(49,1,'2963246343','20240920164015910','2024-09-20 16:40:15',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(50,1,'2963246343','20240920200410178','2024-09-20 20:04:10',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(51,1,'2963246343','20240920201915907','2024-09-20 20:19:15',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(52,1,'2963246343','20240920201923496','2024-09-20 20:19:23',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(53,1,'2963246343','20240920202134206','2024-09-20 20:21:34',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(54,1,'2963246343','20240920202143462','2024-09-20 20:21:43',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(55,1,'2963246343','20240920202151323','2024-09-20 20:21:51',NULL,'1千接口调用额度',1.00,'qqpay','limit2',1,0),(56,1,'2963246343','20240922002546950','2024-09-22 00:25:46','2024-09-22 00:26:21','1千接口调用额度',1.00,'wxpay','limit2',1,2),(57,1,'2963246343','20240923023839208','2024-09-23 02:38:39',NULL,'1个播放器额度',5.00,'wxpay','pie',1,0),(58,1,'2963246343','20240923023906393','2024-09-23 02:39:06','2024-09-23 02:39:34','1千接口调用额度',1.00,'wxpay','limit2',1,2),(59,1,'2963246343','20240923024004290','2024-09-23 02:40:04',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(60,1,'2963246343','20240924001327880','2024-09-24 00:13:27',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(61,1,'2963246343','20240924001351719','2024-09-24 00:13:51',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(62,1,'2963246343','20240924001438290','2024-09-24 00:14:38','2024-09-24 00:15:18','1千接口调用额度',1.00,'wxpay','limit2',1,2),(63,1,'2963246343','20240926063417695','2024-09-26 06:34:17',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(64,1,'2963246343','20240926063424252','2024-09-26 06:34:24',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(65,1,'2963246343','20240926063454876','2024-09-26 06:34:54','2024-10-03 13:30:44','1千接口调用额度',0.01,'wxpay','limit2',1,2),(66,1,'2963246343','20240928015635968','2024-09-28 01:56:35','2024-09-28 01:57:05','1千接口调用额度',0.01,'wxpay','limit2',1,2),(67,1,'2963246343','20240928022816645','2024-09-28 02:28:16',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(68,1,'2963246343','20240928211130651','2024-09-28 21:11:30',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(69,5,'2181838758','20241003123606374','2024-10-03 12:36:06',NULL,'1千接口调用额度',1.00,'qqpay','limit2',1,0),(70,5,'2181838758','20241003123618186','2024-10-03 12:36:18',NULL,'1千接口调用额度',1.00,'wxpay','limit2',1,0),(71,5,'2181838758','20241003123623978','2024-10-03 12:36:23',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(72,5,'2181838758','20241003123640274','2024-10-03 12:36:40',NULL,'1个播放器额度',5.00,'wxpay','pie',1,0),(73,5,'2181838758','20241003123645474','2024-10-03 12:36:45',NULL,'1千接口调用额度',1.00,'qqpay','limit2',1,0),(74,5,'2181838758','20241003124033812','2024-10-03 12:40:33',NULL,'1千接口调用额度',1.00,'alipay','limit2',1,0),(75,1,'2963246343','20241003125323206','2024-10-03 12:53:23','2024-10-03 12:53:42','1千接口调用额度',1.00,'alipay','limit2',1,2),(76,1,'2963246343','20241003130454328','2024-10-03 13:04:54','2024-10-03 13:05:09','1千接口调用额度',1.00,'alipay','limit2',1,2),(77,1,'2963246343','20241003130551798','2024-10-03 13:05:51','2024-10-03 13:06:01','1千接口调用额度',1.00,'alipay','limit2',1,2),(78,1,'2963246343','20241003130649465','2024-10-03 13:06:49','2024-10-03 13:07:01','1千接口调用额度',1.00,'alipay','limit2',1,2),(79,5,'2181838758','20241003131223543','2024-10-03 13:12:23','2024-10-03 13:12:36','1千接口调用额度',0.01,'alipay','limit2',1,2),(80,5,'2181838758','20241003131339175','2024-10-03 13:13:39','2024-10-03 13:14:12','1千接口调用额度',0.01,'qqpay','limit2',1,2),(81,5,'2181838758','20241003131425572','2024-10-03 13:14:25',NULL,'1千接口调用额度',0.01,'wxpay','limit2',1,0),(82,5,'2181838758','20241003131459640','2024-10-03 13:14:59',NULL,'1个播放器额度',5.00,'wxpay','pie',1,0),(83,1,'2963246343','20241003131534265','2024-10-03 13:15:34',NULL,'1千接口调用额度',0.01,'wxpay','limit2',1,0),(84,5,'2181838758','20241003131815848','2024-10-03 13:18:15','2024-10-03 13:18:30','1千接口调用额度',0.01,'alipay','limit2',1,2),(85,1,'2963246343','20241003131829589','2024-10-03 13:18:29',NULL,'1千接口调用额度',0.01,'alipay','limit2',1,0),(86,1,'2963246343','20241003132050609','2024-10-03 13:20:50',NULL,'1千接口调用额度',0.01,'qqpay','limit2',1,0),(87,1,'2963246343','20241003132330303','2024-10-03 13:23:30',NULL,'1千接口调用额度',0.01,'alipay','limit2',1,0),(88,1,'2963246343','20241003132530468','2024-10-03 13:25:30',NULL,'1千接口调用额度',0.01,'alipay','limit2',1,0),(89,1,'2963246343','20241003132858595','2024-10-03 13:28:58',NULL,'1千接口调用额度',0.01,'alipay','limit2',1,0),(90,1,'2963246343','20241003133104778','2024-10-03 13:31:04',NULL,'1个播放器额度',5.00,'wxpay','pie',1,0),(91,1,'2963246343','20241003133112803','2024-10-03 13:31:12','2024-10-03 13:31:24','1个播放器额度',5.00,'qqpay','pie',1,2),(92,1,'2963246343','20241003133614635','2024-10-03 13:36:14',NULL,'1千接口调用额度',0.01,'alipay','limit2',1,0),(93,1,'2963246343','20241003133829514','2024-10-03 13:38:29',NULL,'1千接口调用额度',0.01,'alipay','limit2',1,0),(94,1,'2963246343','20241003142720223','2024-10-03 14:27:20','2024-10-03 14:27:51','1千接口调用额度',0.01,'alipay','limit2',1,2),(95,1,'2963246343','20241003142910353','2024-10-03 14:29:10','2024-10-03 14:29:26','1千接口调用额度',0.01,'alipay','limit2',1,2),(96,1,'2963246343','20241003143231750','2024-10-03 14:32:31','2024-10-03 14:32:45','1千接口调用额度',0.01,'alipay','limit2',1,2),(97,1,'2963246343','20241003143653824','2024-10-03 14:36:53',NULL,'1个播放器额度',5.00,'wxpay','pie',1,0),(98,1,'2963246343','20241003143658582','2024-10-03 14:36:58','2024-10-03 14:37:10','1个播放器额度',5.00,'alipay','pie',1,2),(99,1,'2963246343','20241003144349497','2024-10-03 14:43:49','2024-10-03 14:44:05','1千接口调用额度',0.01,'alipay','limit2',1,2),(100,1,'2963246343','20241003144545883','2024-10-03 14:45:45','2024-10-03 14:45:59','1千接口调用额度',0.01,'alipay','limit2',1,2),(101,5,'2181838758','20241003144745115','2024-10-03 14:47:45','2024-10-03 14:48:08','1千接口调用额度',0.01,'alipay','limit2',1,2),(102,1,'2963246343','20241003165956199','2024-10-03 16:59:56',NULL,'1千接口调用额度',0.01,'alipay','limit2',1,0),(103,1,'2963246343','20241003170033872','2024-10-03 17:00:33',NULL,'五千接口调用额度',0.01,'alipay','limit2',1,0),(104,1,'2963246343','20241004015448600','2024-10-04 01:54:48',NULL,'五千接口调用额度',1.00,'qqpay','limit2',1,0),(105,1,'2963246343','20241004015620618','2024-10-04 01:56:20',NULL,'五千接口调用额度',1.00,'qqpay','limit2',1,0),(106,1,'2963246343','20241004015648312','2024-10-04 01:56:48',NULL,'五千接口调用额度',1.00,'qqpay','limit2',1,0),(107,1,'2963246343','20241004015821572','2024-10-04 01:58:21','2024-10-04 01:59:01','五千接口调用额度',1.00,'alipay','limit2',1,2),(108,1,'2963246343','20241004015928777','2024-10-04 01:59:28','2024-10-04 01:59:37','五千接口调用额度',1.00,'alipay','limit2',1,2),(109,1,'2963246343','20241012154236602','2024-10-12 15:42:36','2024-10-12 15:43:04','五千接口调用额度',1.00,'alipay','limit2',1,2),(110,1,'2963246343','20241021181534991','2024-10-21 18:15:34',NULL,'五千接口调用额度',1.00,'alipay','limit2',1,0),(111,1,'2963246343','20241021181540312','2024-10-21 18:15:40',NULL,'五千接口调用额度',1.00,'wxpay','limit2',1,0);
/*!40000 ALTER TABLE `gui_pays` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_player` WRITE;
/*!40000 ALTER TABLE `gui_player` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_player` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_player_auth` WRITE;
/*!40000 ALTER TABLE `gui_player_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_player_auth` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_player_song_sheet` WRITE;
/*!40000 ALTER TABLE `gui_player_song_sheet` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_player_song_sheet` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_plays` WRITE;
/*!40000 ALTER TABLE `gui_plays` DISABLE KEYS */;
INSERT INTO `gui_plays` VALUES (NULL,'6756cf54e41b0','1','ios','2024-12-09 19:47:23'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 19:47:50'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 19:52:56'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:05:44'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:08:30'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:09:48'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:10:28'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:11:24'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:13:02'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:13:14'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:13:56'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:14:57'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:15:44'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:26:36'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:27:23'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 20:28:07'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:10:33'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:11:56'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:12:44'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:13:36'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:13:49'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:18:25'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:19:11'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:35:04'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:38:40'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 21:47:08'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 22:02:20'),(NULL,'6756cf54e41b0','1','ios','2024-12-09 22:04:06'),(NULL,'6756cf54e41b0','1','ios','2024-12-11 14:50:19'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 10:55:04'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 10:56:16'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 10:57:36'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 11:02:10'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 11:11:37'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 11:11:55'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 11:11:58'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 11:12:01'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 11:12:05'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 11:13:41'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 11:56:30'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 12:27:58'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 12:28:04'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 12:28:09'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 12:28:11'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 12:29:10'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 12:32:42'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 12:36:18'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 12:59:04'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 13:27:31'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 13:33:26'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 14:22:48'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 15:44:06'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 15:53:56'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 16:01:16'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 19:01:31'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 19:01:35'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 19:41:32'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 20:32:08'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 21:44:52'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 21:45:00'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 21:58:16'),(NULL,'6756cf54e41b0','1','ios','2024-12-12 21:58:20'),(NULL,'6756cf54e41b0','1','ios','2024-12-13 00:20:40'),(NULL,'6756cf54e41b0','1','ios','2024-12-13 00:23:56'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 01:09:38'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 01:09:41'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 07:47:49'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 07:50:50'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 11:47:45'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 12:01:42'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 12:03:24'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 12:32:51'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 16:41:09'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 16:43:44'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 16:46:40'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 17:07:29'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 17:08:06'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 17:11:13'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 17:17:07'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 17:23:48'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 18:22:55'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 18:22:59'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 18:23:04'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 18:51:16'),(NULL,'6756cf54e41b0','1','ios','2024-12-14 19:32:20');
/*!40000 ALTER TABLE `gui_plays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gui_song`
--

DROP TABLE IF EXISTS `gui_song`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gui_song` (
  `id` varchar(100) DEFAULT NULL,
  `song_id` varchar(32) DEFAULT NULL COMMENT '歌曲id',
  `music_source` varchar(64) NOT NULL DEFAULT 'legacy',
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

LOCK TABLES `gui_song` WRITE;
/*!40000 ALTER TABLE `gui_song` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_song` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_song_sheet` WRITE;
/*!40000 ALTER TABLE `gui_song_sheet` DISABLE KEYS */;
/*!40000 ALTER TABLE `gui_song_sheet` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `gui_users` WRITE;
/*!40000 ALTER TABLE `gui_users` DISABLE KEYS */;
INSERT INTO `gui_users` VALUES (1,'admin','$2y$12$bsHd7f7Bih5EbvxRgIUceuyNbCAPwfbvVjlYCYlkOoSU4E0lVXliC','2963246343','2963246343@qq.com',0,99999,'7276944f1ad6e19696264e9b5ef81b25','208f376fa692c7ea00fc7d16266cf0b8','F313B6DE224765BCB32F381A907CA690','20000','543CE64052142EEBA1E6CDA4794200FC','116.171.247.89','未知地址','1734172332','2021-03-01 00:00:00','127.0.0.1');
/*!40000 ALTER TABLE `gui_users` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `qqlogin_log` WRITE;
/*!40000 ALTER TABLE `qqlogin_log` DISABLE KEYS */;
INSERT INTO `qqlogin_log` VALUES (2480,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726851439','223.89.159.165'),(2481,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726851469','223.89.159.165'),(2482,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726851666','223.89.159.165'),(2483,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726851781','223.89.159.165'),(2484,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726852187','223.89.159.165'),(2485,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726852380','223.89.159.165'),(2486,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726898192','223.89.159.103'),(2487,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726898375','223.89.159.103'),(2488,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726936130','223.89.159.103'),(2489,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','AC4D9666E0D0A5E8BD1755479426C40F','1726936159','223.89.159.103'),(2490,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1726980978','223.89.159.103'),(2491,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727459781','223.89.154.9'),(2492,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727460248','106.33.188.5'),(2493,'','E38D4C9605873F89FCB21C80CE548A6F','http://tp8.1.qsdurl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727463914','223.89.154.9'),(2494,'','E38D4C9605873F89FCB21C80CE548A6F','http://tp8.1.qsdurl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727464874','223.89.154.9'),(2495,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727466778','223.89.154.9'),(2496,'','E38D4C9605873F89FCB21C80CE548A6F','http://1.94.235.213/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727468340','223.89.154.9'),(2497,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727472598','106.33.188.5'),(2498,'','E38D4C9605873F89FCB21C80CE548A6F','https://cs.guiwl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727514228','223.89.154.9'),(2499,'','E38D4C9605873F89FCB21C80CE548A6F','https://cs.guiwl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727514754','223.89.154.9'),(2500,'','E38D4C9605873F89FCB21C80CE548A6F','http://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727540170','223.89.154.9'),(2501,'','CB8FBDB688409F4D5658C83288A99FBC','https://music.mqywl.cn/Admin/login_callback','轩攸','F8692D1F910C12C1DCCE9371B8094D14','1727670051','140.255.71.106'),(2502,'','E38D4C9605873F89FCB21C80CE548A6F','http://cs.guiwl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684171','223.89.154.9'),(2503,'','E38D4C9605873F89FCB21C80CE548A6F','http://cs.guiwl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684203','223.89.154.9'),(2504,'','E38D4C9605873F89FCB21C80CE548A6F','http://cs.guiwl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684266','223.89.154.9'),(2505,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684317','223.89.154.9'),(2506,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684391','223.89.154.9'),(2507,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684594','223.89.154.9'),(2508,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684716','223.89.154.9'),(2509,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684931','223.89.154.9'),(2510,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727684975','223.89.154.9'),(2511,'','BCFE6A3878A5D07EF91112A8AA851796','https://music.mqywl.cn/Admin/login_callback','ネる゛','27CCBA623C491D82E29BACECADB114A9','1727743110','113.13.135.255'),(2512,'','EF294586617099DDD839B38B585A3A15','https://music.mqywl.cn/Admin/login_callback','酷侠','619D7082ED5AB47FCBEBE6F753857161','1727766172','117.187.122.159'),(2513,'','48B436EDDEB6CB24AFBEFF9D71488E00','https://music.mqywl.cn/Admin/login_callback','霍','7E363298583163C34C5C1E6F1AEE53E0','1727767530','116.31.249.149'),(2514,'','48B436EDDEB6CB24AFBEFF9D71488E00','https://music.mqywl.cn/Admin/login_callback','霍','7E363298583163C34C5C1E6F1AEE53E0','1727767922','116.31.249.149'),(2515,'','EF294586617099DDD839B38B585A3A15','https://music.mqywl.cn/Admin/login_callback','酷侠','619D7082ED5AB47FCBEBE6F753857161','1727793228','117.188.83.120'),(2516,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727798836','223.89.154.47'),(2517,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727798890','223.89.154.47'),(2518,'','E38D4C9605873F89FCB21C80CE548A6F','https://music.mqywl.cn/Admin/login_callback','  ','0EAF0A0C1A4C87E37F9613B286A7FC65','1727799407','223.89.154.47'),(2519,'','F313B6DE224765BCB32F381A907CA690','https://music.mqywl.cn/Admin/login_callback','笒鬼鬼','3D0108C2116C28085E6959845191A71A','1730403844','121.12.162.142'),(2520,'','E38D4C9605873F89FCB21C80CE548A6F','https://cs.guiwl.cn/Admin/login_callback','  ','506BBD86B7187566DCE7BBF76964F325','1731212313','223.89.159.42'),(2521,'','EF294586617099DDD839B38B585A3A15','https://music.mqywl.cn/Admin/login_callback','酷侠','619D7082ED5AB47FCBEBE6F753857161','1731691415','111.85.3.46'),(2522,'','EF294586617099DDD839B38B585A3A15','https://music.mqywl.cn/Admin/login_callback','酷侠','619D7082ED5AB47FCBEBE6F753857161','1731733634','111.85.3.46');
/*!40000 ALTER TABLE `qqlogin_log` ENABLE KEYS */;
UNLOCK TABLES;

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

LOCK TABLES `qqlogin_zhan` WRITE;
/*!40000 ALTER TABLE `qqlogin_zhan` DISABLE KEYS */;
INSERT INTO `qqlogin_zhan` VALUES (2,'085161FE34CF165C71786BEA73F8FE83',NULL,'24677102','你的网站首页','笒鬼鬼播放器','https://music.mqywl.cn/index/index/QqLogin_Callback','2020-07-12 20:37:22',1);
/*!40000 ALTER TABLE `qqlogin_zhan` ENABLE KEYS */;
UNLOCK TABLES;

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
