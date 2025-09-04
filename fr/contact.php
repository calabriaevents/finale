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
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($subject) && !empty($message)) {
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
    <title>Contact Us - Passione Calabria</title>
    <meta name="description" content="Get in touch with us. Contact Passione Calabria for information, suggestions, partnerships or any questions about our beautiful region.">
    
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
                <span class="breadcrumb-item text-gray-900 font-medium">Contact</span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">📧</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Contact Us
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                We'd love to hear from you! Get in touch with us for information, suggestions, partnerships or any questions about Calabria.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if ($form_submitted): ?>
            <!-- Success Message -->
            <div class="bg-white rounded-xl shadow-lg p-8 text-center max-w-2xl mx-auto">
                <div class="text-6xl mb-6">✅</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Message Sent!</h2>
                <p class="text-gray-600 mb-8 text-lg">
                    Thank you for contacting us! We have received your message and will respond as soon as possible.
                </p>
                <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                    Return to Homepage
                </a>
            </div>
            <?php else: ?>
            
            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Contact Information -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-8 h-fit">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="bg-blue-100 rounded-lg p-3 mr-4">
                                    <i data-lucide="mail" class="w-6 h-6 text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                                    <p class="text-gray-600">info@passionecalabria.it</p>
                                    <p class="text-sm text-gray-500">We reply within 24 hours</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="bg-green-100 rounded-lg p-3 mr-4">
                                    <i data-lucide="map-pin" class="w-6 h-6 text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Location</h3>
                                    <p class="text-gray-600">Calabria, Italy</p>
                                    <p class="text-sm text-gray-500">Southern Italy</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="bg-purple-100 rounded-lg p-3 mr-4">
                                    <i data-lucide="clock" class="w-6 h-6 text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Response Time</h3>
                                    <p class="text-gray-600">24-48 hours</p>
                                    <p class="text-sm text-gray-500">Monday to Friday</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                            <h3 class="font-semibold text-blue-900 mb-2">Quick Questions?</h3>
                            <p class="text-blue-800 text-sm mb-3">Check out our FAQ section or browse our articles for immediate answers.</p>
                            <a href="articles.php" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                Browse Articles →
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
                        
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
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                <select id="subject" 
                                        name="subject"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Select a subject</option>
                                    <option value="general" <?php echo ($_POST['subject'] ?? '') === 'general' ? 'selected' : ''; ?>>General Information</option>
                                    <option value="collaboration" <?php echo ($_POST['subject'] ?? '') === 'collaboration' ? 'selected' : ''; ?>>Collaboration Proposal</option>
                                    <option value="business" <?php echo ($_POST['subject'] ?? '') === 'business' ? 'selected' : ''; ?>>Business Partnership</option>
                                    <option value="content" <?php echo ($_POST['subject'] ?? '') === 'content' ? 'selected' : ''; ?>>Content Suggestion</option>
                                    <option value="technical" <?php echo ($_POST['subject'] ?? '') === 'technical' ? 'selected' : ''; ?>>Technical Support</option>
                                    <option value="other" <?php echo ($_POST['subject'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Message *</label>
                                <textarea id="message" 
                                          name="message" 
                                          rows="6"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Tell us how we can help you..."
                                          required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i data-lucide="lightbulb" class="w-5 h-5 text-yellow-600 mt-0.5 mr-3"></i>
                                    <div class="text-yellow-800 text-sm">
                                        <p class="font-medium mb-1">Tip for better assistance:</p>
                                        <p>Please be as specific as possible in your message. Include relevant details like your location in Calabria, dates for events, or specific topics you're interested in.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center">
                                    <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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