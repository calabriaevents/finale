<?php
// Determina la pagina corrente
$currentPage = basename($_SERVER['PHP_SELF']);

// Definisci le voci di menu
$menuItems = [
    ['file' => 'index.php', 'icon' => 'home', 'label' => 'Dashboard'],
    ['file' => 'gestione-home.php', 'icon' => 'layout', 'label' => 'Gestione Home'],
    ['file' => 'articoli.php', 'icon' => 'file-text', 'label' => 'Articoli'],
    ['file' => 'categorie.php', 'icon' => 'tags', 'label' => 'Categorie'],
    ['file' => 'province.php', 'icon' => 'map', 'label' => 'Province'],
    ['file' => 'citta.php', 'icon' => 'map-pin', 'label' => 'Città'],
    ['file' => 'commenti.php', 'icon' => 'message-square', 'label' => 'Commenti'],
    ['file' => 'foto-utenti.php', 'icon' => 'image', 'label' => 'Foto Utenti'],
    ['file' => 'suggerimenti-luoghi.php', 'icon' => 'map-pin', 'label' => 'Suggerimenti Luoghi'],
    ['file' => 'business.php', 'icon' => 'building-2', 'label' => 'Business'],
    ['file' => 'gestione-pacchetti.php', 'icon' => 'package', 'label' => 'Pacchetti Abbonamento'],
    ['file' => 'consumo-pacchetti.php', 'icon' => 'zap', 'label' => 'Pacchetti a Consumo'],
    ['file' => 'abbonamenti.php', 'icon' => 'credit-card', 'label' => 'Abbonamenti'],
    ['file' => 'utenti.php', 'icon' => 'users', 'label' => 'Utenti'],
    ['file' => 'database.php', 'icon' => 'database', 'label' => 'Monitoraggio DB'],
    ['file' => 'impostazioni.php', 'icon' => 'settings', 'label' => 'Impostazioni'],
];
?>
<nav class="flex-1 p-4 overflow-y-auto">
    <ul class="space-y-2">
        <?php foreach ($menuItems as $item): ?>
            <?php
                $isActive = ($currentPage === $item['file']);
                $class = 'flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors';
                if ($isActive) {
                    $class .= ' bg-gray-700 text-white';
                } else {
                    $class .= ' hover:bg-gray-700';
                }
            ?>
            <li>
                <a href="<?php echo $item['file']; ?>" class="<?php echo $class; ?>">
                    <i data-lucide="<?php echo $item['icon']; ?>" class="w-5 h-5"></i>
                    <span><?php echo $item['label']; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
