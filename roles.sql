-- MySQL dump 10.13  Distrib 5.7.35, for Linux (x86_64)
--
-- Host: localhost    Database: mosmos
-- ------------------------------------------------------
-- Server version	5.7.35-0ubuntu0.18.04.1

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
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'2021-08-13 10:24:07','2021-09-04 05:32:46','Admin','Admin','{\"general\":true,\"counties\":true,\"payments\":true,\"fullpayment.infor\":true,\"payments.monitoring\":true,\"commission\":true,\"cities\":true,\"promotions\":true,\"dashboard\":true,\"users\":true,\"users.view\":true,\"users.create\":true,\"users.update\":true,\"users.delete\":true,\"users.roles\":true,\"products\":true,\"products.brands\":true,\"products.categories\":true,\"products.approved\":true,\"products.pending\":true,\"products.rejected\":true,\"bookings\":true,\"pending.bookings\":true,\"active.bookings\":true,\"complete.bookings\":true,\"overdue.bookings\":true,\"revoked.bookings\":true,\"transfer.order\":true,\"unserviced.bookings\":true,\"aggregatepayments\":true,\"aggregate.bookings\":true,\"lmmpayments\":true,\"customers\":true,\"vendors\":true,\"banners\":true,\"sms\":true,\"notifications\":true}'),(2,'2021-08-13 10:43:49','2021-08-13 11:00:06','developer','developer','{\"general\":true,\"counties\":true,\"payments\":true,\"fullpayment.infor\":true,\"payments.monitoring\":true,\"commission\":true,\"cities\":true,\"promotions\":true,\"users\":true,\"users.view\":true,\"users.create\":true,\"users.update\":true,\"users.delete\":true,\"users.roles\":true,\"products\":true,\"products.brands\":true,\"products.categories\":true,\"products.approved\":true,\"products.pending\":true,\"products.rejected\":true,\"bookings\":true,\"pending.bookings\":true,\"active.bookings\":true,\"complete.bookings\":true,\"overdue.bookings\":true,\"revoked.bookings\":true,\"userviced.bookings\":true,\"transfer.order\":true,\"aggregatepayments\":true,\"aggregate.bookings\":true,\"lmmpayments\":true,\"customers\":true,\"vendors\":true,\"banners\":true,\"sms\":true,\"notifications\":true}'),(3,'2021-08-13 10:54:45','2021-08-26 03:33:43','Customer_Service','Customer Service','{\"fullpayment.infor\":true,\"products\":true,\"products.approved\":true,\"products.rejected\":true,\"bookings\":true,\"pending.bookings\":true,\"active.bookings\":true,\"complete.bookings\":true,\"overdue.bookings\":true,\"revoked.bookings\":true,\"userviced.bookings\":true,\"transfer.order\":true,\"lmmpayments\":true,\"sms\":true,\"notifications\":true}');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-10-11 14:23:02
