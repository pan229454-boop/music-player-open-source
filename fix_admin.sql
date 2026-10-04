-- =====================================================
-- 修复管理员账号
-- 如果登录提示"用户名不存在"，执行此脚本
-- =====================================================

-- 检查用户表是否存在，如果不存在则创建
CREATE TABLE IF NOT EXISTS `gui_users` (
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

-- 插入或更新管理员账号
INSERT INTO `gui_users` (`uid`, `username`, `password`, `qq`, `mail`, `power`, `pie`, `skey`, `sid`, `token`, `limit2`, `token2`, `dlip`, `city`, `time`, `regtime`, `regip`) 
VALUES (1, 'admin', '$2y$12$bsHd7f7Bih5EbvxRgIUceuyNbCAPwfbvVjlYCYlkOoSU4E0lVXliC', '2963246343', '2963246343@qq.com', 0, 99999, '7276944f1ad6e19696264e9b5ef81b25', '208f376fa692c7ea00fc7d16266cf0b8', 'F313B6DE224765BCB32F381A907CA690', '20000', '543CE64052142EEBA1E6CDA4794200FC', '116.171.247.89', '未知地址', '17341', '2024-09-12 19:19:36', '116.171.247.89')
ON DUPLICATE KEY UPDATE 
  `password` = '$2y$12$bsHd7f7Bih5EbvxRgIUceuyNbCAPwfbvVjlYCYlkOoSU4E0lVXliC',
  `qq` = '2963246343',
  `mail` = '2963246343@qq.com',
  `power` = 0,
  `pie` = 99999;

-- 设置自增ID从2开始（避免ID冲突）
ALTER TABLE `gui_users` AUTO_INCREMENT = 2;
