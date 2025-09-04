<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Passione Calabria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Simple Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-600">Passione Calabria</h1>
                <div class="flex space-x-2">
                    <a href="../index.php" class="px-3 py-1 text-sm bg-gray-100 rounded">🇮🇹 IT</a>
                    <a href="../en/index.php" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded">🇺🇸 EN</a>
                    <a href="../fr/index.php" class="px-3 py-1 text-sm bg-gray-100 rounded">🇫🇷 FR</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-6">About Us</h1>
            
            <div class="prose max-w-none">
                <p class="text-lg text-gray-700 mb-6">
                    Welcome to Passione Calabria, your ultimate guide to discovering the beauty, culture, and traditions of Calabria, Italy's southern gem.
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Mission</h2>
                <p class="text-gray-700 mb-6">
                    We are passionate about showcasing the authentic Calabria - from its crystal-clear coastlines to its ancient mountain villages, from traditional cuisine to modern innovations. Our goal is to connect travelers and locals with the true essence of this magnificent region.
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">What We Offer</h2>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li>Comprehensive guides to cities, towns, and hidden gems</li>
                    <li>Cultural insights and historical context</li>
                    <li>Local events and festival information</li>
                    <li>Culinary experiences and traditional recipes</li>
                    <li>Outdoor activities and natural attractions</li>
                </ul>
                
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Team</h2>
                <p class="text-gray-700 mb-6">
                    We are a team of local experts, travel enthusiasts, and cultural ambassadors who live and breathe Calabria. Our deep connection to this land drives us to share its stories with the world.
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-blue-900 mb-3">Join Our Community</h3>
                    <p class="text-blue-800">
                        Whether you're planning a visit, looking to relocate, or simply curious about Calabrian culture, we invite you to explore, discover, and fall in love with this incredible region.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Passione Calabria. All rights reserved.</p>
            <p class="mt-2 text-gray-400">🌐 English Version - Translated using the new static file system</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>