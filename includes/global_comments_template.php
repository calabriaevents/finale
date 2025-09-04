<?php
/**
 * Template Globale per Commenti
 * Include questo file in tutti gli articoli per avere commenti condivisi
 */

// Assicurati che $article['slug'] sia disponibile
if (!isset($article) || !isset($article['slug'])) {
    echo '<!-- Errore: slug articolo non disponibile per i commenti globali -->';
    return;
}

$currentSlug = $article['slug'];
$currentLang = $contentManager->getCurrentLanguage() ?? 'it';
?>

<!-- Sistema Globale Commenti - Multilingue -->
<div class="mt-16 border-t border-gray-200 pt-12">
    <h3 class="text-3xl font-bold text-gray-900 mb-8 flex items-center comments-section-title">
        <i data-lucide="message-circle" class="w-8 h-8 mr-3 text-blue-600"></i>
        <?php echo htmlspecialchars($contentManager->getText('global-comments', 'Commenti Globali')); ?> 
        <span class="text-lg text-gray-500 ml-2">(<span id="global-comments-count">0</span>)</span>
    </h3>

    <!-- Info Globale -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i data-lucide="globe" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong><?php echo htmlspecialchars($contentManager->getText('global-comments-info', 'Sistema Commenti Globale:')); ?></strong>
                    <?php echo htmlspecialchars($contentManager->getText('global-comments-description', 'I commenti sono condivisi tra tutte le lingue. Un utente può vedere e aggiungere commenti indipendentemente dalla sua lingua.')); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Rating Globale -->
    <div id="global-rating-info"></div>

    <!-- Form per Nuovo Commento -->
    <div class="bg-gray-50 p-6 rounded-lg mb-8">
        <h4 class="text-xl font-semibold text-gray-900 mb-4">
            <?php echo htmlspecialchars($contentManager->getText('leave-global-comment', 'Lascia un Commento Globale')); ?>
        </h4>
        
        <!-- Message area per feedback -->
        <div id="global-comment-messages"></div>
        
        <form id="global-comments-form" class="space-y-4">
            <!-- Hidden fields -->
            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($currentSlug); ?>">
            <input type="hidden" name="language" value="<?php echo htmlspecialchars($currentLang); ?>">
            <input type="hidden" name="rating" value="" id="rating-input">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="author_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <?php echo htmlspecialchars($contentManager->getText('name-required', 'Nome')); ?> *
                    </label>
                    <input type="text" id="author_name" name="author_name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           maxlength="100">
                </div>
                <div>
                    <label for="author_email" class="block text-sm font-medium text-gray-700 mb-2">
                        <?php echo htmlspecialchars($contentManager->getText('email-required', 'Email')); ?> *
                    </label>
                    <input type="email" id="author_email" name="author_email" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <?php echo htmlspecialchars($contentManager->getText('rating-required', 'Valutazione')); ?> *
                </label>
                <div class="flex items-center space-x-1 mb-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i data-lucide="star" 
                           class="w-6 h-6 text-gray-300 hover:text-yellow-400 transition-colors star-rating cursor-pointer" 
                           data-rating="<?php echo $i; ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="text-sm text-gray-500">
                    <?php echo htmlspecialchars($contentManager->getText('rating-instruction', 'Clicca sulle stelle per dare una valutazione')); ?>
                </p>
            </div>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    <?php echo htmlspecialchars($contentManager->getText('comment-required', 'Commento')); ?> *
                </label>
                <textarea id="content" name="content" rows="4" required
                          placeholder="<?php echo htmlspecialchars($contentManager->getText('global-comment-placeholder', 'Scrivi il tuo commento globale... Sarà visibile a utenti di tutte le lingue!')); ?>"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          minlength="10" maxlength="2000"></textarea>
                <p class="text-sm text-gray-500 mt-1">
                    <?php echo htmlspecialchars($contentManager->getText('comment-length-info', 'Minimo 10 caratteri, massimo 2000')); ?>
                </p>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <i data-lucide="globe" class="w-4 h-4 inline mr-1"></i>
                    <?php echo htmlspecialchars($contentManager->getText('multilingual-comment-note', 'Il tuo commento sarà visibile in tutte le lingue')); ?>
                </div>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center">
                    <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                    <?php echo htmlspecialchars($contentManager->getText('submit-global-comment', 'Invia Commento Globale')); ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Lista Commenti Globali -->
    <div id="global-comments-list" 
         data-slug="<?php echo htmlspecialchars($currentSlug); ?>" 
         data-language="<?php echo htmlspecialchars($currentLang); ?>"
         class="space-y-6">
        
        <!-- Placeholder durante il caricamento -->
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-500">
                <?php echo htmlspecialchars($contentManager->getText('loading-global-comments', 'Caricamento commenti globali...')); ?>
            </p>
        </div>
    </div>
</div>

<!-- Include JavaScript per commenti globali -->
<script src="<?php 
    // Determina il path corretto per JS basato sulla directory corrente
    $current_dir = dirname($_SERVER['SCRIPT_NAME']);
    if (strpos($current_dir, '/it/') !== false || strpos($current_dir, '/en/') !== false || 
        strpos($current_dir, '/fr/') !== false || strpos($current_dir, '/de/') !== false || 
        strpos($current_dir, '/es/') !== false) {
        echo '../assets/js/global-comments.js';
    } else {
        echo 'assets/js/global-comments.js';
    }
?>"></script>

<style>
/* Animazioni per commenti */
.comment-item {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stelle interattive */
.star-rating:hover {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

/* Messaggi di feedback */
.message-fade {
    animation: slideInDown 0.3s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Inizializzazione immediata per questa pagina
document.addEventListener('DOMContentLoaded', function() {
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    // Debug info
    console.log('[Global Comments] Template caricato per slug:', '<?php echo $currentSlug; ?>');
    console.log('[Global Comments] Lingua corrente:', '<?php echo $currentLang; ?>');
});
</script>