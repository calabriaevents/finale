-- =============================================
-- 🗄️ PASSIONE CALABRIA - DATABASE MYSQL IONOS
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
  `og_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_articles_slug` (`slug`),
  KEY `idx_articles_category` (`category_id`),
  KEY `idx_articles_province` (`province_id`),
  KEY `idx_articles_city` (`city_id`),
  KEY `idx_articles_status` (`status`),
  KEY `idx_articles_featured` (`featured`),
  KEY `idx_articles_created` (`created_at`),
  FULLTEXT KEY `idx_articles_search` (`title`,`content`,`excerpt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 📂 Tabella categorie
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#333333',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_categories_slug` (`slug`),
  KEY `idx_categories_parent` (`parent_id`),
  KEY `idx_categories_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🌍 Tabella province
CREATE TABLE `provinces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Calabria',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_provinces_slug` (`slug`),
  KEY `idx_provinces_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🏘️ Tabella cities
CREATE TABLE `cities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` int NOT NULL,
  `postal_code` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `population` int DEFAULT NULL,
  `altitude` int DEFAULT NULL,
  `featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_cities_slug_province` (`slug`,`province_id`),
  KEY `idx_cities_province` (`province_id`),
  KEY `idx_cities_featured` (`featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 💬 Tabella commenti globali (condivisi tra lingue)
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `status` enum('pending','approved','rejected','spam') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_comments_article` (`article_id`),
  KEY `idx_comments_status` (`status`),
  KEY `idx_comments_parent` (`parent_id`),
  KEY `idx_comments_created` (`created_at`),
  KEY `idx_comments_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🔐 Tabella utenti admin
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','editor','author') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'author',
  `status` enum('active','inactive','banned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `login_attempts` int DEFAULT '0',
  `locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_users_username` (`username`),
  UNIQUE KEY `idx_users_email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 📧 Tabella newsletter subscribers
CREATE TABLE `newsletter_subscribers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interests` json DEFAULT NULL,
  `status` enum('active','unsubscribed','bounced','confirmed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `confirmation_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_newsletter_email` (`email`),
  UNIQUE KEY `idx_newsletter_token` (`confirmation_token`),
  KEY `idx_newsletter_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 🎨 Tabella media uploads
CREATE TABLE `media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_filename` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int NOT NULL,
  `width` int DEFAULT NULL,
  `height` int DEFAULT NULL,
  `alt_text` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `article_id` int DEFAULT NULL,
  `upload_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_media_article` (`article_id`),
  KEY `idx_media_type` (`mime_type`),
  KEY `idx_media_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 📊 Tabella analytics
CREATE TABLE `analytics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int DEFAULT NULL,
  `page_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `visitor_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `referer` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_analytics_article` (`article_id`),
  KEY `idx_analytics_date` (`created_at`),
  KEY `idx_analytics_language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- 🌱 INSERIMENTO DATI INIZIALI
-- =============================================

-- Province calabresi
INSERT INTO `provinces` (`id`, `name`, `slug`, `abbreviation`, `latitude`, `longitude`, `featured`) VALUES
(1, 'Catanzaro', 'catanzaro', 'CZ', 38.90597000, 16.59440000, 1),
(2, 'Cosenza', 'cosenza', 'CS', 39.29308000, 16.25609000, 1),
(3, 'Crotone', 'crotone', 'KR', 39.08036000, 17.12538000, 1),
(4, 'Reggio Calabria', 'reggio-calabria', 'RC', 38.11047000, 15.66129000, 1),
(5, 'Vibo Valentia', 'vibo-valentia', 'VV', 38.67624000, 16.10157000, 1);

-- Categorie principali
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `color`, `icon`, `featured`, `sort_order`) VALUES
(1, 'Eventi', 'eventi', 'Tutti gli eventi della Calabria', '#e74c3c', 'calendar', 1, 1),
(2, 'Tradizioni', 'tradizioni', 'Le tradizioni e la cultura calabrese', '#9b59b6', 'heart', 1, 2),
(3, 'Enogastronomia', 'enogastronomia', 'Piatti tipici e prodotti locali', '#27ae60', 'utensils', 1, 3),
(4, 'Turismo', 'turismo', 'Luoghi da visitare in Calabria', '#3498db', 'map-marker', 1, 4),
(5, 'Arte e Cultura', 'arte-e-cultura', 'Musei, monumenti e arte calabrese', '#f39c12', 'palette', 1, 5),
(6, 'Sport', 'sport', 'Eventi sportivi e attività', '#e67e22', 'trophy', 0, 6),
(7, 'Natura', 'natura', 'Parchi e aree naturali', '#2ecc71', 'leaf', 0, 7);

-- Alcune città principali per ogni provincia
INSERT INTO `cities` (`name`, `slug`, `province_id`, `postal_code`, `latitude`, `longitude`, `featured`) VALUES
-- Catanzaro
('Catanzaro', 'catanzaro', 1, '88100', 38.90597000, 16.59440000, 1),
('Lamezia Terme', 'lamezia-terme', 1, '88046', 38.96500000, 16.30917000, 1),
('Soverato', 'soverato', 1, '88068', 38.68815000, 16.55168000, 1),
-- Cosenza
('Cosenza', 'cosenza', 2, '87100', 39.29308000, 16.25609000, 1),
('Rossano', 'rossano', 2, '87067', 39.57678000, 16.63808000, 1),
('Castrovillari', 'castrovillari', 2, '87012', 39.81444000, 16.20000000, 0),
-- Crotone
('Crotone', 'crotone', 3, '88900', 39.08036000, 17.12538000, 1),
('Cirò Marina', 'ciro-marina', 3, '88811', 39.37057000, 17.13017000, 1),
-- Reggio Calabria
('Reggio Calabria', 'reggio-calabria', 4, '89100', 38.11047000, 15.66129000, 1),
('Villa San Giovanni', 'villa-san-giovanni', 4, '89018', 38.21591000, 15.63477000, 0),
('Palmi', 'palmi', 4, '89015', 38.35917000, 15.84806000, 0),
-- Vibo Valentia
('Vibo Valentia', 'vibo-valentia', 5, '89900', 38.67624000, 16.10157000, 1),
('Tropea', 'tropea', 5, '89861', 38.67736000, 15.89682000, 1),
('Pizzo', 'pizzo', 5, '89812', 38.73583000, 16.15750000, 1);

-- Utente admin di default (password: admin123 - CAMBIARE IMMEDIATAMENTE!)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `status`) VALUES
('admin', 'admin@passionecalabria.com', '$2y$12$QjSH496pcT5CEbzjD/vtVeH03tfHKFy36d8J0.L5gLsxG4o3MvJ86', 'admin', 'active');

