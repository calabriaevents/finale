/**
 * RILEVATORE LINGUA BROWSER SUPER SEMPLICE
 * Sostituisce unified-translation-system.js (che causava problemi header)
 * 
 * FA SOLO:
 * - Rileva lingua browser
 * - Reindirizza a cartella corretta (/en/, /fr/, ecc.)
 * 
 * NON FA:
 * - Modifiche DOM
 * - history.replaceState 
 * - Traduzioni inline
 * - Manipolazioni header
 */

(function() {
    'use strict';
    
    // Configurazione lingue supportate
    const SUPPORTED_LANGUAGES = ['it', 'en', 'fr', 'de', 'es'];
    const DEFAULT_LANGUAGE = 'it';
    
    /**
     * Rileva lingua dal browser
     */
    function detectBrowserLanguage() {
        // Prendi lingua primaria del browser
        const browserLang = navigator.language || navigator.languages[0] || 'it';
        const langCode = browserLang.substring(0, 2).toLowerCase();
        
        // Ritorna solo se supportata, altrimenti default
        return SUPPORTED_LANGUAGES.includes(langCode) ? langCode : DEFAULT_LANGUAGE;
    }
    
    /**
     * Ottieni lingua dall'URL corrente
     */
    function getCurrentLanguageFromURL() {
        const path = window.location.pathname;
        const matches = path.match(/^\/([a-z]{2})\//);
        
        if (matches && SUPPORTED_LANGUAGES.includes(matches[1])) {
            return matches[1];
        }
        
        // Se siamo nella root o in una sottocartella senza lingua, è italiano
        return 'it';
    }
    
    /**
     * Controlla se siamo nella root del sito (senza lingua specifica)
     */
    function isInRootWithoutLanguage() {
        const path = window.location.pathname;
        
        // Se siamo in una cartella lingua (/en/, /fr/, ecc.) non fare niente
        if (path.match(/^\/([a-z]{2})\//)) {
            return false;
        }
        
        // Se siamo nella root (/) o in una pagina senza prefisso lingua
        return path === '/' || !path.match(/^\/[a-z]{2}\//);
    }
    
    /**
     * Esegui redirect alla lingua corretta
     */
    function redirectToCorrectLanguage() {
        // Solo se siamo nella root senza specificare lingua
        if (!isInRootWithoutLanguage()) {
            return; // Già in una versione linguistica specifica
        }
        
        const detectedLang = detectBrowserLanguage();
        const currentLang = getCurrentLanguageFromURL();
        
        console.log('🌐 Lingua rilevata:', detectedLang);
        console.log('🌐 Lingua corrente:', currentLang);
        
        // Se la lingua rilevata è diversa dall'italiano, redirect
        if (detectedLang !== 'it' && currentLang === 'it') {
            const currentPath = window.location.pathname;
            const newPath = '/' + detectedLang + (currentPath === '/' ? '/' : currentPath);
            
            console.log('🔄 Redirect a:', newPath);
            window.location.href = newPath;
        }
    }
    
    /**
     * Setup selettori lingua (se presenti)
     */
    function setupLanguageSelectors() {
        document.addEventListener('click', function(e) {
            const langLink = e.target.closest('[data-lang]');
            if (!langLink) return;
            
            e.preventDefault();
            const targetLang = langLink.dataset.lang;
            
            if (!SUPPORTED_LANGUAGES.includes(targetLang)) {
                return;
            }
            
            const currentPath = window.location.pathname;
            let newPath;
            
            if (targetLang === 'it') {
                // Rimuovi prefisso lingua per tornare all'italiano
                newPath = currentPath.replace(/^\/[a-z]{2}/, '') || '/';
            } else {
                // Aggiungi o sostituisci prefisso lingua
                if (currentPath.match(/^\/[a-z]{2}\//)) {
                    newPath = currentPath.replace(/^\/[a-z]{2}/, '/' + targetLang);
                } else {
                    newPath = '/' + targetLang + (currentPath === '/' ? '/' : currentPath);
                }
            }
            
            console.log('🔄 Cambio lingua manuale a:', newPath);
            window.location.href = newPath;
        });
    }
    
    /**
     * Inizializzazione quando DOM è pronto
     */
    function init() {
        console.log('🌐 SimpleLanguageDetector: Inizializzato');
        
        // Setup dei selettori lingua
        setupLanguageSelectors();
        
        // Esegui redirect solo una volta, quando la pagina carica
        redirectToCorrectLanguage();
    }
    
    // Avvia quando DOM è pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Esponi funzioni globali per compatibilità
    window.SimpleLanguageDetector = {
        detectBrowserLanguage,
        getCurrentLanguageFromURL,
        supportedLanguages: SUPPORTED_LANGUAGES
    };
    
})();