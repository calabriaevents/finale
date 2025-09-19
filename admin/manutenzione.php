<?php
session_start();
require_once '../includes/database_mysql.php';
require_once '../includes/auth.php';

// Controlla se l'utente è loggato come admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$db = new Database();

// Gestisci azioni
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'toggle_maintenance':
                $maintenance_enabled = isset($_POST['maintenance_enabled']) ? 1 : 0;
                $maintenance_message = trim($_POST['maintenance_message'] ?? '');
                
                // Aggiorna impostazioni manutenzione
                $db->updateSetting('maintenance_enabled', $maintenance_enabled);
                $db->updateSetting('maintenance_message', $maintenance_message);
                
                $message = $maintenance_enabled ? 'Modalità manutenzione attivata' : 'Modalità manutenzione disattivata';
                $messageType = 'success';
                break;
        }
    }
}

// Ottieni impostazioni attuali
$maintenance_enabled = $db->getSetting('maintenance_enabled') ?? 0;
$maintenance_message = $db->getSetting('maintenance_message') ?? 'Sito in manutenzione. Torneremo presto!';

// Include header
include 'partials/header.php';
?>

<div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 text-white">
        <?php include 'partials/menu.php'; ?>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Modalità Manutenzione</h1>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Stato:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?php echo $maintenance_enabled ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                        <?php echo $maintenance_enabled ? 'ATTIVATA' : 'DISATTIVATA'; ?>
                    </span>
                </div>
            </div>

            <?php if (isset($message)): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'; ?>">
                <div class="flex items-center">
                    <i data-lucide="<?php echo $messageType === 'success' ? 'check-circle' : 'alert-circle'; ?>" class="w-5 h-5 <?php echo $messageType === 'success' ? 'text-green-600' : 'text-red-600'; ?> mr-3"></i>
                    <span class="<?php echo $messageType === 'success' ? 'text-green-800' : 'text-red-800'; ?>"><?php echo $message; ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Stato Attuale -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Stato Attuale</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Modalità Manutenzione</span>
                            <span class="px-2 py-1 rounded text-xs font-medium <?php echo $maintenance_enabled ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                                <?php echo $maintenance_enabled ? 'ATTIVA' : 'INATTIVA'; ?>
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Area Admin</span>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">SEMPRE ATTIVA</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Sito Utenti</span>
                            <span class="px-2 py-1 rounded text-xs font-medium <?php echo $maintenance_enabled ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                                <?php echo $maintenance_enabled ? 'IN MANUTENZIONE' : 'ATTIVO'; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <h3 class="text-sm font-medium text-gray-700">Anteprima Messaggio</h3>
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center">
                                <i data-lucide="wrench" class="w-5 h-5 text-yellow-600 mr-2"></i>
                                <span class="text-yellow-800 text-sm"><?php echo htmlspecialchars($maintenance_message); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestione Manutenzione -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Gestione Manutenzione</h2>
                
                <form method="post" class="space-y-6">
                    <input type="hidden" name="action" value="toggle_maintenance">
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="maintenance_enabled" name="maintenance_enabled" 
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                   <?php echo $maintenance_enabled ? 'checked' : ''; ?>>
                            <label for="maintenance_enabled" class="ml-2 text-sm font-medium text-gray-900">
                                Attiva modalità manutenzione per il sito utenti
                            </label>
                        </div>
                        
                        <div>
                            <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-2">
                                Messaggio di Manutenzione
                            </label>
                            <textarea id="maintenance_message" name="maintenance_message" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Inserisci il messaggio da mostrare agli utenti durante la manutenzione"><?php echo htmlspecialchars($maintenance_message); ?></textarea>
                            <p class="mt-1 text-xs text-gray-500">
                                Questo messaggio verrà mostrato agli utenti quando il sito è in manutenzione. L'area admin rimane sempre accessibile.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                            L'area admin rimane sempre accessibile anche durante la manutenzione
                        </div>
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors font-medium">
                            Aggiorna Impostazioni
                        </button>
                    </div>
                </form>
            </div>

            <!-- Istruzioni -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Come Funziona</h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 mr-2 mt-0.5 text-blue-600"></i>
                        <span>Quando attivata, la modalità manutenzione mostrerà il messaggio personalizzato a tutti i visitatori del sito</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 mr-2 mt-0.5 text-blue-600"></i>
                        <span>L'area admin (<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']); ?>) rimane sempre accessibile</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 mr-2 mt-0.5 text-blue-600"></i>
                        <span>Gli utenti business non potranno accedere alle loro dashboard durante la manutenzione</span>
                    </li>
                    <li class="flex items-start">
                        <i data-lucide="check" class="w-4 h-4 mr-2 mt-0.5 text-blue-600"></i>
                        <span>Disattiva la manutenzione per ripristinare il normale funzionamento del sito</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>