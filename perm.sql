-- MySQL dump 10.13  Distrib 8.0.25, for Linux (x86_64)
--
-- Host: localhost    Database: mosmos
-- ------------------------------------------------------
-- Server version	8.0.25-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (48,0,'general','general','general settings',NULL,NULL),(49,0,'users','users','manage users',NULL,NULL),(50,49,'View Users','users.view','View Users ',NULL,NULL),(51,49,'Create Users','users.create','Create users',NULL,NULL),(52,49,'Update Users','users.update','Update Users',NULL,NULL),(53,49,'Delete Users','users.delete','Delete Users',NULL,NULL),(54,49,'Manage Roles','users.roles','Manage user roles',NULL,NULL),(109,0,'products','products','manage products access',NULL,NULL),(111,109,'brands','products.brands','manage product brands','2021-08-13 08:30:23','2021-08-13 12:01:51'),(112,109,'categories','products.categories','manage product categories','2021-08-13 08:31:34','2021-08-13 12:04:12'),(113,109,'approved products','products.approved','manage approved products','2021-08-13 08:34:33','2021-08-13 12:04:34'),(114,109,'pending products','products.pending','manage pending products','2021-08-13 08:35:20','2021-08-13 12:04:57'),(115,109,'rejected products','products.rejected','manage rejetec products','2021-08-13 08:36:58','2021-08-13 12:05:33'),(116,48,'counties','counties','manage access to counties','2021-08-13 12:14:39','2021-08-13 12:14:39'),(117,0,'bookings','bookings','manage bookings access',NULL,'2021-08-13 12:20:41'),(118,117,'pending bookings','pending.bookings','has access to pending bookings','2021-08-13 12:23:40','2021-08-13 12:23:40'),(119,117,'active bookings','active.bookings','has access to active bookings','2021-08-13 12:24:20','2021-08-13 12:24:20'),(120,117,'complete bookings','complete.bookings','has access to complete bookings','2021-08-13 12:24:58','2021-08-13 12:24:58'),(121,117,'overdue bookings','overdue.bookings','has access to overdue bookings','2021-08-13 12:25:36','2021-08-13 12:25:36'),(122,117,'revoked vookings','revoked.bookings','has access to revoked bookings','2021-08-13 12:26:06','2021-08-13 12:26:06'),(123,117,'userviced bookings','userviced.bookings','has access to unservcied bookings','2021-08-13 12:26:54','2021-08-13 12:26:54'),(124,117,'transfer order','transfer.order','has access to transfer order','2021-08-13 12:27:47','2021-08-13 12:27:47'),(125,48,'payments','payments','payments','2021-08-13 12:32:02','2021-08-13 12:32:02'),(126,48,'full payment infor','fullpayment.infor','access to full payment info','2021-08-13 12:34:36','2021-08-13 12:34:36'),(127,48,'payment monitoring','payments.monitoring','can monitor payments','2021-08-13 12:37:29','2021-08-13 12:37:29'),(128,48,'commission','commission','has access to commission','2021-08-13 12:40:43','2021-08-13 12:40:43'),(129,48,'cities','cities','has access to cities','2021-08-13 12:43:16','2021-08-13 12:43:16'),(130,48,'prmotions','promotions','has access to product promotions','2021-08-13 12:45:05','2021-08-13 12:45:05'),(131,0,'aggregatepayments','aggregatepayments','manage aggregate payments access',NULL,NULL),(132,0,'lmmpayments','lmmpayments','manage lmm payments access',NULL,NULL),(133,131,'all bookings','aggregate.bookings','see aggregate payments','2021-08-13 12:49:24','2021-08-13 12:50:41'),(134,0,'customers','customers','manage customers access',NULL,NULL),(136,0,'vendors','vendors','manage verndors access',NULL,NULL),(137,0,'banners','banners','manage banners access',NULL,NULL),(138,0,'sms','sms','manage sms access',NULL,NULL),(139,0,'notifications','notifications','manage notificatios access',NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-08-13 10:36:20
