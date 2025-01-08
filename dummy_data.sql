/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: test_db
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
-- Table structure for table `admin_network`
--

DROP TABLE IF EXISTS `admin_network`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_network` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned NOT NULL,
  `network_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_admin_network_admin_id` (`admin_id`),
  KEY `fk_admin_network_network_id` (`network_id`),
  CONSTRAINT `fk_admin_network_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_admin_network_network_id` FOREIGN KEY (`network_id`) REFERENCES `networks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_network`
--

LOCK TABLES `admin_network` WRITE;
/*!40000 ALTER TABLE `admin_network` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_network` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role` tinyint(4) NOT NULL DEFAULT 2 COMMENT '1 = Admin, 2 = Manager',
  `display_name` varchar(64) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_recovery_codes` varchar(255) DEFAULT NULL,
  `account_expires_at` timestamp NULL DEFAULT NULL,
  `premium_expires_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(12) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `time_zone` varchar(48) DEFAULT NULL,
  `phone_prefix` varchar(4) DEFAULT NULL,
  `phone_country` varchar(2) DEFAULT NULL,
  `phone` varchar(24) DEFAULT NULL,
  `phone_e164` varchar(24) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `number_of_times_logged_in` int(10) unsigned NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `meta` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  KEY `admins_role_index` (`role`),
  KEY `admins_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=246218489950209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (246218489950208,1,NULL,'System Admin','admin@example.com','2024-12-27 17:45:36','$2y$12$2WVTxC.GCQz3zWyDpKMNlOBv.tlqveqAQMx7iff.j9lXSx7fu7LXW',NULL,0,NULL,NULL,NULL,NULL,'en_US',NULL,'USD','Asia/Qatar',NULL,NULL,NULL,NULL,1,1,0,6,'2025-01-02 09:13:25',NULL,NULL,NULL,NULL,NULL,'2024-12-27 17:45:36','2025-01-02 09:13:25');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliates`
--

DROP TABLE IF EXISTS `affiliates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `network_id` bigint(20) unsigned DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 = regular affiliate',
  `unique_identifier` varchar(32) DEFAULT NULL COMMENT 'Unique affiliate identifier',
  `display_name` varchar(64) DEFAULT NULL COMMENT 'Visible to other users',
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_recovery_codes` varchar(255) DEFAULT NULL,
  `locale` varchar(12) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `time_zone` varchar(48) DEFAULT NULL,
  `phone_prefix` varchar(4) DEFAULT NULL,
  `phone_country` varchar(2) DEFAULT NULL,
  `phone` varchar(24) DEFAULT NULL,
  `phone_e164` varchar(24) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `number_of_times_logged_in` int(10) unsigned NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `number_of_members_affiliated` int(10) unsigned NOT NULL DEFAULT 0,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `affiliates_email_unique` (`email`),
  UNIQUE KEY `affiliates_unique_identifier_unique` (`unique_identifier`),
  KEY `fk_affiliates_network_id` (`network_id`),
  KEY `fk_affiliates_created_by` (`created_by`),
  KEY `fk_affiliates_deleted_by` (`deleted_by`),
  KEY `fk_affiliates_updated_by` (`updated_by`),
  CONSTRAINT `fk_affiliates_created_by` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_affiliates_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_affiliates_network_id` FOREIGN KEY (`network_id`) REFERENCES `networks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_affiliates_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliates`
--

