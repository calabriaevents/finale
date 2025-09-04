<?php
/**
 * NUOVO SISTEMA TRADUZIONE STATICO - Admin Interface
 * 
 * Sistema file statici con download/upload intelligente
 * Sostituisce il vecchio sistema database + DeepL
 */

require_once '../includes/config.php';
require_once '../includes/database.php';

$message = '';
$error = '';

// Lista dei file da tradurre (senza estensione)
$translatable_files = [
    'index' => 'Homepage',
    '404' => 'Pagina 404',
    'articoli' => 'Lista Articoli',
    'articolo' => 'Dettaglio Articolo',
    'categoria' => 'Dettaglio Categoria', 
    'categorie' => 'Lista Categorie',
    'chi-siamo' => 'Chi Siamo',
    'citta-dettaglio' => 'Dettaglio Città',
    'citta' => 'Lista Città',
    'collabora' => 'Collabora',
    'contatti' => 'Contatti',
    'iscrivi-attivita' => 'Iscrivi Attività',
    'mappa' => 'Mappa',
    'page' => 'Pagina Generica',
    'privacy-policy' => 'Privacy Policy',
    'province' => 'Lista Province',
    'provincia' => 'Dettaglio Provincia',
    'registra-business' => 'Registra Business',
    'ricerca' => 'Ricerca',
    'suggerisci-evento' => 'Suggerisci Evento',
    'suggerisci' => 'Suggerisci',
    'termini-servizio' => 'Termini di Servizio'
];

$languages = [
    'it' => ['name' => 'Italiano', 'flag' => '🇮🇹', 'color' => 'blue'],
    'en' => ['name' => 'English', 'flag' => '🇺🇸', 'color' => 'green'], 
    'fr' => ['name' => 'Français', 'flag' => '🇫🇷', 'color' => 'purple'],
    'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪', 'color' => 'yellow'],
    'es' => ['name' => 'Español', 'flag' => '🇪🇸', 'color' => 'red']
];

// Funzione per contare file tradotti per lingua
function countTranslatedFiles($language) {
    global $translatable_files;
    
    if ($language === 'it') {
        // Italiano: conta i file nella cartella it (cartella corrente)
        $count = 0;
        foreach (array_keys($translatable_files) as $file) {
            if (file_exists("../{$file}.php")) {
                $count++;
            }
        }
        return $count;
    }
    
    // Altre lingue: conta i file nella cartella specifica (due livelli su)
    $count = 0;
    $language_dir = "../../{$language}/";
    
    if (!is_dir($language_dir)) {
        return 0;
    }
    
    foreach (array_keys($translatable_files) as $file) {
        if (file_exists("{$language_dir}{$file}.php")) {
            $count++;
        }
    }
    
    return $count;
}

// Funzione per ottenere file mancanti per una lingua
function getMissingFiles($language) {
    global $translatable_files;
    
    if ($language === 'it') {
        return []; // Italiano sempre completo
    }
    
    $missing = [];
    $language_dir = "../../{$language}/";
    
    foreach (array_keys($translatable_files) as $file) {
        if (!file_exists("{$language_dir}{$file}.php")) {
            $missing[] = $file;
        }
    }
    
    return $missing;
}

