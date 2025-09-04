<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qui Nous Sommes - Passione Calabria</title>
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
                    <a href="../en/index.php" class="px-3 py-1 text-sm bg-gray-100 rounded">🇺🇸 EN</a>
                    <a href="../fr/index.php" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded">🇫🇷 FR</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-6">Qui Nous Sommes</h1>
            
            <div class="prose max-w-none">
                <p class="text-lg text-gray-700 mb-6">
                    Bienvenue sur Passione Calabria, votre guide ultime pour découvrir la beauté, la culture et les traditions de la Calabre, le joyau du sud de l'Italie.
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Notre Mission</h2>
                <p class="text-gray-700 mb-6">
                    Nous sommes passionnés par la présentation de la Calabre authentique - de ses côtes cristallines à ses anciens villages de montagne, de la cuisine traditionnelle aux innovations modernes. Notre objectif est de connecter les voyageurs et les locaux avec la véritable essence de cette magnifique région.
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Ce Que Nous Offrons</h2>
                <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                    <li>Guides complets des villes, villages et joyaux cachés</li>
                    <li>Aperçus culturels et contexte historique</li>
                    <li>Informations sur les événements locaux et festivals</li>
                    <li>Expériences culinaires et recettes traditionnelles</li>
                    <li>Activités de plein air et attractions naturelles</li>
                </ul>
                
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Notre Équipe</h2>
                <p class="text-gray-700 mb-6">
                    Nous sommes une équipe d'experts locaux, de passionnés de voyages et d'ambassadeurs culturels qui vivent et respirent la Calabre. Notre connexion profonde à cette terre nous pousse à partager ses histoires avec le monde.
                </p>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-blue-900 mb-3">Rejoignez Notre Communauté</h3>
                    <p class="text-blue-800">
                        Que vous planifiez une visite, cherchiez à déménager, ou soyez simplement curieux de la culture calabraise, nous vous invitons à explorer, découvrir et tomber amoureux de cette région incroyable.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2024 Passione Calabria. Tous droits réservés.</p>
            <p class="mt-2 text-gray-400">🌐 Version Française - Traduit avec le nouveau système de fichiers statiques</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>