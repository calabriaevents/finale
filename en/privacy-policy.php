<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/ContentManagerSimple.php';

$db = new Database();
$contentManager = new ContentManagerSimple($db);
$currentLang = 'en'; // Force English language
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Passione Calabria</title>
    <meta name="description" content="Privacy Policy of Passione Calabria. Learn how we collect, use, and protect your personal information when you use our website.">
    
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
                <span class="breadcrumb-item text-gray-900 font-medium">Privacy Policy</span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">🔒</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Privacy Policy
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                We value your privacy and are committed to protecting your personal information. Learn how we collect, use, and safeguard your data.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    
                    <!-- Last Updated -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                        <div class="flex items-center">
                            <i data-lucide="calendar" class="w-5 h-5 text-blue-600 mr-2"></i>
                            <p class="text-blue-800"><strong>Last Updated:</strong> December 2024</p>
                        </div>
                    </div>

                    <div class="prose prose-lg max-w-none">
                        
                        <!-- Introduction -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                            <p class="text-gray-700 mb-4">
                                Welcome to Passione Calabria ("we", "our", "us"). This Privacy Policy explains how we collect, use, store and protect your personal information when you use our website at passionecalabria.it (the "Service").
                            </p>
                            <p class="text-gray-700">
                                By using our Service, you agree to the collection and use of information in accordance with this policy. We are committed to protecting your privacy and ensuring the security of your personal information.
                            </p>
                        </section>

                        <!-- Information We Collect -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Information We Collect</h2>
                            
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">2.1 Personal Information</h3>
                            <p class="text-gray-700 mb-4">We may collect the following personal information:</p>
                            <ul class="list-disc list-inside text-gray-700 mb-4 space-y-1">
                                <li>Name and contact information (email address, phone number)</li>
                                <li>Information you provide when contacting us or submitting suggestions</li>
                                <li>Comments and feedback you provide on our articles</li>
                                <li>Newsletter subscription preferences</li>
                            </ul>

                            <h3 class="text-xl font-semibold text-gray-800 mb-3">2.2 Automatically Collected Information</h3>
                            <p class="text-gray-700 mb-4">We automatically collect certain information, including:</p>
                            <ul class="list-disc list-inside text-gray-700 mb-4 space-y-1">
                                <li>IP address and browser information</li>
                                <li>Pages viewed and time spent on our website</li>
                                <li>Referral sources and search terms</li>
                                <li>Device information and operating system</li>
                            </ul>

                            <h3 class="text-xl font-semibold text-gray-800 mb-3">2.3 Cookies and Tracking Technologies</h3>
                            <p class="text-gray-700">
                                We use cookies and similar technologies to improve your browsing experience, analyze website traffic, and personalize content. You can control cookie settings through your browser preferences.
                            </p>
                        </section>

                        <!-- How We Use Information -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">3. How We Use Your Information</h2>
                            <p class="text-gray-700 mb-4">We use the collected information for the following purposes:</p>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>To provide and maintain our Service</li>
                                <li>To respond to your comments, questions, and customer service requests</li>
                                <li>To send you newsletters and promotional communications (with your consent)</li>
                                <li>To improve our website and user experience</li>
                                <li>To analyze usage patterns and website performance</li>
                                <li>To detect, prevent, and address technical issues and security breaches</li>
                                <li>To comply with legal obligations</li>
                            </ul>
                        </section>

                        <!-- Information Sharing -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Information Sharing and Disclosure</h2>
                            <p class="text-gray-700 mb-4">
                                We do not sell, trade, or otherwise transfer your personal information to third parties except in the following circumstances:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li><strong>Service Providers:</strong> We may share information with trusted third-party service providers who assist us in operating our website</li>
                                <li><strong>Legal Requirements:</strong> We may disclose information if required by law or to protect our rights and safety</li>
                                <li><strong>Business Transfers:</strong> Information may be transferred in connection with a merger, acquisition, or sale of business assets</li>
                                <li><strong>With Your Consent:</strong> We may share information when you explicitly consent to such disclosure</li>
                            </ul>
                        </section>

                        <!-- Data Security -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Data Security</h2>
                            <p class="text-gray-700 mb-4">
                                We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>SSL encryption for data transmission</li>
                                <li>Secure server infrastructure</li>
                                <li>Regular security audits and updates</li>
                                <li>Access controls and employee training</li>
                            </ul>
                            <p class="text-gray-700 mt-4">
                                However, no method of transmission over the Internet or electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your information, we cannot guarantee absolute security.
                            </p>
                        </section>

                        <!-- Your Rights -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Your Rights and Choices</h2>
                            <p class="text-gray-700 mb-4">You have the following rights regarding your personal information:</p>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li><strong>Access:</strong> Request access to your personal information</li>
                                <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information</li>
                                <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                                <li><strong>Objection:</strong> Object to the processing of your personal information</li>
                                <li><strong>Portability:</strong> Request transfer of your information in a structured format</li>
                                <li><strong>Withdraw Consent:</strong> Withdraw consent for newsletter subscriptions or other optional services</li>
                            </ul>
                            <p class="text-gray-700 mt-4">
                                To exercise these rights, please contact us using the information provided in the Contact section below.
                            </p>
                        </section>

                        <!-- Data Retention -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Data Retention</h2>
                            <p class="text-gray-700">
                                We retain your personal information only for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. When we no longer need your personal information, we will securely delete or anonymize it.
                            </p>
                        </section>

                        <!-- International Transfers -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">8. International Data Transfers</h2>
                            <p class="text-gray-700">
                                Our servers are located in Italy and the European Union. If you are accessing our Service from outside the EU, please be aware that your information may be transferred to, stored, and processed in countries with different privacy laws. We ensure appropriate safeguards are in place for such transfers.
                            </p>
                        </section>

                        <!-- Children's Privacy -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Children's Privacy</h2>
                            <p class="text-gray-700">
                                Our Service is not directed to individuals under the age of 16. We do not knowingly collect personal information from children under 16. If we become aware that we have collected personal information from a child under 16, we will take steps to delete such information.
                            </p>
                        </section>

                        <!-- Changes to Privacy Policy -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Changes to This Privacy Policy</h2>
                            <p class="text-gray-700">
                                We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date. You are advised to review this Privacy Policy periodically for any changes.
                            </p>
                        </section>

                        <!-- Contact Information -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Contact Us</h2>
                            <p class="text-gray-700 mb-4">
                                If you have any questions about this Privacy Policy or our privacy practices, please contact us:
                            </p>
                            
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <i data-lucide="mail" class="w-5 h-5 text-blue-600 mr-3"></i>
                                        <span class="text-gray-700">Email: privacy@passionecalabria.it</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-lucide="map-pin" class="w-5 h-5 text-blue-600 mr-3"></i>
                                        <span class="text-gray-700">Address: Via Roma, 123 - 88100 Catanzaro (CZ), Italy</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-lucide="globe" class="w-5 h-5 text-blue-600 mr-3"></i>
                                        <span class="text-gray-700">Website: www.passionecalabria.it</span>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 text-sm mt-4">
                                We will respond to your inquiry within 30 days of receiving your request.
                            </p>
                        </section>

                        <!-- GDPR Compliance -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">12. GDPR Compliance</h2>
                            <p class="text-gray-700">
                                If you are a resident of the European Economic Area (EEA), you have certain data protection rights under the General Data Protection Regulation (GDPR). This Privacy Policy describes how we collect, use, and protect your personal information in compliance with GDPR requirements.
                            </p>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="../assets/js/main.js"></script>
    <script>
        lucide.createIcons();

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
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

        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>