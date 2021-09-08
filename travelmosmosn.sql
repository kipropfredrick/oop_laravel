-- MySQL dump 10.13  Distrib 5.7.35, for Linux (x86_64)
--
-- Host: localhost    Database: travel_mosmos
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
-- Table structure for table `admin_commissions`
--

DROP TABLE IF EXISTS `admin_commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_commissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `total_booking_cost` double(10,2) NOT NULL,
  `commission_rate` double(2,2) NOT NULL,
  `commission` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_commissions_booking_id_foreign` (`booking_id`),
  CONSTRAINT `admin_commissions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_commissions`
--

LOCK TABLES `admin_commissions` WRITE;
/*!40000 ALTER TABLE `admin_commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_wallets`
--

DROP TABLE IF EXISTS `admin_wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_wallets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `previous_balance` double(10,2) NOT NULL DEFAULT '0.00',
  `current_balance` double(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_wallets`
--

LOCK TABLES `admin_wallets` WRITE;
/*!40000 ALTER TABLE `admin_wallets` DISABLE KEYS */;
INSERT INTO `admin_wallets` VALUES (1,701.40,1119.25,'2021-08-19 21:27:52','2021-08-28 17:53:40'),(2,701.40,1119.25,NULL,'2021-08-28 17:53:40'),(3,701.40,1119.25,NULL,'2021-08-28 17:53:40'),(4,701.40,1119.25,NULL,'2021-08-28 17:53:40'),(5,701.40,1119.25,NULL,'2021-08-28 17:53:40');
/*!40000 ALTER TABLE `admin_wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agency_branches`
--

DROP TABLE IF EXISTS `agency_branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agency_branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) unsigned NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exact_location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agency_branches_agent_id_foreign` (`agent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agency_branches`
--

LOCK TABLES `agency_branches` WRITE;
/*!40000 ALTER TABLE `agency_branches` DISABLE KEYS */;
INSERT INTO `agency_branches` VALUES (1,6,'Mombasa','City Mall','254713302589','travelmosmos@gmail.com','Travel Mos Mos - Mombasa (City Mall)  Branch','2021-08-18 05:55:49','2021-08-18 05:55:49');
/*!40000 ALTER TABLE `agency_branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_accountuser_roles`
--

DROP TABLE IF EXISTS `agent_accountuser_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agent_accountuser_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_accountuser_roles`
--

LOCK TABLES `agent_accountuser_roles` WRITE;
/*!40000 ALTER TABLE `agent_accountuser_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `agent_accountuser_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_accountusers`
--

DROP TABLE IF EXISTS `agent_accountusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agent_accountusers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_accountusers`
--

LOCK TABLES `agent_accountusers` WRITE;
/*!40000 ALTER TABLE `agent_accountusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `agent_accountusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_commissions`
--

DROP TABLE IF EXISTS `agent_commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agent_commissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `agent_id` bigint(20) unsigned NOT NULL,
  `total_booking_cost` double(10,2) NOT NULL,
  `commission_rate` double(2,2) NOT NULL,
  `commission` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agent_commissions_booking_id_foreign` (`booking_id`),
  KEY `agent_commissions_agent_id_foreign` (`agent_id`),
  CONSTRAINT `agent_commissions_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `travel_agents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `agent_commissions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_commissions`
--

LOCK TABLES `agent_commissions` WRITE;
/*!40000 ALTER TABLE `agent_commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `agent_commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_custom_credentials`
--

DROP TABLE IF EXISTS `agent_custom_credentials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agent_custom_credentials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_custom_credentials`
--

LOCK TABLES `agent_custom_credentials` WRITE;
/*!40000 ALTER TABLE `agent_custom_credentials` DISABLE KEYS */;
/*!40000 ALTER TABLE `agent_custom_credentials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_change_logs`
--

DROP TABLE IF EXISTS `booking_change_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_change_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `comment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_change_logs_booking_id_foreign` (`booking_id`),
  CONSTRAINT `booking_change_logs_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_change_logs`
--

LOCK TABLES `booking_change_logs` WRITE;
/*!40000 ALTER TABLE `booking_change_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_change_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `agent_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `package_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `travel_date` date NOT NULL,
  `no_of_kids` int(11) DEFAULT '0',
  `no_of_adults` int(11) NOT NULL DEFAULT '0',
  `kid_unit_cost` double DEFAULT '0',
  `adult_unit_cost` int(11) NOT NULL DEFAULT '0',
  `kids_total` int(11) NOT NULL DEFAULT '0',
  `adults_total` int(11) NOT NULL DEFAULT '0',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_cost` double(10,2) NOT NULL,
  `amount_paid` double(10,2) NOT NULL DEFAULT '0.00',
  `balance` double(10,2) NOT NULL,
  `commission_rate` double NOT NULL DEFAULT '0',
  `agent_commission` double NOT NULL DEFAULT '0',
  `admin_commission` double NOT NULL DEFAULT '0',
  `agent_cancellation_fee` double(8,2) NOT NULL DEFAULT '0.00',
  `system_cancellation_fee` double(8,2) NOT NULL DEFAULT '0.00',
  `customer_balance` double(8,2) NOT NULL DEFAULT '0.00',
  `amount_refunded` double(8,2) NOT NULL DEFAULT '0.00',
  `r_balance` double(8,2) NOT NULL DEFAULT '0.00',
  `comment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `milestones` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `setdate` timestamp NOT NULL DEFAULT '2019-12-31 21:00:00',
  `setreminder` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `user` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Agency Admin',
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Create Booking',
  PRIMARY KEY (`id`),
  KEY `bookings_customer_id_foreign` (`customer_id`),
  KEY `bookings_agent_id_foreign` (`agent_id`),
  CONSTRAINT `bookings_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `travel_agents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (7,1883,6,NULL,'test','T14886','2021-09-10',1,1,1,1,1,1,'complete',2.00,2.00,0.00,4,1.9300000000000002,0.07,0.00,0.00,0.00,0.00,0.00,NULL,NULL,'2021-08-19 14:56:38','2021-08-19 14:56:38','2019-12-31 21:00:00','0','Agency Admin','Create Booking'),(10,1883,6,NULL,'test','T32060','2021-09-11',1,1,1,1,1,1,'complete',2.00,2.00,0.00,4,1.9300000000000002,0.07,0.00,0.00,0.00,0.00,0.00,NULL,NULL,'2021-08-19 15:47:23','2021-08-19 15:47:23','2019-12-31 21:00:00','0','Agency Admin','Create Booking'),(11,1883,6,NULL,'test','T95915','2021-08-25',1,1,100,100,100,100,'active',200.00,125.00,75.00,4,193,7,0.00,0.00,0.00,0.00,0.00,NULL,NULL,'2021-08-10 02:37:42','2021-08-19 17:22:36','2021-09-18 21:00:00','1','Agency Admin','Create Booking'),(12,3712,6,NULL,'test','T82462','2021-08-21',11,1,1,5,11,5,'complete',16.00,110.00,-94.00,4,15.44,0.56,0.00,0.00,0.00,0.00,0.00,NULL,NULL,'2021-08-20 02:37:42','2021-08-20 02:37:42','2022-02-21 21:00:00','3','Agency Admin','Create Booking'),(13,12928,6,NULL,'Masai Mara 3 days 2 nights landcruiser','T85300','2021-11-20',2,2,10000,20000,20000,40000,'cancelled',60000.00,20001.00,39999.00,4,57900,2100,0.00,200.01,19800.99,0.00,0.00,NULL,NULL,'2021-08-20 04:19:33','2021-08-20 04:32:28','2019-12-31 21:00:00','0','Agency Admin','Create Booking'),(14,3712,6,NULL,'Masai Mara Test','T30930','2021-09-30',2,2,100,100,200,200,'revoked',400.00,0.00,400.00,4,386,14,0.00,0.00,0.00,0.00,0.00,NULL,NULL,'2021-08-30 06:48:22','2021-08-30 07:03:03','2019-12-31 21:00:00','0','Agency Admin','Revoked Booking'),(15,1875,6,NULL,'Nairobi Staycation Offers','T19216','2021-09-10',NULL,2,NULL,1500,0,3000,'active',3000.00,1.00,2999.00,4,2895,105,0.00,0.00,0.00,0.00,0.00,NULL,'Milestone','2021-08-30 06:54:04','2021-08-30 06:54:04','2019-12-31 21:00:00','0','Agency Admin','Create Booking'),(16,3712,6,NULL,'Masai Mara Test','T11719','2021-09-30',2,2,100,100,200,200,'revoked',400.00,0.00,400.00,4,386,14,0.00,0.00,0.00,0.00,0.00,NULL,'Pay 30% to get hotel booking','2021-08-30 06:58:07','2021-08-30 07:02:58','2019-12-31 21:00:00','0','Agency Admin','Revoked Booking'),(17,15028,6,NULL,'Masai Mara Testing','T14326','2021-10-30',2,3,500,1000,1000,3000,'active',4000.00,610.00,3390.00,4,3860,140,0.00,0.00,0.00,0.00,0.00,NULL,'50% to reserve your','2021-08-30 07:43:16','2021-08-30 07:43:16','2019-12-31 21:00:00','0','Agency Admin','Create Booking'),(18,3712,6,NULL,'Testing','T40281','2021-10-31',2,2,1000,1000,2000,2000,'revoked',0.00,0.00,0.00,4,0,0,0.00,0.00,0.00,0.00,0.00,NULL,'30%','2021-09-02 04:12:27','2021-09-07 05:21:46','2019-12-31 21:00:00','0','Agency Admin','Revoked Booking'),(19,15611,6,NULL,'Nakuru 3 days 2 nights','T20865','2021-10-30',2,2,500,1000,1000,2000,'active',3000.00,520.00,2480.00,4,2895,105,0.00,0.00,0.00,0.00,0.00,NULL,'30% confirm your booking','2021-09-02 05:23:14','2021-09-02 05:23:14','2019-12-31 21:00:00','0','Agency Admin','Create Booking'),(20,1875,6,NULL,'Nairobi Staycation Offers','T6750','2021-09-09',2,2,500,1000,1000,2000,'active',3000.00,20.00,2980.00,0,0,0,0.00,0.00,0.00,0.00,0.00,NULL,'Milestone','2021-09-07 05:46:15','2021-09-07 05:46:15','2019-12-31 21:00:00','0','Agency Admin','Create Booking'),(21,3712,6,NULL,'Testing 123','T75559','2021-09-30',2,2,1000,1000,2000,2000,'active',4000.00,11.00,3989.00,0,0,0,0.00,0.00,0.00,0.00,0.00,NULL,NULL,'2021-09-07 05:59:06','2021-09-07 05:59:06','2019-12-31 21:00:00','0','Agency Admin','Create Booking'),(22,16627,6,NULL,'Naivasha 2 days 1 night','T74647','2021-09-10',2,3,10000,15000,20000,45000,'active',65000.00,110.00,64890.00,0,0,0,0.00,0.00,0.00,0.00,0.00,NULL,'40% we confirm your booking','2021-09-07 06:27:39','2021-09-07 06:27:39','2019-12-31 21:00:00','0','Agency Admin','Create Booking');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branch_users`
--

DROP TABLE IF EXISTS `branch_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `branch_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branch_users_branch_id_foreign` (`branch_id`),
  KEY `branch_users_user_id_foreign` (`user_id`),
  CONSTRAINT `branch_users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `agency_branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `branch_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch_users`
--

LOCK TABLES `branch_users` WRITE;
/*!40000 ALTER TABLE `branch_users` DISABLE KEYS */;
INSERT INTO `branch_users` VALUES (1,9,1,'Levi','rdfyne@gmail.com','2021-08-18 05:56:33','2021-08-18 05:56:33');
/*!40000 ALTER TABLE `branch_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cancellation_fees`
--

DROP TABLE IF EXISTS `cancellation_fees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cancellation_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rate` double(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cancellation_fees`
--

LOCK TABLES `cancellation_fees` WRITE;
/*!40000 ALTER TABLE `cancellation_fees` DISABLE KEYS */;
/*!40000 ALTER TABLE `cancellation_fees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) unsigned NOT NULL,
  `city_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_country_id_foreign` (`country_id`),
  CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (10,2,'Nairobi','2021-08-18-08-49-11nairobi.jpg','Kenya-Nairobi','2021-08-18 05:49:11','2021-08-18 05:49:11');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rate` double(2,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commissions`
--

LOCK TABLES `commissions` WRITE;
/*!40000 ALTER TABLE `commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `continents`
--

DROP TABLE IF EXISTS `continents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `continents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `continent_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `continents`
--

LOCK TABLES `continents` WRITE;
/*!40000 ALTER TABLE `continents` DISABLE KEYS */;
INSERT INTO `continents` VALUES (2,'Africa','2021-08-18 05:48:02','2021-08-18 05:48:02');
/*!40000 ALTER TABLE `continents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `counties`
--

DROP TABLE IF EXISTS `counties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `counties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `county_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `counties`
--

LOCK TABLES `counties` WRITE;
/*!40000 ALTER TABLE `counties` DISABLE KEYS */;
INSERT INTO `counties` VALUES (48,'Mombasa','2021-08-18 06:00:53','2021-08-18 06:00:53'),(49,'Kwale','2021-08-19 23:20:25','2021-08-19 23:20:25'),(50,'Kilifi','2021-08-19 23:20:31','2021-08-19 23:20:31'),(51,'Tana River','2021-08-19 23:20:39','2021-08-19 23:20:39'),(52,'Lamu','2021-08-19 23:20:50','2021-08-19 23:20:50'),(53,'Taita–Taveta','2021-08-19 23:20:56','2021-08-19 23:20:56'),(54,'Garissa','2021-08-19 23:21:06','2021-08-19 23:21:06'),(55,'Wajir','2021-08-19 23:21:12','2021-08-19 23:21:12'),(56,'Mandera','2021-08-19 23:21:19','2021-08-19 23:21:19'),(57,'Marsabit','2021-08-19 23:21:29','2021-08-19 23:21:29'),(58,'Isiolo','2021-08-19 23:21:35','2021-08-19 23:21:35'),(59,'Meru','2021-08-19 23:21:40','2021-08-19 23:21:40'),(60,'Tharaka-Nithi','2021-08-19 23:21:51','2021-08-19 23:21:51'),(61,'Embu','2021-08-19 23:21:56','2021-08-19 23:21:56'),(62,'Kitui','2021-08-19 23:22:02','2021-08-19 23:22:02'),(63,'Machakos','2021-08-19 23:22:14','2021-08-19 23:22:14'),(64,'Makueni','2021-08-19 23:22:21','2021-08-19 23:22:21'),(65,'Nyandarua','2021-08-19 23:22:43','2021-08-19 23:22:43'),(66,'Nyeri','2021-08-19 23:22:52','2021-08-19 23:22:52'),(67,'Kirinyaga','2021-08-19 23:22:59','2021-08-19 23:22:59'),(68,'Murang\'a','2021-08-19 23:23:45','2021-08-19 23:23:45'),(69,'Kiambu','2021-08-19 23:23:56','2021-08-19 23:23:56'),(70,'Turkana','2021-08-19 23:24:08','2021-08-19 23:24:08'),(71,'West Pokot','2021-08-19 23:24:22','2021-08-19 23:24:22'),(72,'Samburu','2021-08-19 23:25:10','2021-08-19 23:25:10'),(73,'Trans-Nzoia','2021-08-19 23:25:18','2021-08-19 23:25:42'),(74,'Uasin Gishu','2021-08-19 23:26:05','2021-08-19 23:26:05'),(75,'Elgeyo-Marakwet','2021-08-19 23:26:22','2021-08-19 23:26:22'),(76,'Nandi','2021-08-19 23:26:32','2021-08-19 23:26:32'),(77,'Baringo','2021-08-19 23:26:39','2021-08-19 23:26:39'),(78,'Laikipia','2021-08-19 23:26:54','2021-08-19 23:26:54'),(79,'Nakuru','2021-08-19 23:27:23','2021-08-19 23:27:23'),(80,'Narok','2021-08-19 23:27:28','2021-08-19 23:27:28'),(81,'Kajiado','2021-08-19 23:27:34','2021-08-19 23:27:34'),(82,'Kericho','2021-08-19 23:27:49','2021-08-19 23:27:49'),(83,'Bomet','2021-08-19 23:27:54','2021-08-19 23:27:54'),(84,'Kakamega','2021-08-19 23:28:15','2021-08-19 23:28:15'),(85,'Vihiga','2021-08-19 23:28:22','2021-08-19 23:28:22'),(86,'Bungoma','2021-08-19 23:28:32','2021-08-19 23:28:32'),(87,'Busia','2021-08-19 23:28:38','2021-08-19 23:28:38'),(88,'Siaya','2021-08-19 23:28:50','2021-08-19 23:28:50'),(89,'Kisumu','2021-08-19 23:28:56','2021-08-19 23:28:56'),(90,'Homa Bay','2021-08-19 23:29:08','2021-08-19 23:29:08'),(91,'Migori','2021-08-19 23:29:18','2021-08-19 23:29:18'),(92,'Kisii','2021-08-19 23:29:26','2021-08-19 23:29:26'),(93,'Nyamira','2021-08-19 23:29:35','2021-08-19 23:29:35'),(94,'Nairobi','2021-08-19 23:29:41','2021-08-19 23:29:41');
/*!40000 ALTER TABLE `counties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `continent_id` bigint(20) unsigned NOT NULL,
  `country_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `countries_continent_id_foreign` (`continent_id`),
  CONSTRAINT `countries_continent_id_foreign` FOREIGN KEY (`continent_id`) REFERENCES `continents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (2,2,'KE','Kenya','2021-08-18 05:48:22','2021-08-18 05:48:22');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_abr` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `county_id` bigint(20) unsigned NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bookings_count` int(11) NOT NULL DEFAULT '0',
  `balance` double(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_user_id_foreign` (`user_id`),
  CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16628 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1875,1893,48,'254713302589',2,0.00,'2021-08-30 06:54:04','2021-09-07 05:46:15','Nairobi'),(1883,1901,48,'254790535349',7,0.00,'2021-08-19 13:49:43','2021-08-19 17:22:36','Nairobi'),(3712,3762,48,'254713124436',5,0.00,'2021-08-20 02:37:42','2021-09-07 05:59:06','test'),(12928,3763,94,'254792080300',0,19800.99,'2021-08-20 04:19:33','2021-08-20 04:32:28','CBD'),(15028,3764,94,'254721140771',1,0.00,'2021-08-30 07:43:15','2021-08-30 07:43:16','Nairobi City'),(15611,3766,94,'254711756740',1,0.00,'2021-09-02 05:23:14','2021-09-02 05:23:14','Westlands'),(16627,3767,94,'254708888303',1,0.00,'2021-09-07 06:27:39','2021-09-07 06:27:39','Westlands');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotels`
--

DROP TABLE IF EXISTS `hotels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `hotel_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001Test-Hotel',
  `ai_one_person_cost` double(10,2) DEFAULT NULL,
  `ai_two_people_cost` double(10,2) DEFAULT NULL,
  `ai_group_cost` double(10,2) DEFAULT NULL,
  `ai_kids_cost` double(10,2) DEFAULT NULL,
  `fb_one_person_cost` double(10,2) DEFAULT NULL,
  `fb_two_people_cost` double(10,2) DEFAULT NULL,
  `fb_group_cost` double(10,2) DEFAULT NULL,
  `fb_kids_cost` double(10,2) DEFAULT NULL,
  `hb_one_person_cost` double(10,2) DEFAULT NULL,
  `hb_two_people_cost` double(10,2) DEFAULT NULL,
  `hb_group_cost` double(10,2) DEFAULT NULL,
  `hb_kids_cost` double(10,2) DEFAULT NULL,
  `bb_one_person_cost` double(10,2) DEFAULT NULL,
  `bb_two_people_cost` double(10,2) DEFAULT NULL,
  `bb_group_cost` double(10,2) DEFAULT NULL,
  `bb_kids_cost` double(10,2) DEFAULT NULL,
  `w_land_one_person_cost` double(10,2) DEFAULT NULL,
  `w_land_two_people_cost` double(10,2) DEFAULT NULL,
  `w_land_group_cost` double(10,2) DEFAULT NULL,
  `w_land_kids_cost` double(10,2) DEFAULT NULL,
  `w_van_one_person_cost` double(10,2) DEFAULT NULL,
  `w_van_two_people_cost` double(10,2) DEFAULT NULL,
  `w_van_group_cost` double(10,2) DEFAULT NULL,
  `w_van_kids_cost` double(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `w_bus_one_person_cost` double(10,2) DEFAULT NULL,
  `w_bus_two_people_cost` double(10,2) DEFAULT NULL,
  `w_bus_group_cost` double(10,2) DEFAULT NULL,
  `w_bus_kids_cost` double(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotels`
--

LOCK TABLES `hotels` WRITE;
/*!40000 ALTER TABLE `hotels` DISABLE KEYS */;
/*!40000 ALTER TABLE `hotels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) unsigned NOT NULL,
  `period` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `unit` int(11) NOT NULL DEFAULT '0',
  `transactions` int(11) NOT NULL DEFAULT '0',
  `amount_paid` double(8,2) NOT NULL DEFAULT '0.00',
  `balance` double(8,2) NOT NULL DEFAULT '0.00',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_paid',
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `date_invoiced` timestamp NOT NULL DEFAULT '2021-09-01 10:47:18',
  `ref` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (2,6,'Sep 1st, 2021 to Sep 30th, 2021',200.00,50,5,0.00,200.00,'not_paid','2021-09-01','2021-09-30',0,'2021-09-06 08:34:21','INV1730','2021-09-06 08:34:21','2021-09-07 06:34:09');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2021_04_10_191504_create_continents_table',1),(5,'2021_04_10_191724_create_countries_table',1),(6,'2021_04_10_192414_create_cities_table',1),(7,'2021_04_10_192827_create_categories_table',1),(8,'2021_04_10_192828_create_travel_agents_table',1),(9,'2021_04_10_192953_create_travel_packages_table',1),(10,'2021_04_10_194408_create_package_galleries_table',1),(11,'2021_04_10_194703_create_package_categories_table',1),(12,'2021_04_10_200623_create_commissions_table',1),(13,'2021_04_10_200819_create_customers_table',1),(14,'2021_04_10_200820_create_bookings_table',1),(15,'2021_04_10_200821_create_payment_logs_table',1),(16,'2021_04_10_200822_create_payments_table',1),(17,'2021_04_10_205739_create_agent_commissions_table',1),(18,'2021_04_10_210225_create_admin_commissions_table',1),(19,'2021_04_16_181531_add_company_logo_to_travel_agents_table',1),(20,'2021_04_16_182148_add_role_to_users_table',1),(21,'2021_04_21_180129_add_comission_to_countries_table',1),(22,'2021_04_21_193720_add_continent_id_to_travel_packages',1),(23,'2021_04_21_201142_add_currency_to_travel_packages',1),(24,'2021_04_21_201637_create_currencies_table',1),(25,'2021_04_21_210705_add_continent_id_to_travel_agents',1),(26,'2021_04_22_073848_add_cover_image_to_cities_table',1),(27,'2021_04_22_081927_add_slug_to_cities_table',1),(28,'2021_04_22_090850_add_duration_to_travel_packages_table',1),(29,'2021_04_22_092445_add_slug_to_travel_packages_table',1),(30,'2021_04_22_170454_create_counties_table',1),(31,'2021_04_22_204937_create_s_m_s_logs_table',1),(32,'2021_04_26_082532_add_status_to_payment_logs_table',1),(33,'2021_05_19_072041_drop_costs_on_travel_packages_table',1),(34,'2021_05_21_182153_create_hotels_table',1),(35,'2021_05_25_050510_add_package_code_to_travel_packages',1),(36,'2021_05_25_184220_add_hotel_id_to_bookings_table',1),(37,'2021_05_26_055205_add_bookings_count_to_customers_table',1),(38,'2021_05_28_041543_rename_city_id_to_city_on_customers_table',1),(39,'2021_05_31_195308_add_balance_to_customers_table',1),(40,'2021_06_01_095551_create_cancellation_fees_table',1),(41,'2021_06_09_064804_add_slug_to_hotels_table',1),(42,'2021_06_09_072344_add_commission_to_travel_agents_table',1),(43,'2021_06_09_072729_drop_commission_on_countries_table',1),(44,'2021_06_09_085410_add_with_bus_costs_to_hotels_table',1),(45,'2021_06_10_224840_add_code_to_travel_agents',2),(46,'2021_06_14_195043_add_currency_and_default_currency_to_travel_packages',3),(47,'2021_06_14_212918_create_without_accommodations_table',3),(48,'2021_06_14_213925_add_without_accommodation_columns_to_bookings_table',3),(49,'2021_06_15_125101_add_title_to_without_accommodations_table',4),(50,'2021_06_15_165106_add_w_bus_costs_to_without_accommodations_table',4),(51,'2021_06_15_165147_add_w_bus_costs_to_bookings_table',4),(52,'2021_06_15_184314_rename_w_bus_costs_to_without_accommodations_table',4),(53,'2021_06_16_075821_add_slug_to_without_accommodations_table',5),(54,'2021_06_16_090633_add_without_id_to_bookings_table',5),(55,'2021_07_31_082139_create_agency_branches_table',6),(56,'2021_07_31_092036_add_user_id_and_branch_name_agency_branches_table',6),(57,'2021_07_31_095204_drop_agent_foregn_key_constraint_agency_branches_table',6),(58,'2021_08_02_111732_restructure_bookings_table',7),(59,'2021_08_02_113829_create_booking_change_logs_table',7),(60,'2021_08_02_115644_delete_more_columns_on_bookings_table',7),(61,'2021_08_02_125154_delete_package_id_on_bookings_table',7),(62,'2021_08_02_131435_delete_variation_on_bookings_table',7),(63,'2021_08_03_032720_change_no_of_kids_to_nullable_on_bookings_table',7),(64,'2021_08_05_190954_add_date_paid_and_comment_to_payment_logs',8),(65,'2021_08_06_074047_drop_user_id_on_agency_branches_table',8),(66,'2021_08_06_074203_create_branch_users_table',8),(67,'2021_08_06_114832_add_slug_to_travel_agents',9),(68,'2021_08_07_114850_add_wallet_balance_to_travel_agents_table',10),(69,'2021_08_07_120931_change_default_for_comment_payment_logs_table',10),(70,'2021_08_07_125041_create_withdrawal_requests_table',10),(71,'2021_08_07_132426_add_transac_cost_to_withdrawal_requests',10),(72,'2021_08_11_083853_add__conversation_i_d__originator_conversation_i_d__response_code_and__response_description_to_withdrawal_requests_table',11),(73,'2021_08_11_121518_add_agent_cancellatiion_fee_and_system_cancellatiion_fee_and_customer_balance_to_bookings_table',11),(74,'2021_08_11_151243_add_amount_refunded_and_amount_r_balance_to_bookings_table',12),(75,'2021_08_11_175813_add_customer_id_to_withdrawal_requests_table',12),(76,'2021_08_13_090458_add_other_columns_on_the_withdrawal_requests_table',13),(77,'2021_08_13_120716_add_fail_reason_on_withdrawal_requests',14),(78,'2021_08_18_073200_add_commission_to_payments_table',15),(79,'2021_08_19_192657_add_setdateandreminder_to_bookings_table',16),(80,'2021_08_19_193032_create_admin_wallets_table',17),(81,'2021_08_20_053130_add_online_payments_and_offline_payments_columns_on_the_travel_agents_table',18),(82,'2021_08_23_083729_create_agent_custom_credentials_table',19),(83,'2021_08_23_083916_add_agent_custom_credentials_to_trave_agents_table',19),(84,'2021_08_23_092241_add_agent_sms_provider_to_travel_agents_table',19),(85,'2021_08_23_104224_add_agent_id_and_transaction_type_on_payments_table',19),(86,'2021_08_23_124928_add_agent_own_payment_to_travel_agents_table',20),(87,'2021_08_23_131015_add_sms_credits_to_travel_agents_table',20),(88,'2021_08_23_131352_create_s_m_s_top_ups_table',20),(89,'2021_08_23_202309_add_payment_log_id_to_s_m_s_top_ups_table',21),(90,'2021_08_24_101949_add_registration-steps_to_travel-agents_table',22),(91,'2021_08_24_104856_add_register_url_to_travel_agents_table',22),(92,'2021_08_26_052233_add_user_field_to_bookings_table',23),(93,'2021_08_29_194457_create_agent_accountusers_table',24),(94,'2021_08_29_195612_create_agent_accountuser_roles_table',24),(95,'2021_08_29_204814_add_email_to_agent_accountusers_table',24),(96,'2021_08_30_085615_add_milestones_column_to_bookings_table',25),(97,'2021_08_30_103923_add_system_commission_to_travel_agents',26),(98,'2021_08_30_192328_rename_system_commission_to_travel_agents',26),(99,'2021_08_31_115641_add_balance_to_payments_table',27),(100,'2021_08_31_123328_add_own_paybill_payments_to_travel_agents_table',27),(101,'2021_09_01_125231_add_invoiced_to_payments_table',27),(102,'2021_09_01_125859_create_invoices_table',27),(103,'2021_09_01_172611_change_paid_to_boolean',28),(104,'2021_09_01_175513_add_payment_code_to_invoices',28),(105,'2021_09_02_183028_add_system_payment_cost_and_internal_payment_cost_to_travel_agents_table',29),(106,'2021_09_02_222647_add_amount_paid_and_balance_to_invoices_table',30),(107,'2021_09_06_060940_add_transactions_to_invoices_table',31),(108,'2021_09_06_065632_add_unit_to_invoices_table',31),(109,'2021_09_06_074851_change_default_value_for_internal_payment_cost_to_invoices_table',32),(110,'2021_09_07_082745_change_defaults_for_commission_rate_agent_commission_admin_commission_on_bookings_table',33),(111,'2021_09_08_060412_create_system_commissions_table',34);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package_categories`
--

DROP TABLE IF EXISTS `package_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_categories_package_id_foreign` (`package_id`),
  KEY `package_categories_category_id_foreign` (`category_id`),
  CONSTRAINT `package_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `package_categories_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `travel_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_categories`
--

LOCK TABLES `package_categories` WRITE;
/*!40000 ALTER TABLE `package_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `package_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package_galleries`
--

DROP TABLE IF EXISTS `package_galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package_galleries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_galleries_package_id_foreign` (`package_id`),
  CONSTRAINT `package_galleries_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `travel_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_galleries`
--

LOCK TABLES `package_galleries` WRITE;
/*!40000 ALTER TABLE `package_galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `package_galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_logs`
--

DROP TABLE IF EXISTS `payment_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `TransactionType` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TransID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TransTime` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TransAmount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BusinessShortCode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BillRefNumber` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `InvoiceNumber` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OrgAccountBalance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ThirdPartyTransID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MSISDN` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FirstName` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MiddleName` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `LastName` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid',
  `date_recorded` date DEFAULT NULL,
  `comment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'No comment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_logs`
--

LOCK TABLES `payment_logs` WRITE;
/*!40000 ALTER TABLE `payment_logs` DISABLE KEYS */;
INSERT INTO `payment_logs` VALUES (26,'Pay Bill','PHI3QXAE19','20210818120535','500.00','900500','T5924','','501.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-18 06:05:37','2021-08-18 06:05:37'),(27,'Pay Bill','PHI2QYJCYC','20210818123157','100.00','900500','T5924','0','601.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-18 06:31:59','2021-08-18 06:31:59'),(28,'Pay Bill','PHI5QYOJKH','20210818123456','100.00','900500','T5924','0','701.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-18 06:34:58','2021-08-18 06:34:58'),(29,'Offline Payment','PQ9OWJJ','1629279706','10000','900500','T5924','PQ9OWJJ','','','254713124436','Test Masha','','','valid','2021-08-18','Paid with cheque','2021-08-17 21:00:00','2021-08-18 06:41:46'),(30,'Pay Bill','PHI4RAUX4A','20210818164113','100.00','4040299','enter','','4898.00','','254794678898','Tabitha','Wangeci','Chege','valid',NULL,'No comment','2021-08-18 13:41:15','2021-08-18 13:41:15'),(31,'Pay Bill','PHJ4SEEMGU','20210819125351','500.00','4040299','japheth lukhachi','','3099.00','','254721770647','JAPHETH','ELKANA','LUKHACHI','valid',NULL,'No comment','2021-08-19 09:53:52','2021-08-19 09:53:52'),(32,'Pay Bill','PHJ9T72E5R','20210819204432','1.00','4040299','T68484','','6171.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-19 17:44:34','2021-08-19 17:44:34'),(33,'Pay Bill','PHJ8T752T6','20210819204550','1.00','4040299','T48222','','6172.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-19 17:45:52','2021-08-19 17:45:52'),(34,'Pay Bill','PHJ4T7QQQA','20210819205650','1.00','4040299','T14886','','6223.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-19 17:56:52','2021-08-19 17:56:52'),(35,'Pay Bill','PHJ8T7YD1E','20210819210044','1.00','4040299','T14886','','6224.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-19 18:00:46','2021-08-19 18:00:46'),(36,'Pay Bill','PHJ8T8VUT6','20210819212022','4.00','4040299','T75559','','6258.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-19 18:20:24','2021-08-19 18:20:24'),(37,'Offline Payment','PQ9OWJJ','1629397624','1','4040299','T75559','PQ9OWJJ','','','254713124436','Test Masha','','','valid','2021-08-19','Paid in cash','2021-08-18 21:00:00','2021-08-19 15:27:04'),(38,'Offline Payment','PQ9OWJJ23','1629397717','1','4040299','T75559','PQ9OWJJ23','','','254713124436','Test Masha','','','valid','2021-08-19','Cheque','2021-08-18 21:00:00','2021-08-19 15:28:37'),(39,'Pay Bill','PHJ6T9G944','20210819213356','1.00','4040299','T75559','0','6259.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-19 18:33:57','2021-08-19 18:33:57'),(40,'Pay Bill','PHJ5T9W4PB','20210819214553','3.00','4040299','T90508','','6462.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-19 18:45:55','2021-08-19 18:45:55'),(41,'Pay Bill','PHJ4T9Y9DK','20210819214741','1.00','4040299','T32060','','6463.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-19 18:47:42','2021-08-19 18:47:42'),(42,'Pay Bill','PHJ4TBV2PQ','20210819232051','1.00','4040299','T32060','','6799.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-19 20:20:53','2021-08-19 20:20:53'),(43,'Pay Bill','PHJ6TBVT9C','20210819232307','1.00','4040299','T95915','','6800.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-19 20:23:08','2021-08-19 20:23:08'),(44,'Offline Payment','REF00459','1629408952','1','4040299','T75559','REF00459','','','254713124436','Test Masha','','','valid','2021-08-19','Finished paying','2021-08-19 21:00:00','2021-08-19 18:35:52'),(45,'Offline Payment','REF00459','1629409071','1','4040299','T75559','REF00459','','','254713124436','Test Masha','','','valid','2021-08-19','Finished paying','2021-08-19 21:00:00','2021-08-19 18:37:51'),(46,'Offline Payment','REF00459','1629409108','1','4040299','T75559','REF00459','','','254713124436','Test Masha','','','valid','2021-08-19','Finished paying','2021-08-19 21:00:00','2021-08-19 18:38:28'),(47,'Offline Payment','PQ9OWJJ23E','1629429517','1','4040299','T90508','PQ9OWJJ23E','','','254713124436','Test Masha','','','valid','2021-08-20','Cash','2021-08-18 21:00:00','2021-08-20 00:18:37'),(48,'Offline Payment','PQ9OWJJ0','1629429550','1','4040299','T90508','PQ9OWJJ0','','','254713124436','Test Masha','','','valid','2021-08-20','Cash','2021-08-18 21:00:00','2021-08-20 00:19:10'),(49,'Offline Payment','PQ9OWJJ23E','1629429849','1','4040299','T90508','PQ9OWJJ23E','','','254713124436','Test Masha','','','valid','2021-08-20','Test','2021-08-19 21:00:00','2021-08-20 00:24:09'),(50,'Pay Bill','PHK4TDK98A','20210820062601','1.00','4040299','T90508','0','6901.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-20 03:26:03','2021-08-20 03:26:03'),(51,'Pay Bill','PHK2TI55WI','20210820085913','1.00','4040299','T95915','','201.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-20 05:59:15','2021-08-20 05:59:15'),(52,'Offline Payment','REF00458','1629442201','10','4040299','T82462','REF00458','','','254713124436','test','','','valid','2021-08-20','Cash','2021-08-19 21:00:00','2021-08-20 03:50:01'),(53,'Offline Payment','REF00458','1629442320','10','4040299','T82462','REF00458','','','254713124436','test','','','valid','2021-08-20','Cash','2021-08-19 21:00:00','2021-08-20 03:52:00'),(54,'Offline Payment','REF00458','1629442397','10','4040299','T82462','REF00458','','','254713124436','test','','','valid','2021-08-20','Cash','2021-08-19 21:00:00','2021-08-20 03:53:17'),(55,'Offline Payment','cash','1629444226','20000','4040299','T85300','cash','','','254792080300','Vivian','','','valid','2021-08-20','hghhjhfvgfyjg','2021-08-19 21:00:00','2021-08-20 04:23:46'),(56,'Pay Bill','PHK7TM51CX','20210820102820','1.00','4040299','T85300','0','3302.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-20 07:28:22','2021-08-20 07:28:22'),(57,'Pay Bill','PHK4U10N6Y','20210820152907','320.00','900500','','','9987.00','','254796026033','BORU','JATTANI','SARE','invalid',NULL,'No comment','2021-08-20 09:29:09','2021-08-20 09:29:09'),(58,'Pay Bill','PHL5VLVA7Z','20210821163650','1000.00','900500','N955','','10987.00','','254722631477','ROSE','AKOTH','NYAMORI','invalid',NULL,'No comment','2021-08-21 10:36:52','2021-08-21 10:36:52'),(59,'Pay Bill','PHN8XVWRDW','20210823110825','1.00','4040299','T95915','','1921.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-23 08:08:27','2021-08-23 08:08:27'),(60,'Offline Payment','cash','1629706200','2','4040299','T95915','cash','','','254790535349','Brian Mutiso Munywoki','','','valid','2021-08-23','paid in cash','2021-08-22 21:00:00','2021-08-23 05:10:00'),(61,'Pay Bill','PHO5Z01SYH','20210824062117','10.00','4040299','T95915','','10.00','','254790535349','brian','mutiso','munywoki','valid',NULL,'No comment','2021-08-24 03:21:18','2021-08-24 03:21:18'),(62,'Own Pay Bill','PHO0Z2CQ7I','20210824075854','1000.00','900500','g521b','','1000.00','','254722371220','JANE','WAMBUI','KIBE','invalid',NULL,'No comment','2021-08-24 01:58:57','2021-08-24 01:58:57'),(63,'Own Pay Bill','PHO0ZN7SLQ','20210824152555','1.00','900500','3','','1.00','','254799518556','LYDIA','CHEPKEMOI','TONUI','invalid',NULL,'No comment','2021-08-24 09:25:57','2021-08-24 09:25:57'),(64,'Own Pay Bill','PHP31MX8NB','20210825100650','6140.00','900500','247247','','6141.00','','254729398305','DARARE','MOLU','WATO','invalid',NULL,'No comment','2021-08-25 04:06:52','2021-08-25 04:06:52'),(65,'SMS Credit Topup','PHQ62WASZO','20210826064754','100.00','4040299','testagent','0','100.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-26 03:47:56','2021-08-26 03:47:56'),(66,'Own Pay Bill','PHQ2384LO2','20210826112614','2000.00','900500','M1280','','2000.00','','254722302906','MARY','KWAMBOKA','MOSOTI','invalid',NULL,'No comment','2021-08-26 05:26:16','2021-08-26 05:26:16'),(67,'Own Pay Bill','PHR757VE0P','20210827165853','700.00','900500','m1058','','700.00','','254722517879','SAMUEL','GITHUI','MITHAMO','invalid',NULL,'No comment','2021-08-27 10:58:55','2021-08-27 10:58:55'),(68,'Own Pay Bill','PHS05XXJOQ','20210828074915','3000.00','900500','N266','','3700.00','','254722306067','JOHN','NJUGUNA','NGETHE','invalid',NULL,'No comment','2021-08-28 01:49:17','2021-08-28 01:49:17'),(69,'Own Pay Bill','PHS267HZDO','20210828110623','6000.00','900500','k403','','9700.00','','254721624525','JOSEPH','KIMURA','','invalid',NULL,'No comment','2021-08-28 05:06:26','2021-08-28 05:06:26'),(70,'Offline Payment','PQ9OWJJ23ER','1630320450','500','4040299','T14326','PQ9OWJJ23ER','','','254721140771','Kim Jack','','','valid','2021-08-30','Paid to my personal number','2021-08-29 21:00:00','2021-08-30 07:47:30'),(71,'Offline Payment','PQ9OWJJ234','1630320530','100','4040299','T14326','PQ9OWJJ234','','','254721140771','Kim Jack','','','valid','2021-08-30','Paid','2021-08-29 21:00:00','2021-08-30 07:48:50'),(72,'Pay Bill','PHU19F3KRR','20210830140501','10.00','4040299','T14326','0','3560.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-08-30 11:05:03','2021-08-30 11:05:03'),(73,'Offline Payment','Cash to qqq','1630571205','500','4040299','T20865','Cash to qqq','','','254711756740','Brends','','','valid','2021-09-02','Paid in cash','2021-09-01 21:00:00','2021-09-02 05:26:45'),(74,'Pay Bill','PI27DO3EFX','20210902112841','10.00','4040299','T20865','0','1510.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-09-02 08:28:43','2021-09-02 08:28:43'),(75,'Offline Payment','REF00456','1630914211','100','4040299','T82462','REF00456','','','254713124436','test','','','valid','2021-09-06','Test','2021-09-04 21:00:00','2021-09-06 04:43:31'),(76,'Own Pay Bill','PI62JNWZDU','20210906113348','10225.00','900500','247247','','10225.00','','254729951379','KULE','ABDUBA','IRGO','invalid',NULL,'No comment','2021-09-06 05:33:50','2021-09-06 05:33:50'),(77,'Own Pay Bill','PI68JXNNIC','20210906143419','10.00','900500','T20865','0','10235.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-09-06 08:34:21','2021-09-06 08:34:21'),(78,'Pay Bill','PI68JXQDI4','20210906143543','10.00','4040299','T95915','0','10738.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-09-06 11:35:45','2021-09-06 11:35:45'),(79,'Offline Payment','REF00456HHA','1630928590','100','4040299','T95915','REF00456HHA','','','254790535349','Brian Mutiso Munywoki','','','valid','2021-09-06','Cash','2021-09-05 21:00:00','2021-09-06 08:43:10'),(80,'Pay Bill','PI76L78K9Q','20210907114833','10.00','4040299','T6750','0','8011.00','','254713302589','Levi','Simiyu','Kisaka','valid',NULL,'No comment','2021-09-07 08:48:35','2021-09-07 08:48:35'),(81,'Offline Payment','REF004567','1631004557','10','4040299','T6750','REF004567','','','254713302589','Levi Kisaka','','','valid','2021-09-07','Cash Payment','2021-09-05 21:00:00','2021-09-07 05:49:17'),(82,'Offline Payment','A','1631005190','10','4040299','T75559','A','','','254713124436','test','','','valid','2021-09-07','Cash','2021-09-06 21:00:00','2021-09-07 05:59:50'),(83,'Pay Bill','PI76L9EWSW','20210907123118','10.00','4040299','T74647','0','8021.00','','254713124436','Amos','Chengo','Masha','valid',NULL,'No comment','2021-09-07 09:31:21','2021-09-07 09:31:21'),(84,'Offline Payment','cash','1631007249','100','4040299','T74647','cash','','','254708888303','Rachael','','','valid','2021-09-07','paid in cash','2021-09-06 21:00:00','2021-09-07 06:34:09'),(85,'Pay Bill','JSJAMAMAN','20210310142343','1','4040299','TEST123',NULL,NULL,'254713302589','254713302589','Test','Test','Test','verified',NULL,NULL,'2021-09-08 08:30:26','2021-09-08 08:30:26'),(86,'Pay Bill','JSJAMAMAN','20210310142343','1','4040299','TEST123',NULL,NULL,'254713302589','254713302589','Test','Test','Test','verified',NULL,NULL,'2021-09-08 08:32:16','2021-09-08 08:32:16'),(87,'Pay Bill','JSJAMAMAN','20210310142343','1','4040299','TEST123',NULL,NULL,'254713302589','254713302589','Test','Test','Test','verified',NULL,NULL,'2021-09-08 08:38:05','2021-09-08 08:38:05'),(88,'Pay Bill','JSJAMAMAN','20210310142343','1','4040299','TEST123',NULL,NULL,'254713302589','254713302589','Test','Test','Test','verified',NULL,NULL,'2021-09-08 08:42:47','2021-09-08 08:42:47'),(89,'Pay Bill','JSJAMAMAN','20210310142343','1','4040299','TEST123',NULL,NULL,'254713302589','254713302589','Test','Test','Test','verified',NULL,NULL,'2021-09-08 08:44:53','2021-09-08 08:44:53'),(90,'Pay Bill','JSJAMAMAN','20210310142343','1','4040299','TEST123',NULL,NULL,'254713302589','254713302589','Test','Test','Test','verified',NULL,NULL,'2021-09-08 08:47:25','2021-09-08 08:47:25'),(91,'Pay Bill','JSJAMAMAN','20210310142343','1','4040299','TEST123',NULL,NULL,'254713302589','254713302589','Test','Test','Test','verified',NULL,NULL,'2021-09-08 08:49:40','2021-09-08 08:49:40'),(92,'Pay Bill','JSJAMAMAN','20210310142343','1','4040299','TEST123',NULL,NULL,'254713302589','254713302589','Test','Test','Test','verified',NULL,NULL,'2021-09-08 08:56:40','2021-09-08 08:56:40');
/*!40000 ALTER TABLE `payment_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) unsigned DEFAULT NULL,
  `transaction_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_log_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned NOT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `admin_commission` double(10,2) NOT NULL DEFAULT '0.00',
  `balance` double(8,2) DEFAULT NULL,
  `invoiced` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_payment_log_id_foreign` (`payment_log_id`),
  KEY `payments_customer_id_foreign` (`customer_id`),
  CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_payment_log_id_foreign` FOREIGN KEY (`payment_log_id`) REFERENCES `payment_logs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (8,NULL,NULL,32,1883,6,1,0.04,NULL,0,'2021-08-19 17:44:34','2021-08-19 17:44:34'),(9,NULL,NULL,33,1883,3,1,0.04,NULL,0,'2021-08-19 17:45:52','2021-08-19 17:45:52'),(10,NULL,NULL,34,1883,7,1,0.04,NULL,0,'2021-08-19 17:56:52','2021-08-19 17:56:52'),(11,NULL,NULL,35,1883,7,1,0.04,NULL,0,'2021-08-19 18:00:46','2021-08-19 18:00:46'),(17,NULL,NULL,41,1883,10,1,0.04,NULL,0,'2021-08-19 18:47:42','2021-08-19 18:47:42'),(18,NULL,NULL,42,1883,10,1,0.04,NULL,0,'2021-08-19 20:20:53','2021-08-19 20:20:53'),(19,NULL,NULL,43,1883,11,1,0.04,NULL,0,'2021-08-19 20:23:08','2021-08-19 20:23:08'),(25,NULL,NULL,51,1883,11,1,0.04,NULL,0,'2021-08-20 05:59:15','2021-08-20 05:59:15'),(26,NULL,NULL,54,3712,12,10,0.00,NULL,0,'2021-08-20 03:53:17','2021-08-20 03:53:17'),(27,NULL,NULL,55,12928,13,20000,0.00,NULL,0,'2021-08-20 04:23:46','2021-08-20 04:23:46'),(28,NULL,NULL,56,12928,13,1,0.00,NULL,0,'2021-08-20 07:28:22','2021-08-20 07:28:22'),(29,NULL,NULL,59,1883,11,1,0.00,NULL,0,'2021-08-23 08:08:27','2021-08-23 08:08:27'),(30,NULL,NULL,60,1883,11,2,0.00,NULL,0,'2021-08-23 05:10:00','2021-08-23 05:10:00'),(31,NULL,NULL,61,1883,11,10,0.00,NULL,0,'2021-08-24 03:21:18','2021-08-24 03:21:18'),(32,NULL,NULL,70,15028,17,500,0.00,NULL,0,'2021-08-30 07:47:30','2021-08-30 07:47:30'),(33,NULL,NULL,71,15028,17,100,0.00,NULL,0,'2021-08-30 07:48:50','2021-08-30 07:48:50'),(34,NULL,NULL,72,15028,17,10,0.00,NULL,0,'2021-08-30 11:05:03','2021-08-30 11:05:03'),(35,6,'Offline Payment',73,15611,19,500,17.50,482.50,0,'2021-09-02 05:26:45','2021-09-02 05:26:45'),(36,6,'Pay Bill',74,15611,19,10,0.35,9.65,0,'2021-09-02 08:28:43','2021-09-02 08:28:43'),(37,6,'Offline Payment',75,3712,12,100,0.00,100.00,0,'2021-09-06 04:43:31','2021-09-06 04:43:31'),(38,6,'Own Pay Bill',77,15611,19,10,0.00,10.00,0,'2021-09-06 08:34:21','2021-09-06 08:34:21'),(39,6,'Pay Bill',78,1883,11,10,0.00,10.00,0,'2021-09-06 11:35:45','2021-09-06 11:35:45'),(40,6,'Offline Payment',79,1883,11,100,50.00,50.00,0,'2021-09-06 08:43:10','2021-09-06 08:43:10'),(41,6,'Pay Bill',80,1875,20,10,50.00,-40.00,0,'2021-09-07 08:48:35','2021-09-07 08:48:35'),(42,6,'Offline Payment',81,1875,20,10,50.00,-40.00,0,'2021-09-07 05:49:17','2021-09-07 05:49:17'),(43,6,'Offline Payment',82,3712,21,10,50.00,-40.00,0,'2021-09-07 05:59:51','2021-09-07 05:59:51'),(44,6,'Pay Bill',83,16627,22,10,50.00,-40.00,0,'2021-09-07 09:31:21','2021-09-07 09:31:21'),(45,6,'Offline Payment',84,16627,22,100,50.00,50.00,0,'2021-09-07 06:34:09','2021-09-07 06:34:09'),(46,6,'Pay Bill',91,1875,15,1,50.00,-49.00,0,'2021-09-08 08:49:40','2021-09-08 08:49:40'),(47,6,'Pay Bill',92,3712,21,1,50.00,-49.00,0,'2021-09-08 08:56:40','2021-09-08 08:56:40');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_m_s_logs`
--

DROP TABLE IF EXISTS `s_m_s_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_m_s_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `receiver` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `comment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'notification',
  `cost` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_confirmed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_m_s_logs`
--

LOCK TABLES `s_m_s_logs` WRITE;
/*!40000 ALTER TABLE `s_m_s_logs` DISABLE KEYS */;
INSERT INTO `s_m_s_logs` VALUES (1,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T10734 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-03 08:48:38','2021-08-03 08:48:38'),(2,'254713124436','Payment of KES. 1.00 received for Booking Ref. T10734, Payment ref PHI3QSIAL5. Balance KES. 1,499.00.','success','sent','wallet_update_notification','KES 0.8000','2021-08-18 04:23:53','2021-08-18 04:23:53'),(3,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T5924 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-18 06:05:05','2021-08-18 06:05:05'),(4,'254713124436','Payment of KES. 500.00 received for Booking Ref. T5924, Payment reference PHI3QXAE19. Balance KES. 49500.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-18 06:05:38','2021-08-18 06:05:38'),(5,'254713124436','Payment of KES. 100.00 received for Booking Ref. T5924, Payment reference PHI2QYJCYC. Balance KES. 49400.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-18 06:32:00','2021-08-18 06:32:00'),(6,'254713124436','Payment of KES. 100.00 received for Booking Ref. T5924, Payment reference PHI5QYOJKH. Balance KES. 49400.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-18 06:34:59','2021-08-18 06:34:59'),(7,'254713124436','Payment of KES. 10000 received for Booking Ref. T5924, Payment reference PQ9OWJJ. Balance KES. 39500.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-18 06:41:46','2021-08-18 06:41:46'),(8,'254790535349','Please Complete your booking. Use Paybill 900500, account number T48222 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-19 13:49:44','2021-08-19 13:49:44'),(9,'254790535349','Please Complete your booking. Use Paybill 900500, account number T79276 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-19 14:00:50','2021-08-19 14:00:50'),(10,'254790535349','Please Complete your booking. Use Paybill 4040299, account number T41504 and amount Ksh.1','success','sent','after_booking_notification','KES 0.8000','2021-08-19 14:37:58','2021-08-19 14:37:58'),(11,'254790535349','Please Complete your booking. Use Paybill 4040299, account number T68484 and amount Ksh.1','success','sent','after_booking_notification','KES 0.8000','2021-08-19 14:44:16','2021-08-19 14:44:16'),(12,'254790535349','Please Complete your booking. Use Paybill 4040299, account number T14886 and amount Ksh.1','success','sent','after_booking_notification','KES 0.8000','2021-08-19 14:56:38','2021-08-19 14:56:38'),(13,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T75559 and amount Ksh.4','success','sent','after_booking_notification','KES 0.8000','2021-08-19 15:20:07','2021-08-19 15:20:07'),(14,'254713124436','Payment of KES. 1 received for Booking Ref. T75559, Payment reference PQ9OWJJ. Balance KES. 3.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-19 15:27:05','2021-08-19 15:27:05'),(15,'254713124436','Payment of KES. 1 received for Booking Ref. T75559, Payment reference PQ9OWJJ23. Balance KES. 2.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-19 15:28:38','2021-08-19 15:28:38'),(16,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T90508 and amount Ksh.3','success','sent','after_booking_notification','KES 0.8000','2021-08-19 15:45:29','2021-08-19 15:45:29'),(17,'254790535349','Please Complete your booking. Use Paybill 4040299, account number T32060 and amount Ksh.1','success','sent','after_booking_notification','KES 0.8000','2021-08-19 15:47:24','2021-08-19 15:47:24'),(18,'254790535349','Please Complete your booking. Use Paybill 4040299, account number T95915 and amount Ksh.1','success','sent','after_booking_notification','KES 0.8000','2021-08-19 17:22:37','2021-08-19 17:22:37'),(19,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T82462 and amount Ksh.1','success','sent','after_booking_notification','KES 0.8000','2021-08-20 02:37:42','2021-08-20 02:37:42'),(20,'254713124436','Payment of KES. 10 received for Booking Ref. T82462, Payment reference REF00458. Balance KES. 6.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-20 03:53:17','2021-08-20 03:53:17'),(21,'254792080300','Please Complete your booking. Use Paybill 4040299, account number T85300 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-20 04:19:34','2021-08-20 04:19:34'),(22,'254792080300','Payment of KES. 20000 received for Booking Ref. T85300, Payment reference cash. Balance KES. 40000.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-20 04:23:47','2021-08-20 04:23:47'),(23,'254790535349','Payment of KES. 2 received for Booking Ref. T95915, Payment reference cash. Balance KES. 195.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-23 05:10:01','2021-08-23 05:10:01'),(24,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T30930 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-30 06:48:23','2021-08-30 06:48:23'),(25,'254713302589','Please Complete your booking. Use Paybill 4040299, account number T19216 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-30 06:54:05','2021-08-30 06:54:05'),(26,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T11719 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-30 06:58:08','2021-08-30 06:58:08'),(27,'254721140771','Please Complete your booking. Use Paybill 4040299, account number T14326 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-08-30 07:43:16','2021-08-30 07:43:16'),(28,'254721140771','Payment of KES. 500 received for Booking Ref. T14326, Payment reference PQ9OWJJ23ER. Balance KES. 3500.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-30 07:47:31','2021-08-30 07:47:31'),(29,'254721140771','Payment of KES. 100 received for Booking Ref. T14326, Payment reference PQ9OWJJ234. Balance KES. 3400.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-08-30 07:48:51','2021-08-30 07:48:51'),(30,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T40281 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-09-02 04:12:28','2021-09-02 04:12:28'),(31,'254711756740','Please Complete your booking. Use Paybill 4040299, account number T20865 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-09-02 05:23:15','2021-09-02 05:23:15'),(32,'254711756740','Payment of KES. 500 received for Booking Ref. T20865, Payment reference Cash to qqq. Balance KES. 2500.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-09-02 05:26:46','2021-09-02 05:26:46'),(33,'254713124436','Payment of KES. 100 received for Booking Ref. T82462, Payment reference REF00456. Balance KES. -94.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-09-06 04:43:31','2021-09-06 04:43:31'),(34,'254713124436','Congratulations, You have completed Payment for test.','success','sent','payment_completion_notification','KES 0.8000','2021-09-06 04:43:32','2021-09-06 04:43:32'),(35,'254711756740','Payment of KES. 10.00 received for Booking Ref. T20865, Payment reference PI68JXNNIC. Balance KES. 2480.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-09-06 08:34:22','2021-09-06 08:34:22'),(36,'254790535349','Payment of KES. 100 received for Booking Ref. T95915, Payment reference REF00456HHA. Balance KES. 75.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-09-06 08:43:11','2021-09-06 08:43:11'),(37,'254713302589','Please Complete your booking. Use Paybill 4040299, account number T6750 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-09-07 05:46:16','2021-09-07 05:46:16'),(38,'254713302589','Payment of KES. 10 received for Booking Ref. T6750, Payment reference REF004567. Balance KES. 2980.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-09-07 05:49:18','2021-09-07 05:49:18'),(39,'254713124436','Please Complete your booking. Use Paybill 4040299, account number T75559 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-09-07 05:59:07','2021-09-07 05:59:07'),(40,'254713124436','Payment of KES. 10 received for Booking Ref. T75559, Payment reference A. Balance KES. 3990.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-09-07 05:59:51','2021-09-07 05:59:51'),(41,'254708888303','Please Complete your booking. Use Paybill 4040299, account number T74647 and amount Ksh.500','success','sent','after_booking_notification','KES 0.8000','2021-09-07 06:27:39','2021-09-07 06:27:39'),(42,'254708888303','Payment of KES. 100 received for Booking Ref. T74647, Payment reference cash. Balance KES. 64890.Download our app to easily track your payments - http://bit.ly/MosMosApp.','success','sent','payment_notification','KES 1.6000','2021-09-07 06:34:10','2021-09-07 06:34:10');
/*!40000 ALTER TABLE `s_m_s_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `s_m_s_top_ups`
--

DROP TABLE IF EXISTS `s_m_s_top_ups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `s_m_s_top_ups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_log_id` bigint(20) unsigned NOT NULL,
  `agent_id` bigint(20) unsigned NOT NULL,
  `amount` double(10,2) NOT NULL,
  `channel` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mpesa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `s_m_s_top_ups`
--

LOCK TABLES `s_m_s_top_ups` WRITE;
/*!40000 ALTER TABLE `s_m_s_top_ups` DISABLE KEYS */;
INSERT INTO `s_m_s_top_ups` VALUES (1,65,6,100.00,'Mpesa','2021-08-26 03:47:56','2021-08-26 03:47:56');
/*!40000 ALTER TABLE `s_m_s_top_ups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_commissions`
--

DROP TABLE IF EXISTS `system_commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_commissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) unsigned NOT NULL,
  `period` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_amount` double(10,2) NOT NULL,
  `commission_paid` double(10,2) NOT NULL,
  `transactions` int(11) NOT NULL DEFAULT '0',
  `unit` double(10,2) NOT NULL DEFAULT '0.00',
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_commissions`
--

LOCK TABLES `system_commissions` WRITE;
/*!40000 ALTER TABLE `system_commissions` DISABLE KEYS */;
INSERT INTO `system_commissions` VALUES (1,6,'Sep 1st, 2021 to Sep 30th, 2021',2.00,100.00,2,50.00,'2021-09-01','2021-09-30','2021-09-08 08:49:40','2021-09-08 08:49:40');
/*!40000 ALTER TABLE `system_commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `travel_agents`
--

DROP TABLE IF EXISTS `travel_agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `travel_agents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `continent_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `country_id` bigint(20) unsigned NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_payment_cost` double NOT NULL DEFAULT '50',
  `internal_payment_cost` double NOT NULL DEFAULT '50',
  `company_logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_balance` double(10,2) NOT NULL DEFAULT '0.00',
  `sms_credits` double(8,2) NOT NULL DEFAULT '0.00',
  `online_payments` double(12,2) NOT NULL DEFAULT '0.00',
  `offline_payments` double(12,2) NOT NULL DEFAULT '0.00',
  `own_paybill_payments` double(8,2) NOT NULL DEFAULT '0.00',
  `total_payments` double(12,2) NOT NULL DEFAULT '0.00',
  `b2c_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_paybill` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `own_payment` tinyint(1) NOT NULL DEFAULT '0',
  `sms_provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CONSUMER_KEY` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CONSUMER_SECRET` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MPESA_SHORT_CODE` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `STK_PASSKEY` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mpesa_approved` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `SMS_API_KEY` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_approved` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `alphanumeric` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_steps` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '[]',
  `register_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `travel_agents_user_id_foreign` (`user_id`),
  KEY `travel_agents_country_id_foreign` (`country_id`),
  KEY `travel_agents_city_id_foreign` (`city_id`),
  CONSTRAINT `travel_agents_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `travel_agents_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `travel_agents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_agents`
--

LOCK TABLES `travel_agents` WRITE;
/*!40000 ALTER TABLE `travel_agents` DISABLE KEYS */;
INSERT INTO `travel_agents` VALUES (6,2,8,2,10,'254792544021','Travel Mos Mos',50,50,'2021-08-23-07-39-15download (1).png','approved','testagent','testagent',-181.33,100.00,70.00,21452.00,10.00,21522.00,'254792544021',NULL,NULL,NULL,NULL,0,NULL,'kpeGuxTGudTL3LFAcfL3Z6EJYUb75P4u','dnGlAmHzrq3VWxYa','900500','f86688527b69cb3617839424381e34a026077087fdccde46e9b5e46b1eed5e1a','pending',NULL,'pending',NULL,NULL,'2021-08-26 03:47:56','2021-09-07 06:34:09','TMA6036','[1]','');
/*!40000 ALTER TABLE `travel_agents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `travel_packages`
--

DROP TABLE IF EXISTS `travel_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `travel_packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `continent_id` bigint(20) unsigned NOT NULL,
  `agent_id` bigint(20) unsigned NOT NULL,
  `country_id` bigint(20) unsigned NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `cover_page` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `package_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `highlights` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_cost` double(10,2) NOT NULL DEFAULT '0.00',
  `days` int(11) NOT NULL,
  `nights` int(11) NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `travel_packages_agent_id_foreign` (`agent_id`),
  KEY `travel_packages_country_id_foreign` (`country_id`),
  KEY `travel_packages_city_id_foreign` (`city_id`),
  CONSTRAINT `travel_packages_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `travel_agents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `travel_packages_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `travel_packages_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_packages`
--

LOCK TABLES `travel_packages` WRITE;
/*!40000 ALTER TABLE `travel_packages` DISABLE KEYS */;
/*!40000 ALTER TABLE `travel_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3768 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Travel Admin','admin@mosmos.co.ke','admin',NULL,'$2y$10$A7XhuY/FOz4eGtnBDR.6Iurh8RR19d1dpt/l3CJRUoN4dzjmY0lEC',NULL,'2020-08-30 18:08:43','2021-06-07 08:49:06'),(8,'Test Travel Agent','travelmosmos@gmail.com','agent',NULL,'$2y$10$96JGyO.0KyxkIocS6xZFU.IUmDCp0wP4iu0LhndJIhIaomxtxgZzy',NULL,'2021-08-18 05:50:43','2021-08-23 04:39:15'),(9,'Levi','rdfyne@gmail.com','branch-user',NULL,'$2y$10$6tN2mQsIpoyaNcjwq23WruymaZBqU4SEq8dC9rHLIBxQJdiWJf2oy',NULL,'2021-08-18 05:56:33','2021-08-26 03:14:22'),(1893,'Levi Kisaka','levkisaka@gmail.com','user',NULL,'$2y$10$9fX5cUQouFL.U8iQ84xa.uJmzEIpt3G1qA5R8DAgsdUhkiP4odpF2',NULL,'2021-08-30 06:54:04','2021-08-30 06:54:04'),(1901,'Brian Mutiso Munywoki','brianqmutiso@gmail.com','user',NULL,'$2y$10$C6dW4qVv5blZp8QERqDl6.MDQgursqQEWLpSDSRN2nASdXQeGUiIW',NULL,'2021-08-19 13:49:43','2021-08-19 13:49:43'),(3762,'test','mashachengojr@gmail.com','user',NULL,'$2y$10$X58HKOboNL2qxeNrF4u3u.AVbWLEPvWAfLNu0b7ThBfnyhPD2pn8.',NULL,'2021-08-20 02:37:42','2021-08-20 02:37:42'),(3763,'Vivian','info@kiliantravel.co.ke','user',NULL,'$2y$10$b.aZV4KOTo6WKWnwnOAVf.Uxm8Kyfh8oVUzNWkw9jpv992jyjqDW2',NULL,'2021-08-20 04:19:33','2021-08-20 04:19:33'),(3764,'Kim Jack','kimjack22@gmail.com','user',NULL,'$2y$10$rHnJDjckk3irulORfX0vFeQKDfWFgDqepoN8han8qrq4JB8qpilNe',NULL,'2021-08-30 07:43:15','2021-08-30 07:43:15'),(3765,'JOHN MUMO','johnmumo43@gmail.com','user',NULL,'$2y$10$SqVzFqhvdAVunJ2JyQM8t.VfOU6mhO3zX6PYaEHAxuDXGkESXbGK.',NULL,'2021-09-02 04:49:11','2021-09-02 04:49:11'),(3766,'Brends','info@halifax-tours-travel.com','user',NULL,'$2y$10$kB7XFCceCfoFTSL8GRm0wOf82lz/dOhC594V5IEJDXI3GrB2AG..i',NULL,'2021-09-02 05:23:14','2021-09-02 05:23:14'),(3767,'Rachael','rachaelkaranja17@gmail.com','user',NULL,'$2y$10$s5Sv9C4FRQTYcZGbGfrX0uAS1/812edjVWtkiV558uCEZkGAMsGYi',NULL,'2021-09-07 06:27:39','2021-09-07 06:27:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `withdrawal_requests`
--

DROP TABLE IF EXISTS `withdrawal_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `withdrawal_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agent_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `amount` double(10,2) NOT NULL,
  `transac_cost` double(8,2) NOT NULL DEFAULT '0.00',
  `wallet_balance` double(10,2) NOT NULL,
  `destination` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'phone',
  `ConversationID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OriginatorConversationID` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ResponseCode` int(11) DEFAULT NULL,
  `ResponseDescription` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `fail_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'to_agent',
  `TransactionReceipt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `B2CChargesPaidAccountAvailableFunds` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ReceiverPartyPublicName` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TransactionCompletedDateTime` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `B2CUtilityAccountAvailableFunds` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `B2CWorkingAccountAvailableFunds` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `withdrawal_requests`
--

LOCK TABLES `withdrawal_requests` WRITE;
/*!40000 ALTER TABLE `withdrawal_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `withdrawal_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `without_accommodations`
--

DROP TABLE IF EXISTS `without_accommodations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `without_accommodations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_id` bigint(20) unsigned NOT NULL,
  `wa_ai_one_person_cost` double(10,2) DEFAULT NULL,
  `wa_ai_two_people_cost` double(10,2) DEFAULT NULL,
  `wa_ai_group_cost` double(10,2) DEFAULT NULL,
  `wa_ai_kids_cost` double(10,2) DEFAULT NULL,
  `wa_fb_one_person_cost` double(10,2) DEFAULT NULL,
  `wa_fb_two_people_cost` double(10,2) DEFAULT NULL,
  `wa_fb_group_cost` double(10,2) DEFAULT NULL,
  `wa_fb_kids_cost` double(10,2) DEFAULT NULL,
  `wa_hb_one_person_cost` double(10,2) DEFAULT NULL,
  `wa_hb_two_people_cost` double(10,2) DEFAULT NULL,
  `wa_hb_group_cost` double(10,2) DEFAULT NULL,
  `wa_hb_kids_cost` double(10,2) DEFAULT NULL,
  `wa_bb_one_person_cost` double(10,2) DEFAULT NULL,
  `wa_bb_two_people_cost` double(10,2) DEFAULT NULL,
  `wa_bb_group_cost` double(10,2) DEFAULT NULL,
  `wa_bb_kids_cost` double(10,2) DEFAULT NULL,
  `wa_w_land_one_person_cost` double(10,2) DEFAULT NULL,
  `wa_w_land_two_people_cost` double(10,2) DEFAULT NULL,
  `wa_w_land_group_cost` double(10,2) DEFAULT NULL,
  `wa_w_land_kids_cost` double(10,2) DEFAULT NULL,
  `wa_w_van_one_person_cost` double(10,2) DEFAULT NULL,
  `wa_w_van_two_people_cost` double(10,2) DEFAULT NULL,
  `wa_w_van_group_cost` double(10,2) DEFAULT NULL,
  `wa_w_van_kids_cost` double(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `wa_w_bus_one_person_cost` double DEFAULT NULL,
  `wa_w_bus_two_people_cost` double DEFAULT NULL,
  `wa_w_bus_group_cost` double DEFAULT NULL,
  `wa_w_bus_kids_cost` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `without_accommodations`
--

LOCK TABLES `without_accommodations` WRITE;
/*!40000 ALTER TABLE `without_accommodations` DISABLE KEYS */;
/*!40000 ALTER TABLE `without_accommodations` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-09-08 15:06:22
