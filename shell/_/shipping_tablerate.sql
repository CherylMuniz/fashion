-- MySQL dump 10.13  Distrib 5.1.61, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: fashione_magento3
-- ------------------------------------------------------
-- Server version	5.1.61

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `shipping_tablerate`
--

DROP TABLE IF EXISTS `shipping_tablerate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping_tablerate` (
  `pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) NOT NULL DEFAULT '0',
  `dest_country_id` varchar(4) NOT NULL DEFAULT '0',
  `dest_region_id` int(10) NOT NULL DEFAULT '0',
  `dest_zip` varchar(10) NOT NULL DEFAULT '',
  `condition_name` varchar(20) NOT NULL DEFAULT '',
  `condition_value` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`pk`),
  UNIQUE KEY `dest_country` (`website_id`,`dest_country_id`,`dest_region_id`,`dest_zip`,`condition_name`,`condition_value`)
) ENGINE=InnoDB AUTO_INCREMENT=339 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_tablerate`
--

LOCK TABLES `shipping_tablerate` WRITE;
/*!40000 ALTER TABLE `shipping_tablerate` DISABLE KEYS */;
INSERT INTO `shipping_tablerate` VALUES (240,1,'0',0,'','package_value','0.0000','25.0000','0.0000'),(241,1,'AE',0,'','package_value','0.0000','20.0000','0.0000'),(242,1,'AU',0,'','package_value','0.0000','25.0000','0.0000'),(243,1,'CA',0,'','package_value','0.0000','20.0000','0.0000'),(244,1,'GB',0,'','package_value','0.0000','0.0000','0.0000'),(245,1,'HK',0,'','package_value','0.0000','20.0000','0.0000'),(246,1,'JP',0,'','package_value','0.0000','20.0000','0.0000'),(247,1,'MV',0,'','package_value','0.0000','20.0000','0.0000'),(248,1,'NZ',0,'','package_value','0.0000','20.0000','0.0000'),(249,1,'SA',0,'','package_value','0.0000','20.0000','0.0000'),(250,1,'SC',0,'','package_value','0.0000','20.0000','0.0000'),(251,1,'TH',0,'','package_value','0.0000','20.0000','0.0000'),(252,1,'TW',0,'','package_value','0.0000','20.0000','0.0000'),(253,1,'US',0,'','package_value','0.0000','20.0000','0.0000'),(254,1,'ZA',0,'','package_value','0.0000','20.0000','0.0000'),(255,1,'DE',0,'','package_value','0.0000','12.0000','0.0000'),(256,1,'AT',0,'','package_value','0.0000','12.0000','0.0000'),(257,1,'BE',0,'','package_value','0.0000','12.0000','0.0000'),(258,1,'DK',0,'','package_value','0.0000','12.0000','0.0000'),(259,1,'EE',0,'','package_value','0.0000','12.0000','0.0000'),(260,1,'FI',0,'','package_value','0.0000','12.0000','0.0000'),(261,1,'FR',0,'','package_value','0.0000','12.0000','0.0000'),(262,1,'IS',0,'','package_value','0.0000','12.0000','0.0000'),(263,1,'IE',0,'','package_value','0.0000','12.0000','0.0000'),(264,1,'NL',0,'','package_value','0.0000','12.0000','0.0000'),(265,1,'PT',0,'','package_value','0.0000','12.0000','0.0000'),(266,1,'ES',0,'','package_value','0.0000','12.0000','0.0000'),(267,1,'SE',0,'','package_value','0.0000','12.0000','0.0000'),(268,1,'CH',0,'','package_value','0.0000','12.0000','0.0000'),(269,1,'IT',0,'','package_value','0.0000','12.0000','0.0000'),(270,1,'LV',0,'','package_value','0.0000','12.0000','0.0000'),(271,1,'MT',0,'','package_value','0.0000','12.0000','0.0000'),(272,1,'NO',0,'','package_value','0.0000','12.0000','0.0000'),(273,1,'GB',0,'JE','package_value','0.0000','8.0000','0.0000'),(274,1,'GB',0,'GY','package_value','0.0000','8.0000','0.0000'),(275,1,'GB',0,'IM','package_value','0.0000','8.0000','0.0000'),(276,1,'GB',0,'KW15','package_value','0.0000','8.0000','0.0000'),(277,1,'GB',0,'KW16','package_value','0.0000','8.0000','0.0000'),(278,1,'GB',0,'KW17','package_value','0.0000','8.0000','0.0000'),(279,1,'GB',0,'ZE','package_value','0.0000','8.0000','0.0000'),(280,1,'GB',0,'KW1','package_value','0.0000','5.0000','0.0000'),(281,1,'GB',0,'KW2','package_value','0.0000','5.0000','0.0000'),(282,1,'GB',0,'KW3','package_value','0.0000','5.0000','0.0000'),(283,1,'GB',0,'KW5','package_value','0.0000','5.0000','0.0000'),(284,1,'GB',0,'KW6','package_value','0.0000','5.0000','0.0000'),(285,1,'GB',0,'KW7','package_value','0.0000','5.0000','0.0000'),(286,1,'GB',0,'KW8','package_value','0.0000','5.0000','0.0000'),(287,1,'GB',0,'KW9','package_value','0.0000','5.0000','0.0000'),(288,1,'GB',0,'KW10','package_value','0.0000','5.0000','0.0000'),(289,1,'GB',0,'KW11','package_value','0.0000','5.0000','0.0000'),(290,1,'GB',0,'KW12','package_value','0.0000','5.0000','0.0000'),(291,1,'GB',0,'KW13','package_value','0.0000','5.0000','0.0000'),(292,1,'GB',0,'KW14','package_value','0.0000','5.0000','0.0000'),(293,1,'GB',0,'IV','package_value','0.0000','5.0000','0.0000'),(294,1,'GB',0,'PH20','package_value','0.0000','5.0000','0.0000'),(295,1,'GB',0,'PH21','package_value','0.0000','5.0000','0.0000'),(296,1,'GB',0,'PH22','package_value','0.0000','5.0000','0.0000'),(297,1,'GB',0,'PH23','package_value','0.0000','5.0000','0.0000'),(298,1,'GB',0,'PH24','package_value','0.0000','5.0000','0.0000'),(299,1,'GB',0,'PH25','package_value','0.0000','5.0000','0.0000'),(300,1,'GB',0,'PH26','package_value','0.0000','5.0000','0.0000'),(301,1,'GB',0,'PH31','package_value','0.0000','5.0000','0.0000'),(302,1,'GB',0,'PH32','package_value','0.0000','5.0000','0.0000'),(303,1,'GB',0,'PH33','package_value','0.0000','5.0000','0.0000'),(304,1,'GB',0,'PH34','package_value','0.0000','5.0000','0.0000'),(305,1,'GB',0,'PH35','package_value','0.0000','5.0000','0.0000'),(306,1,'GB',0,'PH36','package_value','0.0000','5.0000','0.0000'),(307,1,'GB',0,'PH37','package_value','0.0000','5.0000','0.0000'),(308,1,'GB',0,'PH38','package_value','0.0000','5.0000','0.0000'),(309,1,'GB',0,'PH39','package_value','0.0000','5.0000','0.0000'),(310,1,'GB',0,'PH40','package_value','0.0000','5.0000','0.0000'),(311,1,'GB',0,'PH41','package_value','0.0000','5.0000','0.0000'),(312,1,'GB',0,'PH42','package_value','0.0000','5.0000','0.0000'),(313,1,'GB',0,'PH43','package_value','0.0000','5.0000','0.0000'),(314,1,'GB',0,'PH44','package_value','0.0000','5.0000','0.0000'),(315,1,'GB',0,'HS','package_value','0.0000','5.0000','0.0000'),(316,1,'GB',0,'PA2','package_value','0.0000','5.0000','0.0000'),(317,1,'GB',0,'PA3','package_value','0.0000','5.0000','0.0000'),(318,1,'GB',0,'PA4','package_value','0.0000','5.0000','0.0000'),(319,1,'GB',0,'PA6','package_value','0.0000','5.0000','0.0000'),(320,1,'GB',0,'PA7','package_value','0.0000','5.0000','0.0000'),(321,1,'GB',0,'PA8','package_value','0.0000','5.0000','0.0000'),(322,1,'GB',0,'KA28','package_value','0.0000','5.0000','0.0000'),(323,1,'GB',0,'KA27','package_value','0.0000','5.0000','0.0000'),(324,1,'GB',0,'PO30','package_value','0.0000','8.0000','0.0000'),(325,1,'GB',0,'PO31','package_value','0.0000','8.0000','0.0000'),(326,1,'GB',0,'PO32','package_value','0.0000','8.0000','0.0000'),(327,1,'GB',0,'PO33','package_value','0.0000','8.0000','0.0000'),(328,1,'GB',0,'PO34','package_value','0.0000','8.0000','0.0000'),(329,1,'GB',0,'PO35','package_value','0.0000','8.0000','0.0000'),(330,1,'GB',0,'PO36','package_value','0.0000','8.0000','0.0000'),(331,1,'GB',0,'PO37','package_value','0.0000','8.0000','0.0000'),(332,1,'GB',0,'PO38','package_value','0.0000','8.0000','0.0000'),(333,1,'GB',0,'PO39','package_value','0.0000','8.0000','0.0000'),(334,1,'GB',0,'PO40','package_value','0.0000','8.0000','0.0000'),(335,1,'GB',0,'PO41','package_value','0.0000','8.0000','0.0000'),(336,1,'GB',0,'BT','package_value','0.0000','5.0000','0.0000'),(337,1,'GB',0,'PH15','package_value','0.0000','5.0000','0.0000'),(338,1,'GB',0,'PH16','package_value','0.0000','5.0000','0.0000');
/*!40000 ALTER TABLE `shipping_tablerate` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-01-21 13:15:02
