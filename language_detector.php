<?php
/**
 * SISTEMA RILEVAMENTO LINGUA PRINCIPALE - Passione Calabria
 * Rileva la lingua del browser e reindirizza alla cartella corretta
 */

// Lingue supportate
$supportedLanguages = ['it', 'en', 'fr', 'de', 'es'];
$defaultLanguage = 'it';

/**
 * Rileva la lingua preferita del browser
 */
function detectBrowserLanguage($supportedLanguages, $defaultLanguage) {
    // Se c'è già una preferenza nella sessione/cookie, usala
    if (isset($_COOKIE['preferred_language']) && in_array($_COOKIE['preferred_language'], $supportedLanguages)) {
        return $_COOKIE['preferred_language'];
    }
    
    // Analizza Accept-Language header
    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return $defaultLanguage;
    }
    
    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $languages = [];
    
    // Parse Accept-Language header
    $langEntries = explode(',', $acceptLanguage);
    foreach ($langEntries as $entry) {
        $entry = trim($entry);
        if (empty($entry)) continue;
        
        if (strpos($entry, ';q=') !== false) {
            list($lang, $quality) = explode(';q=', $entry, 2);
            $quality = (float) $quality;
        } else {
            $lang = $entry;
            $quality = 1.0;
        }
        
        $langCode = strtolower(substr(trim($lang), 0, 2));
        
        if (ctype_alpha($langCode) && strlen($langCode) === 2) {
            $languages[] = [
                'code' => $langCode,
                'quality' => $quality
            ];
        }
    }
    
    // Ordina per qualità
    usort($languages, function($a, $b) {
        return $b['quality'] <=> $a['quality'];
    });
    
    // Trova prima lingua supportata
    foreach ($languages as $lang) {
        if (in_array($lang['code'], $supportedLanguages)) {
            return $lang['code'];
        }
    }
    
    return $defaultLanguage;
}

/**
 * Imposta preferenza lingua
 */
function setLanguagePreference($language) {
    // Cookie per 30 giorni
    setcookie('preferred_language', $language, time() + (30 * 24 * 60 * 60), '/');
}

/**
 * Reindirizza alla cartella lingua appropriata
 */
function redirectToLanguageFolder($language, $path = '') {
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
    $currentDir = rtrim(dirname($_SERVER['REQUEST_URI']), '/');
    
    // Costruisci URL completo
    $redirectUrl = $baseUrl . $currentDir . '/' . $language . '/' . ltrim($path, '/');
    
    // Log per debug
    error_log("Language Detection: Redirecting to $redirectUrl (detected: $language)");
    
    // Imposta cookie preferenza
    setLanguagePreference($language);
    
    // Redirect con header appropriati
    header("Location: $redirectUrl", true, 302);
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    exit;
}

/**
 * Controlla se la richiesta è per una risorsa statica
 */
function isStaticResource($uri) {
    $staticExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'pdf', 'zip'];
    $extension = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    return in_array($extension, $staticExtensions);
}

// Ottieni URI richiesto
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedUri = parse_url($requestUri);
$path = $parsedUri['path'] ?? '/';

// Se è una risorsa statica, non fare nulla
if (isStaticResource($path)) {
    return;
}

// Se siamo già in una cartella lingua, non fare redirect
if (preg_match('/^\\/(' . implode('|', $supportedLanguages) . ')\\//i', $path)) {
    return;
}

// Se è una richiesta per API o admin dalla root, lasciar passare
if (preg_match('/^\\/(api|admin|assets|uploads)\\//i', $path)) {
    return;
}

// Rileva lingua e reindirizza
$detectedLanguage = detectBrowserLanguage($supportedLanguages, $defaultLanguage);

// Se richiesta è per la root, reindirizza alla homepage della lingua
if ($path === '/' || $path === '/index.php') {
    redirectToLanguageFolder($detectedLanguage, 'index.php');
}

// Se richiesta è per una pagina specifica, prova a reindirizzare
$requestedPage = ltrim($path, '/');
if (!empty($requestedPage)) {
    redirectToLanguageFolder($detectedLanguage, $requestedPage);
}

// Default: vai alla homepage della lingua rilevata
redirectToLanguageFolder($detectedLanguage);
?>