// Gestione azioni POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'download_missing':
            $language = $_POST['language'] ?? '';
            if (!isset($languages[$language]) || $language === 'it') {
                $error = "❌ Lingua non valida per il download";
                break;
            }
            
            $missing_files = getMissingFiles($language);
            if (empty($missing_files)) {
                $error = "✅ Nessun file da tradurre per {$languages[$language]['name']}";
                break;
            }
            
            // Crea ZIP con file da tradurre
            $zip = new ZipArchive();
            $zip_name = "traduzioni-{$language}-" . date('Y-m-d-H-i') . ".zip";
            $zip_path = "../../uploads/{$zip_name}";
            
            if (!is_dir('../../uploads')) {
                mkdir('../../uploads', 0755, true);
            }
            
            if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
                foreach ($missing_files as $file) {
                    $original_file = "../{$file}.php";
                    if (file_exists($original_file)) {
                        $new_name = "{$file}-{$language}.php";
                        $zip->addFile($original_file, $new_name);
                    }
                }
                $zip->close();
                
                // Download del file
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zip_name . '"');
                header('Content-Length: ' . filesize($zip_path));
                readfile($zip_path);
                unlink($zip_path); // Rimuovi file temporaneo
                exit;
            } else {
                $error = "❌ Errore nella creazione dell'archivio ZIP";
            }
            break;
            
        case 'upload_translations':
            if (!isset($_FILES['translation_files']) || $_FILES['translation_files']['error'] === UPLOAD_ERR_NO_FILE) {
                $error = "❌ Nessun file caricato";
                break;
            }
            
            $uploaded_files = $_FILES['translation_files'];
            $success_count = 0;
            $error_count = 0;
            $upload_errors = [];
            
            // Gestione upload multipli
            $file_count = is_array($uploaded_files['name']) ? count($uploaded_files['name']) : 1;
            
            for ($i = 0; $i < $file_count; $i++) {
                $file_name = is_array($uploaded_files['name']) ? $uploaded_files['name'][$i] : $uploaded_files['name'];
                $tmp_name = is_array($uploaded_files['tmp_name']) ? $uploaded_files['tmp_name'][$i] : $uploaded_files['tmp_name'];
                $file_error = is_array($uploaded_files['error']) ? $uploaded_files['error'][$i] : $uploaded_files['error'];
                
                if ($file_error !== UPLOAD_ERR_OK) {
                    $error_count++;
                    continue;
                }
                
                // Riconosci lingua dal nome file (es: about-en.php)
                if (preg_match('/(.+)-([a-z]{2})\.php$/', $file_name, $matches)) {
                    $base_name = $matches[1];
                    $language = $matches[2];
                    
                    if (!isset($languages[$language]) || $language === 'it') {
                        $upload_errors[] = "File {$file_name}: lingua non valida";
                        $error_count++;
                        continue;
                    }
                    
                    if (!isset($translatable_files[$base_name])) {
                        $upload_errors[] = "File {$file_name}: nome file non riconosciuto";
                        $error_count++;
                        continue;
                    }
                    
                    // Crea cartella se non esiste
                    $language_dir = "../../{$language}/";
                    if (!is_dir($language_dir)) {
                        mkdir($language_dir, 0755, true);
                    }
                    
                    // Sposta file nella cartella corretta
                    $target_path = "{$language_dir}{$base_name}.php";
                    if (move_uploaded_file($tmp_name, $target_path)) {
                        $success_count++;
                    } else {
                        $upload_errors[] = "File {$file_name}: errore spostamento";
                        $error_count++;
                    }
                } else {
                    $upload_errors[] = "File {$file_name}: formato nome non valido (usa nome-lingua.php)";
                    $error_count++;
                }
            }
            
            if ($success_count > 0) {
                $message = "✅ Upload completato: {$success_count} file caricati con successo";
                if ($error_count > 0) {
                    $message .= ", {$error_count} errori";
                }
            } else {
                $error = "❌ Nessun file caricato correttamente";
            }
            
            if (!empty($upload_errors)) {
                $error .= "<br>Dettagli errori:<br>" . implode('<br>', array_slice($upload_errors, 0, 5));
            }
            break;
    }
}

// Calcola statistiche per ogni lingua
$stats = [];
$total_files = count($translatable_files);

