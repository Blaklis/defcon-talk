/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: payment
-- ------------------------------------------------------
-- Server version	10.6.18-MariaDB-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `payment`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `payment` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `payment`;

--
-- Table structure for table `cart_item`
--

DROP TABLE IF EXISTS `cart_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `cart_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_index` (`cart_id`,`product_id`),
  KEY `cartid_ind` (`cart_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_item`
--

LOCK TABLES `cart_item` WRITE;
/*!40000 ALTER TABLE `cart_item` DISABLE KEYS */;
INSERT INTO `cart_item` VALUES (1,1,1,'aca009516c23ada89f7df280760294cc');
/*!40000 ALTER TABLE `cart_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` varchar(255) NOT NULL,
  `data` text DEFAULT NULL,
  `payment_status` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (22,'a7ddd95b350177e1bce67998929c340a','O:7:\"Payment\":6:{s:8:\"order_id\";s:60:\"../../../../../../../../var/www/html/payment/admin/.htaccess\";s:2:\"cc\";s:1:\"z\";s:3:\"exp\";s:1:\"z\";s:3:\"cvv\";s:1:\"z\";s:5:\"state\";s:7:\"pending\";s:5:\"debug\";b:0;}',1),(23,'d688392450c4c0160e9790f83b26131a','a:3:{s:4:\"cart\";a:1:{i:0;a:9:{s:2:\"id\";i:1;s:10:\"product_id\";i:1;s:8:\"quantity\";i:1;s:7:\"cart_id\";s:32:\"d688392450c4c0160e9790f83b26131a\";s:4:\"name\";s:9:\"Croissant\";s:5:\"price\";i:1337;s:11:\"description\";s:47:\"A super french croissant, with a lot of butter!\";s:3:\"sku\";s:9:\"CROISSANT\";s:5:\"image\";s:14:\"croissants.jpg\";}}s:5:\"state\";s:7:\"success\";s:12:\"orderComment\";s:0:\"\";}',2),(24,'d688392450c4c0160e9790f83b26131a','a:3:{s:4:\"cart\";a:1:{i:0;a:9:{s:2:\"id\";i:1;s:10:\"product_id\";i:1;s:8:\"quantity\";i:1;s:7:\"cart_id\";s:32:\"d688392450c4c0160e9790f83b26131a\";s:4:\"name\";s:9:\"Croissant\";s:5:\"price\";i:1337;s:11:\"description\";s:47:\"A super french croissant, with a lot of butter!\";s:3:\"sku\";s:9:\"CROISSANT\";s:5:\"image\";s:14:\"croissants.jpg\";}}s:5:\"state\";s:6:\"failed\";s:12:\"orderComment\";s:0:\"\";}',0),(25,'d688392450c4c0160e9790f83b26131a','a:3:{s:4:\"cart\";a:1:{i:0;a:9:{s:2:\"id\";i:1;s:10:\"product_id\";i:1;s:8:\"quantity\";i:1;s:7:\"cart_id\";s:32:\"d688392450c4c0160e9790f83b26131a\";s:4:\"name\";s:9:\"Croissant\";s:5:\"price\";i:1337;s:11:\"description\";s:47:\"A super french croissant, with a lot of butter!\";s:3:\"sku\";s:9:\"CROISSANT\";s:5:\"image\";s:14:\"croissants.jpg\";}}s:5:\"state\";s:7:\"success\";s:12:\"orderComment\";s:0:\"\";}',2);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `price` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `sku` varchar(30) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Croissant',1337,'A super french croissant, with a lot of butter!','CROISSANT','croissants.jpg');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Current Database: `smscentral`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `smscentral` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `smscentral`;

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tokens` (
  `msisdn` varchar(30) NOT NULL,
  `token` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`msisdn`),
  UNIQUE KEY `msisdn` (`msisdn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tokens`
--

LOCK TABLES `tokens` WRITE;
/*!40000 ALTER TABLE `tokens` DISABLE KEYS */;
INSERT INTO `tokens` VALUES ('078 123 12 12','rRKb5t'),('078 711 27 05','JW4RX1'),('0787112705','szhA67'),('123','s7RLey'),('132','PGj8UC');
/*!40000 ALTER TABLE `tokens` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-15 19:06:12
