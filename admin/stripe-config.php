<?php
require_once '../includes/config.php';
require_once '../includes/database_mysql.php';

// Verifica se l'utente è loggato come admin
// requireLogin(); // Disabilitato per ora

$db = new Database();
$success = '';
$error = '';

// Gestione form di configurazione Stripe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_stripe_config') {
    try {
        $publishableKey = sanitize($_POST['stripe_publishable_key'] ?? '');
        $secretKey = sanitize($_POST['stripe_secret_key'] ?? '');
        $webhookSecret = sanitize($_POST['stripe_webhook_secret'] ?? '');

        // Valida le chiavi
        if (empty($publishableKey) || empty($secretKey) || empty($webhookSecret)) {
            throw new Exception('Tutti e tre i campi (Chiave Pubblicabile, Chiave Segreta e Segreto Webhook) sono obbligatori.');
        }

        if (!str_starts_with($publishableKey, 'pk_')) {
            throw new Exception('La chiave pubblicabile non è valida. Dovrebbe iniziare con "pk_".');
        }

        if (!str_starts_with($secretKey, 'sk_')) {
            throw new Exception('La chiave segreta non è valida. Dovrebbe iniziare con "sk_".');
        }

        if (!str_starts_with($webhookSecret, 'whsec_')) {
            throw new Exception('Il segreto del webhook non è valido. Dovrebbe iniziare con "whsec_".');
        }

        // Salva le impostazioni
        $db->setSetting('stripe_publishable_key', $publishableKey, 'text');
        $db->setSetting('stripe_secret_key', $secretKey, 'password');
        $db->setSetting('stripe_webhook_secret', $webhookSecret, 'password');

        $success = 'Configurazione Stripe salvata con successo!';

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Carica impostazioni attuali
$currentPublishableKey = $db->getSetting('stripe_publishable_key') ?? '';
$currentSecretKey = $db->getSetting('stripe_secret_key') ?? '';
$currentWebhookSecret = $db->getSetting('stripe_webhook_secret') ?? '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurazione Stripe - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="min-h-screen bg-gray-100 flex">
    <div class="bg-gray-900 text-white w-64 flex flex-col">
        <div class="p-4 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-yellow-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-sm">PC</span>
                </div>
                <div>
                    <h1 class="font-bold text-lg">Admin Panel</h1>
                    <p class="text-xs text-gray-400">Passione Calabria</p>
                </div>
            </div>
        </div>
        <?php include 'partials/menu.php'; ?>
        <div class="p-4 border-t border-gray-700">
            <a href="../index.php" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-700 transition-colors"><i data-lucide="log-out" class="w-5 h-5"></i><span>Torna al Sito</span></a>
        </div>
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
            <h1 class="text-2xl font-bold text-gray-900">Configurazione Stripe</h1>
        </header>
        <main class="flex-1 overflow-auto p-6">
            <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Chiavi API di Stripe</h2>
                </div>

                <form method="POST" action="stripe-config.php" class="p-6">
                    <input type="hidden" name="action" value="save_stripe_config">
                    <div class="space-y-6">
                        <div>
                            <label for="stripe_publishable_key" class="block text-sm font-medium text-gray-700 mb-2">Chiave Pubblicabile <span class="text-red-500">*</span></label>
                            <input type="text" id="stripe_publishable_key" name="stripe_publishable_key" value="<?php echo htmlspecialchars($currentPublishableKey); ?>" placeholder="pk_test_..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">Utilizzata nel frontend per identificare il tuo account con Stripe.</p>
                        </div>

                        <div>
                            <label for="stripe_secret_key" class="block text-sm font-medium text-gray-700 mb-2">Chiave Segreta <span class="text-red-500">*</span></label>
                            <input type="password" id="stripe_secret_key" name="stripe_secret_key" value="<?php echo htmlspecialchars($currentSecretKey); ?>" placeholder="sk_test_..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">Utilizzata nel backend per creare pagamenti e gestire le transazioni. **Non condividerla mai.**</p>
                        </div>

                        <div>
                            <label for="stripe_webhook_secret" class="block text-sm font-medium text-gray-700 mb-2">Segreto del Webhook <span class="text-red-500">*</span></label>
                            <input type="password" id="stripe_webhook_secret" name="stripe_webhook_secret" value="<?php echo htmlspecialchars($currentWebhookSecret); ?>" placeholder="whsec_..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">Utilizzata per verificare che le notifiche di pagamento provengano realmente da Stripe. Fondamentale per la sicurezza.</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t border-gray-200 mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Salva Configurazione
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>