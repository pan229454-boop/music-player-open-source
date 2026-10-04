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
DROP TABLE IF EXISTS `gui_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_pays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_player_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_player_song_sheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_plays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_song`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_song_sheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `gui_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `qqlogin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
DROP TABLE IF EXISTS `qqlogin_zhan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;

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
