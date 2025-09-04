<?php
/**
 * INDEX PRINCIPALE - Passione Calabria
 * Rileva lingua browser e reindirizza alla cartella corretta
 */

// Include il sistema di rilevamento lingua
require_once __DIR__ . '/language_detector.php';

// Se arriviamo qui, significa che non c'è stato redirect
// Mostra una pagina di fallback temporanea
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passione Calabria - Benvenuti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Redirect JavaScript di backup (se PHP non ha funzionato)
        (function() {
            const supportedLanguages = ['it', 'en', 'fr', 'de', 'es'];
            const defaultLanguage = 'it';
            
            function detectBrowserLanguage() {
                const userLang = navigator.language || navigator.languages[0] || defaultLanguage;
                const langCode = userLang.substring(0, 2).toLowerCase();
                return supportedLanguages.includes(langCode) ? langCode : defaultLanguage;
            }
            
            // Aspetta che la pagina carichi per fare redirect
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const detectedLang = detectBrowserLanguage();
                    console.log('🌐 JavaScript fallback: Detected language', detectedLang);
                    window.location.href = '/' + detectedLang + '/';
                }, 100);
            });
        })();
    </script>
</head>
<body class="bg-gradient-to-br from-blue-500 via-teal-500 to-yellow-500 min-h-screen flex items-center justify-center">
    
    <div class="text-center text-white max-w-2xl mx-auto px-4">
        <!-- Logo -->
        <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-8 backdrop-blur-sm">
            <span class="text-3xl font-bold text-white">PC</span>
        </div>
        
        <!-- Title -->
        <h1 class="text-4xl md:text-6xl font-bold mb-4">
            Passione Calabria
        </h1>
        
        <p class="text-xl md:text-2xl mb-8 text-blue-100">
            La tua guida alla Calabria
        </p>
        
        <!-- Loading indicator -->
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-white mb-6"></div>
        
        <p class="text-lg mb-8">
            🌐 Rilevamento lingua in corso...
        </p>
        
        <!-- Manual language selection -->
        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8">
            <h2 class="text-2xl font-bold mb-6">Scegli la tua lingua / Choose your language</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <a href="/it/" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl p-4 transition-all transform hover:scale-105">
                    <div class="text-3xl mb-2">🇮🇹</div>
                    <div class="font-semibold">Italiano</div>
                </a>
                <a href="/en/" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl p-4 transition-all transform hover:scale-105">
                    <div class="text-3xl mb-2">🇺🇸</div>
                    <div class="font-semibold">English</div>
                </a>
                <a href="/fr/" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl p-4 transition-all transform hover:scale-105">
                    <div class="text-3xl mb-2">🇫🇷</div>
                    <div class="font-semibold">Français</div>
                </a>
                <a href="/de/" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl p-4 transition-all transform hover:scale-105">
                    <div class="text-3xl mb-2">🇩🇪</div>
                    <div class="font-semibold">Deutsch</div>
                </a>
                <a href="/es/" class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl p-4 transition-all transform hover:scale-105">
                    <div class="text-3xl mb-2">🇪🇸</div>
                    <div class="font-semibold">Español</div>
                </a>
            </div>
        </div>
        
        <p class="text-sm text-blue-200 mt-6">
            Se non vieni reindirizzato automaticamente, clicca sulla tua lingua preferita
        </p>
    </div>
    
</body>
</html>