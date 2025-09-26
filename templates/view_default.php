<main class="container mx-auto px-4 py-8">
    <article class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header con pulsante città -->
        <div class="relative">
            <?php if ($article['featured_image']): ?>
            <img src="/<?php echo htmlspecialchars($article['featured_image']); ?>" 
                 alt="<?php echo htmlspecialchars($article['title']); ?>" 
                 class="w-full h-96 object-cover">
            
            <!-- Overlay con pulsante città se presente -->
            <?php if (!empty($article['city_id']) && !empty($article['city_name'])): ?>
            <div class="absolute top-4 right-4">
                <a href="citta-dettaglio.php?id=<?php echo $article['city_id']; ?>" 
                   class="inline-flex items-center px-4 py-2 bg-white/90 hover:bg-white backdrop-blur-sm text-gray-900 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 font-medium">
                    <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-blue-600"></i>
                    <?php echo htmlspecialchars($article['city_name']); ?>
                </a>
            </div>
            <?php endif; ?>
            
            <!-- Upload Experience Button Overlay -->
            <div class="absolute bottom-4 left-4">
                <button onclick="openUploadModal(<?php echo $article['id']; ?>)" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600/90 hover:bg-blue-700 backdrop-blur-sm text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 font-medium">
                    <i data-lucide="camera" class="w-4 h-4 mr-2"></i>
                    Condividi la tua foto
                </button>
            </div>
            <?php endif; ?>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Title and Meta -->
            <div class="mb-6">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    <?php echo htmlspecialchars($article['title']); ?>
                </h1>
                
                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-4">
                    <div class="flex items-center">
                        <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                        <?php echo htmlspecialchars($article['author']); ?>
                    </div>
                    <div class="flex items-center">
                        <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                        <?php echo date('d M Y', strtotime($article['created_at'])); ?>
                    </div>
                    <div class="flex items-center">
                        <i data-lucide="tag" class="w-4 h-4 mr-1"></i>
                        <a href="categoria.php?id=<?php echo $article['category_id']; ?>" 
                           class="text-blue-600 hover:text-blue-800 hover:underline">
                            <?php echo htmlspecialchars($article['category_name']); ?>
                        </a>
                    </div>
                    <?php if (!empty($article['province_name'])): ?>
                    <div class="flex items-center">
                        <i data-lucide="map" class="w-4 h-4 mr-1"></i>
                        <?php echo htmlspecialchars($article['province_name']); ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($article['views'] > 0): ?>
                    <div class="flex items-center">
                        <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                        <?php echo number_format($article['views']); ?> visualizzazioni
                    </div>
                    <?php endif; ?>
                </div>

                <!-- City Button (if no featured image) -->
                <?php if (!$article['featured_image'] && !empty($article['city_id']) && !empty($article['city_name'])): ?>
                <div class="mb-4">
                    <a href="citta-dettaglio.php?id=<?php echo $article['city_id']; ?>" 
                       class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-900 rounded-lg transition-colors duration-200 font-medium">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>
                        📍 <?php echo htmlspecialchars($article['city_name']); ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Excerpt if available -->
            <?php if (!empty($article['excerpt'])): ?>
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                <p class="text-lg text-gray-700 leading-relaxed italic">
                    <?php echo htmlspecialchars($article['excerpt']); ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="prose prose-lg max-w-none">
                <?php
                // Convert content to properly formatted paragraphs
                $content = htmlspecialchars($article['content']);
                $paragraphs = explode("\n", $content);
                foreach ($paragraphs as $p) {
                    $trimmed = trim($p);
                    if ($trimmed) {
                        echo "<p class='mb-4 leading-relaxed'>" . nl2br($trimmed) . "</p>";
                    }
                }
                ?>
            </div>

            <!-- Upload Experience Button (if no featured image) -->
            <?php if (!$article['featured_image']): ?>
            <div class="mt-8 text-center">
                <button onclick="openUploadModal(<?php echo $article['id']; ?>)" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                    <i data-lucide="camera" class="w-5 h-5 mr-2"></i>
                    Condividi la Tua Esperienza
                </button>
                <p class="mt-2 text-sm text-gray-500">
                    Hai visitato questo posto? Carica la tua foto e racconta la tua storia!
                </p>
            </div>
            <?php endif; ?>
        </div>
    </article>
</main>

<!-- Include User Experiences Section -->
<?php
$article_id = $article['id'];
$province_id = $article['province_id'] ?? null;
include __DIR__ . '/../partials/user-experiences.php';
?>

<!-- Include Reviews Section -->
<?php include __DIR__ . '/../partials/reviews.php'; ?>

<!-- Include User Upload Modal -->
<?php include __DIR__ . '/../partials/user-upload-modal.php'; ?>

<!-- Initialize UserUploadModal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize upload modal
    if (typeof UserUploadModal !== 'undefined') {
        UserUploadModal.init();
    }
    
    // Create Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>