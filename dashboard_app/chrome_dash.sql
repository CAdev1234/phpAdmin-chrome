-- MySQL dump 10.13  Distrib 8.0.24, for Win64 (x86_64)
--
-- Host: localhost    Database: chrome_dash
-- ------------------------------------------------------
-- Server version	8.0.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (8,'test','$2y$10$YT5lcmpKdWN3OmBYHBLKXOcgXJ.cE9SzL.TLUTeCLTvzSkV2PIQRK','josiah@mail.com','4671727272','2021-06-26 22:24:07','2021-06-26 22:24:07');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_data_tb`
--

DROP TABLE IF EXISTS `client_data_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_data_tb` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) DEFAULT NULL,
  `last_connect` varchar(255) DEFAULT NULL,
  `current_website` varchar(255) DEFAULT NULL,
  `time_spending` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `user_identity` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_data_tb`
--

LOCK TABLES `client_data_tb` WRITE;
/*!40000 ALTER TABLE `client_data_tb` DISABLE KEYS */;
INSERT INTO `client_data_tb` VALUES (139,'149.7.16.128','2021-07-01 15:47:51','www.linkedin.com','0 hours and 0 minutes, 7 seconds','GB','/upload/image/28.png','51.5085','-0.1257','149.7.16.128','2021-07-01 15:47:51','2021-07-01 15:47:51'),(141,'2.21.171.46','6/29/2021, 10:54:26 PM','www.demoserver.live','0 hours and 0 minutes, 1 seconds','PT','/upload/image/29.png','39.5','-8','2.21.171.46','2021-06-29 16:54:26','2021-06-29 16:54:26'),(142,'2.16.36.102','6/29/2021, 10:54:26 PM','www.demoserver.live','0 hours and 0 minutes, 1 seconds','AU','/upload/image/30.png','-27','133','2.16.36.102','2021-06-29 16:54:26','2021-06-29 16:54:26'),(143,'2.16.130.134','6/29/2021, 10:54:26 PM','www.demoserver.live','0 hours and 0 minutes, 1 seconds','BG','/upload/image/31.png','50.8333','4','2.16.130.134','2021-06-29 16:54:26','2021-06-29 16:54:26'),(144,'23.208.167.0','6/29/2021, 10:54:26 PM','www.demoserver.live','0 hours and 0 minutes, 1 seconds','GD','/upload/image/32.png','12.1167','-61.6667','23.208.167.0','2021-06-29 16:54:26','2021-06-29 16:54:26');
/*!40000 ALTER TABLE `client_data_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_tb`
--

DROP TABLE IF EXISTS `client_tb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_tb` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `identity` varchar(255) DEFAULT NULL,
  `last_activity` varchar(255) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `country_code` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_tb`
--

LOCK TABLES `client_tb` WRITE;
/*!40000 ALTER TABLE `client_tb` DISABLE KEYS */;
INSERT INTO `client_tb` VALUES (28,'149.7.16.128','2021-07-01 15:48:14','51.5085','-0.1257','GB','2021-06-27 23:31:03','2021-07-01 15:47:51'),(29,'2.21.171.46','2021-06-29 16:54:22','39.5','-8','PT','2021-06-29 11:01:15','2021-06-29 16:53:08'),(30,'2.16.36.102','2021-06-29 19:03:45','-27','133','AU','2021-06-27 23:31:03','2021-06-29 16:54:26'),(31,'2.16.130.134','2021-06-29 16:54:22','50.8333','4','BG','2021-06-29 11:01:15','2021-06-29 16:53:08'),(32,'23.208.167.0','2021-06-29 16:54:22','12.1167','-61.6667','GD','2021-06-29 11:01:15','2021-06-29 16:53:08');
/*!40000 ALTER TABLE `client_tb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `upload_image`
--

DROP TABLE IF EXISTS `upload_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `upload_image` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upload_image`
--

LOCK TABLES `upload_image` WRITE;
/*!40000 ALTER TABLE `upload_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `upload_image` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-07-01 21:48:14
