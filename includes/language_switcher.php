<?php
/**
 * Language Switcher Helper - Path dinamici per cambio lingua
 * Funziona da qualsiasi cartella del progetto
 */

// Rileva la lingua corrente dal percorso
$current_uri = $_SERVER['REQUEST_URI'];
$current_path = dirname($_SERVER['SCRIPT_NAME']);
$current_file = basename($_SERVER['SCRIPT_NAME']);

// Determina la lingua corrente e il path base
$current_lang = 'it'; // default
$base_path = '';

if (preg_match('/\/(it|en|fr|de|es)\//', $current_path, $matches)) {
    $current_lang = $matches[1];
    // Siamo dentro una cartella lingua, il path base è ../
    $base_path = '../';
} else {
    // Siamo nella root, il path base è ./
    $base_path = './';
}

// Array delle lingue supportate
$languages = [
    'it' => ['name' => 'Italiano', 'flag' => '🇮🇹', 'title' => 'Italiano'],
    'en' => ['name' => 'English', 'flag' => '🇺🇸', 'title' => 'English'],
    'fr' => ['name' => 'Français', 'flag' => '🇫🇷', 'title' => 'Français'],
    'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪', 'title' => 'Deutsch'],
    'es' => ['name' => 'Español', 'flag' => '🇪🇸', 'title' => 'Español']
];

/**
 * Genera il path corretto per una lingua specifica
 */
function getLanguageUrl($target_lang, $current_lang, $current_file, $base_path) {
    if ($target_lang === 'it') {
        // Italiano è nella root se non siamo già in IT
        if ($current_lang === 'it') {
            return $current_file; // Stessa pagina
        } else {
            return $base_path . "it/" . $current_file;
        }
    } else {
        // Altre lingue sono in sottocartelle
        if ($current_lang === 'it') {
            return $base_path . $target_lang . "/" . $current_file;
        } else {
            return $base_path . $target_lang . "/" . $current_file;
        }
    }
}

/**
 * Genera HTML per i link del cambio lingua
 */
function renderLanguageSwitcher($current_lang, $current_file, $base_path) {
    global $languages;
    
    $html = '<div class="flex items-center space-x-2">' . "\n";
    $html .= '<span class="text-xs text-blue-200 mr-3">Lingua:</span>' . "\n";
    
    foreach ($languages as $lang_code => $lang_info) {
        $url = getLanguageUrl($lang_code, $current_lang, $current_file, $base_path);
        $is_active = ($lang_code === $current_lang);
        
        $css_class = $is_active 
            ? 'px-2 py-1 bg-white bg-opacity-20 rounded text-sm font-medium hover:bg-opacity-30 transition-colors'
            : 'px-2 py-1 bg-white bg-opacity-10 rounded text-sm hover:bg-opacity-20 transition-colors';
        
        $html .= sprintf(
            '<a href="%s" class="%s" title="%s">%s %s</a>' . "\n",
            htmlspecialchars($url),
            $css_class,
            htmlspecialchars($lang_info['title']),
            $lang_info['flag'],
            $lang_code
        );
    }
    
    $html .= '</div>';
    return $html;
}
?>