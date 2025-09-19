-- Aggiunge campo galleria immagini alla tabella cities
-- Eseguire dopo city_improvements_2025.sql

-- Aggiungere campo gallery_images JSON alla tabella cities
ALTER TABLE cities 
ADD COLUMN gallery_images JSON DEFAULT NULL AFTER google_maps_link;

-- Nota: Eseguire tramite phpMyAdmin