-- Articoli di esempio
INSERT INTO `articles` (`title`, `slug`, `content`, `excerpt`, `category_id`, `province_id`, `city_id`, `featured`, `status`) VALUES
('La Sagra del Peperoncino di Diamante', 'sagra-peperoncino-diamante', 
'<p>La Sagra del Peperoncino di Diamante è uno degli eventi gastronomici più importanti della Calabria. Ogni settembre, la cittadina costiera si anima con stand, spettacoli e degustazioni.</p><p>Il peperoncino calabrese, detto "diavolicchio", è protagonista assoluto di questa manifestazione che attira migliaia di visitatori da tutta Italia.</p>',
'La famosa sagra che celebra il peperoncino calabrese nella splendida Diamante',
1, 2, 2, 1, 'published'),

('Le Serre Calabresi: un paradiso naturale', 'serre-calabresi-natura',
'<p>Le Serre Calabresi rappresentano uno dei paesaggi più suggestivi della nostra regione. Questi monti, che si estendono tra le province di Catanzaro, Reggio Calabria e Vibo Valentia, offrono panorami mozzafiato.</p><p>La biodiversità di questa zona è straordinaria, con boschi di faggio, abeti e castagni che ospitano una fauna ricchissima.</p>',
'Alla scoperta delle montagne calabresi e della loro incredibile biodiversità',
7, 1, 1, 1, 'published'),

