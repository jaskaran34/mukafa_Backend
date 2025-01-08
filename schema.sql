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
) ENGINE=InnoDB AUTO_INCREMENT=248180587270145 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=248167659880449 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-02  8:38:09
