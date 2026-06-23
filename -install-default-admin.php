<?php

include_once "config/mysql.php";
$mysqli = mysqli_connect($db_host, $db_user, $db_password, $db_name);

$q = "

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `adm_settings`;
CREATE TABLE IF NOT EXISTS `adm_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `adminEmail` varchar(255) DEFAULT NULL,
  `adminLang` varchar(2) DEFAULT NULL,
  `show_debug` int(5) DEFAULT 1,
  `show_domdata` int(5) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `adm_settings` (`id`, `username`, `password`, `adminEmail`, `adminLang`, `show_debug`, `show_domdata`) VALUES
(1, 'admin', '1a1dc91c907325c69271ddf0c944bc72', 'demo@email.com', 'en', 1, 1);
COMMIT;

";

$res = mysqli_multi_query($mysqli,$q) or die(mysqli_error($mysqli));
echo "Admin Reset.";