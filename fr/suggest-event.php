<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/ContentManagerSimple.php';

$db = new Database();
$contentManager = new ContentManagerSimple($db);
$currentLang = 'en'; // Force English language

$provinces = $db->getProvinces();
$categories = $db->getCategories();

// Handle form submission
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $location = trim($_POST['location'] ?? '');
        $category_id = $_POST['category_id'] ?? null;
        $province_id = $_POST['province_id'] ?? null;
        $organizer = trim($_POST['organizer'] ?? '');
        $contact_email = trim($_POST['contact_email'] ?? '');
        $contact_phone = trim($_POST['contact_phone'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $price = $_POST['price'] ?? 0;
        
        // Validate required fields
        if (empty($title) || empty($description) || empty($start_date) || empty($location) || empty($organizer) || empty($contact_email)) {
            throw new Exception('All required fields must be filled.');
        }
        
        if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address.');
        }
        
        // Create event suggestion
        if ($db->createEventSuggestion($title, $description, $start_date, $end_date, $location, $category_id, $province_id, $organizer, $contact_email, $contact_phone, $website, $price)) {
            $success = true;
            // Reset form
            $_POST = [];
        } else {
            $error = 'Error sending suggestion. Please try again later.';
        }
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest an Event - Passione Calabria</title>
    <meta name="description" content="Share an event from Calabria with our community. Festivals, fairs, concerts, exhibitions and much more to discover.">

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

    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="breadcrumb">
                <span class="breadcrumb-item"><a href="index.php" class="text-blue-600 hover:text-blue-700">Home</a></span>
                <span class="breadcrumb-item text-gray-900 font-medium">Suggest Event</span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">🎉</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Suggest an Event
            </h1>
            <p class="text-xl md:text-2xl text-yellow-100 mb-6">
                Share a Calabrian event with us
            </p>
            <p class="text-lg text-orange-100 max-w-3xl mx-auto">
                Have you discovered a festival, fair, concert or event that deserves to be known? 
                Help us enrich our calendar of Calabrian events!
            </p>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <?php if ($success): ?>
            <div class="mb-8 bg-green-100 border-l-4 border-green-500 text-green-700 p-6 rounded-r-lg">
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-6 h-6 mr-3 text-green-500"></i>
                    <div>
                        <h3 class="text-lg font-semibold">Suggestion Sent Successfully!</h3>
                        <p class="mt-1">Thank you for sharing this event with us. Our team will review it and publish it soon if deemed suitable.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="mb-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-r-lg">
                <div class="flex items-center">
                    <i data-lucide="alert-circle" class="w-6 h-6 mr-3 text-red-500"></i>
                    <div>
                        <h3 class="text-lg font-semibold">Error</h3>
                        <p class="mt-1"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form action="" method="POST" class="space-y-6">
                    
                    <!-- Event Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="calendar" class="w-6 h-6 mr-3 text-amber-600"></i>
                            Event Information
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Event Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" id="title" required
                                       value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="e.g. 'Nduja Festival">
                            </div>
                            
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category
                                </label>
                                <select name="category_id" id="category_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                    <option value="">Select category</option>
                                    <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo isset($_POST['category_id']) && $_POST['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Event Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="4" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                      placeholder="Describe the event, its special features, what to expect..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- Date and Location -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="map-pin" class="w-6 h-6 mr-3 text-amber-600"></i>
                            Date and Location
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="start_date" id="start_date" required
                                       value="<?php echo htmlspecialchars($_POST['start_date'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Date
                                </label>
                                <input type="datetime-local" name="end_date" id="end_date"
                                       value="<?php echo htmlspecialchars($_POST['end_date'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="province_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Province
                                </label>
                                <select name="province_id" id="province_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                                    <option value="">Select province</option>
                                    <?php foreach ($provinces as $province): ?>
                                    <option value="<?php echo $province['id']; ?>" <?php echo isset($_POST['province_id']) && $_POST['province_id'] == $province['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($province['name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                    Location/Address <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="location" id="location" required
                                       value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="e.g. Piazza del Popolo, Tropea (VV)">
                            </div>
                            
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Price (€)
                                </label>
                                <input type="number" name="price" id="price" step="0.01" min="0"
                                       value="<?php echo htmlspecialchars($_POST['price'] ?? '0'); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="0.00">
                                <p class="mt-1 text-sm text-gray-500">Enter 0 if the event is free</p>
                            </div>
                        </div>
                    </div>

                    <!-- Organizer Contacts -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                            <i data-lucide="user" class="w-6 h-6 mr-3 text-amber-600"></i>
                            Organizer Contact
                        </h2>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="organizer" class="block text-sm font-medium text-gray-700 mb-2">
                                    Organizer Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="organizer" id="organizer" required
                                       value="<?php echo htmlspecialchars($_POST['organizer'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="Name of the organizer">
                            </div>
                            
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contact Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="contact_email" id="contact_email" required
                                       value="<?php echo htmlspecialchars($_POST['contact_email'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="email@example.com">
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone
                                </label>
                                <input type="tel" name="contact_phone" id="contact_phone"
                                       value="<?php echo htmlspecialchars($_POST['contact_phone'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="+39 xxx xxxxxxx">
                            </div>
                            
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                                    Website
                                </label>
                                <input type="url" name="website" id="website"
                                       value="<?php echo htmlspecialchars($_POST['website'] ?? ''); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="https://www.event.com">
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-amber-50 p-6 rounded-lg border border-amber-200">
                        <h3 class="flex items-center text-lg font-semibold text-amber-800 mb-3">
                            <i data-lucide="info" class="w-5 h-5 mr-2"></i>
                            Important Notes
                        </h3>
                        <ul class="text-amber-700 text-sm space-y-2">
                            <li>• Your suggestion will be reviewed before publication</li>
                            <li>• We reserve the right to modify or reject events that do not comply with our guidelines</li>
                            <li>• Events must be real and verifiable</li>
                            <li>• You will receive an email confirmation once the event is published</li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center pt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-full font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            Send Suggestion
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-br from-blue-600 to-amber-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Have Other Suggestions?
            </h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                Besides events, you can also suggest interesting places, typical restaurants or unique experiences in Calabria.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="suggest.php" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                    <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                    Suggest a Place
                </a>
                <a href="index.php" class="inline-flex items-center px-6 py-3 bg-transparent border-2 border-white text-white rounded-full font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    <i data-lucide="home" class="w-5 h-5 mr-2"></i>
                    Back to Homepage
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="../assets/js/main.js"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Auto-set end date when start date changes (optional)
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDateInput = document.getElementById('end_date');
            
            if (!endDateInput.value) {
                const endDate = new Date(startDate);
                endDate.setHours(startDate.getHours() + 3); // Default: 3 hours later
                
                const year = endDate.getFullYear();
                const month = String(endDate.getMonth() + 1).padStart(2, '0');
                const day = String(endDate.getDate()).padStart(2, '0');
                const hours = String(endDate.getHours()).padStart(2, '0');
                const minutes = String(endDate.getMinutes()).padStart(2, '0');
                
                endDateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            }
        });

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

        document.querySelectorAll('.bg-white, .bg-amber-50').forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>