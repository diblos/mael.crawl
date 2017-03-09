-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.16-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for crawldev
CREATE DATABASE IF NOT EXISTS `crawldev` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crawldev`;


-- Dumping structure for table crawldev.botnet
CREATE TABLE IF NOT EXISTS `botnet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `link` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `guid` varchar(50) NOT NULL,
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`guid`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table crawldev.defacement
CREATE TABLE IF NOT EXISTS `defacement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attacker` varchar(50) NOT NULL,
  `team` varchar(50) NOT NULL,
  `homepage_deface` varchar(5) NOT NULL,
  `mass_deface` varchar(5) NOT NULL,
  `re_deface` varchar(5) NOT NULL,
  `special_deface` varchar(5) NOT NULL,
  `location` varchar(20) NOT NULL,
  `domain` varchar(100) NOT NULL,
  `os` varchar(50) NOT NULL,
  `listdate` timestamp NULL DEFAULT NULL,
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table crawldev.malmware
CREATE TABLE IF NOT EXISTS `malmware` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(150) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `r_lookup` varchar(100) NOT NULL COMMENT 'reverse lookup',
  `description` varchar(200) NOT NULL,
  `registrant` varchar(100) NOT NULL,
  `asn` int(11) NOT NULL,
  `asname` varchar(100) NOT NULL COMMENT 'autonomous system name',
  `country` varchar(50) NOT NULL,
  `md5` varchar(50) DEFAULT NULL,
  `PED` varchar(150) DEFAULT NULL COMMENT 'Tool: PE Dump',
  `UQ` varchar(150) DEFAULT NULL COMMENT 'Tool: URL Query',
  `listdate` timestamp NULL DEFAULT NULL,
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table crawldev.phishing
CREATE TABLE IF NOT EXISTS `phishing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(300) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `target_brand` varchar(50) NOT NULL,
  `listdate` timestamp NULL DEFAULT NULL,
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table crawldev.spam
CREATE TABLE IF NOT EXISTS `spam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `host` varchar(150) NOT NULL,
  `country` varchar(200) NOT NULL,
  `latest_type_threat` varchar(50) NOT NULL,
  `total_website` varchar(50) NOT NULL,
  `total_browser` varchar(50) NOT NULL,
  `latest_activity` timestamp NULL DEFAULT NULL,
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