('La \'Nduja di Spilinga: tradizione e sapore', 'nduja-spilinga-tradizione',
'<p>La \'Nduja di Spilinga è uno dei prodotti gastronomici più rappresentativi della Calabria. Questo salume piccante, nato nel piccolo paese di Spilinga, ha conquistato il mondo intero.</p><p>La sua preparazione segue antiche tradizioni tramandate di generazione in generazione, utilizzando carni suine selezionate e il famoso peperoncino calabrese.</p>',
'Storia e tradizione del salume piccante più famoso della Calabria',
3, 5, 13, 1, 'published');

-- =============================================
-- 🔗 VINCOLI E RELAZIONI
-- =============================================

ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_articles_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_articles_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL;

ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

ALTER TABLE `cities`
  ADD CONSTRAINT `fk_cities_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;

ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_parent` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE SET NULL;

ALTER TABLE `analytics`
  ADD CONSTRAINT `fk_analytics_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE SET NULL;

-- =============================================
-- 🎯 INDICI AGGIUNTIVI PER PERFORMANCE
-- =============================================

-- Indici compositi per query frequenti
CREATE INDEX `idx_articles_category_status` ON `articles` (`category_id`, `status`);
CREATE INDEX `idx_articles_province_status` ON `articles` (`province_id`, `status`);
CREATE INDEX `idx_articles_featured_status` ON `articles` (`featured`, `status`);
CREATE INDEX `idx_comments_article_status` ON `comments` (`article_id`, `status`);

-- =============================================
-- 📝 VISTE UTILI
-- =============================================

-- Vista per articoli con informazioni complete
CREATE VIEW `v_articles_full` AS
SELECT 
    a.*,
    c.name as category_name,
    c.slug as category_slug,
    c.color as category_color,
    p.name as province_name,
    p.slug as province_slug,
    ci.name as city_name,
    ci.slug as city_slug,
    (SELECT COUNT(*) FROM comments WHERE article_id = a.id AND status = 'approved') as comments_count,
    (SELECT AVG(rating) FROM comments WHERE article_id = a.id AND status = 'approved' AND rating > 0) as avg_rating
FROM articles a
LEFT JOIN categories c ON a.category_id = c.id
LEFT JOIN provinces p ON a.province_id = p.id
LEFT JOIN cities ci ON a.city_id = ci.id;

-- Vista per statistiche categoria
CREATE VIEW `v_category_stats` AS
SELECT 
    c.id,
    c.name,
    c.slug,
    COUNT(a.id) as articles_count,
    COUNT(CASE WHEN a.featured = 1 THEN 1 END) as featured_count
FROM categories c
LEFT JOIN articles a ON c.id = a.category_id AND a.status = 'published'
GROUP BY c.id, c.name, c.slug;

COMMIT;

-- =============================================
-- 📋 NOTE IMPORTANTI PER IONOS
-- =============================================
/*
1. Modifica il nome del database nella riga 21 con il tuo database Ionos
2. Aggiorna il file includes/config.php con i dati del tuo database:
   - DB_HOST: il tuo host MySQL (es. db1234567.hosting-data.io)
   - DB_NAME: nome del tuo database
   - DB_USER: il tuo username MySQL  
   - DB_PASS: la tua password MySQL

3. IMPORTANTE: Cambia la password dell'utente admin dopo il primo login!
   Username: admin
   Password: admin123

4. Assicurati che il tuo hosting Ionos supporti:
   - MySQL 8.0 o superiore
   - JSON data type
   - Full-text search
   - Foreign key constraints

5. Per le immagini, crea una cartella "uploads" nella root del sito
   e assicurati che abbia i permessi di scrittura (755 o 644)

6. Per le performance, considera di abilitare il caching MySQL
   e di ottimizzare gli indici in base alle query più frequenti

7. Backup periodici: configura backup automatici tramite il pannello Ionos
*/