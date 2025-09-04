<?php
/**
 * API Globale per Commenti - Sistema Multilingue
 * 
 * Questo file gestisce i commenti globalmente, indipendentemente dalla lingua
 * URL: /global_comments_api.php?action=get&slug=nome-articolo
 * URL: /global_comments_api.php (POST per aggiungere commento)
 */

// Previeni accesso diretto senza parametri
if (empty($_GET) && empty($_POST)) {
    header('HTTP/1.1 404 Not Found');
    exit;
}

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php'; 
require_once __DIR__ . '/includes/GlobalCommentManager.php';

header('Content-Type: application/json; charset=utf-8');

// Inizializza database e gestore commenti globale
try {
    $db = new Database();
    $globalComments = new GlobalCommentManager($db);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    
    case 'get':
        // Ottieni commenti per slug
        $slug = $_GET['slug'] ?? '';
        
        if (empty($slug)) {
            http_response_code(400);
            echo json_encode(['error' => 'Slug richiesto']);
            exit;
        }
        
        if (!$globalComments->slugExists($slug)) {
            http_response_code(404);
            echo json_encode(['error' => 'Articolo non trovato']);
            exit;
        }
        
        $comments = $globalComments->getCommentsBySlug($slug);
        $count = $globalComments->getCommentsCountBySlug($slug);
        $rating = $globalComments->getAverageRatingBySlug($slug);
        
        echo json_encode([
            'success' => true,
            'comments' => $comments,
            'total_comments' => $count,
            'average_rating' => $rating['average'],
            'total_ratings' => $rating['total']
        ]);
        break;
        
    case 'add':
        // Aggiungi nuovo commento
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Metodo non consentito']);
            exit;
        }
        
        $slug = $_POST['slug'] ?? '';
        $authorName = trim($_POST['author_name'] ?? '');
        $authorEmail = trim($_POST['author_email'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $rating = (int)($_POST['rating'] ?? 0);
        $language = $_POST['language'] ?? 'it';
        
        // Validazione
        $errors = [];
        
        if (empty($slug)) {
            $errors[] = 'Slug articolo richiesto';
        } elseif (!$globalComments->slugExists($slug)) {
            $errors[] = 'Articolo non trovato';
        }
        
        if (empty($authorName)) {
            $errors[] = 'Nome richiesto';
        } elseif (strlen($authorName) > 100) {
            $errors[] = 'Nome troppo lungo';
        }
        
        if (empty($authorEmail)) {
            $errors[] = 'Email richiesta';
        } elseif (!filter_var($authorEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email non valida';
        }
        
        if (empty($content)) {
            $errors[] = 'Commento richiesto';
        } elseif (strlen($content) < 10) {
            $errors[] = 'Commento troppo breve (minimo 10 caratteri)';
        } elseif (strlen($content) > 2000) {
            $errors[] = 'Commento troppo lungo';
        }
        
        if ($rating < 1 || $rating > 5) {
            $errors[] = 'Valutazione deve essere tra 1 e 5 stelle';
        }
        
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['error' => implode(', ', $errors)]);
            exit;
        }
        
        // Sanitizza input
        $authorName = htmlspecialchars($authorName, ENT_QUOTES, 'UTF-8');
        $authorEmail = filter_var($authorEmail, FILTER_SANITIZE_EMAIL);
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        
        // Aggiungi commento
        $success = $globalComments->addCommentBySlug($slug, $authorName, $authorEmail, $content, $rating, $language);
        
        if ($success) {
            echo json_encode([
                'success' => true,
                'message' => 'Commento aggiunto con successo!'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Errore durante l\'aggiunta del commento']);
        }
        break;
        
    case 'stats':
        // Statistiche globali per slug
        $slug = $_GET['slug'] ?? '';
        
        if (empty($slug) || !$globalComments->slugExists($slug)) {
            http_response_code(400);
            echo json_encode(['error' => 'Slug non valido']);
            exit;
        }
        
        $count = $globalComments->getCommentsCountBySlug($slug);
        $rating = $globalComments->getAverageRatingBySlug($slug);
        
        echo json_encode([
            'success' => true,
            'total_comments' => $count,
            'average_rating' => $rating['average'],
            'total_ratings' => $rating['total']
        ]);
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Azione non riconosciuta']);
}
?>