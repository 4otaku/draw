SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `<pr>helper`;
CREATE TABLE `<pr>helper` (
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `<pr>helper` (`id`) VALUES (1);

DROP TABLE IF EXISTS `<pr>meta`;
CREATE TABLE `<pr>meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Tags only',
  `area` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Categories only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identity` (`type`,`alias`,`area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>meta_count`;
CREATE TABLE IF NOT EXISTS `<pr>meta_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `module` varchar(16) NOT NULL,
  `area` varchar(16) NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`type`,`alias`,`module`,`area`),
  KEY `list_active_tags` (`type`,`count`),
  KEY `list_large_tags` (`type`,`module`,`area`,`count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>tag_variants`;
CREATE TABLE `<pr>tag_variants` (
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `variant` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `language` enum('','eng','jap','rus')  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE KEY `variant` (`variant`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `<pr>art`;
CREATE TABLE `<pr>art` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `weight` mediumint(8) unsigned NOT NULL,
  `resized` float unsigned NOT NULL,
  `extension` varchar(6) COLLATE utf8_general_ci NOT NULL,
  `thumbnail` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `meta` text COLLATE utf8_general_ci NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `description` text character set utf8 NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` enum('main','deleted') COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`),
  FULLTEXT KEY `index` (`meta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>comment`;
CREATE TABLE `<pr>comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `root` int(10) unsigned NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  `place` varchar(32) character set utf8 NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `area` varchar(32) character set utf8 NOT NULL,
  `username` varchar(256) character set utf8 NOT NULL default 'Анонимно',
  `email` varchar(256) character set utf8 NOT NULL default 'default@avatar.mail',
  `ip` bigint(20) NOT NULL,
  `cookie` varchar(32) character set utf8 NOT NULL,
  `text` text character set utf8 NOT NULL,
  `pretty_text` text character set utf8 NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `selector` (`place`,`item_id`,`root`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>cron`;
CREATE TABLE `<pr>cron` (
  `name` varchar(64) NOT NULL,
  `last_call` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `period` time NOT NULL default '00:01:00',
  `runtime` float unsigned NOT NULL,
  `memory` bigint(20) unsigned NOT NULL,
  `status` enum('idle','process') NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `<pr>cron` (`name`, `last_call`, `period`, `runtime`, `memory`, `status`) 
VALUES 
('do_tag_count_cache', '', '00:01:00', '', '', 'idle');

DROP TABLE IF EXISTS `<pr>cache`;
CREATE TABLE IF NOT EXISTS `<pr>cache` (
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`key`),
  KEY `selector` (`expires`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `<pr>session`;
CREATE TABLE IF NOT EXISTS `<pr>session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cookie` varchar(32) NOT NULL,
  `key` varchar(128) NOT NULL,
  `value` text NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`cookie`,`key`),
  KEY `expires` (`cookie`,`updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>user`;
CREATE TABLE IF NOT EXISTS `<pr>user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `cookie` varchar(32) NOT NULL,
  `rights` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `login_name` (`username`,`password`),
  KEY `login_mail` (`email`,`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
