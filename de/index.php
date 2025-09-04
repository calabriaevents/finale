<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/ContentManagerSimple.php';

// Initialize database and multilingual content manager
$db = new Database();
$contentManager = new ContentManagerSimple();
$currentLang = 'en'; // Force English language
$langInfo = $contentManager->getCurrentLanguageInfo();

// Load data for homepage
$categories = $db->getCategories();
$provinces = $db->getProvinces();
$featuredArticles = $db->getFeaturedArticles();
$homeSections = $db->getHomeSections();

// Load homepage settings
$settings = $db->getSettings();
$settingsArray = [];
foreach ($settings as $setting) {
    $settingsArray[$setting['key']] = $setting['value'];
}

// Load articles for each category (for sliders)
foreach ($categories as &$category) {
    $category['articles'] = $db->getArticlesByCategory($category['id'], 6); // Max 6 articles per slider
    $category['article_count'] = $db->getArticleCountByCategory($category['id']);
}
unset($category); // Unset reference

// Find hero section
$heroSection = null;
foreach ($homeSections as $section) {
    if ($section['section_name'] === 'hero') {
        $heroSection = $section;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passione Calabria - Your Guide to Calabria</title>
    <meta name="description" content="Discover the beauty of Calabria: crystal clear seas, medieval villages, unique gastronomy and thousand-year traditions.">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Custom Tailwind Configuration -->
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
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-50 font-sans">
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section-new text-white py-24" 
             style="background: linear-gradient(135deg, rgba(30, 58, 138, 0.9), rgba(30, 64, 175, 0.8), rgba(59, 130, 246, 0.7), rgba(245, 158, 11, 0.8)), url('<?php echo $heroSection['image_path'] ?? 'https://images.unsplash.com/photo-1499092346589-b9b6be3e94b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 600px; display: flex; align-items: center;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center w-full">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6">
                <?php echo htmlspecialchars($heroSection['title'] ?? 'Explore Calabria'); ?>
            </h1>
            <p class="text-xl md:text-2xl text-yellow-400 mb-8">
                <?php echo htmlspecialchars($heroSection['subtitle'] ?? 'Crystal clear sea and thousand-year history'); ?>
            </p>
            <p class="text-lg md:text-xl text-gray-200 mb-12 max-w-3xl mx-auto">
                <?php echo htmlspecialchars($heroSection['description'] ?? 'Immerse yourself in the beauty of Calabria, with its dream beaches, fascinating historic center and breathtaking views from the cliff.'); ?>
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                <a href="categories.php" class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold transition-colors">
                    <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                    <span>Discover Calabria</span>
                </a>
                <a href="map.php" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white hover:bg-white hover:text-gray-800 text-white rounded-full font-semibold transition-colors">
                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                    <span>View Map</span>
                </a>
            </div>

            <!-- Search Widget -->
            <div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 shadow-2xl">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">What are you looking for?</h2>
                <form action="search.php" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Places, events, traditions...</label>
                        <input
                            type="text"
                            name="q"
                            placeholder="Enter what you want to explore"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                        <select name="provincia" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
                            <option value="">All provinces</option>
                            <?php foreach ($provinces as $province): ?>
                            <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center">
                            <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    <?php echo htmlspecialchars($settingsArray['events_title'] ?? 'Events and App'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Download our app to stay always updated on events in Calabria.
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <!-- App Store Badges -->
                <div class="flex flex-col sm:flex-row justify-center items-center gap-8 mb-12">
                    <?php 
                    $eventSettings = $db->getSettings();
                    $eventData = [];
                    foreach ($eventSettings as $setting) {
                        $eventData[$setting['key']] = $setting['value'];
                    }
                    ?>
                    
                    <?php if (!empty($eventData['app_store_link']) && !empty($eventData['app_store_image'])): ?>
                    <a href="<?php echo htmlspecialchars($eventData['app_store_link']); ?>" target="_blank" class="transition-transform hover:scale-105">
                        <img src="<?php echo htmlspecialchars($eventData['app_store_image']); ?>" alt="Download on App Store" class="h-14 w-auto">
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($eventData['play_store_link']) && !empty($eventData['play_store_image'])): ?>
                    <a href="<?php echo htmlspecialchars($eventData['play_store_link']); ?>" target="_blank" class="transition-transform hover:scale-105">
                        <img src="<?php echo htmlspecialchars($eventData['play_store_image']); ?>" alt="Download on Google Play" class="h-14 w-auto">
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <?php if (!empty($eventData['vai_app_link'])): ?>
                    <a href="<?php echo htmlspecialchars($eventData['vai_app_link']); ?>" target="_blank" class="inline-flex items-center justify-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold transition-colors">
                        <i data-lucide="smartphone" class="w-5 h-5 mr-2"></i>
                        <span>Go to App</span>
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo htmlspecialchars($eventData['suggerisci_evento_link'] ?? 'suggest-event.php'); ?>" class="inline-flex items-center justify-center px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-full font-semibold transition-colors">
                        <i data-lucide="plus-circle" class="w-5 h-5 mr-2"></i>
                        <span>Suggest Event</span>
                    </a>
                </div>

                <!-- Info Text -->
                <div class="text-center mt-8">
                    <p class="text-gray-600">
                        Do you have an event to share? Let us know and we'll evaluate including it on our platform.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    <?php echo htmlspecialchars($settingsArray['categories_title'] ?? 'Explore by Category'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo htmlspecialchars($settingsArray['categories_description'] ?? 'Discover Calabria through its different facets: from unspoiled nature to rich cultural tradition.'); ?>
                </p>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($categories as $index => $category): ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <!-- Category Header -->
                    <div class="bg-gradient-to-br from-blue-500 to-teal-600 relative overflow-hidden p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium">
                                <span><?php echo $category['article_count']; ?> articles</span>
                            </div>
                            <div class="text-4xl"><?php echo $category['icon']; ?></div>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2"><?php echo htmlspecialchars($category['name']); ?></h3>
                        <p class="text-blue-100 text-sm"><?php echo htmlspecialchars($category['description']); ?></p>
                    </div>
                    
                    <!-- Articles Preview -->
                    <?php if (!empty($category['articles'])): ?>
                    <div class="p-6 pb-4">
                        <div class="space-y-3">
                            <?php foreach (array_slice($category['articles'], 0, 2) as $article): ?>
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-9 bg-gray-200 rounded flex-shrink-0 overflow-hidden">
                                    <?php if ($article['featured_image']): ?>
                                    <img src="<?php echo htmlspecialchars(getImageUrl($article['featured_image'])); ?>" 
                                         alt="<?php echo htmlspecialchars($article['title']); ?>"
                                         class="w-full h-full object-cover">
                                    <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-blue-200 to-teal-300 flex items-center justify-center">
                                        <i data-lucide="image" class="w-3 h-3 text-gray-500"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="article.php?slug=<?php echo $article['slug']; ?>" class="block">
                                        <h4 class="text-sm font-semibold text-gray-900 hover:text-blue-600 transition-colors line-clamp-2 leading-tight">
                                            <?php echo htmlspecialchars($article['title']); ?>
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <?php echo formatDate($article['created_at']); ?>
                                        </p>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="p-6 text-center">
                        <i data-lucide="file-text" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-500 mb-4">No articles available</p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Category Footer -->
                    <div class="px-6 pb-6">
                        <div class="border-t pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="bookmark" class="w-4 h-4 mr-1"></i>
                                    <?php echo $category['article_count']; ?> contents
                                </span>
                                <a href="category.php?id=<?php echo $category['id']; ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm transition-colors">
                                    <span>Explore</span> <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-16">
                <a href="categories.php" class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold transition-colors">
                    <span><?php echo htmlspecialchars($settingsArray['categories_button_text'] ?? 'View All Categories'); ?></span> <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Provinces Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    <?php echo htmlspecialchars($settingsArray['provinces_title'] ?? 'Explore the Provinces'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo htmlspecialchars($settingsArray['provinces_description'] ?? 'Each Calabrian province holds unique treasures: from the Tyrrhenian coast to the Ionian one, from the Sila mountains to Aspromonte.'); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($provinces as $index => $province):
                    $colors = ['blue', 'orange', 'green', 'purple', 'orange'];
                    $color = $colors[$index % count($colors)];
                    $articleCount = $db->getArticleCountByProvince($province['id']);
                    $cities = $db->getCitiesByProvince($province['id']);
                ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                    <div class="aspect-[4/3] relative overflow-hidden">
                        <?php if (!empty($province['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars(getImageUrl($province['image_path'])); ?>" 
                             alt="<?php echo htmlspecialchars($province['name']); ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-black/10"></div>
                        <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-<?php echo $color; ?>-500 to-<?php echo $color; ?>-600"></div>
                        <?php endif; ?>
                        
                        <div class="absolute top-4 left-4">
                            <span class="bg-<?php echo $color; ?>-600 text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg">
                                <?php echo htmlspecialchars($province['name']); ?>
                            </span>
                        </div>
                        
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm shadow-lg">
                                <?php echo $articleCount; ?> contents
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($province['name']); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($province['description']); ?></p>

                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 mb-2">MAIN LOCATIONS:</h4>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach (array_slice($cities, 0, 3) as $city): ?>
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm"><?php echo htmlspecialchars($city['name']); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 flex items-center">
                                <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                <?php echo $articleCount; ?> contents
                            </span>
                            <a href="province.php?id=<?php echo $province['id']; ?>" class="text-<?php echo $color; ?>-600 hover:text-<?php echo $color; ?>-700 font-semibold flex items-center">
                                <span>Explore</span> <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6"><?php echo htmlspecialchars($settingsArray['cta_title'] ?? 'Want to Make Your Calabria Known?'); ?></h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                <?php echo htmlspecialchars($settingsArray['cta_description'] ?? 'Join our community! Share your favorite places, traditions and stories.'); ?>
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="<?php echo htmlspecialchars($settingsArray['cta_button1_link'] ?? 'collaborate.php'); ?>" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                    <span><?php echo htmlspecialchars($settingsArray['cta_button1_text'] ?? 'Collaborate with Us'); ?></span>
                </a>
                <a href="<?php echo htmlspecialchars($settingsArray['cta_button2_link'] ?? 'suggest.php'); ?>" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white rounded-full font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                    <span><?php echo htmlspecialchars($settingsArray['cta_button2_text'] ?? 'Suggest a Place'); ?></span>
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                <?php echo htmlspecialchars($settingsArray['newsletter_title'] ?? 'Stay Connected with Calabria'); ?>
            </h2>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                <?php echo htmlspecialchars($settingsArray['newsletter_description'] ?? 'Subscribe to our newsletter to receive the best content and never miss the most interesting events in the region.'); ?>
            </p>

            <form action="<?php echo htmlspecialchars($settingsArray['newsletter_form_action'] ?? '../api/newsletter.php'); ?>" method="POST" class="max-w-md mx-auto flex gap-4">
                <input
                    type="email"
                    name="email"
                    placeholder="<?php echo htmlspecialchars($settingsArray['newsletter_placeholder'] ?? 'Enter your email'); ?>"
                    required
                    class="flex-1 px-6 py-4 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-full font-semibold transition-colors">
                    <span><?php echo htmlspecialchars($settingsArray['newsletter_button'] ?? 'Subscribe Free'); ?></span>
                </button>
            </form>
            <p class="text-sm text-gray-500 mt-4">
                <?php echo htmlspecialchars($settingsArray['newsletter_privacy'] ?? 'We respect your privacy. No spam, only quality content.'); ?>
            </p>

            <!-- Social Media -->
            <div class="mt-12">
                <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($settingsArray['social_follow_text'] ?? 'Follow us on social media'); ?></p>
                <div class="flex justify-center space-x-6">
                    <?php if (!empty($settingsArray['social_facebook'])): ?>
                    <a href="<?php echo htmlspecialchars($settingsArray['social_facebook']); ?>" target="_blank" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                        <i data-lucide="facebook" class="w-6 h-6"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($settingsArray['social_instagram'])): ?>
                    <a href="<?php echo htmlspecialchars($settingsArray['social_instagram']); ?>" target="_blank" class="w-12 h-12 bg-pink-500 text-white rounded-full flex items-center justify-center hover:bg-pink-600 transition-colors">
                        <i data-lucide="instagram" class="w-6 h-6"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($settingsArray['social_twitter'])): ?>
                    <a href="<?php echo htmlspecialchars($settingsArray['social_twitter']); ?>" target="_blank" class="w-12 h-12 bg-blue-400 text-white rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                        <i data-lucide="twitter" class="w-6 h-6"></i>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($settingsArray['social_youtube'])): ?>
                    <a href="<?php echo htmlspecialchars($settingsArray['social_youtube']); ?>" target="_blank" class="w-12 h-12 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                        <i data-lucide="youtube" class="w-6 h-6"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="../assets/js/main.js"></script>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>