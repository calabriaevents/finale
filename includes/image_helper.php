<?php
/**
 * Helper function per gestire i path delle immagini
 * Assicura che tutte le immagini puntino sempre a /uploads dal root
 */

if (!function_exists('getImageUrl')) {
    /**
     * Converte un path relativo di immagine in un URL assoluto dal root
     * 
     * @param string $imagePath Path dell'immagine (es: "uploads/articles/image.jpg")
     * @return string URL assoluto (es: "/uploads/articles/image.jpg")
     */
    function getImageUrl($imagePath) {
        if (empty($imagePath)) {
            return '';
        }
        
        // Se il path inizia già con / è già assoluto
        if (strpos($imagePath, '/') === 0) {
            return $imagePath;
        }
        
        // Se il path inizia con "uploads/", aggiungi solo / all'inizio
        if (strpos($imagePath, 'uploads/') === 0) {
            return '/' . $imagePath;
        }
        
        // Se il path non contiene "uploads", assumiamo sia nella cartella uploads
        return '/uploads/' . $imagePath;
    }
}

if (!function_exists('getImagePath')) {
    /**
     * Alias di getImageUrl per compatibilità
     */
    function getImagePath($imagePath) {
        return getImageUrl($imagePath);
    }
}

if (!function_exists('fixImageUrl')) {
    /**
     * Fix immediato per URL immagini esistenti
     */
    function fixImageUrl($imageUrl) {
        return getImageUrl($imageUrl);
    }
}