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

INSERT INTO `draw`.`draw_meta` (`type`, `alias`, `name`, `color`, `area`) VALUES ('author', 'anonymous', 'гость', NULL, NULL);

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
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `weight` mediumint(8) unsigned NOT NULL,
  `resized` float unsigned NOT NULL,
  `extension` varchar(6) NOT NULL,
  `meta` text NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `description` text NOT NULL,
  `timer` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` enum('main','deleted') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`user_id`,`date`),
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

ALTER TABLE  `<pr>user` ADD  `last_draw` DATETIME NOT NULL ,
ADD INDEX (  `last_draw` )

CREATE TABLE IF NOT EXISTS `<pr>painter_themes` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	`name` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`disabled` TINYINT UNSIGNED NOT NULL, 
	`pro_menu_color_text` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`pro_menu_color_off` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`pro_menu_color_off_hl` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`pro_menu_color_off_dk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`pro_menu_color_on` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`pro_menu_color_on_hl` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`pro_menu_color_on_dk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_bk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_frame` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_off` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_off_hl` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_off_dk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_on` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_on_hl` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_on_dk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`bar_color_text` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`window_color_text` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`window_color_frame` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`window_color_bk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`window_color_bar` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`window_color_bar_hl` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`window_color_bar_text` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`dlg_color_bk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`dlg_color_text` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_bk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_bk2` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`l_m_color` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`l_m_color_text` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_text` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_icon` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_frame` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_iconselect` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_bar` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_bar_hl` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`color_bar_shadow` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`tool_color_bk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`tool_color_button` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`tool_color_button_hl` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`tool_color_button_dk` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`tool_color_button2` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`tool_color_text` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`tool_color_bar` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
	`tool_color_frame` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE  `<pr>painter_themes` ADD UNIQUE  `selector` (  `disabled` ,  `id` );

INSERT INTO `<pr>painter_themes` (`id`, `name`, `disabled`, `pro_menu_color_text`, `pro_menu_color_off`, `pro_menu_color_off_hl`, `pro_menu_color_off_dk`, `pro_menu_color_on`, `pro_menu_color_on_hl`, `pro_menu_color_on_dk`, `bar_color_bk`, `bar_color_frame`, `bar_color_off`, `bar_color_off_hl`, `bar_color_off_dk`, `bar_color_on`, `bar_color_on_hl`, `bar_color_on_dk`, `bar_color_text`, `window_color_text`, `window_color_frame`, `window_color_bk`, `window_color_bar`, `window_color_bar_hl`, `window_color_bar_text`, `dlg_color_bk`, `dlg_color_text`, `color_bk`, `color_bk2`, `l_m_color`, `l_m_color_text`, `color_text`, `color_icon`, `color_frame`, `color_iconselect`, `color_bar`, `color_bar_hl`, `color_bar_shadow`, `tool_color_bk`, `tool_color_button`, `tool_color_button_hl`, `tool_color_button_dk`, `tool_color_button2`, `tool_color_text`, `tool_color_bar`, `tool_color_frame`) VALUES ('1', 'Стандартная', '0', '#FFFFFF', '#222233', '#333344', '0', '#ff0000', '#ff8888', '#660000', '#00ffff', '#ff0000', '#ffffff', '#ffffff', '#888888', '#aaaaaa', '#aaaaaa', '#aaaaaa', '0xff0000', '#ff0000', '#ffff00', '#000000', '#777777', '#888888', '#000000', '#ccccff', '0', '#FFFF0', '#FF00FF', '#ffffff', '#0000ff', '0', '#0FFFF', '0xff', '#112233', '0', '#665544', '#778899', '#aabbcc', '#ddeeff', '#9900ff', '#ff0099', '#ffffff', '0', '#00ff00', '#ff0000');
INSERT INTO `<pr>user` (`id`, `username`, `password`, `email`, `cookie`, `rights`) VALUES ('0', 'anonymous', '---', '', '', '0');

CREATE TABLE IF NOT EXISTS `<pr>description` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `description_id` varchar(32) NOT NULL,
  `text` text NOT NULL,
  `pretty_text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`type`,`description_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `<pr>description` (`id`, `type`, `description_id`, `text`, `pretty_text`) VALUES
(1, 'author', 'anonymous', 'Это общая галерея для гостей. <br />\r\n<br />\r\nСюда попадают изображения нарисованные не зарегистрировавшимися художниками.', 'Это общая галерея для гостей. \r\n\r\nСюда попадают изображения нарисованные не зарегистрировавшимися художниками.');
