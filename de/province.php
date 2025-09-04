<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/ContentManagerSimple.php';

$db = new Database();
$contentManager = new ContentManagerSimple($db);
$currentLang = 'en'; // Force English language

// Check if province ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: provinces.php");
    exit;
}

$provinceId = (int)$_GET['id'];

// Load province data
$province = $db->getProvinceById($provinceId);
if (!$province) {
    header("Location: provinces.php");
    exit;
}

// Load province articles
$articles = $db->getArticlesByProvince($provinceId);
$articleCount = $db->getArticleCountByProvince($provinceId);

// Load province cities
$cities = $db->getCitiesByProvince($provinceId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($province['name'] . ' - Provinces of Calabria'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($province['description']); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="../assets/css/style.css">

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
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>

    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="breadcrumb">
                <span class="breadcrumb-item"><a href="index.php" class="text-blue-600 hover:text-blue-700">Home</a></span>
                <span class="breadcrumb-item"><a href="provinces.php" class="text-blue-600 hover:text-blue-700">Provinces</a></span>
                <span class="breadcrumb-item text-gray-900 font-medium"><?php echo htmlspecialchars($province['name']); ?></span>
            </nav>
        </div>
    </div>

    <!-- Province Hero -->
    <div class="bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">🏛️</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Province of <?php echo htmlspecialchars($province['name']); ?>
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                <?php echo htmlspecialchars($province['description']); ?>
            </p>
            <div class="mt-8 flex justify-center gap-4 flex-wrap">
                <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full">
                    <?php echo $articleCount; ?> <?php echo $articleCount == 1 ? 'article' : 'articles'; ?>
                </span>
                <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full">
                    <?php echo count($cities); ?> <?php echo count($cities) == 1 ? 'city' : 'cities'; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Cities Section -->
            <?php if (!empty($cities)): ?>
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                    Main Cities
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($cities as $city): ?>
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900">
                                <?php echo htmlspecialchars($city['name']); ?>
                            </h3>
                            <i data-lucide="map-pin" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <p class="text-gray-600 mb-4">
                            <?php echo htmlspecialchars($city['description']); ?>
                        </p>
                        <?php if ($city['latitude'] && $city['longitude']): ?>
                        <div class="flex items-center text-sm text-gray-500">
                            <i data-lucide="navigation" class="w-4 h-4 mr-1"></i>
                            <span><?php echo number_format($city['latitude'], 4); ?>, <?php echo number_format($city['longitude'], 4); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Articles Section -->
            <?php if (!empty($articles)): ?>
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                    Articles from <?php echo htmlspecialchars($province['name']); ?>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($articles as $article): ?>
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                        <!-- Article Image -->
                        <div class="aspect-[4/3] bg-gradient-to-br from-blue-500 to-teal-600 relative overflow-hidden">
                            <?php if ($article['featured_image']): ?>
                            <img src="<?php echo htmlspecialchars($article['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($article['title']); ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php else: ?>
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-teal-600"></div>
                            <?php endif; ?>
                            
                            <div class="absolute inset-0 bg-black/40"></div>
                            
                            <!-- Article Meta -->
                            <div class="absolute top-4 left-4 right-4">
                                <div class="flex justify-between items-start">
                                    <span class="bg-white/20 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm">
                                        <?php echo htmlspecialchars($article['category_name'] ?? 'Article'); ?>
                                    </span>
                                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        <?php echo $article['views']; ?> views
                                    </span>
                                </div>
                            </div>
                            
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <h3 class="text-xl font-bold mb-2 line-clamp-2">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </h3>
                            </div>
                        </div>

                        <!-- Article Content -->
                        <div class="p-6">
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($article['excerpt']); ?>
                            </p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                        <?php echo formatDate($article['created_at']); ?>
                                    </span>
                                    <span class="flex items-center">
                                        <i data-lucide="user" class="w-4 h-4 mr-1"></i>
                                        <?php echo htmlspecialchars($article['author']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <a href="article.php?slug=<?php echo $article['slug']; ?>" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                                <span>Read more</span> <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <!-- Empty Articles State -->
            <div class="text-center py-20">
                <div class="text-6xl mb-6">📝</div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    No articles available
                </h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    There are no articles for this province yet, but we're preparing fantastic ones!
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="provinces.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition-colors">
                        Explore Other Provinces
                    </a>
                    <a href="suggest.php" class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-6 py-3 rounded-full font-semibold transition-colors">
                        Suggest Content
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Province Map Section -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                    Interactive Map of <?php echo htmlspecialchars($province['name']); ?>
                </h2>
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="mb-6">
                        <p class="text-gray-600 text-center mb-4">
                            Explore <?php echo htmlspecialchars($province['name']); ?> with the interactive map. Discover cities, places of interest and landmarks.
                        </p>
                    </div>
                    <div id="province-map" class="w-full h-96 bg-gray-100 rounded-lg overflow-hidden">
                        <!-- Province-specific Leaflet map -->
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">
                            <?php echo count($cities); ?> cities displayed
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Related Provinces -->
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">
                    Explore Other Provinces
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php 
                    $otherProvinces = array_filter($db->getProvinces(), function($prov) use ($provinceId) {
                        return $prov['id'] != $provinceId;
                    });
                    ?>
                    <?php foreach ($otherProvinces as $relatedProvince): ?>
                    <a href="province.php?id=<?php echo $relatedProvince['id']; ?>" 
                       class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all group">
                        <div class="text-4xl mb-3">🏛️</div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                            <?php echo htmlspecialchars($relatedProvince['name']); ?>
                        </h4>
                        <p class="text-gray-600 text-sm mb-4">
                            <?php echo htmlspecialchars(substr($relatedProvince['description'], 0, 80)); ?>...
                        </p>
                        <div class="flex items-center text-blue-600 font-semibold">
                            <span>Explore</span> <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="../assets/js/main.js"></script>
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

        document.querySelectorAll('.bg-white').forEach(card => {
            observer.observe(card);
        });
        
        // Initialize province-specific map
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('province-map')) {
                initProvinceMap();
            }
        });
        
        function initProvinceMap() {
            const provinceCities = <?php echo json_encode($cities); ?>;
            const provinceName = <?php echo json_encode($province['name']); ?>;
            
            if (provinceCities.length === 0) {
                document.getElementById('province-map').innerHTML = `
                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                        <div class="text-center">
                            <i data-lucide="map-off" class="w-16 h-16 mx-auto mb-4"></i>
                            <p>Map not available for this province</p>
                        </div>
                    </div>
                `;
                lucide.createIcons();
                return;
            }
            
            // Calculate map center from first city
            const firstCity = provinceCities[0];
            let centerLat = parseFloat(firstCity.latitude) || 39.0;
            let centerLng = parseFloat(firstCity.longitude) || 16.5;
            
            const map = L.map('province-map').setView([centerLat, centerLng], 10);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            
            // Add marker for each city
            provinceCities.forEach(city => {
                if (city.latitude && city.longitude) {
                    const marker = L.marker([parseFloat(city.latitude), parseFloat(city.longitude)]).addTo(map);
                    marker.bindPopup(`
                        <div class="p-2">
                            <h3 class="font-bold text-lg">${city.name}</h3>
                            <p class="text-gray-600">${city.description || 'City of ' + provinceName}</p>
                        </div>
                    `);
                }
            });
            
            // Adjust view to include all markers
            if (provinceCities.length > 1) {
                const markers = provinceCities
                    .filter(city => city.latitude && city.longitude)
                    .map(city => L.marker([parseFloat(city.latitude), parseFloat(city.longitude)]));
                if (markers.length > 0) {
                    const group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            }
        }
    </script>
</body>
</html>