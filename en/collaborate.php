<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/ContentManagerSimple.php';

$db = new Database();
$contentManager = new ContentManagerSimple($db);
$currentLang = 'en'; // Force English language

$form_submitted = false;
$form_error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        // Here would go the code to send the email
        // For now, we just show a success message
        $form_submitted = true;
    } else {
        $form_error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaborate - Passione Calabria</title>
    <meta name="description" content="Join our team and help us showcase the beauty of Calabria. Send us your collaboration proposal and contribute to our project.">
    
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
                <span class="breadcrumb-item text-gray-900 font-medium">Collaborate</span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">🤝</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Collaborate with Us
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Are you passionate about Calabria and want to contribute to our project? Join our team and help us showcase the beauty and culture of this magnificent region.
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
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Thank You!</h2>
                <p class="text-gray-600 mb-8 text-lg">
                    Your collaboration proposal has been sent successfully. We will contact you as soon as possible.
                </p>
                <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Return to Homepage
                </a>
            </div>
            <?php else: ?>
            
            <!-- Collaboration Info -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Why Collaborate with Us?</h2>
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center">
                        <div class="text-4xl mb-3">✍️</div>
                        <h3 class="font-bold text-gray-900 mb-2">Content Creation</h3>
                        <p class="text-gray-600 text-sm">Write articles about places, traditions, and culture of Calabria</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl mb-3">📸</div>
                        <h3 class="font-bold text-gray-900 mb-2">Photography</h3>
                        <p class="text-gray-600 text-sm">Share your photos and contribute to our visual gallery</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl mb-3">🎯</div>
                        <h3 class="font-bold text-gray-900 mb-2">Local Expertise</h3>
                        <p class="text-gray-600 text-sm">Help us with your local knowledge and authentic stories</p>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="font-bold text-blue-900 mb-3">What we're looking for:</h3>
                    <ul class="text-blue-800 space-y-2">
                        <li class="flex items-start">
                            <i data-lucide="check" class="w-5 h-5 text-blue-600 mt-0.5 mr-2"></i>
                            Writers passionate about Calabria and its territories
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check" class="w-5 h-5 text-blue-600 mt-0.5 mr-2"></i>
                            Photographers with high-quality images of the region
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check" class="w-5 h-5 text-blue-600 mt-0.5 mr-2"></i>
                            Local experts who know hidden gems and traditions
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check" class="w-5 h-5 text-blue-600 mt-0.5 mr-2"></i>
                            Translators to help us reach an international audience
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Collaboration Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us your proposal</h2>
                <p class="text-gray-600 mb-8">
                    Fill out the form below to send us your collaboration proposal. Tell us about yourself and how you would like to contribute to our project.
                </p>

                <?php if ($form_error): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mt-0.5 mr-3"></i>
                        <div class="text-red-800">
                            Please fill in all fields correctly.
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Your full name"
                                   required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="your.email@example.com"
                                   required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Proposal *</label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="8"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Tell us about yourself, your skills, and how you would like to collaborate with us. Include any relevant experience or specific ideas you have for contributing to our project."
                                  required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Please include in your message:</strong>
                        </p>
                        <ul class="text-sm text-gray-600 space-y-1 ml-4">
                            <li>• Your background and experience</li>
                            <li>• Type of collaboration you're interested in</li>
                            <li>• Any portfolio or examples of your work</li>
                            <li>• Your availability and commitment level</li>
                        </ul>
                    </div>

                    <div class="text-center">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            Send Proposal
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