<?php
require_once 'includes/config.php';
require_once 'includes/database_mysql.php';

$db = new Database();

// Verifica se l'ID città è fornito
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: citta.php");
    exit;
}

$cityId = (int)$_GET['id'];

// Carica dati città estesi
$city = $db->getCityExtendedById($cityId);
if (!$city) {
    header("Location: citta.php");
    exit;
}

// Carica articoli della città raggruppati per categoria
$allArticles = $db->getArticlesByCity($cityId);
$articlesByCategory = [];
$totalArticles = count($allArticles);

foreach ($allArticles as $article) {
    $categoryName = $article['category_name'] ?? 'Senza Categoria';
    if (!isset($articlesByCategory[$categoryName])) {
        $articlesByCategory[$categoryName] = [];
    }
    $articlesByCategory[$categoryName][] = $article;
}

// Carica tutte le categorie per mostrare anche quelle senza articoli
$allCategories = $db->getCategories();

// Carica altre città della stessa provincia
$relatedCities = array_filter($db->getCitiesByProvince($city['province_id']), function($c) use ($cityId) {
    return $c['id'] != $cityId;
});

// Parse gallery images
$galleryImages = [];
if (!empty($city['gallery_images'])) {
    $galleryImages = json_decode($city['gallery_images'], true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($city['name']); ?> - Scopri la Calabria</title>
    <meta name="description" content="<?php echo htmlspecialchars($city['description'] ?: 'Scopri ' . $city['name'] . ', città della provincia di ' . $city['province_name'] . ' in Calabria.'); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'calabria-blue': {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8'
                        },
                        'calabria-gold': {
                            50: '#fffbeb',
                            500: '#f59e0b',
                            600: '#d97706'
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.7s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideInRight: {
                            '0%': { opacity: '0', transform: 'translateX(30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50">
    <?php include 'includes/header.php'; ?>

    <!-- Breadcrumb -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="index.php" class="text-slate-600 hover:text-blue-600 transition-colors">Home</a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                <a href="citta.php" class="text-slate-600 hover:text-blue-600 transition-colors">Città</a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                <a href="provincia.php?id=<?php echo $city['province_id']; ?>" class="text-slate-600 hover:text-blue-600 transition-colors"><?php echo htmlspecialchars($city['province_name']); ?></a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                <span class="text-slate-900 font-medium"><?php echo htmlspecialchars($city['name']); ?></span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
        <?php if ($city['hero_image']): ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo htmlspecialchars($city['hero_image']); ?>" 
                 alt="<?php echo htmlspecialchars($city['name']); ?>" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/70 via-slate-900/50 to-slate-900/30"></div>
        </div>
        <?php else: ?>
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-blue-600 via-purple-600 to-teal-500"></div>
        <?php endif; ?>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <div class="animate-fade-in-up">
                <div class="text-6xl mb-6">🏛️</div>
                <h1 class="text-5xl md:text-7xl font-black mb-4 tracking-tight">
                    <?php echo htmlspecialchars($city['name']); ?>
                </h1>
                <?php if ($city['hero_subtitle']): ?>
                <p class="text-xl md:text-2xl font-medium mb-6 text-blue-100">
                    <?php echo htmlspecialchars($city['hero_subtitle']); ?>
                </p>
                <?php endif; ?>
                <p class="text-lg md:text-xl max-w-4xl mx-auto mb-8 text-slate-200 leading-relaxed">
                    <?php echo htmlspecialchars($city['description'] ?: 'Città della provincia di ' . $city['province_name']); ?>
                </p>
                
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <div class="bg-white/20 backdrop-blur-sm border border-white/30 px-6 py-3 rounded-full">
                        <div class="flex items-center">
                            <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                            <span class="font-medium"><?php echo htmlspecialchars($city['province_name']); ?></span>
                        </div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm border border-white/30 px-6 py-3 rounded-full">
                        <div class="flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                            <span class="font-medium"><?php echo $totalArticles; ?> <?php echo $totalArticles == 1 ? 'articolo' : 'articoli'; ?></span>
                        </div>
                    </div>
                    <?php if ($city['latitude'] && $city['longitude']): ?>
                    <div class="bg-white/20 backdrop-blur-sm border border-white/30 px-6 py-3 rounded-full">
                        <div class="flex items-center">
                            <i data-lucide="navigation" class="w-5 h-5 mr-2"></i>
                            <span class="font-medium"><?php echo number_format($city['latitude'], 2); ?>, <?php echo number_format($city['longitude'], 2); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <?php if ($city['events_app_link']): ?>
                    <a href="<?php echo htmlspecialchars($city['events_app_link']); ?>" target="_blank"
                       class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-8 py-4 rounded-full font-semibold transition-all transform hover:scale-105 shadow-lg">
                        <i data-lucide="calendar-days" class="w-5 h-5 inline mr-2"></i>
                        Scopri gli Eventi
                    </a>
                    <?php endif; ?>
                    <?php if ($city['google_maps_link']): ?>
                    <a href="<?php echo htmlspecialchars($city['google_maps_link']); ?>" target="_blank"
                       class="bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 text-white px-8 py-4 rounded-full font-semibold transition-all">
                        <i data-lucide="map" class="w-5 h-5 inline mr-2"></i>
                        Come Arrivare
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <i data-lucide="chevron-down" class="w-8 h-8"></i>
        </div>
    </section>

    <!-- Main Content -->
    <main class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Gallery Section -->
            <?php if (!empty($galleryImages)): ?>
            <section class="mb-20">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-slate-900 mb-4">Galleria di <?php echo htmlspecialchars($city['name']); ?></h2>
                    <p class="text-xl text-slate-600 max-w-2xl mx-auto">Scopri la bellezza di questa città attraverso le nostre immagini selezionate</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($galleryImages as $index => $image): ?>
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 <?php echo $index === 0 ? 'md:col-span-2 md:row-span-2' : ''; ?>">
                        <img src="<?php echo htmlspecialchars($image['path']); ?>" 
                             alt="<?php echo htmlspecialchars($image['title'] ?? 'Galleria'); ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-white font-semibold text-lg"><?php echo htmlspecialchars($image['title'] ?? ''); ?></h3>
                                <?php if (!empty($image['description'])): ?>
                                <p class="text-slate-200 text-sm mt-1"><?php echo htmlspecialchars($image['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Events Section -->
            <?php if ($city['events_app_link']): ?>
            <section class="mb-20">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl p-8 md:p-12 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
                    <div class="relative z-10 max-w-4xl">
                        <div class="flex flex-col lg:flex-row items-center">
                            <div class="lg:w-2/3 mb-8 lg:mb-0">
                                <div class="text-5xl mb-4">📅</div>
                                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                                    Eventi di <?php echo htmlspecialchars($city['name']); ?>
                                </h2>
                                <p class="text-xl text-blue-100 mb-6 leading-relaxed">
                                    Scopri tutti gli eventi, le manifestazioni e le attività in programma nella città. 
                                    Non perdere le occasioni per vivere <?php echo htmlspecialchars($city['name']); ?> al meglio!
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="<?php echo htmlspecialchars($city['events_app_link']); ?>" target="_blank"
                                       class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-4 rounded-2xl font-bold transition-all transform hover:scale-105 shadow-lg">
                                        <i data-lucide="calendar-days" class="w-5 h-5 inline mr-2"></i>
                                        Vai agli Eventi
                                    </a>
                                    <a href="#articoli" 
                                       class="border-2 border-white/30 hover:border-white/50 hover:bg-white/10 px-8 py-4 rounded-2xl font-semibold transition-all">
                                        <i data-lucide="scroll-text" class="w-5 h-5 inline mr-2"></i>
                                        Leggi gli Articoli
                                    </a>
                                </div>
                            </div>
                            <div class="lg:w-1/3 lg:pl-12">
                                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6">
                                    <div class="text-center">
                                        <div class="text-4xl mb-3">🎉</div>
                                        <h3 class="text-xl font-bold mb-2">App Eventi</h3>
                                        <p class="text-blue-100 text-sm">
                                            La tua guida completa agli eventi locali
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Map Section -->
            <?php if ($city['google_maps_link']): ?>
            <section class="mb-20">
                <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-slate-200">
                    <div class="text-center mb-12">
                        <div class="text-5xl mb-4">🗺️</div>
                        <h2 class="text-4xl font-bold text-slate-900 mb-4">
                            Come Raggiungere <?php echo htmlspecialchars($city['name']); ?>
                        </h2>
                        <p class="text-xl text-slate-600 max-w-2xl mx-auto">
                            Trova la strada migliore per arrivare a destinazione con Google Maps
                        </p>
                    </div>
                    
                    <div class="flex flex-col lg:flex-row items-center gap-12">
                        <div class="lg:w-1/2">
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="car" class="w-6 h-6 text-green-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900">In Auto</h3>
                                        <p class="text-slate-600">Navigazione GPS precisa fino a destinazione</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="train" class="w-6 h-6 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900">Trasporti Pubblici</h3>
                                        <p class="text-slate-600">Treni, autobus e collegamenti locali</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="plane" class="w-6 h-6 text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900">Aeroporti Vicini</h3>
                                        <p class="text-slate-600">Connessioni aeree per raggiungere la Calabria</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8">
                                <a href="<?php echo htmlspecialchars($city['google_maps_link']); ?>" target="_blank"
                                   class="inline-flex items-center bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 text-white px-8 py-4 rounded-2xl font-bold transition-all transform hover:scale-105 shadow-lg">
                                    <i data-lucide="navigation" class="w-5 h-5 mr-2"></i>
                                    Apri in Google Maps
                                </a>
                            </div>
                        </div>
                        
                        <div class="lg:w-1/2">
                            <div class="bg-slate-100 rounded-2xl p-8 h-80 flex items-center justify-center">
                                <div class="text-center text-slate-500">
                                    <i data-lucide="map-pin" class="w-16 h-16 mx-auto mb-4"></i>
                                    <p class="text-lg font-medium">Mappa Interattiva</p>
                                    <p class="text-sm">Clicca "Apri in Google Maps" per la navigazione completa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Articles by Category Section -->
            <section id="articoli" class="mb-20">
                <div class="text-center mb-16">
                    <div class="text-5xl mb-4">📚</div>
                    <h2 class="text-4xl font-bold text-slate-900 mb-4">
                        Esplora <?php echo htmlspecialchars($city['name']); ?>
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                        Scopri tutto quello che c'è da sapere su questa meravigliosa città attraverso i nostri articoli organizzati per categoria
                    </p>
                </div>

                <?php if (empty($articlesByCategory)): ?>
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="text-6xl mb-6">📝</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">
                        Contenuti in Arrivo
                    </h3>
                    <p class="text-slate-600 mb-8 max-w-md mx-auto">
                        Non ci sono ancora articoli per <?php echo htmlspecialchars($city['name']); ?>, ma stiamo preparando contenuti fantastici!
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="citta.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition-colors">
                            Esplora Altre Città
                        </a>
                        <a href="suggerisci.php" class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-6 py-3 rounded-full font-semibold transition-colors">
                            Suggerisci Contenuti
                        </a>
                    </div>
                </div>
                <?php else: ?>
                
                <!-- Categories with Articles -->
                <div class="space-y-16">
                    <?php foreach ($articlesByCategory as $categoryName => $articles): ?>
                    <div class="category-section">
                        <div class="flex items-center mb-8">
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($categoryName); ?></h3>
                                <p class="text-slate-600"><?php echo count($articles); ?> <?php echo count($articles) == 1 ? 'articolo' : 'articoli'; ?> in questa categoria</p>
                            </div>
                            <div class="text-3xl">
                                <?php
                                // Get category icon
                                foreach ($allCategories as $cat) {
                                    if ($cat['name'] === $categoryName) {
                                        echo $cat['icon'] ?: '📄';
                                        break;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <?php foreach ($articles as $article): ?>
                            <article class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-200">
                                <!-- Article Image -->
                                <div class="aspect-[4/3] relative overflow-hidden">
                                    <?php if ($article['featured_image']): ?>
                                    <img src="<?php echo htmlspecialchars($article['featured_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($article['title']); ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                        <i data-lucide="image" class="w-12 h-12 text-slate-400"></i>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="absolute top-4 right-4">
                                        <div class="bg-white/90 backdrop-blur-sm text-slate-700 px-3 py-1 rounded-full text-sm font-medium">
                                            <?php echo $article['views']; ?> views
                                        </div>
                                    </div>
                                </div>

                                <!-- Article Content -->
                                <div class="p-6">
                                    <h4 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </h4>
                                    
                                    <p class="text-slate-600 mb-4 line-clamp-3">
                                        <?php echo htmlspecialchars($article['excerpt'] ?: substr(strip_tags($article['content']), 0, 150) . '...'); ?>
                                    </p>
                                    
                                    <div class="flex items-center justify-between text-sm text-slate-500 mb-4">
                                        <div class="flex items-center">
                                            <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                            <?php echo date('d M Y', strtotime($article['created_at'])); ?>
                                        </div>
                                        <div class="flex items-center">
                                            <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                            <?php echo htmlspecialchars($article['author'] ?? 'Redazione'); ?>
                                        </div>
                                    </div>
                                    
                                    <a href="articolo.php?slug=<?php echo $article['slug']; ?>" 
                                       class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                                        Leggi tutto
                                        <i data-lucide="arrow-right" class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <!-- Related Cities Section -->
            <?php if (!empty($relatedCities)): ?>
            <section class="mb-20">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-slate-900 mb-4">
                        Altre Città di <?php echo htmlspecialchars($city['province_name']); ?>
                    </h3>
                    <p class="text-xl text-slate-600">Esplora altre destinazioni nella stessa provincia</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach (array_slice($relatedCities, 0, 4) as $relatedCity): 
                        $relatedArticleCount = $db->getArticleCountByCity($relatedCity['id']);
                    ?>
                    <a href="citta-dettaglio.php?id=<?php echo $relatedCity['id']; ?>" 
                       class="group bg-white rounded-2xl shadow-sm hover:shadow-lg p-6 transition-all duration-300 border border-slate-200 hover:border-blue-200">
                        <div class="text-4xl mb-4">🏛️</div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                            <?php echo htmlspecialchars($relatedCity['name']); ?>
                        </h4>
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2">
                            <?php echo htmlspecialchars(substr($relatedCity['description'] ?: 'Città di ' . $city['province_name'], 0, 80) . '...'); ?>
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">
                                <?php echo $relatedArticleCount; ?> articoli
                            </span>
                            <div class="flex items-center text-blue-600 font-semibold text-sm group-hover:translate-x-1 transition-transform">
                                <span>Esplora</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
    <script>
        // Inizializza Lucide icons
        lucide.createIcons();

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Observe sections for animations
        document.querySelectorAll('section, .category-section').forEach(section => {
            observer.observe(section);
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroSection = document.querySelector('section');
            if (heroSection) {
                const rate = scrolled * -0.5;
                heroSection.style.transform = `translateY(${rate}px)`;
            }
        });

        // Gallery modal (if needed in future)
        const galleryImages = document.querySelectorAll('.gallery-image');
        galleryImages.forEach(image => {
            image.addEventListener('click', function() {
                // Future: implement lightbox functionality
            });
        });

        // Auto-hide success messages
        const successMessage = document.querySelector('.bg-green-50');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s';
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.remove();
                }, 500);
            }, 5000);
        }
    </script>
</body>
</html>