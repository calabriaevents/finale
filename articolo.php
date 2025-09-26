<?php
require_once 'includes/config.php';
require_once 'includes/database_mysql.php';

if (!isset($_GET['slug'])) {
    header('Location: index.php');
    exit;
}

$slug = $_GET['slug'];
$db = new Database();
$article = $db->getArticleBySlug($slug);

if (!$article) {
    header('HTTP/1.0 404 Not Found');
    echo 'Articolo non trovato';
    exit;
}

$db->incrementArticleViews($article['id']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Passione Calabria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>

    <?php
    // --- Template Dispatcher ---
    // Check the category NAME to be robust, instead of a hard-coded ID.
    $category_name = trim($article['category_name'] ?? '');

    if ($category_name === 'Hotel e Alloggi') {
        include 'templates/view_hotel.php';
    } else if ($category_name === 'Ristorazione') {
        include 'templates/view_ristorazione.php';
    } else if ($category_name === 'Stabilimenti Balneari') {
        include 'templates/view_stabilimenti.php';
    } else if ($category_name === 'Arte e Cultura') {
        include 'templates/view_arte_cultura.php';
    } else if ($category_name === 'Musei e Gallerie') {
        include 'templates/view_musei_gallerie.php';
    } else if ($category_name === 'Patrimonio Storico') {
        include 'templates/view_patrimonio_storico.php';
    } else if ($category_name === 'Piazze e Vie Storiche') {
        include 'templates/view_piazze_vie_storiche.php';
    } else if ($category_name === 'Siti Archeologici') {
        include 'templates/view_siti_archeologici.php';
    } else if ($category_name === 'Chiese e Santuari') {
        include 'templates/view_chiese_santuari.php';
    } else if ($category_name === 'Teatri e Anfiteatri') {
        include 'templates/view_teatri_anfiteatri.php';
    } else if ($category_name === 'Parchi e Aree Verdi') {
        include 'templates/view_parchi_aree_verdi.php';
    } else if ($category_name === 'Attività Sportive e Avventura') {
        include 'templates/view_attivita_sportive_avventura.php';
    } else if ($category_name === 'Itinerari Tematici') {
        include 'templates/view_itinerari_tematici.php';
    } else if ($category_name === 'Tour e Guide') {
        include 'templates/view_tour_guide.php';
    } else if ($category_name === 'Shopping e Artigianato') {
        include 'templates/view_shopping_artigianato.php';
    } else if ($category_name === 'Benessere e Relax') {
        include 'templates/view_benessere_relax.php';
    } else if ($category_name === 'Trasporti') {
        include 'templates/view_trasporti.php';
    } else {
        // Fallback to a default template for all other categories
        include 'templates/view_default.php';
    }
    ?>

    <?php include 'includes/footer.php'; ?>
    
    <!-- Include User Upload Modal -->
    <?php include 'partials/user-upload-modal.php'; ?>
    
    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script>
        lucide.createIcons();

        // Initialize UserUploadModal
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof UserUploadModal !== 'undefined') {
                UserUploadModal.init();
            }
        });
    </script>
</body>
</html>
