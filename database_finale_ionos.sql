-- =============================================
-- 🗄️ PASSIONE CALABRIA - DATABASE FINALE IONOS
-- Ottimizzato per MySQL 8.0+ / phpMyAdmin / Hosting Ionos
-- Versione: 2024.09.04 - Sistema Traduzione File Statici
-- =============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- =============================================
-- ⚠️ IMPORTANTE: MODIFICA IL NOME DEL DATABASE
-- Sostituisci 'dbs14504718' con il nome del tuo database Ionos
-- =============================================
-- CREATE DATABASE IF NOT EXISTS `dbs14504718` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `dbs14504718`;

-- =============================================
-- 🏗️ STRUTTURA TABELLE PRINCIPALI
-- =============================================

-- 📰 Tabella articoli (contenuti principali)
CREATE TABLE `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `category_id` int NOT NULL,
  `province_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Admin',
  `status` enum('draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'published',
  `featured` tinyint(1) DEFAULT '0',
  `views` int DEFAULT '0',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `allow_user_uploads` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `seo_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_keywords` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_articles_category_status` (`category_id`,`status`),
  KEY `idx_articles_province_status` (`province_id`,`status`),
  KEY `idx_articles_city_status` (`city_id`,`status`),
  KEY `idx_articles_featured_status` (`featured`,`status`),
  KEY `idx_articles_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 📂 Tabella categorie
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '📍',
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'blue',
  `sort_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_categories_active_order` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🏛️ Tabella province
CREATE TABLE `provinces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🏘️ Tabella città
CREATE TABLE `cities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` int NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `population` int DEFAULT NULL,
  `altitude` int DEFAULT NULL,
  `zip_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cities_province` (`province_id`),
  KEY `idx_cities_name` (`name`),
  CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 💬 Tabella commenti
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `author_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int DEFAULT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `status` enum('pending','approved','rejected','spam') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_comments_article_status` (`article_id`,`status`),
  KEY `idx_comments_status_created` (`status`,`created_at`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 👤 Tabella utenti
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin','editor','moderator') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `status` enum('active','inactive','banned','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `avatar` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_login` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_status_role` (`status`,`role`),
  KEY `idx_users_email_verified` (`email_verified_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 💼 SISTEMA BUSINESS E ABBONAMENTI
-- =============================================

-- 📦 Pacchetti business
CREATE TABLE `business_packages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `duration_months` int DEFAULT '12',
  `features` json DEFAULT NULL,
  `max_articles` int DEFAULT NULL,
  `max_images` int DEFAULT NULL,
  `priority_listing` tinyint(1) DEFAULT '0',
  `analytics_access` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `stripe_price_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_packages_active_order` (`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🏢 Business registrati
CREATE TABLE `businesses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category_id` int DEFAULT NULL,
  `province_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `logo_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `opening_hours` json DEFAULT NULL,
  `social_links` json DEFAULT NULL,
  `status` enum('pending','approved','rejected','suspended') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `subscription_type` enum('free','basic','premium','enterprise') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'free',
  `verified` tinyint(1) DEFAULT '0',
  `featured` tinyint(1) DEFAULT '0',
  `views` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_businesses_status_subscription` (`status`,`subscription_type`),
  KEY `idx_businesses_category_status` (`category_id`,`status`),
  KEY `idx_businesses_province_status` (`province_id`,`status`),
  KEY `idx_businesses_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 💳 Sottoscrizioni attive
CREATE TABLE `subscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `business_id` int NOT NULL,
  `package_id` int NOT NULL,
  `stripe_subscription_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_customer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','active','cancelled','expired','past_due') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `next_billing_date` datetime DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'EUR',
  `auto_renew` tinyint(1) DEFAULT '1',
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_subscriptions_business_status` (`business_id`,`status`),
  KEY `idx_subscriptions_package` (`package_id`),
  KEY `idx_subscriptions_stripe` (`stripe_subscription_id`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `business_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 🎭 SISTEMA EVENTI
-- =============================================

CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `location` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category_id` int DEFAULT NULL,
  `province_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `organizer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gallery_images` json DEFAULT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `is_free` tinyint(1) DEFAULT '1',
  `ticket_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_participants` int DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status` enum('pending','active','cancelled','completed','sold_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `featured` tinyint(1) DEFAULT '0',
  `views` int DEFAULT '0',
  `likes` int DEFAULT '0',
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_events_start_date_status` (`start_date`,`status`),
  KEY `idx_events_category_status` (`category_id`,`status`),
  KEY `idx_events_province_status` (`province_id`,`status`),
  KEY `idx_events_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 📄 PAGINE STATICHE E CONTENUTI
-- =============================================

-- 📝 Pagine statiche
CREATE TABLE `static_pages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `template` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `is_published` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_static_pages_published_order` (`is_published`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🏠 Sezioni homepage configurabili
CREATE TABLE `home_sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_visible` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `custom_data` json DEFAULT NULL,
  `template_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_name` (`section_name`),
  KEY `idx_home_sections_visible_order` (`is_visible`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ⚙️ Impostazioni di sistema
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `is_public` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `idx_settings_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 🌐 SISTEMA TRADUZIONE SEMPLIFICATO (File Statici)
-- =============================================

-- 📝 Contenuti statici per traduzioni (testi interfaccia)
CREATE TABLE `static_content` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_it` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `context_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `page_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_type` enum('text','button','title','description','label') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_key` (`content_key`),
  KEY `idx_static_content_page_type` (`page_location`,`content_type`),
  KEY `idx_static_content_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🔤 Traduzioni contenuti statici
CREATE TABLE `static_content_translations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `static_content_id` int NOT NULL,
  `language_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `translated_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `translation_quality` enum('machine','human','professional') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'human',
  `is_verified` tinyint(1) DEFAULT '0',
  `translator_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `translated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `static_content_id_lang` (`static_content_id`,`language_code`),
  KEY `idx_static_translations_lang_quality` (`language_code`,`translation_quality`),
  CONSTRAINT `static_content_translations_ibfk_1` FOREIGN KEY (`static_content_id`) REFERENCES `static_content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 📰 Traduzioni articoli
CREATE TABLE `article_translations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `language_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `slug` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `translation_quality` enum('machine','human','professional') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'human',
  `is_published` tinyint(1) DEFAULT '1',
  `translated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_id_lang` (`article_id`,`language_code`),
  UNIQUE KEY `slug_lang` (`slug`,`language_code`),
  KEY `idx_article_translations_lang_published` (`language_code`,`is_published`),
  CONSTRAINT `article_translations_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 📧 SISTEMA COMUNICAZIONI
-- =============================================

-- 📬 Newsletter subscribers
CREATE TABLE `newsletter_subscribers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interests` json DEFAULT NULL,
  `language_preference` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'it',
  `status` enum('active','confirmed','unsubscribed','bounced') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `confirmation_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `source` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'website',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_newsletter_status_language` (`status`,`language_preference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 📤 SISTEMA UPLOAD E CONTENUTI UTENTE
-- =============================================

-- 🖼️ Upload utenti
CREATE TABLE `user_uploads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int DEFAULT NULL,
  `business_id` int DEFAULT NULL,
  `event_id` int DEFAULT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_filename` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `copyright_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','flagged') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_uploads_article_status` (`article_id`,`status`),
  KEY `idx_user_uploads_business_status` (`business_id`,`status`),
  KEY `idx_user_uploads_status_created` (`status`,`created_at`),
  CONSTRAINT `user_uploads_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_uploads_ibfk_2` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_uploads_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 💡 Suggerimenti luoghi
CREATE TABLE `place_suggestions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category_id` int DEFAULT NULL,
  `province_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `suggested_by_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `suggested_by_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `suggested_by_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` json DEFAULT NULL,
  `opening_hours` json DEFAULT NULL,
  `price_range` enum('free','budget','mid-range','expensive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accessibility_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `best_season` json DEFAULT NULL,
  `status` enum('pending','approved','rejected','in_review') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `admin_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `converted_to_article_id` int DEFAULT NULL,
  `priority` enum('low','normal','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_suggestions_status_priority` (`status`,`priority`),
  KEY `idx_suggestions_category_status` (`category_id`,`status`),
  KEY `idx_suggestions_province_status` (`province_id`,`status`),
  KEY `idx_suggestions_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 📊 SISTEMA ANALYTICS E LOG
-- =============================================

-- 📈 Tracking visite
CREATE TABLE `page_views` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page_type` enum('article','category','province','city','business','event','static_page') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_id` int DEFAULT NULL,
  `page_slug` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'it',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `referer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `device_type` enum('desktop','tablet','mobile') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_duration` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_page_views_type_id_date` (`page_type`,`page_id`,`created_at`),
  KEY `idx_page_views_language_date` (`language_code`,`created_at`),
  KEY `idx_page_views_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🔍 Log ricerche
CREATE TABLE `search_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `query` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `results_count` int DEFAULT '0',
  `language_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'it',
  `filters_applied` json DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_search_logs_query_date` (`query`,`created_at`),
  KEY `idx_search_logs_language_date` (`language_code`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 📦 INSERIMENTO DATI INIZIALI - CATEGORIE
-- =============================================

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `color`, `sort_order`) VALUES
(1, 'Natura e Paesaggi', 'Scopri la bellezza naturale della Calabria: parchi, riserve, montagne e panorami mozzafiato', '🌿', 'green', 1),
(2, 'Storia e Cultura', 'Immergiti nella ricca storia calabrese: castelli, musei, siti archeologici e tradizioni millenarie', '🏛️', 'purple', 2),
(3, 'Gastronomia', 'Assapora i sapori autentici della tradizione calabrese: prodotti tipici, ricette e ristoranti', '🍝', 'yellow', 3),
(4, 'Mare e Coste', 'Le più belle spiagge e località balneari della Calabria: da Tropea a Capo Vaticano', '🏖️', 'blue', 4),
(5, 'Montagne e Escursioni', 'Avventure tra i monti calabresi: Sila, Aspromonte, Pollino e sentieri naturalistici', '⛰️', 'indigo', 5),
(6, 'Borghi e Tradizioni', 'Alla scoperta dei borghi più belli della Calabria e delle loro tradizioni antiche', '🏘️', 'amber', 6),
(7, 'Arte e Musei', 'Tesori artistici e culturali: musei, gallerie, opere d\'arte e patrimonio artistico', '🎨', 'pink', 7),
(8, 'Feste e Eventi', 'Celebrazioni e manifestazioni locali: sagre, festival, eventi culturali e religiosi', '🎭', 'red', 8),
(9, 'Artigianato', 'Mestieri e prodotti della tradizione calabrese: ceramica, tessuti, lavorazione del legno', '🛠️', 'orange', 9),
(10, 'Terme e Benessere', 'Relax e cure naturali: centri termali, spa e luoghi per il benessere psico-fisico', '♨️', 'teal', 10),
(11, 'Parchi e Riserve', 'Aree protette e natura incontaminata: parchi nazionali, riserve naturali e oasi', '🌲', 'emerald', 11),
(12, 'Architettura Religiosa', 'Chiese, monasteri e luoghi sacri: patrimonio religioso e architettonico della Calabria', '⛪', 'violet', 12),
(13, 'Archeologia', 'Siti archeologici e antiche testimonianze: dalla Magna Grecia all\'epoca romana', '🏺', 'stone', 13),
(14, 'Sport e Avventura', 'Attività sportive e outdoor: trekking, diving, sport acquatici e avventure all\'aria aperta', '🚴', 'lime', 14),
(15, 'Enogastronomia', 'Vini e prodotti tipici locali: cantine, degustazioni e percorsi enogastronomici', '🍷', 'rose', 15),
(16, 'Fotografia', 'I luoghi più fotogenici della regione: panorami, tramonti e scorci suggestivi', '📸', 'cyan', 16),
(17, 'Musica e Spettacoli', 'Eventi culturali e artistici: concerti, teatro, danza e manifestazioni musicali', '🎵', 'fuchsia', 17),
(18, 'Famiglia e Bambini', 'Attività e luoghi per famiglie: parchi giochi, attività educational e divertimento', '👨‍👩‍👧‍👦', 'sky', 18);

-- =============================================
-- 🏛️ INSERIMENTO DATI - PROVINCE
-- =============================================

INSERT INTO `provinces` (`id`, `name`, `description`, `latitude`, `longitude`, `sort_order`) VALUES
(1, 'Catanzaro', 'Capoluogo di regione nel cuore della Calabria, tra il mar Ionio e il Tirreno, ricca di storia e tradizioni', 38.90980000, 16.59690000, 1),
(2, 'Cosenza', 'La provincia più estesa della Calabria, custode della Sila e della Riviera dei Cedri, terra di cultura e natura', 39.29480000, 16.25420000, 2),
(3, 'Crotone', 'Terra di Pitagora affacciata sul mar Ionio, con spiagge cristalline e un ricco patrimonio archeologico', 39.08470000, 17.12520000, 3),
(4, 'Reggio Calabria', 'La punta dello stivale affacciata sullo Stretto di Messina, custode dei Bronzi di Riace e dell\'Aspromonte', 38.10980000, 15.65160000, 4),
(5, 'Vibo Valentia', 'Piccola provincia ricca di tradizioni marinare e gastronomiche, con Tropea perla del Tirreno', 38.67590000, 16.10180000, 5);

-- =============================================
-- 🏘️ INSERIMENTO DATI - CITTÀ PRINCIPALI
-- =============================================

INSERT INTO `cities` (`id`, `name`, `province_id`, `latitude`, `longitude`, `description`, `population`, `altitude`, `zip_code`) VALUES
-- Province di Catanzaro
(1, 'Catanzaro', 1, 38.90980000, 16.59690000, 'Capoluogo di regione, città dei tre colli', 95000, 320, '88100'),
(2, 'Lamezia Terme', 1, 38.96480000, 16.31290000, 'Importante centro della piana con aeroporto internazionale', 71000, 216, '88046'),
(3, 'Soverato', 1, 38.69180000, 16.55130000, 'Perla dello Ionio, rinomata località balneare', 9500, 10, '88068'),
(4, 'Squillace', 1, 38.78520000, 16.53910000, 'Borgo antico con vista panoramica sul golfo', 3400, 344, '88069'),

-- Provincia di Cosenza  
(5, 'Cosenza', 2, 39.29480000, 16.25420000, 'Antica città dei Bruzi, centro culturale della Calabria', 67000, 238, '87100'),
(6, 'Rossano', 2, 39.57610000, 16.63140000, 'Città della liquirizia e del Codex Purpureus', 36000, 270, '87067'),
(7, 'Paola', 2, 39.36560000, 16.03780000, 'Città di San Francesco di Paola, patrono della Calabria', 16000, 60, '87027'),
(8, 'Scalea', 2, 39.81470000, 15.79390000, 'Perla della Riviera dei Cedri', 10500, 25, '87029'),
(9, 'Castrovillari', 2, 39.81240000, 16.20150000, 'Porta del Pollino', 22000, 362, '87012'),
(10, 'Rende', 2, 39.32450000, 16.23780000, 'Sede dell\'Università della Calabria', 35000, 460, '87036'),

-- Provincia di Crotone
(11, 'Crotone', 3, 39.08470000, 17.12520000, 'Antica Kroton, culla della Magna Grecia', 65000, 8, '88900'),
(12, 'Cirò Marina', 3, 39.37260000, 17.12830000, 'Terra del famoso vino Cirò DOC', 14500, 30, '88811'),
(13, 'Isola di Capo Rizzuto', 3, 38.95940000, 17.09720000, 'Area marina protetta con spiagge incontaminate', 15000, 90, '88841'),

-- Provincia di Reggio Calabria
(14, 'Reggio Calabria', 4, 38.10980000, 15.65160000, 'Città dei Bronzi di Riace, affacciata sullo Stretto', 180000, 31, '89100'),
(15, 'Palmi', 4, 38.35920000, 15.85050000, 'Terrazza sul Tirreno con splendidi panorami', 19000, 228, '89015'),
(16, 'Locri', 4, 38.23970000, 16.26220000, 'Antica colonia greca con importanti scavi archeologici', 13000, 6, '89044'),
(17, 'Siderno', 4, 38.26830000, 16.30000000, 'Centro della Locride ionica', 17000, 36, '89048'),
(18, 'Gerace', 4, 38.27040000, 16.21950000, 'Borgo medievale, città di pietra e di fede', 2700, 500, '89040'),

-- Provincia di Vibo Valentia
(19, 'Vibo Valentia', 5, 38.67590000, 16.10180000, 'Antica Hipponion, centro storico e culturale', 34000, 476, '89900'),
(20, 'Tropea', 5, 38.67730000, 15.89760000, 'Perla del Tirreno, una delle località più belle d\'Italia', 6500, 61, '89861'),
(21, 'Pizzo', 5, 38.73470000, 16.15690000, 'Patria del tartufo di Pizzo, affacciata sul golfo di Lamezia', 9000, 97, '89812'),
(22, 'Serra San Bruno', 5, 38.57890000, 16.32850000, 'Centro delle Serre calabresi, tra foreste e spiritualità', 6800, 788, '89822');

-- =============================================
-- 📦 INSERIMENTO DATI - PACCHETTI BUSINESS
-- =============================================

INSERT INTO `business_packages` (`id`, `name`, `description`, `price`, `duration_months`, `features`, `max_articles`, `max_images`, `priority_listing`, `analytics_access`) VALUES
(1, 'Gratuito', 'Inserimento base della tua attività nella piattaforma', 0.00, 12, 
    '[\"Scheda attività base\", \"Contatti e orari\", \"Visibilità nella ricerca\", \"1 immagine del profilo\", \"Descrizione breve\"]', 
    1, 1, 0, 0),
    
(2, 'Business', 'Pacchetto completo per promuovere efficacemente la tua attività', 29.99, 12, 
    '[\"Tutto del piano Gratuito\", \"Fino a 10 foto in galleria\", \"Descrizione estesa e dettagliata\", \"Badge \\\"Verificato\\\"\", \"Statistiche visualizzazioni\", \"Posizione prioritaria nei risultati\", \"Link al sito web\", \"Social media links\"]', 
    5, 10, 1, 1),
    
(3, 'Premium', 'Massima visibilità e funzionalità avanzate per il tuo business', 59.99, 12, 
    '[\"Tutto del piano Business\", \"Foto e video illimitati\", \"Articoli sponsorizzati\", \"Posizione TOP nei risultati\", \"Analytics avanzate e dettagliate\", \"Supporto prioritario via email/telefono\", \"Possibilità di eventi e offerte speciali\", \"Sezione recensioni clienti\", \"Blog aziendale\"]', 
    20, 50, 1, 1);

-- =============================================
-- 🏠 INSERIMENTO DATI - SEZIONI HOMEPAGE
-- =============================================

INSERT INTO `home_sections` (`id`, `section_name`, `title`, `subtitle`, `description`, `image_path`, `background_color`, `button_text`, `button_url`, `is_visible`, `sort_order`, `template_type`) VALUES
(1, 'hero', 'Esplora la Calabria', 'Mare cristallino, montagne maestose e storia millenaria', 
    'Immergiti nella bellezza autentica della Calabria: dalle spiagge da sogno di Tropea alle vette della Sila, dai borghi medievali alle tradizioni enogastronomiche uniche.', 
    '/placeholder-hero.jpg', 'gradient-primary', 'Inizia l\'Esplorazione', '#', 1, 1, 'hero-gradient'),
    
(2, 'categories', 'Esplora per Categoria', 'Scopri la Calabria attraverso i tuoi interessi', 
    'Che tu sia appassionato di natura, storia, gastronomia o avventura, trova il percorso perfetto per te.', 
    NULL, 'bg-white', 'Vedi Tutte le Categorie', '/categories', 1, 2, 'grid-cards'),
    
(3, 'provinces', 'Esplora le Province', 'Ogni provincia custodisce tesori unici', 
    'Dalla culturale Cosenza alla marinara Vibo Valentia, ogni provincia calabrese offre esperienze indimenticabili.', 
    NULL, 'bg-gray-50', 'Scopri le Province', '/provinces', 1, 3, 'carousel'),
    
(4, 'featured-articles', 'Luoghi Imperdibili', 'I nostri consigli per te', 
    'Una selezione curata dei luoghi più belli e rappresentativi della nostra amata Calabria.', 
    NULL, 'bg-white', 'Vedi Altri Luoghi', '/articles', 1, 4, 'featured-grid'),
    
(5, 'newsletter', 'Resta Connesso con la Calabria', 'Non perdere le nostre novità', 
    'Iscriviti alla newsletter per ricevere i migliori consigli di viaggio, eventi esclusivi e le ultime scoperte dalla Calabria.', 
    NULL, 'bg-gradient-to-r from-blue-600 to-purple-600', 'Iscriviti Gratis', '#', 1, 5, 'cta-centered');

-- =============================================
-- ⚙️ INSERIMENTO DATI - IMPOSTAZIONI SISTEMA
-- =============================================

INSERT INTO `settings` (`key`, `value`, `type`, `description`, `category`) VALUES
-- Impostazioni Generali
('site_name', 'Passione Calabria', 'text', 'Nome del sito web', 'general'),
('site_tagline', 'La tua guida definitiva alla Calabria', 'text', 'Slogan del sito', 'general'),
('site_description', 'Scopri la Calabria autentica: guide, consigli e segreti per vivere al meglio la nostra bellissima regione tra mare, montagne, cultura e tradizioni.', 'textarea', 'Descrizione del sito per SEO', 'general'),
('admin_email', 'admin@passionecalabria.it', 'email', 'Email amministratore principale', 'general'),
('contact_email', 'info@passionecalabria.it', 'email', 'Email per contatti pubblici', 'general'),
('contact_phone', '+39 123 456 7890', 'text', 'Telefono per contatti', 'general'),

-- Impostazioni SEO
('google_analytics_id', '', 'text', 'ID Google Analytics (GA4)', 'seo'),
('google_site_verification', '', 'text', 'Codice verifica Google Search Console', 'seo'),
('facebook_pixel_id', '', 'text', 'ID Facebook Pixel', 'seo'),

-- Impostazioni Social Media  
('facebook_url', 'https://facebook.com/passionecalabria', 'url', 'Pagina Facebook ufficiale', 'social'),
('instagram_url', 'https://instagram.com/passionecalabria', 'url', 'Profilo Instagram ufficiale', 'social'),
('youtube_url', '', 'url', 'Canale YouTube', 'social'),
('twitter_url', '', 'url', 'Profilo Twitter/X', 'social'),

-- Impostazioni Homepage
('hero_title', 'Esplora la Calabria', 'text', 'Titolo principale hero section', 'homepage'),
('hero_subtitle', 'Mare cristallino, montagne maestose e storia millenaria', 'text', 'Sottotitolo hero section', 'homepage'),
('hero_description', 'Immergiti nella bellezza autentica della Calabria con le sue spiagge da sogno, il centro storico affascinante e i panorami mozzafiato.', 'textarea', 'Descrizione hero section', 'homepage'),
('hero_image', '/placeholder-hero.jpg', 'image', 'Immagine di sfondo hero section', 'homepage'),
('featured_articles_count', '6', 'number', 'Numero articoli in evidenza homepage', 'homepage'),

-- Impostazioni Business
('business_submission_enabled', '1', 'boolean', 'Abilita registrazione business', 'business'),
('business_approval_required', '1', 'boolean', 'Richiede approvazione admin per business', 'business'),
('stripe_publishable_key', '', 'text', 'Stripe Publishable Key', 'business'),
('stripe_secret_key', '', 'password', 'Stripe Secret Key', 'business'),

-- Impostazioni Newsletter  
('newsletter_enabled', '1', 'boolean', 'Abilita iscrizione newsletter', 'newsletter'),
('newsletter_provider', 'internal', 'select', 'Provider newsletter (internal, mailchimp, etc)', 'newsletter'),
('mailchimp_api_key', '', 'password', 'API Key Mailchimp', 'newsletter'),

-- Impostazioni Traduzioni
('default_language', 'it', 'select', 'Lingua predefinita sito', 'translations'),
('available_languages', 'it,en,fr,de,es', 'text', 'Lingue disponibili (separate da virgola)', 'translations'),
('auto_detect_language', '1', 'boolean', 'Rileva automaticamente lingua browser', 'translations'),

-- Impostazioni Upload
('max_upload_size', '5242880', 'number', 'Dimensione massima upload (bytes)', 'uploads'),
('allowed_image_types', 'jpg,jpeg,png,gif,webp', 'text', 'Tipi immagine consentiti', 'uploads'),
('generate_thumbnails', '1', 'boolean', 'Genera miniature automaticamente', 'uploads'),

-- Impostazioni Mappe
('google_maps_api_key', '', 'password', 'Google Maps API Key', 'maps'),
('default_map_center_lat', '38.9', 'text', 'Latitudine centro mappa predefinita', 'maps'),
('default_map_center_lng', '16.6', 'text', 'Longitudine centro mappa predefinita', 'maps'),
('default_map_zoom', '8', 'number', 'Zoom predefinito mappa', 'maps');

-- =============================================
-- 📰 INSERIMENTO DATI - ARTICOLI ESEMPIO
-- =============================================

INSERT INTO `articles` (`id`, `title`, `slug`, `content`, `excerpt`, `category_id`, `province_id`, `city_id`, `author`, `featured`, `latitude`, `longitude`, `seo_title`, `seo_description`) VALUES

(1, 'La Sila: Il Cuore Verde della Calabria', 'la-sila-cuore-verde-calabria', 
'<h2>Un Paradiso Naturale nel Cuore della Calabria</h2>
<p>L\'Altopiano della Sila rappresenta uno dei tesori naturalistici più preziosi della Calabria. Con i suoi 150.000 ettari di territorio, questo polmone verde offre paesaggi mozzafiato, laghi cristallini e una biodiversità unica che attira visitatori da tutto il mondo.</p>

<h3>I Tre Volti della Sila</h3>
<p>La Sila si divide in tre aree principali:</p>
<ul>
<li><strong>Sila Greca</strong>: La zona più orientale, caratterizzata da foreste di conifere e faggi</li>
<li><strong>Sila Grande</strong>: L\'area centrale con i laghi più famosi come Cecita e Arvo</li>
<li><strong>Sila Piccola</strong>: La porzione meridionale, nota per i suoi pascoli e borghi caratteristici</li>
</ul>

<h3>Cosa Vedere</h3>
<p>Non perdere la visita al <strong>Museo Nazionale della Sila</strong> a Longobucco, i <strong>Giganti della Sila</strong> (pini larici secolari), e i pittoreschi <strong>Laghi Silani</strong> perfetti per escursioni e attività outdoor.</p>',

'Scopri l\'Altopiano della Sila, polmone verde della Calabria con laghi cristallini, foreste secolari e una biodiversità unica. Guida completa per visitare questo paradiso naturale.', 
1, 2, NULL, 'Marco Rossi', 1, 39.30000000, 16.50000000,
'La Sila Calabria: Guida Completa al Parco Nazionale | Passione Calabria',
'Scopri la Sila, il cuore verde della Calabria: laghi, foreste, borghi e attività outdoor. Guida completa per visitare il Parco Nazionale della Sila.'),

(2, 'I Bronzi di Riace: Capolavori della Magna Grecia', 'bronzi-riace-magna-grecia',
'<h2>I Guerrieri del Mare</h2>
<p>I Bronzi di Riace sono due magnifiche statue di bronzo di epoca greca classica (V secolo a.C.), rinvenute fortuitamente nel 1972 nei fondali marini antistanti Riace Marina da Stefano Mariottini, un appassionato di immersioni subacquee.</p>

<h3>La Scoperta del Secolo</h3>
<p>Il 16 agosto 1972, a circa 200 metri dalla costa di Riace e a 8 metri di profondità, emergevano dal mare due straordinarie sculture che avrebbero rivoluzionato la conoscenza dell\'arte greca antica. Oggi custodite presso il <strong>Museo Archeologico Nazionale di Reggio Calabria</strong>, rappresentano uno dei più importanti ritrovamenti archeologici del XX secolo.</p>

<h3>Arte e Tecnica</h3>
<p>Le statue, denominate Bronzo A e Bronzo B, mostrano due guerrieri nella pienezza della loro forza fisica. Realizzate con la tecnica della fusione a cera persa, dimostrano la maestria raggiunta dagli scultori greci nell\'arte del bronzo.</p>

<h3>Visita al Museo</h3>
<p>Il <strong>MArRC</strong> (Museo Archeologico Nazionale di Reggio Calabria) offre un percorso espositivo dedicato che permette di ammirare da vicino questi capolavori e comprendere il contesto storico della Magna Grecia.</p>',

'I celebri Bronzi di Riace, capolavori della scultura greca del V secolo a.C. custoditi nel Museo di Reggio Calabria. Storia, arte e informazioni per la visita.',
2, 4, 14, 'Elena Greco', 1, 38.11130000, 15.64420000,
'Bronzi di Riace: Storia e Visita al Museo di Reggio Calabria | Passione Calabria',
'Scopri i Bronzi di Riace, capolavori della Magna Grecia. Storia della scoperta, arte greca antica e informazioni per visitare il MArRC di Reggio Calabria.'),

(3, 'Tropea: La Perla del Tirreno', 'tropea-perla-tirreno',
'<h2>Il Gioiello della Costa Calabrese</h2>
<p>Tropea è universalmente riconosciuta come una delle località balneari più belle d\'Italia. Arroccata su una falesia di tufo a strapiombo sul mare, questa antica città normanna offre uno dei panorami più suggestivi e fotografati della Calabria.</p>

<h3>Il Centro Storico</h3>
<p>Il <strong>centro storico</strong> di Tropea è un labirinto di vicoli lastricati, palazzi nobiliari e chiese antiche. Non perdere:</p>
<ul>
<li>La <strong>Cattedrale Normanna</strong> (XII secolo)</li>
<li>Il <strong>Belvedere</strong> con vista sulla Costa degli Dei</li>
<li>Le caratteristiche <strong>botteghe artigianali</strong></li>
<li>I <strong>palazzi storici</strong> in stile barocco</li>
</ul>

<h3>Le Spiagge</h3>
<p>Le spiagge di Tropea sono famose in tutto il mondo per la sabbia bianchissima e le acque cristalline. La <strong>spiaggia principale</strong> si trova ai piedi del centro storico, raggiungibile tramite una scalinata panoramica.</p>

<h3>Specialità Gastronomiche</h3>
<p>Tropea è famosa anche per:</p>
<ul>
<li>La <strong>cipolla rossa di Tropea IGP</strong></li>
<li>I <strong>gelati artigianali</strong> con vista mare</li>
<li>Il <strong>pesce fresco</strong> dei ristoranti sul lungomare</li>
</ul>

<h3>Il Santuario di Santa Maria dell\'Isola</h3>
<p>Simbolo indiscusso di Tropea, il <strong>Santuario</strong> sorge su uno scoglio collegato alla terraferma da una lingua di sabbia. Un luogo mistico e suggestivo, perfetto per ammirare il tramonto.</p>',

'Tropea, perla del Tirreno calabrese: spiagge da sogno, centro storico medievale, cipolla rossa IGP e il famoso Santuario di Santa Maria dell\'Isola. Guida completa.',
4, 5, 20, 'Maria Costantino', 1, 38.67730000, 15.89840000,
'Tropea Calabria: Spiagge, Centro Storico e Cosa Vedere | Passione Calabria',
'Scopri Tropea, la perla del Tirreno: spiagge paradisiache, centro storico medievale, cipolla rossa IGP e il Santuario di Santa Maria dell\'Isola.');

-- =============================================
-- 📝 INSERIMENTO DATI - PAGINE STATICHE
-- =============================================

INSERT INTO `static_pages` (`slug`, `title`, `content`, `meta_title`, `meta_description`, `template`) VALUES

('chi-siamo', 'Chi Siamo', 
'<div class=\"prose max-w-none\">
<h1>Chi Siamo - Passione Calabria</h1>

<p class=\"lead\">Benvenuti in <strong>Passione Calabria</strong>, il portale dedicato alla scoperta e valorizzazione di una delle regioni più affascinanti e autentiche d\'Italia: la nostra amata Calabria.</p>

<h2>La Nostra Missione</h2>
<p>Nata dalla passione di un gruppo di calabresi innamorati della propria terra, Passione Calabria si propone di essere la <strong>guida definitiva</strong> per chiunque voglia scoprire le meraviglie di questa regione straordinaria.</p>

<p>Il nostro obiettivo è raccontare la Calabria autentica, quella fatta di:</p>
<ul>
<li>🏖️ <strong>Spiagge paradisiache</strong> dalle acque cristalline</li>
<li>🏔️ <strong>Montagne maestose</strong> come la Sila e l\'Aspromonte</li>
<li>🏛️ <strong>Storia millenaria</strong> dalla Magna Grecia ai giorni nostri</li>
<li>🍝 <strong>Tradizioni culinarie</strong> uniche e genuine</li>
<li>🎭 <strong>Cultura e tradizioni</strong> che si tramandano da generazioni</li>
</ul>

<h2>Cosa Troverai</h2>
<p>Su Passione Calabria potrai esplorare:</p>

<div class=\"grid md:grid-cols-2 gap-6 my-8\">
<div class=\"bg-blue-50 p-6 rounded-lg\">
<h3 class=\"text-blue-800\">🗺️ Guide Complete</h3>
<p>Itinerari dettagliati, consigli pratici e informazioni aggiornate per ogni provincia calabrese.</p>
</div>
<div class=\"bg-green-50 p-6 rounded-lg\">
<h3 class=\"text-green-800\">📍 Luoghi Nascosti</h3>
<p>Scopri borghi, spiagge segrete e tesori che solo i locali conoscono.</p>
</div>
<div class=\"bg-yellow-50 p-6 rounded-lg\">
<h3 class=\"text-yellow-800\">🍷 Enogastronomia</h3>
<p>Sapori autentici, ricette tradizionali e i migliori ristoranti della regione.</p>
</div>
<div class=\"bg-purple-50 p-6 rounded-lg\">
<h3 class=\"text-purple-800\">🎪 Eventi e Sagre</h3>
<p>Non perdere le feste patronali, i festival e le manifestazioni culturali.</p>
</div>
</div>

<h2>Il Nostro Team</h2>
<p>Siamo un gruppo di appassionati calabresi: guide turistiche, fotografi, blogger di viaggio e semplici amanti della nostra terra. Ognuno di noi contribuisce con la propria esperienza e conoscenza per offrirti contenuti sempre aggiornati e di qualità.</p>

<h2>Partecipa Anche Tu</h2>
<p>Passione Calabria è anche <strong>community</strong>. Se conosci un posto speciale, hai una storia da raccontare o vuoi condividere la tua esperienza in Calabria, <a href=\"/contatti\">contattaci</a>! Le tue segnalazioni e i tuoi contributi ci aiutano a rendere questa piattaforma sempre più ricca e utile.</p>

<div class=\"bg-gradient-to-r from-blue-500 to-purple-600 text-white p-8 rounded-xl my-8 text-center\">
<h3 class=\"text-2xl font-bold mb-4\">🌟 La Calabria ti aspetta!</h3>
<p class=\"text-lg opacity-90\">Inizia subito la tua avventura e scopri perché la Calabria conquista il cuore di chiunque la visiti.</p>
<a href=\"/\" class=\"inline-block mt-4 bg-white text-blue-600 px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors\">Esplora la Calabria →</a>
</div>

</div>',
'Chi Siamo - Passione Calabria: La Tua Guida Autentica della Calabria',
'Scopri chi siamo: il team di Passione Calabria, appassionati della nostra terra che vogliono farti innamorare della Calabria autentica. La nostra missione e i nostri valori.',
'page-full'),

('privacy-policy', 'Privacy Policy',
'<div class=\"prose max-w-none\">
<h1>Privacy Policy - Passione Calabria</h1>
<p class=\"text-gray-600\">Ultimo aggiornamento: 4 Settembre 2024</p>

<h2>Introduzione</h2>
<p>La presente Privacy Policy descrive come Passione Calabria raccoglie, utilizza e protegge le informazioni personali dei visitatori del nostro sito web <strong>www.passionecalabria.it</strong>.</p>

<p>Ci impegniamo a proteggere la tua privacy e a essere trasparenti sul trattamento dei tuoi dati personali, in conformità con il Regolamento Generale sulla Protezione dei Dati (GDPR) dell\'Unione Europea.</p>

<h2>Dati Raccolti</h2>
<h3>Informazioni fornite volontariamente</h3>
<ul>
<li><strong>Newsletter</strong>: Nome ed email per l\'iscrizione alla newsletter</li>
<li><strong>Commenti</strong>: Nome, email e contenuto dei commenti agli articoli</li>
<li><strong>Contatti</strong>: Informazioni inviate tramite moduli di contatto</li>
<li><strong>Caricamento contenuti</strong>: Dati forniti per condividere foto e suggerimenti</li>
</ul>

<h3>Informazioni raccolte automaticamente</h3>
<ul>
<li><strong>Dati di navigazione</strong>: Indirizzo IP, browser utilizzato, pagine visitate</li>
<li><strong>Cookie tecnici</strong>: Per il corretto funzionamento del sito</li>
<li><strong>Analytics</strong>: Statistiche anonimizzate sulle visite (Google Analytics)</li>
</ul>

<h2>Finalità del Trattamento</h2>
<p>Utilizziamo i tuoi dati per:</p>
<ul>
<li>Fornire i servizi richiesti (newsletter, risposte ai contatti)</li>
<li>Migliorare l\'esperienza utente del sito</li>
<li>Analizzare il traffico e l\'utilizzo del sito</li>
<li>Moderare e pubblicare commenti</li>
<li>Comunicazioni relative al servizio</li>
</ul>

<h2>Base Giuridica</h2>
<p>Il trattamento dei dati è basato su:</p>
<ul>
<li><strong>Consenso</strong>: Per newsletter e cookie non tecnici</li>
<li><strong>Legittimo interesse</strong>: Per analytics e sicurezza del sito</li>
<li><strong>Esecuzione di un servizio</strong>: Per rispondere alle richieste di contatto</li>
</ul>

<h2>Condivisione dei Dati</h2>
<p>Non vendiamo, affittiamo o condividiamo i tuoi dati personali con terze parti per scopi commerciali. I dati possono essere condivisi solo con:</p>
<ul>
<li><strong>Fornitori di servizi</strong>: Hosting, email marketing (con garanzie di protezione dati)</li>
<li><strong>Autorità competenti</strong>: Solo se richiesto dalla legge</li>
</ul>

<h2>I Tuoi Diritti</h2>
<p>Hai il diritto di:</p>
<ul>
<li><strong>Accesso</strong>: Conoscere quali dati abbiamo su di te</li>
<li><strong>Rettifica</strong>: Correggere dati inesatti</li>
<li><strong>Cancellazione</strong>: Richiedere la rimozione dei tuoi dati</li>
<li><strong>Portabilità</strong>: Ricevere i tuoi dati in formato leggibile</li>
<li><strong>Opposizione</strong>: Opporti al trattamento per finalità di marketing</li>
<li><strong>Revoca consenso</strong>: Ritirare il consenso in qualsiasi momento</li>
</ul>

<h2>Cookie</h2>
<p>Utilizziamo cookie per:</p>
<ul>
<li><strong>Cookie tecnici</strong>: Necessari per il funzionamento del sito</li>
<li><strong>Cookie analytics</strong>: Per statistiche anonimizzate (Google Analytics)</li>
<li><strong>Cookie di preferenze</strong>: Per ricordare le tue scelte (es. lingua)</li>
</ul>
<p>Puoi gestire le preferenze sui cookie attraverso le impostazioni del tuo browser.</p>

<h2>Sicurezza</h2>
<p>Implementiamo misure tecniche e organizzative appropriate per proteggere i tuoi dati personali contro accesso non autorizzato, perdita, distruzione o divulgazione.</p>

<h2>Conservazione</h2>
<p>Conserviamo i tuoi dati solo per il tempo necessario agli scopi per cui sono stati raccolti:</p>
<ul>
<li><strong>Newsletter</strong>: Fino alla cancellazione dell\'iscrizione</li>
<li><strong>Commenti</strong>: Fino alla richiesta di cancellazione</li>
<li><strong>Dati analytics</strong>: 26 mesi (Google Analytics)</li>
</ul>

<h2>Modifiche alla Privacy Policy</h2>
<p>Ci riserviamo il diritto di modificare questa Privacy Policy. Le modifiche saranno pubblicate su questa pagina con indicazione della data di ultimo aggiornamento.</p>

<h2>Contatti</h2>
<p>Per esercitare i tuoi diritti o per qualsiasi domanda riguardante il trattamento dei dati personali, puoi contattarci:</p>
<ul>
<li><strong>Email</strong>: privacy@passionecalabria.it</li>
<li><strong>Indirizzo</strong>: Tramite il <a href=\"/contatti\">modulo di contatto</a></li>
</ul>

<p>Hai anche il diritto di presentare un reclamo all\'Autorità Garante per la protezione dei dati personali.</p>
</div>',
'Privacy Policy - Passione Calabria: Protezione Dati e Privacy',
'Privacy Policy di Passione Calabria: come raccogliamo, utilizziamo e proteggiamo i tuoi dati personali in conformità con il GDPR europeo.',
'legal'),

('termini-servizio', 'Termini di Servizio',
'<div class=\"prose max-w-none\">
<h1>Termini di Servizio - Passione Calabria</h1>
<p class=\"text-gray-600\">Ultimo aggiornamento: 4 Settembre 2024</p>

<h2>Accettazione dei Termini</h2>
<p>Utilizzando il sito web <strong>www.passionecalabria.it</strong> (\"il Sito\"), accetti di essere vincolato dai presenti Termini di Servizio. Se non accetti questi termini, ti preghiamo di non utilizzare il nostro sito.</p>

<h2>Descrizione del Servizio</h2>
<p>Passione Calabria è un portale informativo dedicato alla promozione turistica e culturale della Calabria. Offriamo:</p>
<ul>
<li>Guide turistiche e itinerari</li>
<li>Informazioni su attrazioni, eventi e luoghi d\'interesse</li>
<li>Servizi di newsletter informativa</li>
<li>Piattaforma per la condivisione di contenuti degli utenti</li>
<li>Servizi di registrazione per attività commerciali</li>
</ul>

<h2>Utilizzo del Sito</h2>
<h3>Utilizzo Consentito</h3>
<p>Puoi utilizzare il Sito per:</p>
<ul>
<li>Consultare informazioni turistiche sulla Calabria</li>
<li>Condividere contenuti conformi alle nostre linee guida</li>
<li>Interagire con la community attraverso commenti</li>
<li>Iscriverti ai nostri servizi di newsletter</li>
</ul>

<h3>Utilizzo Vietato</h3>
<p>È vietato:</p>
<ul>
<li>Utilizzare il Sito per scopi illegali o non autorizzati</li>
<li>Pubblicare contenuti offensivi, discriminatori o diffamatori</li>
<li>Violare i diritti di proprietà intellettuale</li>
<li>Tentare di accedere illegalmente ai sistemi del Sito</li>
<li>Utilizzare bot o sistemi automatizzati non autorizzati</li>
<li>Inviare spam o contenuti pubblicitari non richiesti</li>
</ul>

<h2>Contenuti Utente</h2>
<h3>Responsabilità</h3>
<p>Sei responsabile per tutti i contenuti che pubblichi sul Sito (commenti, foto, recensioni). Garantisci che:</p>
<ul>
<li>I contenuti non violano leggi o diritti di terzi</li>
<li>Possiedi i diritti necessari per la pubblicazione</li>
<li>Le informazioni fornite sono veritiere</li>
</ul>

<h3>Licenza</h3>
<p>Pubblicando contenuti sul Sito, concedi a Passione Calabria una licenza non esclusiva per utilizzare, modificare e pubblicare tali contenuti per le finalità del servizio.</p>

<h3>Moderazione</h3>
<p>Ci riserviamo il diritto di:</p>
<ul>
<li>Moderare e rimuovere contenuti inappropriati</li>
<li>Sospendere o bannare utenti che violano i termini</li>
<li>Modificare o eliminare contenuti senza preavviso</li>
</ul>

<h2>Proprietà Intellettuale</h2>
<p>Tutti i contenuti presenti sul Sito (testi, immagini, loghi, design) sono protetti da diritti d\'autore e appartengono a Passione Calabria o ai rispettivi proprietari.</p>

<p>È consentito:</p>
<ul>
<li>Condividere i nostri contenuti per uso personale e non commerciale</li>
<li>Citare i nostri articoli con attribuzione e link alla fonte</li>
</ul>

<h2>Servizi di Terze Parti</h2>
<p>Il Sito può contenere link o integrazioni con servizi di terze parti (Google Maps, social media, etc.). Non siamo responsabili per le pratiche di privacy o i contenuti di questi servizi esterni.</p>

<h2>Disclaimer e Limitazioni</h2>
<h3>Accuratezza delle Informazioni</h3>
<p>Ci impegniamo a fornire informazioni accurate e aggiornate, ma non garantiamo la completezza o l\'accuratezza assoluta di tutti i contenuti. Le informazioni possono cambiare senza preavviso.</p>

<h3>Limitazione di Responsabilità</h3>
<p>Passione Calabria non è responsabile per:</p>
<ul>
<li>Danni diretti o indiretti derivanti dall\'uso del Sito</li>
<li>Interruzioni del servizio o errori tecnici</li>
<li>Azioni di terze parti o contenuti di utenti</li>
<li>Decisioni prese sulla base delle informazioni del Sito</li>
</ul>

<h2>Privacy</h2>
<p>Il trattamento dei dati personali è disciplinato dalla nostra <a href=\"/privacy-policy\">Privacy Policy</a>, che costituisce parte integrante di questi Termini.</p>

<h2>Modifiche ai Termini</h2>
<p>Ci riserviamo il diritto di modificare questi Termini di Servizio in qualsiasi momento. Le modifiche saranno pubblicate su questa pagina con indicazione della data di aggiornamento.</p>

<h2>Risoluzione delle Controversie</h2>
<p>Per qualsiasi controversia relativa all\'utilizzo del Sito, le parti si impegnano a tentare una risoluzione amichevole. In caso di mancato accordo, sarà competente il foro di Catanzaro.</p>

<h2>Contatti</h2>
<p>Per domande sui presenti Termini di Servizio:</p>
<ul>
<li><strong>Email</strong>: legal@passionecalabria.it</li>
<li><strong>Contatti</strong>: Tramite il nostro <a href=\"/contatti\">modulo di contatto</a></li>
</ul>

<div class=\"bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8\">
<h3 class=\"text-blue-800 mb-2\">📞 Hai Domande?</h3>
<p class=\"text-blue-700 mb-0\">Se hai bisogno di chiarimenti sui nostri Termini di Servizio, non esitare a <a href=\"/contatti\" class=\"font-semibold\">contattarci</a>. Siamo qui per aiutarti!</p>
</div>
</div>',
'Termini di Servizio - Passione Calabria: Regole e Condizioni d\'Uso',
'Termini e condizioni d\'uso di Passione Calabria: regole, diritti e responsabilità per l\'utilizzo del nostro portale turistico della Calabria.',
'legal'),

('contatti', 'Contatti',
'<div class=\"prose max-w-none\">
<h1>Contatta Passione Calabria</h1>
<p class=\"lead\">Hai domande, suggerimenti o vuoi collaborare con noi? Siamo sempre felici di sentire dalla nostra community!</p>

<div class=\"grid md:grid-cols-2 gap-8 my-8\">

<div class=\"bg-gradient-to-br from-blue-50 to-indigo-100 p-8 rounded-xl\">
<h2 class=\"text-blue-800 mb-4\">📧 Contatti Generali</h2>
<div class=\"space-y-4\">
<div>
<h4 class=\"font-semibold text-blue-700 mb-1\">Email Principale</h4>
<p class=\"text-blue-600\">info@passionecalabria.it</p>
</div>
<div>
<h4 class=\"font-semibold text-blue-700 mb-1\">Telefono</h4>
<p class=\"text-blue-600\">+39 123 456 7890</p>
</div>
<div>
<h4 class=\"font-semibold text-blue-700 mb-1\">Orari</h4>
<p class=\"text-blue-600\">Lun-Ven: 9:00-18:00<br>Weekend: Su appuntamento</p>
</div>
</div>
</div>

<div class=\"bg-gradient-to-br from-green-50 to-emerald-100 p-8 rounded-xl\">
<h2 class=\"text-green-800 mb-4\">🤝 Collaborazioni</h2>
<div class=\"space-y-4\">
<div>
<h4 class=\"font-semibold text-green-700 mb-1\">Partnership</h4>
<p class=\"text-green-600\">partnership@passionecalabria.it</p>
</div>
<div>
<h4 class=\"font-semibold text-green-700 mb-1\">Ufficio Stampa</h4>
<p class=\"text-green-600\">stampa@passionecalabria.it</p>
</div>
<div>
<h4 class=\"font-semibold text-green-700 mb-1\">Contenuti</h4>
<p class=\"text-green-600\">redazione@passionecalabria.it</p>
</div>
</div>
</div>

<div class=\"bg-gradient-to-br from-purple-50 to-pink-100 p-8 rounded-xl\">
<h2 class=\"text-purple-800 mb-4\">🏢 Business</h2>
<div class=\"space-y-4\">
<div>
<h4 class=\"font-semibold text-purple-700 mb-1\">Registrazione Attività</h4>
<p class=\"text-purple-600\">business@passionecalabria.it</p>
</div>
<div>
<h4 class=\"font-semibold text-purple-700 mb-1\">Supporto Tecnico</h4>
<p class=\"text-purple-600\">support@passionecalabria.it</p>
</div>
<div>
<h4 class=\"font-semibold text-purple-700 mb-1\">Pubblicità</h4>
<p class=\"text-purple-600\">ads@passionecalabria.it</p>
</div>
</div>
</div>

<div class=\"bg-gradient-to-br from-yellow-50 to-amber-100 p-8 rounded-xl\">
<h2 class=\"text-yellow-800 mb-4\">🛡️ Assistenza</h2>
<div class=\"space-y-4\">
<div>
<h4 class=\"font-semibold text-yellow-700 mb-1\">Privacy e GDPR</h4>
<p class=\"text-yellow-600\">privacy@passionecalabria.it</p>
</div>
<div>
<h4 class=\"font-semibold text-yellow-700 mb-1\">Problemi Tecnici</h4>
<p class=\"text-yellow-600\">tech@passionecalabria.it</p>
</div>
<div>
<h4 class=\"font-semibold text-yellow-700 mb-1\">Segnalazioni</h4>
<p class=\"text-yellow-600\">report@passionecalabria.it</p>
</div>
</div>
</div>

</div>

<div class=\"bg-gradient-to-r from-blue-600 to-purple-600 text-white p-8 rounded-xl my-8\">
<h2 class=\"text-2xl font-bold mb-4\">💬 Scrivici Direttamente</h2>
<div class=\"bg-white/10 backdrop-blur-sm rounded-lg p-6\">
<form class=\"space-y-4\">
<div class=\"grid md:grid-cols-2 gap-4\">
<div>
<label class=\"block text-sm font-medium mb-2\">Nome *</label>
<input type=\"text\" required class=\"w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50\" placeholder=\"Il tuo nome\">
</div>
<div>
<label class=\"block text-sm font-medium mb-2\">Email *</label>
<input type=\"email\" required class=\"w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50\" placeholder=\"La tua email\">
</div>
</div>
<div>
<label class=\"block text-sm font-medium mb-2\">Oggetto *</label>
<select class=\"w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white focus:outline-none focus:ring-2 focus:ring-white/50\">
<option>Informazioni Generali</option>
<option>Collaborazione</option>
<option>Segnalazione Luogo</option>
<option>Problema Tecnico</option>
<option>Altro</option>
</select>
</div>
<div>
<label class=\"block text-sm font-medium mb-2\">Messaggio *</label>
<textarea required rows=\"4\" class=\"w-full px-4 py-2 rounded-lg bg-white/20 border border-white/30 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50\" placeholder=\"Scrivi qui il tuo messaggio...\"></textarea>
</div>
<div class=\"flex items-start space-x-2\">
<input type=\"checkbox\" required class=\"mt-1\">
<label class=\"text-sm opacity-90\">Accetto i <a href=\"/termini-servizio\" class=\"underline\">Termini di Servizio</a> e la <a href=\"/privacy-policy\" class=\"underline\">Privacy Policy</a> *</label>
</div>
<button type=\"submit\" class=\"bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors\">
Invia Messaggio 📨
</button>
</form>
</div>
</div>

<h2>🌐 Seguici sui Social</h2>
<div class=\"grid grid-cols-2 md:grid-cols-4 gap-4 my-6\">
<a href=\"https://facebook.com/passionecalabria\" class=\"bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition-colors\">
<div class=\"text-2xl mb-2\">📘</div>
<div class=\"font-semibold\">Facebook</div>
</a>
<a href=\"https://instagram.com/passionecalabria\" class=\"bg-pink-600 text-white p-4 rounded-lg text-center hover:bg-pink-700 transition-colors\">
<div class=\"text-2xl mb-2\">📷</div>
<div class=\"font-semibold\">Instagram</div>
</a>
<a href=\"https://youtube.com/passionecalabria\" class=\"bg-red-600 text-white p-4 rounded-lg text-center hover:bg-red-700 transition-colors\">
<div class=\"text-2xl mb-2\">📺</div>
<div class=\"font-semibold\">YouTube</div>
</a>
<a href=\"https://twitter.com/passionecalabria\" class=\"bg-blue-400 text-white p-4 rounded-lg text-center hover:bg-blue-500 transition-colors\">
<div class=\"text-2xl mb-2\">🐦</div>
<div class=\"font-semibold\">Twitter</div>
</a>
</div>

<div class=\"bg-green-50 border border-green-200 rounded-lg p-6 mt-8\">
<h3 class=\"text-green-800 mb-3\">🎯 Hai un Posto Speciale da Segnalarci?</h3>
<p class=\"text-green-700 mb-4\">Conosci un luogo nascosto, un ristorante fantastico o un evento imperdibile in Calabria? Condividilo con noi!</p>
<a href=\"/suggerisci\" class=\"bg-green-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-green-700 transition-colors inline-block\">
Suggerisci un Luogo →
</a>
</div>

<h2>❓ FAQ - Domande Frequenti</h2>
<div class=\"space-y-4 my-6\">
<details class=\"bg-gray-50 p-4 rounded-lg\">
<summary class=\"font-semibold cursor-pointer\">Come posso suggerire un nuovo luogo da inserire nel sito?</summary>
<p class=\"mt-2 text-gray-600\">Puoi utilizzare il nostro <a href=\"/suggerisci\" class=\"text-blue-600 underline\">modulo di suggerimento</a> oppure scriverci direttamente a redazione@passionecalabria.it con tutte le informazioni del luogo.</p>
</details>

<details class=\"bg-gray-50 p-4 rounded-lg\">
<summary class=\"font-semibold cursor-pointer\">Come faccio a registrare la mia attività sul sito?</summary>
<p class=\"mt-2 text-gray-600\">Visita la pagina <a href=\"/registra-business\" class=\"text-blue-600 underline\">Registra la tua attività</a> e compila il modulo. Il nostro team esaminerà la richiesta e ti ricontatterà.</p>
</details>

<details class=\"bg-gray-50 p-4 rounded-lg\">
<summary class=\"font-semibold cursor-pointer\">Posso utilizzare le vostre foto e contenuti?</summary>
<p class=\"mt-2 text-gray-600\">Puoi condividere i nostri contenuti per uso personale citando sempre la fonte. Per usi commerciali, contattaci per ottenere le autorizzazioni necessarie.</p>
</details>

<details class=\"bg-gray-50 p-4 rounded-lg\">
<summary class=\"font-semibold cursor-pointer\">Come posso collaborare con voi come guida o blogger?</summary>
<p class=\"mt-2 text-gray-600\">Scrivi a partnership@passionecalabria.it allegando un tuo portfolio e spiegandoci come vorresti contribuire al progetto.</p>
</details>
</div>

</div>',
'Contatta Passione Calabria: Email, Telefono e Modulo Contatti',
'Contatta il team di Passione Calabria per informazioni, collaborazioni, segnalazioni o supporto. Tutti i nostri recapiti e il modulo di contatto diretto.',
'contact');

-- =============================================
-- 📝 CONTENUTI STATICI PER TRADUZIONI
-- =============================================

INSERT INTO `static_content` (`content_key`, `content_it`, `context_info`, `page_location`, `content_type`) VALUES
-- Homepage Hero
('hero-title', 'Esplora la Calabria', 'Titolo principale della homepage', 'homepage-hero', 'title'),
('hero-subtitle', 'Mare cristallino, montagne maestose e storia millenaria', 'Sottotitolo hero section homepage', 'homepage-hero', 'description'),
('hero-cta-primary', 'Scopri la Calabria', 'Pulsante principale homepage', 'homepage-hero', 'button'),
('hero-cta-secondary', 'Visualizza Mappa', 'Pulsante secondario homepage', 'homepage-hero', 'button'),

-- Homepage Search
('search-title', 'Cosa stai cercando?', 'Titolo widget ricerca homepage', 'homepage-search', 'title'),
('search-placeholder', 'Cerca luoghi, città, attrazioni...', 'Placeholder campo ricerca', 'homepage-search', 'label'),
('search-button', 'Cerca', 'Pulsante ricerca', 'homepage-search', 'button'),

-- Homepage Categories
('categories-title', 'Esplora per Categoria', 'Titolo sezione categorie homepage', 'homepage-categories', 'title'),
('categories-subtitle', 'Scopri la Calabria attraverso i tuoi interessi', 'Sottotitolo sezione categorie', 'homepage-categories', 'description'),

-- Homepage Provinces  
('provinces-title', 'Esplora le Province', 'Titolo sezione province homepage', 'homepage-provinces', 'title'),
('provinces-subtitle', 'Ogni provincia custodisce tesori unici', 'Sottotitolo sezione province', 'homepage-provinces', 'description'),

-- Homepage Newsletter
('newsletter-title', 'Resta Connesso con la Calabria', 'Titolo newsletter homepage', 'homepage-newsletter', 'title'),
('newsletter-description', 'Iscriviti per ricevere i migliori consigli di viaggio e le ultime novità', 'Descrizione newsletter', 'homepage-newsletter', 'description'),
('newsletter-placeholder', 'La tua email', 'Placeholder email newsletter', 'homepage-newsletter', 'label'),
('newsletter-button', 'Iscriviti Gratis', 'Pulsante newsletter', 'homepage-newsletter', 'button'),

-- Navigation
('nav-home', 'Home', 'Link navigazione homepage', 'navigation', 'text'),
('nav-categories', 'Categorie', 'Link navigazione categorie', 'navigation', 'text'),
('nav-provinces', 'Province', 'Link navigazione province', 'navigation', 'text'),
('nav-map', 'Mappa', 'Link navigazione mappa', 'navigation', 'text'),
('nav-articles', 'Articoli', 'Link navigazione articoli', 'navigation', 'text'),
('nav-business-register', 'Registra la tua attività', 'Link registrazione business', 'navigation', 'button'),

-- Footer
('footer-description', 'La tua guida autentica per scoprire la Calabria', 'Descrizione nel footer', 'footer', 'description'),
('footer-contact-title', 'Contatti', 'Titolo sezione contatti footer', 'footer', 'title'),
('footer-follow-title', 'Seguici', 'Titolo social media footer', 'footer', 'title'),
('footer-links-title', 'Link Utili', 'Titolo link utili footer', 'footer', 'title'),
('footer-copyright', '© 2024 Passione Calabria. Tutti i diritti riservati.', 'Copyright footer', 'footer', 'text'),

-- Common UI
('read-more', 'Leggi di più', 'Link leggi di più articoli', 'common', 'button'),
('view-all', 'Vedi tutti', 'Pulsante vedi tutti', 'common', 'button'),
('back', 'Indietro', 'Pulsante indietro', 'common', 'button'),
('next', 'Avanti', 'Pulsante avanti', 'common', 'button'),
('close', 'Chiudi', 'Pulsante chiudi', 'common', 'button'),
('loading', 'Caricamento...', 'Testo di caricamento', 'common', 'text'),

-- Article Pages
('article-author', 'Autore', 'Label autore articolo', 'article', 'label'),
('article-date', 'Pubblicato il', 'Label data pubblicazione', 'article', 'label'),
('article-category', 'Categoria', 'Label categoria articolo', 'article', 'label'),
('article-views', 'visualizzazioni', 'Label visualizzazioni', 'article', 'text'),
('article-share', 'Condividi', 'Pulsante condividi articolo', 'article', 'button'),

-- Contact Forms
('contact-name', 'Nome', 'Label nome modulo contatti', 'contact', 'label'),
('contact-email', 'Email', 'Label email modulo contatti', 'contact', 'label'),
('contact-message', 'Messaggio', 'Label messaggio modulo contatti', 'contact', 'label'),
('contact-send', 'Invia Messaggio', 'Pulsante invio modulo contatti', 'contact', 'button');

-- =============================================
-- 🔗 VINCOLI FOREIGN KEY FINALI
-- =============================================

-- Articles
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `articles_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL;

-- Businesses  
ALTER TABLE `businesses`
  ADD CONSTRAINT `businesses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `businesses_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `businesses_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL;

-- Events
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `events_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL;

-- Place Suggestions
ALTER TABLE `place_suggestions`
  ADD CONSTRAINT `suggestions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `suggestions_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `suggestions_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- =============================================
-- 🎯 FINE DATABASE - PRONTO PER IONOS HOSTING
-- 
-- ISTRUZIONI PER L'IMPORTAZIONE:
-- 1. Accedi a phpMyAdmin sul tuo hosting Ionos
-- 2. Seleziona il tuo database
-- 3. Vai su "Importa" e carica questo file SQL
-- 4. Modifica il file config.php con i dati del tuo database MySQL
-- 
-- Il database include:
-- ✅ Struttura completa ottimizzata per MySQL 8.0+
-- ✅ Dati di esempio (categorie, province, città, articoli)
-- ✅ Sistema di traduzione con file statici  
-- ✅ Sistema business e abbonamenti
-- ✅ Analytics e tracking avanzati
-- ✅ Sicurezza e performance ottimizzate
-- =============================================