LOCK TABLES `affiliates` WRITE;
/*!40000 ALTER TABLE `affiliates` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics`
--

DROP TABLE IF EXISTS `analytics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analytics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) unsigned NOT NULL,
  `member_id` bigint(20) unsigned DEFAULT NULL,
  `staff_id` bigint(20) unsigned DEFAULT NULL,
  `card_id` bigint(20) unsigned DEFAULT NULL,
  `reward_id` bigint(20) unsigned DEFAULT NULL,
  `event` varchar(250) DEFAULT NULL,
  `locale` varchar(12) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `purchase_amount` bigint(20) unsigned DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_analytics_partner_id` (`partner_id`),
  KEY `fk_analytics_member_id` (`member_id`),
  KEY `fk_analytics_staff_id` (`staff_id`),
  KEY `fk_analytics_card_id` (`card_id`),
  KEY `fk_analytics_reward_id` (`reward_id`),
  CONSTRAINT `fk_analytics_card_id` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_analytics_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_analytics_partner_id` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_analytics_reward_id` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_analytics_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248204889153537 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics`
--

LOCK TABLES `analytics` WRITE;
/*!40000 ALTER TABLE `analytics` DISABLE KEYS */;
INSERT INTO `analytics` VALUES (248170867589120,248167659880448,NULL,NULL,248170717884416,NULL,'card_view','en_US',NULL,NULL,NULL,NULL,'2025-01-02 06:09:50','2025-01-02 06:09:50'),(248171217649664,248167659880448,NULL,NULL,248170717884416,248168816246784,'reward_view','en_US',NULL,NULL,NULL,NULL,'2025-01-02 06:11:16','2025-01-02 06:11:16'),(248182665420800,248167659880448,248174524727296,NULL,248182636138496,NULL,'card_view','en_US',NULL,NULL,NULL,NULL,'2025-01-02 06:57:51','2025-01-02 06:57:51'),(248185589501952,248167659880448,248174524727296,248172729171968,248170717884416,NULL,'issue_points',NULL,'USD',100,10,NULL,'2025-01-02 07:09:44','2025-01-02 07:09:44'),(248204889153536,248167659880448,248174524727296,NULL,248170717884416,NULL,'card_view','en_US',NULL,NULL,NULL,NULL,'2025-01-02 08:28:16','2025-01-02 08:28:16');
/*!40000 ALTER TABLE `analytics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_member`
--

DROP TABLE IF EXISTS `card_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_member` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `card_id` bigint(20) unsigned NOT NULL,
  `member_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_card_member_card_id` (`card_id`),
  KEY `fk_card_member_member_id` (`member_id`),
  CONSTRAINT `fk_card_member_card_id` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_card_member_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_member`
--

LOCK TABLES `card_member` WRITE;
/*!40000 ALTER TABLE `card_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `card_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_reward`
--

DROP TABLE IF EXISTS `card_reward`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_reward` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `card_id` bigint(20) unsigned NOT NULL,
  `reward_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_card_reward_card_id` (`card_id`),
  KEY `fk_card_reward_reward_id` (`reward_id`),
  CONSTRAINT `fk_card_reward_card_id` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_card_reward_reward_id` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_reward`
--

LOCK TABLES `card_reward` WRITE;
/*!40000 ALTER TABLE `card_reward` DISABLE KEYS */;
INSERT INTO `card_reward` VALUES (1,248170717884416,248168816246784,'2025-01-02 06:09:14','2025-01-02 06:09:14'),(2,248170717884416,248171858092032,'2025-01-02 06:15:10','2025-01-02 06:15:10'),(3,248182636138496,248181118619648,'2025-01-02 06:57:43','2025-01-02 06:57:43');
/*!40000 ALTER TABLE `card_reward` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `club_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT 'loyalty',
  `icon` varchar(32) DEFAULT NULL,
  `head` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`head`)),
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`title`)),
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`description`)),
  `unique_identifier` varchar(32) DEFAULT NULL,
  `issue_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiration_date` timestamp NULL DEFAULT NULL,
  `bg_color` varchar(25) DEFAULT NULL,
  `bg_color_opacity` tinyint(4) DEFAULT NULL,
  `text_color` varchar(32) DEFAULT NULL,
  `text_label_color` varchar(32) DEFAULT NULL,
  `qr_color_light` varchar(32) DEFAULT NULL,
  `qr_color_dark` varchar(32) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `initial_bonus_points` int(10) unsigned DEFAULT NULL,
  `points_expiration_months` int(10) unsigned DEFAULT NULL,
  `currency_unit_amount` int(10) unsigned DEFAULT NULL,
  `points_per_currency` int(10) unsigned DEFAULT NULL,
  `point_value` decimal(8,4) unsigned DEFAULT NULL,
  `min_points_per_purchase` bigint(20) unsigned DEFAULT NULL,
  `max_points_per_purchase` bigint(20) unsigned DEFAULT NULL,
  `min_points_per_redemption` bigint(20) unsigned DEFAULT NULL,
  `max_points_per_redemption` bigint(20) unsigned DEFAULT NULL,
  `custom_rule1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_rule1`)),
  `custom_rule2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_rule2`)),
  `custom_rule3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_rule3`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_visible_by_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_visible_when_logged_in` tinyint(1) NOT NULL DEFAULT 0,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `total_amount_purchased` int(10) unsigned NOT NULL DEFAULT 0,
  `number_of_points_issued` int(10) unsigned NOT NULL DEFAULT 0,
  `last_points_issued_at` timestamp NULL DEFAULT NULL,
  `number_of_points_redeemed` int(10) unsigned NOT NULL DEFAULT 0,
  `number_of_rewards_redeemed` int(10) unsigned NOT NULL DEFAULT 0,
  `last_reward_redeemed_at` timestamp NULL DEFAULT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `last_view` timestamp NULL DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cards_unique_identifier_unique` (`unique_identifier`),
  KEY `1` (`club_id`),
  KEY `fk_cards_created_by` (`created_by`),
  KEY `fk_cards_deleted_by` (`deleted_by`),
  KEY `fk_cards_updated_by` (`updated_by`),
  CONSTRAINT `1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cards_created_by` FOREIGN KEY (`created_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cards_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cards_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `partners` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248182636138497 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` VALUES (248170717884416,248168997384192,'gold card','loyalty',NULL,'{\"en_US\":\"jumbosouq\"}','{\"en_US\":\"Dipankar Y\"}','{\"en_US\":\"you are a gold member\"}','935-567-539-677','2025-01-02 06:03:00','2025-01-26 20:59:00','#080808',75,'#c03030','#e2cbcb','#fcfcfc','#1f1f1f','USD',0,1,1,5,NULL,10,100000,NULL,NULL,NULL,NULL,NULL,1,1,0,0,0,100,10,'2025-01-02 07:09:44',0,0,NULL,2,'2025-01-02 08:28:16','{\"round_points_up\":1,\"website\":\"https:\\/\\/brewcoffee.com\",\"route\":\"https:\\/\\/brewcoffee.com\",\"phone\":\"+917889603907\"}',248167659880448,NULL,248167659880448,NULL,'2025-01-02 06:09:14','2025-01-02 08:28:16'),(248182636138496,248180587270144,'diamond club','loyalty',NULL,'{\"en_US\":\"diamond card\"}','{\"en_US\":\"Diamond club\"}','{\"en_US\":\"you are a diamond member\"}','450-227-268-932','2025-01-02 06:53:00','2029-01-02 06:53:00','#080808',75,'#d20f0f','#dedede','#fcfcfc','#1f1f1f','USD',5000,12,1,1,NULL,1,100000,NULL,NULL,NULL,NULL,NULL,1,1,0,0,0,0,0,NULL,0,0,NULL,1,'2025-01-02 06:57:51','{\"round_points_up\":1,\"website\":\"https:\\/\\/brewcoffee.com\",\"route\":\"https:\\/\\/brewcoffee.com\",\"phone\":\"7889481714\"}',248167659880448,NULL,NULL,NULL,'2025-01-02 06:57:43','2025-01-02 06:57:51');
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clubs`
--

DROP TABLE IF EXISTS `clubs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clubs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(96) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `locale` varchar(12) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `time_zone` varchar(48) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_clubs_created_by` (`created_by`),
  KEY `fk_clubs_deleted_by` (`deleted_by`),
  KEY `fk_clubs_updated_by` (`updated_by`),
  CONSTRAINT `fk_clubs_created_by` FOREIGN KEY (`created_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_clubs_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_clubs_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `partners` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248216521805825 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clubs`
--

LOCK TABLES `clubs` WRITE;
/*!40000 ALTER TABLE `clubs` DISABLE KEYS */;
INSERT INTO `clubs` VALUES (248167659896832,'General',NULL,NULL,NULL,NULL,1,0,0,0,NULL,248167659880448,NULL,NULL,NULL,'2025-01-02 05:56:47','2025-01-02 05:56:47'),(248168997384192,'electric',NULL,NULL,NULL,NULL,1,0,0,0,NULL,248167659880448,NULL,248167659880448,NULL,'2025-01-02 06:02:14','2025-01-02 06:02:47'),(248180587270144,'diamond club',NULL,NULL,NULL,NULL,1,0,0,0,NULL,248167659880448,NULL,NULL,NULL,'2025-01-02 06:49:23','2025-01-02 06:49:23'),(248216521805824,'General',NULL,NULL,NULL,NULL,1,0,0,0,NULL,248216521760768,NULL,NULL,NULL,'2025-01-02 09:15:36','2025-01-02 09:15:36');
/*!40000 ALTER TABLE `clubs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` varchar(2) NOT NULL COMMENT 'ISO 3166-1 alpha-2',
  `alpha_3` varchar(3) DEFAULT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `native_name` varchar(255) DEFAULT NULL,
  `capital` varchar(255) DEFAULT NULL,
  `top_level_domain` varchar(255) DEFAULT NULL,
  `calling_code` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `subregion` varchar(255) DEFAULT NULL,
  `population` int(10) unsigned DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lon` decimal(11,8) DEFAULT NULL,
  `demonym` varchar(255) DEFAULT NULL,
  `area` int(10) unsigned DEFAULT NULL,
  `gini` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country_country`
--

DROP TABLE IF EXISTS `country_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_country` (
  `country_id` varchar(2) NOT NULL,
  `neighbour_id` varchar(2) NOT NULL,
  PRIMARY KEY (`country_id`,`neighbour_id`),
  KEY `country_country_country_id_index` (`country_id`),
  KEY `country_country_neighbour_id_index` (`neighbour_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_country`
--

LOCK TABLES `country_country` WRITE;
/*!40000 ALTER TABLE `country_country` DISABLE KEYS */;
/*!40000 ALTER TABLE `country_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country_currency`
--

DROP TABLE IF EXISTS `country_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_currency` (
  `country_id` varchar(2) NOT NULL,
  `currency_id` varchar(3) NOT NULL,
  PRIMARY KEY (`country_id`,`currency_id`),
  KEY `country_currency_country_id_index` (`country_id`),
  KEY `country_currency_currency_id_index` (`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_currency`
--

LOCK TABLES `country_currency` WRITE;
/*!40000 ALTER TABLE `country_currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `country_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country_language`
--

DROP TABLE IF EXISTS `country_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_language` (
  `country_id` varchar(2) NOT NULL,
  `language_id` varchar(2) NOT NULL,
  PRIMARY KEY (`country_id`,`language_id`),
  KEY `country_language_country_id_index` (`country_id`),
  KEY `country_language_language_id_index` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_language`
--

LOCK TABLES `country_language` WRITE;
/*!40000 ALTER TABLE `country_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `country_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `id` varchar(3) NOT NULL COMMENT 'ISO 4217',
  `name` varchar(255) NOT NULL,
  `name_plural` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `symbol_native` varchar(255) NOT NULL,
  `decimal_digits` tinyint(3) unsigned NOT NULL,
  `rounding` tinyint(3) unsigned NOT NULL,
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
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` varchar(2) NOT NULL COMMENT 'ISO 639-1',
  `iso639_2` varchar(3) NOT NULL,
  `iso639_2b` varchar(3) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `native_name` varchar(255) DEFAULT NULL,
  `family` varchar(255) DEFAULT NULL,
  `wiki_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `languages_iso639_2_index` (`iso639_2`),
  KEY `languages_iso639_2b_index` (`iso639_2b`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `collection_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL,
  `conversions_disk` varchar(255) DEFAULT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`manipulations`)),
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`custom_properties`)),
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`generated_conversions`)),
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`responsive_images`)),
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'App\\Models\\Reward',248168816246784,'ac2e2180-01be-4849-9e0a-9163fcef7f8f','image1','coffee-cup','coffee-cup.png','image/png','files','files',17067,'[]','[]','{\"xs\":true,\"sm\":true,\"md\":true}','[]',1,'2025-01-02 06:01:29','2025-01-02 06:01:30'),(2,'App\\Models\\Card',248170717884416,'8daf9dd9-3683-4373-973d-4f5e926fcd6b','background','card-placeholder','card-placeholder.jpg_copy','image/jpeg','files','files',9011,'[]','[]','{\"sm\":true,\"md\":true}','[]',1,'2025-01-02 06:09:14','2025-01-02 06:09:14'),(3,'App\\Models\\Reward',248171858092032,'682850e7-743e-4cc4-a500-af75bac15b55','image1','cup','cup.png','image/png','files','files',20881,'[]','[]','{\"xs\":true,\"sm\":true,\"md\":true}','[]',1,'2025-01-02 06:13:52','2025-01-02 06:13:52'),(4,'App\\Models\\Reward',248181118619648,'d8c55d58-61c6-498d-88db-2ebc3e4124d4','image1','cup','cup.png','image/png','files','files',20881,'[]','[]','{\"xs\":true,\"sm\":true,\"md\":true}','[]',1,'2025-01-02 06:51:33','2025-01-02 06:51:33'),(5,'App\\Models\\Card',248182636138496,'6f0b1833-909c-4b23-a0bb-de9bfb951023','background','card-placeholder','card-placeholder.jpg_copy','image/jpeg','files','files',9011,'[]','[]','{\"sm\":true,\"md\":true}','[]',1,'2025-01-02 06:57:43','2025-01-02 06:57:43'),(6,'App\\Models\\Partner',248216521760768,'b04d2441-a98c-48be-affa-402549a0beba','avatar','logo','logo.svg','image/svg+xml','files','files',11463,'[]','[]','[]','[]',1,'2025-01-02 09:15:36','2025-01-02 09:15:36');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_id` bigint(20) unsigned DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 = regular member',
  `member_number` varchar(32) DEFAULT NULL COMMENT 'Unique number in format of: xxx-xxx-xxx-xxx',
  `unique_identifier` varchar(32) DEFAULT NULL COMMENT 'Unique identifier',
  `display_name` varchar(64) DEFAULT NULL COMMENT 'Visible to other users',
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = unknown, 1 = male, 2 = female',
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_recovery_codes` varchar(255) DEFAULT NULL,
  `account_expires_at` timestamp NULL DEFAULT NULL,
  `premium_expires_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(12) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `time_zone` varchar(48) DEFAULT NULL,
  `phone_prefix` varchar(4) DEFAULT NULL,
  `phone_country` varchar(2) DEFAULT NULL,
  `phone` varchar(24) DEFAULT NULL,
  `phone_e164` varchar(24) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_vip` tinyint(1) NOT NULL DEFAULT 0,
  `accepts_emails` tinyint(1) NOT NULL DEFAULT 0,
  `accepts_text_messages` tinyint(1) NOT NULL DEFAULT 0,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `number_of_times_logged_in` int(10) unsigned NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `number_of_emails_received` int(10) unsigned NOT NULL DEFAULT 0,
  `number_of_text_messages_received` int(10) unsigned NOT NULL DEFAULT 0,
  `number_of_reviews_written` int(10) unsigned NOT NULL DEFAULT 0,
  `number_of_ratings_given` int(10) unsigned NOT NULL DEFAULT 0,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_email_unique` (`email`),
  UNIQUE KEY `members_member_number_unique` (`member_number`),
  UNIQUE KEY `members_unique_identifier_unique` (`unique_identifier`),
  KEY `fk_members_affiliate_id` (`affiliate_id`),
  KEY `fk_members_created_by` (`created_by`),
  KEY `fk_members_deleted_by` (`deleted_by`),
  KEY `fk_members_updated_by` (`updated_by`),
  CONSTRAINT `fk_members_affiliate_id` FOREIGN KEY (`affiliate_id`) REFERENCES `affiliates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_members_created_by` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_members_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_members_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248174524727297 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (248173223522304,NULL,1,NULL,'090-394-492-964',NULL,'lakshita','lakshita@example.com',NULL,'$2y$12$tXWzem8BwVFTF6OiEUFwsu1qDpJ1b7WCkCz7wCEHyD.lhruLC3ipy',NULL,NULL,0,0,NULL,NULL,NULL,NULL,'en_US',NULL,'USD','Asia/Calcutta',NULL,NULL,NULL,NULL,1,0,0,0,0,0,0,NULL,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'2025-01-02 06:19:25','2025-01-02 06:19:25'),(248174524727296,NULL,1,NULL,'893-478-066-325',NULL,'lakshita','lakshita1@example.com','2025-01-02 06:25:16','$2y$12$lA7fPIqh4fZ5mErNWPYY7OioSUOloUOwkt3zyvYtnhkYONgx8VVQW',NULL,NULL,0,0,NULL,NULL,NULL,NULL,'en_US',NULL,'USD','Africa/Abidjan',NULL,NULL,NULL,NULL,1,0,0,0,0,0,5,'2025-01-02 08:27:04',0,0,0,0,NULL,NULL,NULL,NULL,NULL,'2025-01-02 06:24:43','2025-01-02 08:27:20');
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1),(2,'2022_07_20_180758_create_laravel_iso_countries_table',1),(3,'2022_07_20_180811_create_admins_table',1),(4,'2022_07_20_180838_create_networks_table',1),(5,'2022_07_20_180851_create_partners_table',1),(6,'2022_07_20_180904_create_affiliates_table',1),(7,'2022_07_20_180918_create_clubs_table',1),(8,'2022_07_20_180931_create_staff_table',1),(9,'2022_07_20_180945_create_members_table',1),(10,'2022_07_20_180958_create_cards_table',1),(11,'2022_07_20_181011_create_rewards_table',1),(12,'2022_07_20_181024_create_transactions_table',1),(13,'2022_07_20_181038_create_password_resets_table',1),(14,'2022_07_20_190500_create_media_table',1),(15,'2023_03_24_135708_create_admin_network_table',1),(16,'2023_03_27_083413_create_notifications_table',1),(17,'2023_04_17_092525_create_card_member_table',1),(18,'2023_05_04_123042_create_card_reward_table',1),(19,'2023_05_30_110215_create_analytics_table',1),(20,'2023_06_05_093755_create_sessions_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `networks`
--

DROP TABLE IF EXISTS `networks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `networks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(96) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `host` varchar(250) DEFAULT NULL,
  `slug` varchar(250) DEFAULT NULL,
  `locale` varchar(12) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `time_zone` varchar(48) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `networks_slug_unique` (`slug`),
  KEY `fk_networks_created_by` (`created_by`),
  KEY `fk_networks_deleted_by` (`deleted_by`),
  KEY `fk_networks_updated_by` (`updated_by`),
  CONSTRAINT `fk_networks_created_by` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_networks_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_networks_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248167462400001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `networks`
--

LOCK TABLES `networks` WRITE;
/*!40000 ALTER TABLE `networks` DISABLE KEYS */;
INSERT INTO `networks` VALUES (246218489991168,'Default',NULL,NULL,NULL,NULL,NULL,'USD',NULL,1,1,0,1,NULL,246218489950208,NULL,NULL,NULL,'2024-12-27 17:45:36','2024-12-27 17:45:36'),(248167462400000,'jumbosouq',NULL,NULL,NULL,NULL,NULL,'USD',NULL,1,0,0,0,NULL,246218489950208,NULL,NULL,NULL,'2025-01-02 05:55:59','2025-01-02 05:55:59');
/*!40000 ALTER TABLE `networks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `network_id` bigint(20) unsigned DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 = regular partner',
  `display_name` varchar(64) DEFAULT NULL COMMENT 'Visible to other users',
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_recovery_codes` varchar(255) DEFAULT NULL,
  `account_expires_at` timestamp NULL DEFAULT NULL,
  `premium_expires_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(12) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `time_zone` varchar(48) DEFAULT NULL,
  `phone_prefix` varchar(4) DEFAULT NULL,
  `phone_country` varchar(2) DEFAULT NULL,
  `phone` varchar(24) DEFAULT NULL,
  `phone_e164` varchar(24) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `number_of_times_logged_in` int(10) unsigned NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partners_email_unique` (`email`),
  KEY `fk_partners_network_id` (`network_id`),
  KEY `fk_partners_created_by` (`created_by`),
  KEY `fk_partners_deleted_by` (`deleted_by`),
  KEY `fk_partners_updated_by` (`updated_by`),
  CONSTRAINT `fk_partners_created_by` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_partners_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_partners_network_id` FOREIGN KEY (`network_id`) REFERENCES `networks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_partners_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248216521760769 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (248167659880448,248167462400000,1,NULL,'Dipankar','dipankar@example.com','2025-01-02 05:57:57','$2y$12$Goxt7DHkoUsvGsWFGHqXe.vVIVGz6povtQa38Spv2FcBE/Z.MKQYC',NULL,0,NULL,NULL,NULL,NULL,'en_US',NULL,'USD','Asia/Qatar',NULL,NULL,NULL,NULL,1,0,0,2,'2025-01-02 06:25:51','{\"cards_on_homepage\":1}',246218489950208,NULL,NULL,NULL,'2025-01-02 05:56:47','2025-01-02 06:25:51'),(248216521760768,246218489991168,1,NULL,'jumbosouq','jumbosouq@example.com',NULL,'$2y$12$nt3UhPE704VbEJQ3mQYKw.PIMnZWeY2Zaqjgq9CYZi72SaOFAadbC',NULL,0,NULL,NULL,NULL,NULL,'en_US',NULL,'USD','Asia/Qatar',NULL,NULL,NULL,NULL,1,0,0,0,NULL,'{\"cards_on_homepage\":1}',246218489950208,NULL,NULL,NULL,'2025-01-02 09:15:36','2025-01-02 09:15:36');
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (4,'App\\Models\\Admin',246218489950208,'AdminAPIToken','f76bab5b7200271a99f7fbadb56bb5a388a741e4cda7032c9575b28d80aea532','[\"*\"]',NULL,NULL,'2024-12-31 06:22:21','2024-12-31 06:22:21'),(5,'App\\Models\\Admin',246218489950208,'AdminAPIToken','944751be186272ccc8546ec71bdd2d85c57a065638f7d9ad28f82f6363025950','[\"*\"]',NULL,NULL,'2024-12-31 07:06:01','2024-12-31 07:06:01'),(6,'App\\Models\\Admin',246218489950208,'AdminAPIToken','d8f3ad2e4d20db3124678f5e1c457786d94fb8f1b0dc66614b401c9cad2a7710','[\"*\"]','2024-12-31 07:12:40',NULL,'2024-12-31 07:11:35','2024-12-31 07:12:40'),(7,'App\\Models\\Admin',246218489950208,'AdminAPIToken','9a572b65c7a5c10c85f63bd7ad3e13b561bed3edcdd42b0244cbe19dfbb2c070','[\"*\"]',NULL,NULL,'2024-12-31 10:01:03','2024-12-31 10:01:03'),(8,'App\\Models\\Admin',246218489950208,'AdminAPIToken','f92dd93d82976147b108f5670824939fe057416f849d1e3732b4b5c01a0ae983','[\"*\"]',NULL,NULL,'2024-12-31 10:04:32','2024-12-31 10:04:32'),(9,'App\\Models\\Admin',246218489950208,'AdminAPIToken','dbc8fc4b56234ddf6745836a78cd0eec74518394aa501628378f2701270fe2b0','[\"*\"]','2024-12-31 12:17:55',NULL,'2024-12-31 12:17:28','2024-12-31 12:17:55'),(10,'App\\Models\\Admin',246218489950208,'AdminAPIToken','159c476c22eb6b499b33c37586f5fd1150946fbf61b2e6af2f1b2a25285c4372','[\"*\"]',NULL,NULL,'2024-12-31 12:39:01','2024-12-31 12:39:01'),(11,'App\\Models\\Admin',246218489950208,'AdminAPIToken','426dbffe2c1be2c6a48b9f78bcf2b33c22526e8303178b5863f6185a4fb24565','[\"*\"]',NULL,NULL,'2025-01-01 17:01:28','2025-01-01 17:01:28'),(12,'App\\Models\\Admin',246218489950208,'AdminAPIToken','870f373ed157d188e27148f6730acdaf26395e9f54a949ce37dee6b3bac2d6b4','[\"*\"]','2025-01-01 19:11:53',NULL,'2025-01-01 17:33:00','2025-01-01 19:11:53');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rewards`
--

DROP TABLE IF EXISTS `rewards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rewards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`title`)),
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`description`)),
  `max_number_to_redeem` int(11) NOT NULL DEFAULT 0,
  `points` int(10) unsigned NOT NULL,
  `active_from` timestamp NULL DEFAULT NULL,
  `expiration_date` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `number_of_times_redeemed` int(10) unsigned NOT NULL DEFAULT 0,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `last_view` timestamp NULL DEFAULT NULL,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rewards_created_by` (`created_by`),
  KEY `fk_rewards_deleted_by` (`deleted_by`),
  KEY `fk_rewards_updated_by` (`updated_by`),
  CONSTRAINT `fk_rewards_created_by` FOREIGN KEY (`created_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rewards_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rewards_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `partners` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248181118619649 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rewards`
--

LOCK TABLES `rewards` WRITE;
/*!40000 ALTER TABLE `rewards` DISABLE KEYS */;
INSERT INTO `rewards` VALUES (248168816246784,'26 jan offer','{\"en_US\":\"january offers\"}','{\"en_US\":\"celebrate 26 january\"}',0,11,'2025-01-02 05:58:00','2025-01-26 20:59:00',1,0,1,'2025-01-02 06:11:16',0,0,NULL,248167659880448,NULL,NULL,NULL,'2025-01-02 06:01:29','2025-01-02 06:11:16'),(248171858092032,'kite festival offer','{\"en_US\":\"kite festival 2025\"}','{\"en_US\":\"claim rewards at kite festival\"}',0,50,'2025-01-02 06:12:00','2026-01-14 20:59:00',1,0,0,NULL,0,0,NULL,248167659880448,NULL,NULL,NULL,'2025-01-02 06:13:52','2025-01-02 06:13:52'),(248181118619648,'26 feb offer','{\"en_US\":\"diamond club\"}','{\"en_US\":\"get reward\"}',0,0,'2025-01-02 06:49:00','2026-01-02 06:49:00',1,0,0,NULL,0,0,NULL,248167659880448,NULL,NULL,NULL,'2025-01-02 06:51:33','2025-01-02 06:51:33');
/*!40000 ALTER TABLE `rewards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `club_id` bigint(20) unsigned DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 = regular staff member',
  `unique_identifier` varchar(32) DEFAULT NULL COMMENT 'Unique identifier',
  `display_name` varchar(64) DEFAULT NULL COMMENT 'Visible to other users',
  `name` varchar(128) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_recovery_codes` varchar(255) DEFAULT NULL,
  `account_expires_at` timestamp NULL DEFAULT NULL,
  `locale` varchar(12) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `time_zone` varchar(48) DEFAULT NULL,
  `phone_prefix` varchar(4) DEFAULT NULL,
  `phone_country` varchar(2) DEFAULT NULL,
  `phone` varchar(24) DEFAULT NULL,
  `phone_e164` varchar(24) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_undeletable` tinyint(1) NOT NULL DEFAULT 0,
  `is_uneditable` tinyint(1) NOT NULL DEFAULT 0,
  `number_of_times_logged_in` int(10) unsigned NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_email_unique` (`email`),
  UNIQUE KEY `staff_unique_identifier_unique` (`unique_identifier`),
  KEY `fk_staff_club_id` (`club_id`),
  KEY `fk_staff_created_by` (`created_by`),
  KEY `fk_staff_deleted_by` (`deleted_by`),
  KEY `fk_staff_updated_by` (`updated_by`),
  CONSTRAINT `fk_staff_club_id` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_staff_created_by` FOREIGN KEY (`created_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_staff_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_staff_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `partners` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248172729171969 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (248172729171968,248168997384192,1,'695-182-751-269',NULL,'Jaskaran singh','jaskaran@example.com','2025-01-02 06:31:17','$2y$12$GItMkRfSzuAnx3Dzxf.nUuB6f0HtLgKlaCkrXobF0ElE86tpJLlFC',NULL,0,NULL,NULL,NULL,NULL,NULL,'USD','Asia/Qatar',NULL,NULL,NULL,NULL,1,0,0,4,'2025-01-02 07:03:40',NULL,248167659880448,NULL,NULL,NULL,'2025-01-02 06:17:25','2025-01-02 07:03:40');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) unsigned DEFAULT NULL,
  `member_id` bigint(20) unsigned NOT NULL,
  `card_id` bigint(20) unsigned DEFAULT NULL,
  `reward_id` bigint(20) unsigned DEFAULT NULL,
  `partner_name` varchar(128) DEFAULT NULL,
  `partner_email` varchar(128) NOT NULL,
  `staff_name` varchar(128) DEFAULT NULL,
  `staff_email` varchar(128) NOT NULL,
  `card_title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`card_title`)),
  `reward_title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reward_title`)),
  `reward_points` int(10) unsigned DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `purchase_amount` bigint(20) unsigned DEFAULT NULL,
  `points` int(11) NOT NULL,
  `points_used` int(10) unsigned NOT NULL DEFAULT 0,
  `currency_unit_amount` int(10) unsigned DEFAULT NULL,
  `points_per_currency` int(10) unsigned DEFAULT NULL,
  `point_value` decimal(8,4) DEFAULT NULL,
  `min_points_per_purchase` int(10) unsigned DEFAULT NULL,
  `max_points_per_purchase` int(10) unsigned DEFAULT NULL,
  `min_points_per_redemption` int(10) unsigned DEFAULT NULL,
  `max_points_per_redemption` int(10) unsigned DEFAULT NULL,
  `event` varchar(250) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_transactions_staff_id` (`staff_id`),
  KEY `fk_transactions_member_id` (`member_id`),
  KEY `fk_transactions_card_id` (`card_id`),
  KEY `fk_transactions_reward_id` (`reward_id`),
  KEY `fk_transactions_created_by` (`created_by`),
  KEY `fk_transactions_deleted_by` (`deleted_by`),
  KEY `fk_transactions_updated_by` (`updated_by`),
  CONSTRAINT `fk_transactions_card_id` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transactions_created_by` FOREIGN KEY (`created_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transactions_deleted_by` FOREIGN KEY (`deleted_by`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transactions_member_id` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transactions_reward_id` FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_transactions_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_transactions_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `partners` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=248185589477377 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (248185589477376,248172729171968,248174524727296,248170717884416,NULL,'Dipankar','dipankar@example.com','Jaskaran singh','jaskaran@example.com','{\"en_US\":\"jumbosouq\"}',NULL,NULL,'USD',100,10,0,NULL,5,NULL,10,100000,NULL,NULL,'staff_credited_points_for_purchase','invoice no #0001','2025-02-02 07:09:44','{\"round_points_up\":true}',248167659880448,NULL,NULL,NULL,'2025-01-02 07:09:44','2025-01-02 07:09:44');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-02  9:18:20
