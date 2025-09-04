<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/ContentManagerSimple.php';

// Initialize database
$db = new Database();
$contentManager = new ContentManagerSimple($db);
$currentLang = 'en'; // Force English language

// Get packages and other data
$packages = [];
$categories = [];
$provinces = [];

try {
    $stmt = $db->pdo->prepare('SELECT * FROM business_packages WHERE is_active = 1 ORDER BY sort_order ASC');
    $stmt->execute();
    $packages = $stmt->fetchAll();
    
    $stmt = $db->pdo->prepare('SELECT * FROM categories ORDER BY name ASC');
    $stmt->execute();
    $categories = $stmt->fetchAll();
    
    $stmt = $db->pdo->prepare('SELECT * FROM provinces ORDER BY name ASC');
    $stmt->execute();
    $provinces = $stmt->fetchAll();
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Get selected package from URL
$selectedPackageId = $_GET['package'] ?? null;
$selectedPackage = null;
if ($selectedPackageId) {
    foreach ($packages as $package) {
        if ($package['id'] == $selectedPackageId) {
            $selectedPackage = $package;
            break;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Business - Passione Calabria</title>
    <meta name="description" content="Register your business on Passione Calabria and reach thousands of tourists in Calabria. Choose from our affordable packages and grow your visibility.">

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
                <span class="breadcrumb-item text-gray-900 font-medium">Register Business</span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">🏢</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Register Your Business
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-8">
                Join the Passione Calabria community and connect with thousands of tourists visiting our beautiful region. Showcase your business and grow your customer base.
            </p>
            
            <?php if ($selectedPackage): ?>
            <div class="mt-6 inline-flex items-center bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-full">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                Selected Package: <strong class="ml-1"><?php echo htmlspecialchars($selectedPackage['name']); ?></strong>
                <?php if ($selectedPackage['price'] > 0): ?>
                - €<?php echo number_format($selectedPackage['price'], 2); ?>/year
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Benefits Section -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Why Choose Passione Calabria?</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-4xl mb-3">👥</div>
                    <h3 class="font-bold text-gray-900 mb-2">Targeted Audience</h3>
                    <p class="text-gray-600 text-sm">Reach tourists and locals interested in authentic Calabrian experiences</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">📱</div>
                    <h3 class="font-bold text-gray-900 mb-2">Mobile Optimized</h3>
                    <p class="text-gray-600 text-sm">Your business profile looks perfect on all devices and screens</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-3">🌍</div>
                    <h3 class="font-bold text-gray-900 mb-2">Multi-language</h3>
                    <p class="text-gray-600 text-sm">Reach international visitors with our multi-language platform</p>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <form id="business-registration-form" class="space-y-8">
            <!-- Business Information -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i data-lucide="building-2" class="w-6 h-6 text-blue-600 mr-3"></i>
                    Business Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name *</label>
                        <input type="text" id="business_name" name="business_name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Your business name">
                    </div>

                    <div>
                        <label for="business_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" id="business_email" name="business_email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="info@yourbusiness.com">
                    </div>

                    <div>
                        <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" id="business_phone" name="business_phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="+39 123 456 7890">
                    </div>

                    <div>
                        <label for="business_website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" id="business_website" name="business_website"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://yourbusiness.com">
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category_id" name="category_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="province_id" class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                        <select id="province_id" name="province_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a province</option>
                            <?php foreach ($provinces as $province): ?>
                            <option value="<?php echo $province['id']; ?>"><?php echo htmlspecialchars($province['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" id="business_address" name="business_address"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Street, Number, City">
                    </div>

                    <div class="md:col-span-2">
                        <label for="business_description" class="block text-sm font-medium text-gray-700 mb-2">Business Description</label>
                        <textarea id="business_description" name="business_description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe your business, the services you offer and what makes you unique..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Package Selection -->
            <?php if (!$selectedPackageId): ?>
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i data-lucide="package" class="w-6 h-6 text-purple-600 mr-3"></i>
                    Choose Your Plan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($packages as $package): 
                        $features = json_decode($package['features'], true) ?: [];
                    ?>
                    <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 transition-colors cursor-pointer package-option" data-package-id="<?php echo $package['id']; ?>">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($package['name']); ?></h3>
                            <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($package['description']); ?></p>
                        </div>

                        <div class="text-center mb-6">
                            <?php if ($package['price'] == 0): ?>
                            <div class="text-3xl font-bold text-gray-900">Free</div>
                            <?php else: ?>
                            <div class="text-3xl font-bold text-gray-900">
                                €<?php echo number_format($package['price'], 2); ?>
                                <span class="text-sm font-normal text-gray-500">/year</span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <ul class="space-y-2 mb-6">
                            <?php foreach (array_slice($features, 0, 4) as $feature): ?>
                            <li class="flex items-start text-sm">
                                <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                <span class="text-gray-700"><?php echo htmlspecialchars($feature); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                        <input type="radio" name="selected_package" value="<?php echo $package['id']; ?>" class="hidden package-radio">
                        <div class="text-center">
                            <div class="package-selected-indicator hidden bg-blue-600 text-white py-2 px-4 rounded-lg font-medium">
                                ✓ Selected
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <input type="hidden" name="selected_package" value="<?php echo $selectedPackageId; ?>">
            <?php endif; ?>

            <!-- Terms and Submit -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" id="accept_terms" name="accept_terms" required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                        <span class="ml-3 text-sm text-gray-700">
                            I accept the <a href="terms-of-service.php" class="text-blue-600 hover:text-blue-700 underline" target="_blank">Terms of Service</a> 
                            and <a href="privacy-policy.php" class="text-blue-600 hover:text-blue-700 underline" target="_blank">Privacy Policy</a> *
                        </span>
                    </label>
                </div>

                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" id="marketing_consent" name="marketing_consent"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                        <span class="ml-3 text-sm text-gray-700">
                            I consent to receive marketing communications and service updates (optional)
                        </span>
                    </label>
                </div>

                <div class="text-center">
                    <button type="submit" id="submit-button" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i data-lucide="credit-card" class="w-5 h-5 mr-2 inline"></i>
                        <span id="button-text">Complete Registration</span>
                    </button>
                    
                    <p class="text-sm text-gray-500 mt-4">
                        <i data-lucide="shield-check" class="w-4 h-4 mr-1 inline"></i>
                        Secure payments via Stripe. No hidden costs.
                    </p>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Modal -->
    <div id="loading-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg text-center max-w-sm mx-4">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Processing...</h3>
            <p class="text-sm text-gray-600" id="loading-message">We're processing your request...</p>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg text-center max-w-md mx-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check" class="w-8 h-8 text-green-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Registration Complete!</h3>
            <p class="text-gray-600 mb-6" id="success-message">Your business has been successfully registered.</p>
            <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                Return to Homepage
            </a>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="../assets/js/main.js"></script>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Package selection handler
        document.querySelectorAll('.package-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove selection from all packages
                document.querySelectorAll('.package-option').forEach(pkg => {
                    pkg.classList.remove('border-blue-500', 'bg-blue-50');
                    pkg.querySelector('.package-selected-indicator').classList.add('hidden');
                    pkg.querySelector('.package-radio').checked = false;
                });
                
                // Select current package
                this.classList.add('border-blue-500', 'bg-blue-50');
                this.querySelector('.package-selected-indicator').classList.remove('hidden');
                this.querySelector('.package-radio').checked = true;
                
                updateSubmitButton();
            });
        });
        
        function updateSubmitButton() {
            const selectedPackage = document.querySelector('input[name="selected_package"]:checked');
            const submitButton = document.getElementById('submit-button');
            const buttonText = document.getElementById('button-text');
            
            if (!selectedPackage) {
                buttonText.textContent = 'Select a Plan';
                return;
            }
            
            // Find package data
            const packageId = selectedPackage.value;
            const packages = <?php echo json_encode($packages); ?>;
            const pkg = packages.find(p => p.id == packageId);
            
            if (pkg) {
                if (pkg.price == 0) {
                    buttonText.textContent = 'Register for Free';
                } else {
                    buttonText.textContent = `Proceed to Payment (€${parseFloat(pkg.price).toFixed(2)})`;
                }
            }
        }
        
        // Form submission
        document.getElementById('business-registration-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = document.getElementById('submit-button');
            const loadingModal = document.getElementById('loading-modal');
            const loadingMessage = document.getElementById('loading-message');
            
            // Validate form
            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }
            
            const selectedPackageInput = document.querySelector('input[name="selected_package"]:checked') || document.querySelector('input[name="selected_package"][type="hidden"]');
            if (!selectedPackageInput) {
                alert('Please select a plan before continuing.');
                return;
            }
            
            // Prepare data
            const formData = new FormData(this);
            const businessData = {
                name: formData.get('business_name'),
                email: formData.get('business_email'),
                phone: formData.get('business_phone'),
                website: formData.get('business_website'),
                description: formData.get('business_description'),
                category_id: formData.get('category_id') || null,
                province_id: formData.get('province_id') || null,
                address: formData.get('business_address')
            };
            
            // Show loading
            submitButton.disabled = true;
            loadingModal.classList.remove('hidden');
            loadingMessage.textContent = 'Processing registration...';
            
            try {
                const response = await fetch('../api/stripe-checkout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        package_id: selectedPackageInput.value,
                        business_data: businessData
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    if (result.type === 'free') {
                        // Free registration completed
                        loadingModal.classList.add('hidden');
                        document.getElementById('success-message').textContent = 'Your free registration has been completed successfully!';
                        document.getElementById('success-modal').classList.remove('hidden');
                    } else if (result.type === 'paid') {
                        // Redirect to Stripe Checkout
                        loadingMessage.textContent = 'Redirecting to Stripe...';
                        window.location.href = result.checkout_url;
                    }
                } else {
                    throw new Error(result.message || 'Registration error');
                }
                
            } catch (error) {
                loadingModal.classList.add('hidden');
                submitButton.disabled = false;
                alert('Error: ' + error.message);
                console.error('Registration error:', error);
            }
        });
        
        // Initialize on page load
        updateSubmitButton();
        
        // Auto-select package if provided in URL
        <?php if ($selectedPackageId): ?>
        const preSelectedRadio = document.querySelector('input[name="selected_package"][value="<?php echo $selectedPackageId; ?>"]');
        if (preSelectedRadio) {
            preSelectedRadio.checked = true;
        }
        updateSubmitButton();
        <?php endif; ?>

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