foreach ($languages as $lang_code => $lang_info) {
    $translated_count = countTranslatedFiles($lang_code);
    $missing_count = $total_files - $translated_count;
    $percentage = $total_files > 0 ? round(($translated_count / $total_files) * 100, 1) : 0;
    
    $stats[$lang_code] = [
        'translated' => $translated_count,
        'total' => $total_files,
        'missing' => $missing_count,
        'percentage' => $percentage,
        'missing_files' => getMissingFiles($lang_code)
    ];
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo Sistema Traduzione - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-4">
                <a href="index.php" class="text-blue-600 hover:text-blue-700">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">🌐 Sistema Traduzione Nuovo</h1>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-4">
                <p class="text-gray-700 font-medium">📁 Sistema File Statici</p>
                <p class="text-sm text-gray-600 mt-1">
                    Download → Traduci in locale → Upload automatico nelle cartelle giuste
                </p>
            </div>
        </div>

        <!-- Messaggi -->
        <?php if ($message): ?>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mt-0.5 mr-3"></i>
                <div class="text-green-800"><?php echo $message; ?></div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mt-0.5 mr-3"></i>
                <div class="text-red-800"><?php echo $error; ?></div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Contatori per Lingua (RETTANGOLI RICHIESTI) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <?php foreach ($languages as $lang_code => $lang_info): ?>
            <?php $stat = $stats[$lang_code]; ?>
            <div class="bg-white rounded-xl shadow-lg border-l-4 border-<?php echo $lang_info['color']; ?>-500 overflow-hidden">
                <!-- Header del rettangolo -->
                <div class="bg-<?php echo $lang_info['color']; ?>-50 px-6 py-4 border-b border-<?php echo $lang_info['color']; ?>-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-<?php echo $lang_info['color']; ?>-900 text-lg">
                                <?php echo $lang_info['flag']; ?> <?php echo $lang_info['name']; ?>
                            </h3>
                            <p class="text-xs text-<?php echo $lang_info['color']; ?>-600 uppercase tracking-wide">
                                <?php echo $lang_code; ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-<?php echo $lang_info['color']; ?>-700">
                                <?php echo $stat['percentage']; ?>%
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Body del rettangolo -->
                <div class="px-6 py-4">
                    <div class="space-y-3">
                        <!-- Barra progresso -->
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-<?php echo $lang_info['color']; ?>-500 h-3 rounded-full transition-all duration-300" 
                                 style="width: <?php echo $stat['percentage']; ?>%"></div>
                        </div>
                        
                        <!-- Statistiche -->
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="text-center">
                                <div class="font-bold text-green-600 text-xl"><?php echo $stat['translated']; ?></div>
                                <div class="text-gray-500">Tradotte</div>
                            </div>
                            <div class="text-center">
                                <div class="font-bold text-red-600 text-xl"><?php echo $stat['missing']; ?></div>
                                <div class="text-gray-500">Mancanti</div>
                            </div>
                        </div>
                        
                        <!-- Totale -->
                        <div class="text-center pt-2 border-t border-gray-100">
                            <div class="text-xs text-gray-500">
                                Totale: <span class="font-medium"><?php echo $stat['total']; ?> pagine</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer con azioni -->
                <?php if ($lang_code !== 'it' && $stat['missing'] > 0): ?>
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                    <form method="POST" class="w-full">
                        <input type="hidden" name="action" value="download_missing">
                        <input type="hidden" name="language" value="<?php echo $lang_code; ?>">
                        <button type="submit" class="w-full bg-<?php echo $lang_info['color']; ?>-600 hover:bg-<?php echo $lang_info['color']; ?>-700 text-white text-sm px-3 py-2 rounded-lg font-medium transition-colors flex items-center justify-center">
                            <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                            Scarica <?php echo $stat['missing']; ?> file
                        </button>
                    </form>
                </div>
                <?php elseif ($lang_code !== 'it'): ?>
                <div class="px-6 py-3 bg-green-50 border-t border-green-100">
                    <div class="text-center text-sm text-green-700 font-medium">
                        ✅ Completato
                    </div>
                </div>
                <?php else: ?>
                <div class="px-6 py-3 bg-blue-50 border-t border-blue-100">
                    <div class="text-center text-sm text-blue-700 font-medium">
                        🏠 Lingua base
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Upload Files Tradotti -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold mb-6 flex items-center">
                <i data-lucide="upload" class="w-6 h-6 mr-3 text-green-600"></i>
                📤 Upload File Tradotti
            </h3>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-blue-900 mb-2">📋 Come funziona:</h4>
                <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                    <li><strong>Scarica</strong> i file da tradurre usando i pulsanti qui sopra</li>
                    <li><strong>Traduci</strong> i file in locale mantenendo i nomi (es: index-en.php, about-fr.php)</li>
                    <li><strong>Carica</strong> tutti i file tradotti qui sotto - il sistema li metterà nelle cartelle giuste!</li>
                </ol>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="action" value="upload_translations">
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                    <i data-lucide="upload-cloud" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
                    <label for="translation_files" class="cursor-pointer">
                        <span class="text-lg font-medium text-gray-700 hover:text-blue-600">
                            Clicca per selezionare i file tradotti
                        </span>
                        <input type="file" 
                               id="translation_files" 
                               name="translation_files[]" 
                               multiple 
                               accept=".php" 
                               class="hidden"
                               onchange="updateFileList(this)">
                    </label>
                    <p class="text-sm text-gray-500 mt-2">
                        Seleziona più file .php tradotti (formati: nome-lingua.php)
                    </p>
                </div>
                
                <div id="file-list" class="hidden space-y-2">
                    <h4 class="font-medium text-gray-700">File selezionati:</h4>
                    <div id="file-items" class="bg-gray-50 rounded-lg p-4 max-h-40 overflow-y-auto"></div>
                </div>
                
                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-lg font-bold text-lg transition-colors flex items-center justify-center"
                        id="upload-btn" disabled>
                    <i data-lucide="upload" class="w-5 h-5 mr-3"></i>
                    🚀 Carica File Tradotti
                </button>
            </form>
        </div>

        <!-- Informazioni Sistema -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl p-8">
            <h3 class="text-xl font-bold mb-4 text-indigo-900">
                ℹ️ Come funziona il nuovo sistema
            </h3>
            <div class="grid md:grid-cols-2 gap-6 text-sm">
                <div>
                    <h4 class="font-semibold text-indigo-800 mb-2">📁 Struttura File:</h4>
                    <ul class="space-y-1 text-indigo-700">
                        <li>• <code>/it/</code> - File italiani (originali)</li>
                        <li>• <code>/en/</code> - File inglesi tradotti</li>
                        <li>• <code>/fr/</code> - File francesi tradotti</li>
                        <li>• <code>/de/</code> - File tedeschi tradotti</li>
                        <li>• <code>/es/</code> - File spagnoli tradotti</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-indigo-800 mb-2">🔄 Workflow:</h4>
                    <ul class="space-y-1 text-indigo-700">
                        <li>• Scarica file con nomi: <code>index-en.php</code></li>
                        <li>• Traduci il contenuto mantenendo il nome</li>
                        <li>• Upload automatico in <code>/en/index.php</code></li>
                        <li>• Il sito rileva automaticamente la lingua utente</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        function updateFileList(input) {
            const fileList = document.getElementById('file-list');
            const fileItems = document.getElementById('file-items');
            const uploadBtn = document.getElementById('upload-btn');
            
            if (input.files.length > 0) {
                fileList.classList.remove('hidden');
                uploadBtn.disabled = false;
                uploadBtn.classList.remove('opacity-50');
                
                fileItems.innerHTML = '';
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];
                    const div = document.createElement('div');
                    div.className = 'flex items-center justify-between bg-white p-2 rounded border';
                    
                    // Riconosci lingua dal nome file
                    let language = 'Sconosciuta';
                    let isValid = false;
                    const match = file.name.match(/(.+)-([a-z]{2})\.php$/);
                    if (match) {
                        const langCode = match[2];
                        const languages = {it: 'Italiano', en: 'Inglese', fr: 'Francese', de: 'Tedesco', es: 'Spagnolo'};
                        if (languages[langCode]) {
                            language = languages[langCode];
                            isValid = true;
                        }
                    }
                    
                    div.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <i data-lucide="file-text" class="w-4 h-4 ${isValid ? 'text-green-500' : 'text-red-500'}"></i>
                            <span class="font-medium">${file.name}</span>
                            <span class="text-sm px-2 py-1 rounded-full ${isValid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                                ${language}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500">
                            ${(file.size / 1024).toFixed(1)} KB
                        </div>
                    `;
                    fileItems.appendChild(div);
                }
                lucide.createIcons();
            } else {
                fileList.classList.add('hidden');
                uploadBtn.disabled = true;
                uploadBtn.classList.add('opacity-50');
            }
        }
    </script>
</body>
</html>