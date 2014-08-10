-- DROP DATABASE `aoxpro_zcgd`;
CREATE DATABASE `aoxpro_haier`;

USE `aoxpro_haier`;

CREATE TABLE `user` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT '用户自增ID',
  `name` varchar(32) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `phone` varchar(32) NOT NULL COMMENT '手机号',
  `email` varchar(32) NOT NULL COMMENT 'email',
  `weibo_id` varchar(64) DEFAULT NULL COMMENT '微博用户ID, s_%s 新浪; t_%s 腾讯',
  `weibo_name` varchar(64) DEFAULT NULL COMMENT '微博用户名',
  `total_score` bigint NOT NULL DEFAULT 3000 COMMENT '用户总得分',
  `level` smallint NOT NULL DEFAULT '0' COMMENT '用户当前到达难度，0-5',
  `login_time` bigint NOT NULL DEFAULT '0' COMMENT '登录时间',
  `create_time` bigint NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `weibo_id_idx` (`weibo_id`),
  UNIQUE KEY `name_idx` (`name`),
  KEY `create_time_idx` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息表'; 

CREATE TABLE `qualified_user` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `weibo_id` varchar(64) NOT NULL COMMENT '微博用户ID, s_%s 新浪; t_%s 腾讯',
  `weibo_name` varchar(64) NOT NULL DEFAULT '微博用户' COMMENT '微博用户名',
  `weibo_passport` varchar(64) NOT NULL DEFAULT '微博用户' COMMENT '微博账户名',
  `done_times` bigint NOT NULL DEFAULT '1' COMMENT '当天完成游戏次数', 
  `date_time` bigint NOT NULL DEFAULT '0' COMMENT '用户参与游戏日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_user_idx` (`weibo_id`, `date_time`),
  KEY `date_time_idx` (`date_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖资格表'; 

CREATE TABLE `user_feeds` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `weibo_id` varchar(64) NOT NULL COMMENT '微博用户ID, s_%s 新浪; t_%s 腾讯',
  `weibo_name` varchar(64) NOT NULL DEFAULT '微博用户' COMMENT '微博用户名',
  `weibo_face` varchar(64) NOT NULL DEFAULT '微博用户' COMMENT '微博用户名',
  `feeds_pic` varchar(64) NOT NULL DEFAULT '' COMMENT '微博配图',
  `feeds_detail` varchar(1024) NOT NULL DEFAULT '1' COMMENT '微博内容', 
  `platform` varchar(16) NOT NULL COMMENT '微博平台, tencent 腾讯; sina 腾讯',
  `create_time` bigint NOT NULL DEFAULT '0' COMMENT '用户参与游戏日期',
  PRIMARY KEY (`id`),
  KEY `create_time_idx` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户微博表'; 

CREATE TABLE `action_log` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT '流水ID',
  `weibo_id` bigint NOT NULL DEFAULT '0' COMMENT '用户ID',
  `action_name` varchar(16) NOT NULL DEFAULT '' COMMENT '动作名',
  `action_body` text NOT NULL DEFAULT '' COMMENT '动作内容',
  `action_time` bigint NOT NULL DEFAULT '0' COMMENT '动作时间',
  PRIMARY KEY (`id`),
  KEY `weibo_id_idx` (`weibo_id`),
  KEY `action_time_idx` (`action_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户动作日志';

CREATE TABLE `pv_stat` (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `count` bigint NOT NULL DEFAULT 0 COMMENT '值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='访问人数';

