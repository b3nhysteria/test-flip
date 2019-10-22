# ************************************************************
# Sequel Pro SQL dump
# Version 5224
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.26)
# Database: test
# Generation Time: 2019-10-22 19:49:00 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table bank
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bank`;

CREATE TABLE `bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `bank` WRITE;
/*!40000 ALTER TABLE `bank` DISABLE KEYS */;

INSERT INTO `bank` (`id`, `name`)
VALUES
	(1,'BNI'),
	(2,'MANDIRI'),
	(3,'BCA');

/*!40000 ALTER TABLE `bank` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table flip_service
# ------------------------------------------------------------

DROP TABLE IF EXISTS `flip_service`;

CREATE TABLE `flip_service` (
  `id` varchar(40) NOT NULL DEFAULT '',
  `request` text NOT NULL,
  `response` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1: pending. 2: success',
  `merchant_id` varchar(40) NOT NULL DEFAULT '',
  `request_id` bigint(20) unsigned NOT NULL,
  `receipt` varchar(300) DEFAULT NULL,
  `time_served` datetime DEFAULT NULL,
  `fee` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `merchant_use_flip` (`merchant_id`),
  CONSTRAINT `merchant_use_flip` FOREIGN KEY (`merchant_id`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `flip_service` WRITE;
/*!40000 ALTER TABLE `flip_service` DISABLE KEYS */;

INSERT INTO `flip_service` (`id`, `request`, `response`, `created_at`, `status`, `merchant_id`, `request_id`, `receipt`, `time_served`, `fee`)
VALUES
	('221020190731525daf5928c94d8','{\"bank_code\":3,\"account_number\":\"1234567890\",\"amount\":10,\"remark\":\"191020191214255daafe21c01e2\"}','{\"id\":7524883683,\"amount\":10000,\"status\":\"PENDING\",\"timestamp\":\"2019-10-23 02:38:31\",\"bank_code\":\"bni\",\"account_number\":\"1234567890\",\"beneficiary_name\":\"PT FLIP\",\"remark\":\"sample remark\",\"receipt\":\"https:\\/\\/flip-receipt.oss-ap-southeast-5.aliyuncs.com\\/debit_receipt\\/126316_3d07f9fef9612c7275b3c36f7e1e5762.jpg\",\"time_served\":\"2019-10-23 02:47:31\",\"fee\":4000}','2019-10-23 02:31:52',1,'191020191214255daafe21c01e2',7524883683,'https://flip-receipt.oss-ap-southeast-5.aliyuncs.com/debit_receipt/126316_3d07f9fef9612c7275b3c36f7e1e5762.jpg','2019-10-23 02:47:31',4000);

/*!40000 ALTER TABLE `flip_service` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table merchant
# ------------------------------------------------------------

DROP TABLE IF EXISTS `merchant`;

CREATE TABLE `merchant` (
  `id` varchar(40) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `balance` decimal(11,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `merchant` WRITE;
/*!40000 ALTER TABLE `merchant` DISABLE KEYS */;

INSERT INTO `merchant` (`id`, `name`, `balance`, `created_at`)
VALUES
	('191020191145205daaf7500c7de','althaf',10000.00,'2019-10-19 18:49:30'),
	('191020191214035daafe0ba4a54','althaf1',10000.00,'2019-10-19 19:14:03'),
	('191020191214075daafe0f1c72c','altha2',10000.00,'2019-10-19 19:14:07'),
	('191020191214095daafe1190b8c','altha23',10000.00,'2019-10-19 19:14:09'),
	('191020191214125daafe14ce417','altha234',10000.00,'2019-10-19 19:14:12'),
	('191020191214165daafe18155e3','altha2346',10000.00,'2019-10-19 19:14:16'),
	('191020191214195daafe1b11c64','altha23467',10000.00,'2019-10-19 19:14:19'),
	('191020191214225daafe1e2063a','altha234678',10000.00,'2019-10-19 19:14:22'),
	('191020191214255daafe21c01e2','altha234679',19870.00,'2019-10-19 19:14:25');

/*!40000 ALTER TABLE `merchant` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table merchant_bank_account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `merchant_bank_account`;

CREATE TABLE `merchant_bank_account` (
  `id` varchar(40) NOT NULL DEFAULT '',
  `merchant_id` varchar(40) NOT NULL DEFAULT '',
  `bank_account` varchar(40) NOT NULL DEFAULT '',
  `bank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_bank_merchant` (`merchant_id`),
  CONSTRAINT `account_bank_merchant` FOREIGN KEY (`merchant_id`) REFERENCES `merchant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `merchant_bank_account` WRITE;
/*!40000 ALTER TABLE `merchant_bank_account` DISABLE KEYS */;

INSERT INTO `merchant_bank_account` (`id`, `merchant_id`, `bank_account`, `bank_id`)
VALUES
	('191020191145205daaf750169d2','191020191145205daaf7500c7de','1234567890',1),
	('191020191214035daafe0ba5413','191020191214035daafe0ba4a54','1234567890',2),
	('191020191214075daafe0f1cd18','191020191214075daafe0f1c72c','1234567890',3),
	('191020191214095daafe1192c14','191020191214095daafe1190b8c','1234567890',1),
	('191020191214125daafe14ce9f3','191020191214125daafe14ce417','1234567890',2),
	('191020191214165daafe18157c4','191020191214165daafe18155e3','1234567890',3),
	('191020191214195daafe1b14a81','191020191214195daafe1b11c64','1234567890',1),
	('191020191214225daafe1e20777','191020191214225daafe1e2063a','1234567890',2),
	('191020191214255daafe21c055c','191020191214255daafe21c01e2','1234567890',3);

/*!40000 ALTER TABLE `merchant_bank_account` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
