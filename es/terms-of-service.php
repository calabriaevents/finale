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
    <title>Terms of Service - Passione Calabria</title>
    <meta name="description" content="Terms of Service for Passione Calabria. Read the terms and conditions that govern your use of our website and services.">
    
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
                <span class="breadcrumb-item text-gray-900 font-medium">Terms of Service</span>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 via-teal-500 to-yellow-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">📋</div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Terms of Service
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                These terms and conditions govern your use of our website and services. Please read them carefully before using our platform.
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
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                            <p class="text-gray-700 mb-4">
                                Welcome to Passione Calabria ("we", "our", "us"). These Terms of Service ("Terms") govern your use of our website located at passionecalabria.it (the "Service") operated by Passione Calabria.
                            </p>
                            <p class="text-gray-700">
                                By accessing or using our Service, you agree to be bound by these Terms. If you disagree with any part of these terms, then you may not access the Service.
                            </p>
                        </section>

                        <!-- Description of Service -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Description of Service</h2>
                            <p class="text-gray-700 mb-4">
                                Passione Calabria is a web platform dedicated to promoting the culture, tourism, and traditions of the Calabria region in Italy. Our Service includes:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>Travel and tourism information about Calabria</li>
                                <li>Articles about local culture, history, and traditions</li>
                                <li>City and province guides</li>
                                <li>Interactive maps and location information</li>
                                <li>User-generated content and suggestions</li>
                                <li>Newsletter and communication services</li>
                            </ul>
                        </section>

                        <!-- User Accounts -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">3. User Accounts and Registration</h2>
                            <p class="text-gray-700 mb-4">
                                While many features of our Service are available without registration, some features may require you to create an account. When you create an account:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 mb-4 space-y-1">
                                <li>You must provide accurate and complete information</li>
                                <li>You are responsible for maintaining the security of your account</li>
                                <li>You must notify us immediately of any unauthorized use</li>
                                <li>You are responsible for all activities that occur under your account</li>
                            </ul>
                            <p class="text-gray-700">
                                We reserve the right to suspend or terminate accounts that violate these Terms or engage in harmful activities.
                            </p>
                        </section>

                        <!-- User Content -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">4. User Content and Conduct</h2>
                            
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">4.1 Content Submission</h3>
                            <p class="text-gray-700 mb-4">
                                Users may submit content including comments, suggestions, photos, and other materials. By submitting content, you:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 mb-4 space-y-1">
                                <li>Grant us a non-exclusive license to use, modify, and display your content</li>
                                <li>Represent that you own or have the right to submit the content</li>
                                <li>Agree that your content does not violate any laws or third-party rights</li>
                                <li>Understand that we may remove content at our discretion</li>
                            </ul>

                            <h3 class="text-xl font-semibold text-gray-800 mb-3">4.2 Prohibited Content</h3>
                            <p class="text-gray-700 mb-4">Users may not submit content that:</p>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>Is illegal, harmful, threatening, abusive, or defamatory</li>
                                <li>Violates copyright, trademark, or other intellectual property rights</li>
                                <li>Contains spam, advertisements, or promotional material</li>
                                <li>Is sexually explicit, pornographic, or inappropriate</li>
                                <li>Promotes discrimination, hate speech, or violence</li>
                                <li>Contains malware, viruses, or malicious code</li>
                            </ul>
                        </section>

                        <!-- Intellectual Property -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Intellectual Property Rights</h2>
                            
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">5.1 Our Content</h3>
                            <p class="text-gray-700 mb-4">
                                The Service and its original content, features, and functionality are and will remain the exclusive property of Passione Calabria. The Service is protected by copyright, trademark, and other laws.
                            </p>

                            <h3 class="text-xl font-semibold text-gray-800 mb-3">5.2 Third-Party Content</h3>
                            <p class="text-gray-700 mb-4">
                                Our Service may contain links to third-party websites or services. We do not own or control these third-party resources and are not responsible for their content or practices.
                            </p>

                            <h3 class="text-xl font-semibold text-gray-800 mb-3">5.3 Copyright Infringement</h3>
                            <p class="text-gray-700">
                                We respect intellectual property rights and will respond to notices of alleged copyright infringement. If you believe your copyrighted work has been infringed, please contact us with detailed information.
                            </p>
                        </section>

                        <!-- Privacy and Data -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Privacy and Data Protection</h2>
                            <p class="text-gray-700 mb-4">
                                Your privacy is important to us. Our Privacy Policy explains how we collect, use, and protect your information when you use our Service. By using our Service, you agree to the collection and use of information in accordance with our Privacy Policy.
                            </p>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i data-lucide="shield-check" class="w-5 h-5 text-blue-600 mt-1 mr-3"></i>
                                    <div class="text-blue-800">
                                        <p class="font-semibold mb-1">GDPR Compliance</p>
                                        <p class="text-sm">We comply with the General Data Protection Regulation (GDPR) and other applicable data protection laws. You have rights regarding your personal data, including access, correction, and deletion.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Service Availability -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Service Availability and Modifications</h2>
                            <p class="text-gray-700 mb-4">
                                We strive to maintain continuous service availability, but we do not guarantee that:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 mb-4 space-y-1">
                                <li>The Service will always be available or uninterrupted</li>
                                <li>The Service will be free of errors or defects</li>
                                <li>Any content will be accurate or up-to-date</li>
                                <li>The Service will meet your specific requirements</li>
                            </ul>
                            <p class="text-gray-700">
                                We reserve the right to modify, suspend, or discontinue the Service at any time, with or without notice.
                            </p>
                        </section>

                        <!-- Disclaimers and Limitation of Liability -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Disclaimers and Limitation of Liability</h2>
                            
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">8.1 Disclaimers</h3>
                            <p class="text-gray-700 mb-4">
                                THE SERVICE IS PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS. WE DISCLAIM ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.
                            </p>

                            <h3 class="text-xl font-semibold text-gray-800 mb-3">8.2 Limitation of Liability</h3>
                            <p class="text-gray-700">
                                IN NO EVENT SHALL PASSIONE CALABRIA BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, OR ANY LOSS OF PROFITS OR REVENUES, WHETHER INCURRED DIRECTLY OR INDIRECTLY.
                            </p>
                        </section>

                        <!-- Travel and Tourism Information -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Travel and Tourism Information</h2>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 mt-1 mr-3"></i>
                                    <div class="text-yellow-800">
                                        <p class="font-semibold mb-1">Important Notice</p>
                                        <p class="text-sm">Travel information provided on our Service is for general guidance only. Always verify current conditions, opening hours, and travel requirements before visiting any destination.</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700">
                                We provide travel and tourism information as a service to our users, but we are not responsible for:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 space-y-1">
                                <li>Changes in business hours, closures, or availability</li>
                                <li>Accuracy of third-party information</li>
                                <li>Safety conditions at destinations</li>
                                <li>Travel restrictions or requirements</li>
                                <li>Quality of services at recommended locations</li>
                            </ul>
                        </section>

                        <!-- Indemnification -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Indemnification</h2>
                            <p class="text-gray-700">
                                You agree to defend, indemnify, and hold harmless Passione Calabria and its officers, directors, employees, and agents from and against any claims, damages, obligations, losses, liabilities, costs, or debt arising from your use of the Service or violation of these Terms.
                            </p>
                        </section>

                        <!-- Termination -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Termination</h2>
                            <p class="text-gray-700 mb-4">
                                We may terminate or suspend your account and access to the Service immediately, without prior notice, for any reason including:
                            </p>
                            <ul class="list-disc list-inside text-gray-700 mb-4 space-y-1">
                                <li>Breach of these Terms</li>
                                <li>Harmful or illegal activity</li>
                                <li>At our sole discretion</li>
                            </ul>
                            <p class="text-gray-700">
                                Upon termination, your right to use the Service will cease immediately. Provisions that by their nature should survive termination will remain in effect.
                            </p>
                        </section>

                        <!-- Governing Law -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Governing Law and Jurisdiction</h2>
                            <p class="text-gray-700">
                                These Terms shall be interpreted and governed by the laws of Italy. Any disputes arising from these Terms or use of the Service shall be resolved in the courts of Catanzaro, Italy.
                            </p>
                        </section>

                        <!-- Changes to Terms -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">13. Changes to Terms</h2>
                            <p class="text-gray-700">
                                We reserve the right to modify or replace these Terms at any time. If we make material changes, we will provide notice on our website or by email. Your continued use of the Service after changes become effective constitutes acceptance of the new Terms.
                            </p>
                        </section>

                        <!-- Severability -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">14. Severability and Entire Agreement</h2>
                            <p class="text-gray-700 mb-4">
                                If any provision of these Terms is found to be unenforceable, the remaining provisions will remain in full force and effect. These Terms constitute the entire agreement between you and Passione Calabria regarding use of the Service.
                            </p>
                        </section>

                        <!-- Contact Information -->
                        <section class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">15. Contact Information</h2>
                            <p class="text-gray-700 mb-4">
                                If you have questions about these Terms, please contact us:
                            </p>
                            
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <i data-lucide="mail" class="w-5 h-5 text-blue-600 mr-3"></i>
                                        <span class="text-gray-700">Email: legal@passionecalabria.it</span>
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
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
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

        // Add table of contents if needed (for better navigation on long terms)
        function createTableOfContents() {
            const sections = document.querySelectorAll('h2');
            if (sections.length > 5) {
                // Create TOC only if there are many sections
                const toc = document.createElement('div');
                toc.className = 'bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8';
                toc.innerHTML = '<h3 class="font-semibold text-blue-900 mb-3">Table of Contents</h3>';
                
                const tocList = document.createElement('ul');
                tocList.className = 'list-disc list-inside text-blue-800 space-y-1 text-sm';
                
                sections.forEach((section, index) => {
                    const id = `section-${index}`;
                    section.id = id;
                    
                    const listItem = document.createElement('li');
                    const link = document.createElement('a');
                    link.href = `#${id}`;
                    link.textContent = section.textContent;
                    link.className = 'hover:text-blue-900 hover:underline';
                    listItem.appendChild(link);
                    tocList.appendChild(listItem);
                });
                
                toc.appendChild(tocList);
                
                // Insert TOC after the last updated notice
                const lastUpdated = document.querySelector('.bg-blue-50');
                if (lastUpdated) {
                    lastUpdated.parentNode.insertBefore(toc, lastUpdated.nextSibling);
                }
            }
        }
        
        // Initialize TOC if needed
        createTableOfContents();
    </script>
</body>
</html>