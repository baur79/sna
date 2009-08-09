# Sequel Pro dump
# Version 1180
# http://code.google.com/p/sequel-pro
#
# Host: localhost (MySQL 5.0.67)
# Database: sna_development
# Generation Time: 2009-08-09 18:27:40 +0200
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table messages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` char(36) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `user_id` char(36) NOT NULL,
  `form_user_id` char(36) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` (`id`,`created`,`modified`,`user_id`,`form_user_id`,`subject`,`body`)
VALUES
	('4a6c45fa-6200-47bb-aac7-02378784ca84','2009-07-26 14:03:06','2009-07-26 14:03:06','4a648ce4-08a4-46e2-91f8-024a8784ca84','4a648ce4-08a4-46e2-91f8-024a8784ca84','Thats a Message From X to Y','Hai!');

/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table shouts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shouts`;

CREATE TABLE `shouts` (
  `id` char(36) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `user_id` char(36) NOT NULL,
  `from_user_id` char(36) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `shouts` WRITE;
/*!40000 ALTER TABLE `shouts` DISABLE KEYS */;
INSERT INTO `shouts` (`id`,`created`,`modified`,`user_id`,`from_user_id`,`text`)
VALUES
	('4a6c45fa-6200-47bb-aac7-02378784ca83','0000-00-00 00:00:00','0000-00-00 00:00:00','4a6c45fa-6200-47bb-aac7-02378784ca84','4a6c45fa-6200-47bb-aac7-02378784ca84','Hio');

/*!40000 ALTER TABLE `shouts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_options`;

CREATE TABLE `user_options` (
  `id` int(11) NOT NULL auto_increment,
  `modified` datetime NOT NULL,
  `user_id` char(36) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(1000) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_KEY_PER_USER` (`user_id`,`key`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `user_options` WRITE;
/*!40000 ALTER TABLE `user_options` DISABLE KEYS */;
INSERT INTO `user_options` (`id`,`modified`,`user_id`,`key`,`value`)
VALUES
	(1,'2009-07-28 17:43:20','4a648ce4-08a4-46e2-91f8-024a8784ca84','landingPage',''),
	(2,'2009-07-28 17:53:07','4a6c68a7-7b94-4370-8b37-02378784ca84','landingPage','controller=>users, action=>view, somebody'),
	(3,'0000-00-00 00:00:00','4a76e294-2b98-4913-a931-03a08784ca84','landingPage','controller=>messages');

/*!40000 ALTER TABLE `user_options` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL default '0',
  `is_disabled` tinyint(1) NOT NULL default '0',
  `has_accepted_tos` tinyint(1) NOT NULL default '0',
  `is_hidden` tinyint(1) NOT NULL default '0',
  `username` varchar(50) NOT NULL,
  `password` char(64) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `activation_key` varchar(19) NOT NULL default '',
  `last_login` datetime default NULL,
  `forgot_password_key` varchar(19) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_USERNAME` (`username`),
  UNIQUE KEY `UNIQUE_NICKNAME` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`,`created`,`modified`,`is_deleted`,`is_disabled`,`has_accepted_tos`,`is_hidden`,`username`,`password`,`nickname`,`email`,`activation_key`,`last_login`,`forgot_password_key`)
VALUES
	('4a648ce4-08a4-46e2-91f8-024a8784ca84','2009-08-03 16:15:22','2009-08-09 18:03:20',0,0,1,0,'ionas','d234c827a80548e868cac076365c483fcdfadb80050a682fffd67a42e1dd012b','Jonas','foo@bar.com','','2009-08-09 16:38:52','F348-E690-4CCE-B360'),
	('4a7ef8b6-da50-4a76-b5cf-0a8f8784ca84','2009-08-09 18:26:30','2009-08-09 18:26:30',0,0,1,0,'abc','d234c827a80548e868cac076365c483fcdfadb80050a682fffd67a42e1dd012b','foo','M8R-sx51ch@mailinator.com','F8B6-030C-4BED-AC59','2009-08-09 18:26:30',NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;





/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
