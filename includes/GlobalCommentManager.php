<?php
/**
 * GlobalCommentManager - Sistema di commenti globale
 * 
 * I commenti sono condivisi tra tutte le lingue per lo stesso articolo (stesso slug)
 * Un utente può vedere i commenti in tutte le lingue per un determinato luogo/articolo
 */

class GlobalCommentManager {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Ottiene tutti i commenti per un determinato slug (tutte le lingue)
     */
    public function getCommentsBySlug($slug) {
        try {
            // Prendi tutti gli article_id che hanno questo slug
            $stmt = $this->db->prepare("
                SELECT DISTINCT id FROM articles WHERE slug = ? AND status = 'published'
            ");
            $stmt->execute([$slug]);
            $articleIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($articleIds)) {
                return [];
            }
            
            // Crea placeholder per IN clause
            $placeholders = str_repeat('?,', count($articleIds) - 1) . '?';
            
            // Ottieni tutti i commenti per questi articoli
            $stmt = $this->db->prepare("
                SELECT c.*, a.title as article_title, a.slug as article_slug
                FROM comments c
                JOIN articles a ON c.article_id = a.id
                WHERE c.article_id IN ($placeholders) 
                AND c.status = 'approved'
                ORDER BY c.created_at DESC
            ");
            $stmt->execute($articleIds);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log('Error getting global comments: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Aggiunge un commento usando lo slug invece dell'ID specifico
     */
    public function addCommentBySlug($slug, $authorName, $authorEmail, $content, $rating, $language = 'it') {
        try {
            // Trova l'article_id per la lingua specificata, se non esiste usa il primo disponibile
            $stmt = $this->db->prepare("
                SELECT id FROM articles 
                WHERE slug = ? AND status = 'published'
                ORDER BY CASE 
                    WHEN title LIKE '%$language%' THEN 1
                    ELSE 2
                END
                LIMIT 1
            ");
            $stmt->execute([$slug]);
            $articleId = $stmt->fetchColumn();
            
            if (!$articleId) {
                throw new Exception("Articolo non trovato per slug: $slug");
            }
            
            // Inserisci il commento
            $stmt = $this->db->prepare("
                INSERT INTO comments (article_id, author_name, author_email, content, rating, status, created_at)
                VALUES (?, ?, ?, ?, ?, 'approved', datetime('now'))
            ");
            
            return $stmt->execute([$articleId, $authorName, $authorEmail, $content, $rating]);
            
        } catch (Exception $e) {
            error_log('Error adding global comment: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Conta i commenti globali per slug
     */
    public function getCommentsCountBySlug($slug) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(c.id) as count
                FROM comments c
                JOIN articles a ON c.article_id = a.id
                WHERE a.slug = ? AND c.status = 'approved'
            ");
            $stmt->execute([$slug]);
            return (int)$stmt->fetchColumn();
            
        } catch (Exception $e) {
            error_log('Error counting global comments: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Ottiene la valutazione media globale per slug
     */
    public function getAverageRatingBySlug($slug) {
        try {
            $stmt = $this->db->prepare("
                SELECT AVG(c.rating) as avg_rating, COUNT(c.id) as total_ratings
                FROM comments c
                JOIN articles a ON c.article_id = a.id
                WHERE a.slug = ? AND c.status = 'approved' AND c.rating > 0
            ");
            $stmt->execute([$slug]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'average' => $result['avg_rating'] ? round($result['avg_rating'], 1) : 0,
                'total' => (int)$result['total_ratings']
            ];
            
        } catch (Exception $e) {
            error_log('Error getting global rating: ' . $e->getMessage());
            return ['average' => 0, 'total' => 0];
        }
    }
    
    /**
     * Verifica se uno slug esiste nel sistema
     */
    public function slugExists($slug) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM articles WHERE slug = ? AND status = 'published'
            ");
            $stmt->execute([$slug]);
            return $stmt->fetchColumn() > 0;
            
        } catch (Exception $e) {
            error_log('Error checking slug existence: ' . $e->getMessage());
            return false;
        }
    }
}
?>