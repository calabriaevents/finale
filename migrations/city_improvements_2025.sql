-- Migrazione per Miglioramenti Pagine Città - 2025
-- Aggiunge support per hero images, Google Maps links, foto utenti e commenti per città

-- 1. Aggiungere campi alla tabella cities per immagine hero e link Google Maps
ALTER TABLE cities 
ADD COLUMN hero_image VARCHAR(500) DEFAULT NULL AFTER description,
ADD COLUMN google_maps_link VARCHAR(500) DEFAULT NULL AFTER hero_image;

-- 2. Aggiungere city_id alla tabella user_uploads per foto specifiche delle città
ALTER TABLE user_uploads 
ADD COLUMN city_id INT DEFAULT NULL AFTER article_id,
ADD INDEX idx_city_id (city_id);

-- Aggiungere foreign key constraint per city_id in user_uploads
ALTER TABLE user_uploads 
ADD CONSTRAINT fk_user_uploads_city_id 
FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE;

-- 3. Aggiungere city_id alla tabella comments per commenti specifici delle città
ALTER TABLE comments 
ADD COLUMN city_id INT DEFAULT NULL AFTER article_id,
ADD INDEX idx_comments_city_id (city_id);

-- Aggiungere foreign key constraint per city_id in comments
ALTER TABLE comments 
ADD CONSTRAINT fk_comments_city_id 
FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE;

-- 4. Aggiornare commenti esistenti - rendere article_id nullable per permettere commenti solo città
ALTER TABLE comments 
MODIFY COLUMN article_id INT DEFAULT NULL;

-- 5. Aggiungere alcuni dati di esempio per test (opzionale)
-- UPDATE cities SET hero_image = 'assets/images/cities/default-hero.jpg' WHERE hero_image IS NULL;

-- Nota: Ricorda di eseguire questo SQL tramite phpMyAdmin o cliente MySQL