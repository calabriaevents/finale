<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/ContentManagerSimple.php';

$db = new Database();
$contentManager = new ContentManagerSimple($db);
$currentLang = 'en'; // Force English language
$cities = $db->getCities();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map - Passione Calabria</title>
    <meta name="description" content="Interactive map of Calabria showing all cities and provinces. Explore the beautiful regions and discover your next destination.">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
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
                <span class="breadcrumb-item text-gray-900 font-medium">Map</span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">🗺️</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Map of Calabria
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-8">
                Explore the interactive map of Calabria and discover all the cities, provinces and places of interest in this magnificent region of southern Italy.
            </p>
            <div class="flex justify-center gap-4 flex-wrap">
                <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full">
                    <?php echo count($cities); ?> cities mapped
                </span>
                <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-full">
                    5 provinces
                </span>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Map Controls -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 md:mb-0">
                        Interactive Map
                    </h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Cities</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Provincial capitals</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i data-lucide="info" class="w-5 h-5 text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-blue-800">
                            <p class="font-medium mb-1">How to use the map:</p>
                            <ul class="text-sm space-y-1">
                                <li>• Click on markers to view information about cities</li>
                                <li>• Use mouse wheel to zoom in and out</li>
                                <li>• Drag the map to navigate around Calabria</li>
                                <li>• Click on city names in popups to visit detailed pages</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div id="calabria-map" class="w-full h-[600px]"></div>
                
                <!-- Map Footer -->
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="text-center md:text-left mb-4 md:mb-0">
                            <h3 class="font-semibold text-gray-900 mb-1">Explore Calabria</h3>
                            <p class="text-gray-600 text-sm">Click on cities to discover articles, events and local information</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="cities.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors inline-flex items-center">
                                <i data-lucide="list" class="w-4 h-4 mr-2"></i>
                                View All Cities
                            </a>
                            <a href="provinces.php" class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-6 py-2 rounded-lg font-semibold transition-colors inline-flex items-center">
                                <i data-lucide="map" class="w-4 h-4 mr-2"></i>
                                Provinces
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-12">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl mb-2">🏘️</div>
                    <div class="text-2xl font-bold text-blue-600"><?php echo count($cities); ?></div>
                    <div class="text-gray-600">Cities</div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl mb-2">🏛️</div>
                    <div class="text-2xl font-bold text-blue-600">5</div>
                    <div class="text-gray-600">Provinces</div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl mb-2">🌊</div>
                    <div class="text-2xl font-bold text-blue-600">783</div>
                    <div class="text-gray-600">Km of coastline</div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl mb-2">⛰️</div>
                    <div class="text-2xl font-bold text-blue-600">15,222</div>
                    <div class="text-gray-600">Km² area</div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="../assets/js/main.js"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Initialize map
        document.addEventListener('DOMContentLoaded', function() {
            initCalabriaMap();
        });

        function initCalabriaMap() {
            // Center map on Calabria
            const map = L.map('calabria-map').setView([39.0, 16.5], 9);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Cities data from PHP
            const cities = <?php echo json_encode($cities); ?>;

            // Provincial capitals (for different marker style)
            const provincialCapitals = ['Catanzaro', 'Cosenza', 'Crotone', 'Reggio Calabria', 'Vibo Valentia'];

            // Add markers for each city
            cities.forEach(function(city) {
                if (city.latitude && city.longitude) {
                    // Check if city is a provincial capital
                    const isCapital = provincialCapitals.includes(city.name);
                    
                    // Create custom icon based on city type
                    let markerIcon;
                    if (isCapital) {
                        markerIcon = L.divIcon({
                            className: 'custom-marker capital-marker',
                            html: '<div class="w-4 h-4 bg-red-500 border-2 border-white rounded-full shadow-lg"></div>',
                            iconSize: [16, 16],
                            iconAnchor: [8, 8]
                        });
                    } else {
                        markerIcon = L.divIcon({
                            className: 'custom-marker city-marker',
                            html: '<div class="w-3 h-3 bg-blue-500 border-2 border-white rounded-full shadow-lg"></div>',
                            iconSize: [12, 12],
                            iconAnchor: [6, 6]
                        });
                    }

                    const marker = L.marker([city.latitude, city.longitude], { icon: markerIcon }).addTo(map);
                    
                    // Create popup content
                    const popupContent = `
                        <div class="p-2 min-w-[200px]">
                            <h3 class="font-bold text-lg text-gray-900 mb-2">${city.name}</h3>
                            <p class="text-gray-600 mb-2">${city.description || 'City in ' + city.province_name}</p>
                            <div class="text-sm text-gray-500 mb-3">
                                <i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i>
                                Province of ${city.province_name}
                            </div>
                            <a href="city-detail.php?id=${city.id}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                Explore city <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                            </a>
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent);
                    
                    // Re-initialize Lucide icons when popup opens
                    marker.on('popupopen', function() {
                        setTimeout(() => lucide.createIcons(), 100);
                    });
                }
            });

            // Add region boundaries (optional - simple rectangle for demo)
            const calabriaBounds = [
                [38.0, 15.5], // Southwest
                [40.2, 17.2]  // Northeast
            ];
            
            // Add a subtle region boundary
            L.rectangle(calabriaBounds, {
                color: '#3b82f6',
                weight: 2,
                opacity: 0.3,
                fillOpacity: 0.05
            }).addTo(map);

            // Fit map to show all cities
            if (cities.length > 0) {
                const group = new L.featureGroup();
                cities.forEach(function(city) {
                    if (city.latitude && city.longitude) {
                        group.addLayer(L.marker([city.latitude, city.longitude]));
                    }
                });
                
                if (group.getLayers().length > 0) {
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            }
        }

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
    </script>
</body>
</html>