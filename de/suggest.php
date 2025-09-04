<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/ContentManagerSimple.php';

$db = new Database();
$contentManager = new ContentManagerSimple($db);
$currentLang = 'en'; // Force English language

$form_submitted = false;
$form_error = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input sanitization and validation
    $place_name = trim($_POST['place_name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $user_name = trim($_POST['user_name'] ?? '');
    $user_email = filter_var($_POST['user_email'] ?? '', FILTER_SANITIZE_EMAIL);
    
    // Length validation
    $validation_errors = [];
    if (strlen($place_name) > 200) $validation_errors[] = 'Place name too long';
    if (strlen($location) > 200) $validation_errors[] = 'Location too long';
    if (strlen($description) > 2000) $validation_errors[] = 'Description too long';
    if (strlen($user_name) > 100) $validation_errors[] = 'User name too long';
    if (strlen($user_email) > 255) $validation_errors[] = 'Email too long';

    if (empty($validation_errors) && !empty($place_name) && !empty($location) && !empty($description) && !empty($user_name) && !empty($user_email) && filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Here would go the code to save the suggestion
            // $db->createPlaceSuggestion($place_name, $description, $location, $user_name, $user_email);
            $form_submitted = true;
        } catch (Exception $e) {
            error_log('Error saving suggestion: ' . $e->getMessage());
            $form_error = true;
            $error_message = 'Error saving suggestion. Please try again later.';
        }
    } else {
        $form_error = true;
        $error_message = !empty($validation_errors) ? implode(', ', $validation_errors) : 'Please fill in all fields correctly.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest a Place - Passione Calabria</title>
    <meta name="description" content="Know a special place in Calabria that we should feature? Share your suggestion with us and help discover hidden gems of our beautiful region.">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                <span class="breadcrumb-item text-gray-900 font-medium">Suggest a Place</span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">💡</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Suggest a Place
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Do you know a special place in Calabria that we should absolutely feature in our portal? Share it with us and help others discover the hidden gems of our beautiful region.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if ($form_submitted): ?>
            <!-- Success Message -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <div class="text-6xl mb-6">✅</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Thank you for your suggestion!</h2>
                <p class="text-gray-600 mb-8 text-lg">
                    We have received your suggestion and our team will review it as soon as possible. If approved, your recommended place will be featured on our website.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        Return to Homepage
                    </a>
                    <a href="suggest.php" class="border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                        Suggest Another Place
                    </a>
                </div>
            </div>
            <?php else: ?>
            
            <!-- Suggestion Information -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Help Us Discover Calabria</h2>
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center">
                        <div class="text-4xl mb-3">🏛️</div>
                        <h3 class="font-bold text-gray-900 mb-2">Historic Places</h3>
                        <p class="text-gray-600 text-sm">Ancient ruins, castles, museums, and archaeological sites</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl mb-3">🌊</div>
                        <h3 class="font-bold text-gray-900 mb-2">Natural Beauty</h3>
                        <p class="text-gray-600 text-sm">Beaches, parks, mountains, and hidden natural spots</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl mb-3">🍝</div>
                        <h3 class="font-bold text-gray-900 mb-2">Local Experiences</h3>
                        <p class="text-gray-600 text-sm">Restaurants, festivals, traditions, and cultural events</p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <i data-lucide="lightbulb" class="w-6 h-6 text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <h3 class="font-bold text-yellow-900 mb-2">What makes a great suggestion?</h3>
                            <ul class="text-yellow-800 space-y-1 text-sm">
                                <li>• Places with unique history or cultural significance</li>
                                <li>• Hidden gems that tourists might not know about</li>
                                <li>• Local businesses that represent authentic Calabrian culture</li>
                                <li>• Natural spots with exceptional beauty or interest</li>
                                <li>• Accurate location information and detailed descriptions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestion Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Share Your Suggestion</h2>

                <?php if ($form_error): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mt-0.5 mr-3"></i>
                        <div class="text-red-800">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    
                    <!-- Place Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="map-pin" class="w-5 h-5 mr-2 text-blue-600"></i>
                            Place Information
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="place_name" class="block text-sm font-medium text-gray-700 mb-2">Place Name *</label>
                                <input type="text" 
                                       id="place_name" 
                                       name="place_name" 
                                       maxlength="200"
                                       value="<?php echo htmlspecialchars($_POST['place_name'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="e.g. Castello di Pizzo"
                                       required>
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                                <input type="text" 
                                       id="location" 
                                       name="location" 
                                       maxlength="200"
                                       value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="e.g. Pizzo, Vibo Valentia"
                                       required>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="6"
                                      maxlength="2000"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Describe the place in detail: what makes it special? What can visitors expect? Include historical information, interesting facts, or personal experiences..."
                                      required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            <p class="text-sm text-gray-500 mt-1">Maximum 2000 characters</p>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="user" class="w-5 h-5 mr-2 text-blue-600"></i>
                            Your Information
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="user_name" class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                                <input type="text" 
                                       id="user_name" 
                                       name="user_name" 
                                       maxlength="100"
                                       value="<?php echo htmlspecialchars($_POST['user_name'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Your full name"
                                       required>
                            </div>
                            <div>
                                <label for="user_email" class="block text-sm font-medium text-gray-700 mb-2">Your Email *</label>
                                <input type="email" 
                                       id="user_email" 
                                       name="user_email" 
                                       maxlength="255"
                                       value="<?php echo htmlspecialchars($_POST['user_email'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="your.email@example.com"
                                       required>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <i data-lucide="shield-check" class="w-5 h-5 text-blue-600 mt-0.5 mr-3"></i>
                                <div class="text-blue-800 text-sm">
                                    <p class="font-medium mb-1">Privacy Notice:</p>
                                    <p>We will only use your contact information to follow up on your suggestion if needed. Your personal data will not be shared or used for marketing purposes.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            Send Suggestion
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="../assets/js/main.js"></script>
    <script>
        lucide.createIcons();

        // Character count for description
        const descriptionField = document.getElementById('description');
        if (descriptionField) {
            const maxLength = 2000;
            const createCountDisplay = () => {
                const countDisplay = document.createElement('div');
                countDisplay.className = 'text-sm text-gray-500 mt-1';
                countDisplay.id = 'char-count';
                return countDisplay;
            };
            
            const updateCharCount = () => {
                const currentLength = descriptionField.value.length;
                const remaining = maxLength - currentLength;
                let countDisplay = document.getElementById('char-count');
                
                if (!countDisplay) {
                    countDisplay = createCountDisplay();
                    descriptionField.parentNode.appendChild(countDisplay);
                }
                
                countDisplay.textContent = `${currentLength}/${maxLength} characters`;
                
                if (remaining < 100) {
                    countDisplay.classList.remove('text-gray-500');
                    countDisplay.classList.add('text-orange-500');
                }
                if (remaining < 0) {
                    countDisplay.classList.remove('text-orange-500');
                    countDisplay.classList.add('text-red-500');
                }
                if (remaining >= 100) {
                    countDisplay.classList.remove('text-orange-500', 'text-red-500');
                    countDisplay.classList.add('text-gray-500');
                }
            };
            
            descriptionField.addEventListener('input', updateCharCount);
            updateCharCount(); // Initial count
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