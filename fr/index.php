<?php
// French Homepage - Passione Calabria
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/ContentManagerSimple.php';

// Force French language
$_SESSION['user_language'] = 'fr';
$_GET['lang'] = 'fr';

// Initialize database and content manager
$db = new Database();
$contentManager = new ContentManagerSimple();
$currentLang = $contentManager->getCurrentLanguage();
$langInfo = $contentManager->getCurrentLanguageInfo();

// Load data for the homepage
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
unset($category);

// Find hero section
$heroSection = null;
foreach ($homeSections as $section) {
    if ($section['section_name'] === 'hero') {
        $heroSection = $section;
        break;
    }
}

// Helper function for French translated texts
function getFrenchText($key, $default) {
    global $contentManager;
    return $contentManager->getText($key, $default);
}
?>
<!DOCTYPE html>
<html lang="fr" data-lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Découvrir la Calabre - Votre guide de l'Italie du Sud</title>
    <meta name="description" content="Explorez la beauté de la Calabre : mer cristalline, villages médiévaux, gastronomie unique et traditions millénaires.">

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
    <?php include '../includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section-new text-white py-24" 
             style="background: linear-gradient(135deg, rgba(30, 58, 138, 0.9), rgba(30, 64, 175, 0.8), rgba(59, 130, 246, 0.7), rgba(245, 158, 11, 0.8)), url('<?php echo $heroSection['image_path'] ?? 'https://images.unsplash.com/photo-1499092346589-b9b6be3e94b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 600px; display: flex; align-items: center;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center w-full">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6">
                Découvrir la Calabre
            </h1>
            <p class="text-xl md:text-2xl text-yellow-400 mb-8">
                Mer cristalline et histoire millénaire
            </p>
            <p class="text-lg md:text-xl text-gray-200 mb-12 max-w-3xl mx-auto">
                Plongez dans la beauté de la Calabre, avec ses plages de rêve, son centre historique fascinant et ses vues à couper le souffle depuis la falaise.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                <a href="../categorie.php?lang=fr" class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold transition-colors">
                    <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                    <span>Explorer la Calabre</span>
                </a>
                <a href="../mappa.php?lang=fr" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white hover:bg-white hover:text-gray-800 text-white rounded-full font-semibold transition-colors">
                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                    <span>Voir la Carte</span>
                </a>
            </div>

            <!-- Search Widget -->
            <div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 shadow-2xl">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Que cherchez-vous ?</h2>
                <form action="../ricerca.php" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="hidden" name="lang" value="fr">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lieux, événements, traditions...</label>
                        <input
                            type="text"
                            name="q"
                            placeholder="Entrez ce que vous voulez explorer"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                        <select name="provincia" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900">
                            <option value="">Toutes les provinces</option>
                            <?php foreach ($provinces as $province): ?>
                            <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Explorer par Catégorie
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez la Calabre à travers ses différentes facettes : de la nature préservée à la riche tradition culturelle.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach (array_slice($categories, 0, 6) as $category): ?>
                <a href="../categoria.php?id=<?php echo $category['id']; ?>&lang=fr" class="group block">
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 border border-gray-100">
                        <div class="text-4xl mb-4"><?php echo $category['icon']; ?></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </h3>
                        <p class="text-gray-600 mb-4">
                            <?php echo htmlspecialchars($category['description']); ?>
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                <?php echo $category['article_count']; ?> articles
                            </span>
                            <span class="text-blue-600 font-semibold group-hover:text-blue-700 transition-colors">
                                Explorer →
                            </span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-12">
                <a href="../categorie.php?lang=fr" class="inline-flex items-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold transition-colors">
                    <span>Voir Toutes les Catégories</span>
                    <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Provinces Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Explorer les Provinces
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Chaque province calabraise recèle des trésors uniques : de la côte tyrrhénienne à la côte ionienne, des montagnes de la Sila à l'Aspromonte.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <?php foreach ($provinces as $province): ?>
                <a href="../provincia.php?id=<?php echo $province['id']; ?>&lang=fr" class="group block">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                        <div class="aspect-[4/3] bg-gradient-to-br from-blue-500 to-teal-600 relative">
                            <?php if ($province['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($province['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($province['name']); ?>"
                                 class="w-full h-full object-cover">
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/40"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="text-xl font-bold"><?php echo htmlspecialchars($province['name']); ?></h3>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-600 text-sm mb-3">
                                <?php echo htmlspecialchars($province['description']); ?>
                            </p>
                            <span class="text-blue-600 font-semibold text-sm group-hover:text-blue-700 transition-colors">
                                Découvrir →
                            </span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Restez Connecté avec la Calabre
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Abonnez-vous à notre newsletter pour recevoir le meilleur contenu et ne jamais manquer les événements les plus intéressants de la région.
            </p>
            <form action="../api/newsletter.php" method="POST" class="flex flex-col sm:flex-row gap-4 justify-center">
                <input type="hidden" name="lang" value="fr">
                <input
                    type="email"
                    name="email"
                    placeholder="Entrez votre email"
                    required
                    class="px-6 py-3 rounded-full text-gray-900 flex-1 max-w-md focus:outline-none focus:ring-2 focus:ring-white"
                >
                <button type="submit" class="px-8 py-3 bg-white text-blue-600 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    S'abonner Gratuitement
                </button>
            </form>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/unified-translation-system.js"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Scroll animations
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

        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>