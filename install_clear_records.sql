-- =====================================================
-- 清空使用记录版本
-- 保留原数据库 music_ovoeo_cn
-- 仅清空用户产生的使用数据，保留配置和管理员账号
-- =====================================================

-- 清空聊天记录
TRUNCATE TABLE `gui_chat`;

-- 清空播放记录
TRUNCATE TABLE `gui_plays`;

-- 清空支付记录
TRUNCATE TABLE `gui_pays`;

-- 清空订单记录
TRUNCATE TABLE `gui_order`;

-- 清空QQ登录日志
TRUNCATE TABLE `qqlogin_log`;

-- 清空QQ登录状态
TRUNCATE TABLE `qqlogin_zhan`;

-- 清空用户歌曲（可选，如果有用户添加的歌曲）
TRUNCATE TABLE `gui_song`;

-- 清空用户歌单（可选，如果有用户创建的歌单）
TRUNCATE TABLE `gui_song_sheet`;

-- 重置自增ID（让新数据从1开始）
ALTER TABLE `gui_chat` AUTO_INCREMENT = 1;
ALTER TABLE `gui_plays` AUTO_INCREMENT = 1;
ALTER TABLE `gui_pays` AUTO_INCREMENT = 1;
ALTER TABLE `gui_order` AUTO_INCREMENT = 1;
ALTER TABLE `qqlogin_log` AUTO_INCREMENT = 1;
ALTER TABLE `qqlogin_zhan` AUTO_INCREMENT = 1;
ALTER TABLE `gui_song` AUTO_INCREMENT = 1;
ALTER TABLE `gui_song_sheet` AUTO_INCREMENT = 1;
