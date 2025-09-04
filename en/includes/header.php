<?php
// Header completamente riscritto per evitare problemi di scomparsa
require_once __DIR__ . '/../../includes/language_switcher.php';
?>
<!-- Header Fisso - Riscrittura Completa -->
<header id="main-header" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; z-index: 999999 !important; background: linear-gradient(to right, #2563eb, #14b8a6, #f59e0b) !important; display: block !important; visibility: visible !important; opacity: 1 !important;" class="relative z-[999] text-white shadow-lg"
    <!-- Top Bar -->
    <div style="background: rgba(0, 0, 0, 0.1); padding: 0.5rem 0;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-2">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>Scopri la Calabria</span>
                </div>
                
                <!-- Language Flags - Dynamic -->
                <?php echo renderLanguageSwitcher($current_lang, $current_file, $base_path); ?>
                
                <div class="hidden sm:block">
                    <span>Benvenuto in Passione Calabria</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav style="position: relative;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="index.php" class="flex items-center space-x-3">
                        <div style="width: 3rem; height: 3rem; background: linear-gradient(to right, #3b82f6, #f59e0b); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <span class="text-white font-bold text-lg">PC</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">
                                Passione <span style="color: #fde047;">Calabria</span>
                            </h1>
                            <p style="color: #bfdbfe; font-size: 0.875rem;">La tua guida alla Calabria</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links - Desktop -->
                <div class="hidden lg:flex items-center justify-center flex-1">
                    <div class="flex items-center space-x-8">
                        <a href="index.php" style="color: white; text-decoration: none; font-weight: 500; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Home</a>
                        <a href="categories.php" style="color: white; text-decoration: none; font-weight: 500; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Categories</a>
                        <a href="provinces.php" style="color: white; text-decoration: none; font-weight: 500; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Provinces</a>
                        <a href="map.php" style="color: white; text-decoration: none; font-weight: 500; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Map</a>
                        <a href="iscrivi-attivita.php" style="background: #f59e0b; color: white; padding: 0.5rem 1rem; border-radius: 9999px; text-decoration: none; font-weight: 500; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#d97706'" onmouseout="this.style.backgroundColor='#f59e0b'">Iscrivi la tua attività</a>
                        <a href="../admin/" style="background: #dc2626; color: white; padding: 0.5rem 1rem; border-radius: 9999px; text-decoration: none; font-weight: 500; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#b91c1c'" onmouseout="this.style.backgroundColor='#dc2626'">Admin</a>
                    </div>
                </div>
                
                <!-- Spacer for centering -->
                <div class="hidden lg:block" style="width: 8rem;"></div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden" style="padding: 0.5rem; background: transparent; border: none; color: white; cursor: pointer;">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden" style="background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(4px);">
            <div style="padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="index.php" style="color: white; text-decoration: none; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Home</a>
                <a href="categories.php" style="color: white; text-decoration: none; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Categories</a>
                <a href="provinces.php" style="color: white; text-decoration: none; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Provinces</a>
                <a href="map.php" style="color: white; text-decoration: none; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Map</a>
                <a href="iscrivi-attivita.php" style="color: white; text-decoration: none; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Iscrivi la tua attività</a>
                <a href="../admin/" style="color: white; text-decoration: none; padding: 0.5rem 0; transition: color 0.2s;" onmouseover="this.style.color='#fde047'" onmouseout="this.style.color='white'">Admin</a>
            </div>
        </div>
    </nav>
</header>

<!-- Spacer per compensare header fisso -->
<div id="header-spacer" style="height: 120px;"></div>

<!-- JavaScript per funzionalità header -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
        });
        
        // Chiudi menu mobile cliccando fuori
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }
    
    // Assicura che header rimanga sempre visibile
    const header = document.getElementById('main-header');
    if (header) {
        header.style.display = 'block';
        header.style.visibility = 'visible';
        header.style.opacity = '1';
    }
});

// Proteggi header da qualsiasi interferenza
setInterval(function() {
    const header = document.getElementById('main-header');
    if (header) {
        if (header.style.display === 'none' || header.style.visibility === 'hidden' || header.style.opacity === '0') {
            header.style.display = 'block';
            header.style.visibility = 'visible';
            header.style.opacity = '1';
        }
    }
}, 100);
</script>