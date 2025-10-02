<?php
// Carica le dipendenze di Composer, inclusa la libreria di Stripe
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once '../includes/database_mysql.php';

// Verifica che la sessione contenga i dati necessari
if (!isset($_SESSION['business_id']) || !isset($_SESSION['package_id'])) {
    header('Location: ../iscrizione-attivita.php');
    exit;
}

$db = new Database();
$business_id = $_SESSION['business_id'];
$package_id = $_SESSION['package_id'];

// Recupera i dati del business e del pacchetto dal database
try {
    $stmt = $db->pdo->prepare("SELECT * FROM businesses WHERE id = ?");
    $stmt->execute([$business_id]);
    $business = $stmt->fetch();
    
    $stmt = $db->pdo->prepare("SELECT * FROM business_packages WHERE id = ?");
    $stmt->execute([$package_id]);
    $package = $stmt->fetch();
    
    if (!$business || !$package) {
        throw new Exception('Dati business o pacchetto non trovati.');
    }
    
    // Se il pacchetto è gratuito, reindirizza direttamente alla pagina di successo
    if ($package['price'] == 0) {
        // Qui potresti voler attivare l'abbonamento gratuito nel DB prima del redirect
        header('Location: ../registrazione-completata.php');
        exit;
    }
    
} catch (Exception $e) {
    header('Location: ../iscrizione-attivita.php?error=' . urlencode($e->getMessage()));
    exit;
}

// Recupera le chiavi API di Stripe dalle impostazioni
$stripePublishableKey = $db->getSetting('stripe_publishable_key');
$stripeSecretKey = $db->getSetting('stripe_secret_key');
$isStripeConfigured = !empty($stripePublishableKey) && !empty($stripeSecretKey);
$checkout_session_id = null;
$error = '';

if ($isStripeConfigured) {
    try {
        // Inizializza la libreria di Stripe con la chiave segreta
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        // Crea la sessione di checkout di Stripe
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => htmlspecialchars($package['name']),
                        'description' => htmlspecialchars($package['description']),
                    ],
                    'unit_amount' => $package['price'] * 100, // Prezzo in centesimi di Euro
                    'recurring' => ['interval' => 'year'], // Specifica che è un abbonamento annuale
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription', // Modalità abbonamento
            'success_url' => SITE_URL . '/registrazione-completata.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => SITE_URL . '/api/stripe-checkout.php', // Ritorna a questa pagina in caso di annullamento
            'metadata' => [ // Dati personalizzati che Stripe ci restituirà tramite webhook
                'business_id' => $business_id,
                'package_id' => $package_id,
                'operation_type' => 'new_subscription'
            ],
            'customer_email' => $business['email'],
        ]);
        
        // Salva l'ID della sessione per usarlo nel frontend
        $checkout_session_id = $checkout_session->id;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        $error = 'Errore Stripe: ' . $e->getMessage();
    }
} else {
    $error = 'La configurazione di Stripe non è completa. Contatta l\'amministratore.';
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completa Pagamento - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-50">
    <?php include '../includes/header.php'; ?>
    
    <main class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Riepilogo Ordine</h1>
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-lg font-semibold"><?php echo htmlspecialchars($package['name']); ?></h2>
                            <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($package['description']); ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold">€<?php echo number_format($package['price'], 2); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <p><strong>Errore!</strong></p>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php elseif ($isStripeConfigured && $checkout_session_id): ?>
                    <h2 class="text-xl font-semibold mb-4">Sei quasi alla fine!</h2>
                    <p class="text-gray-600 mb-6">Clicca sul pulsante qui sotto per procedere al pagamento sicuro sulla piattaforma di Stripe.</p>
                    <button id="checkout-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                        Paga Ora €<?php echo number_format($package['price'], 2); ?>
                    </button>
                <?php else: ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        <p>Impossibile inizializzare il pagamento. Riprova più tardi.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>
    
    <script>
        lucide.createIcons();
        <?php if ($isStripeConfigured && $checkout_session_id): ?>
        const stripe = Stripe('<?php echo $stripePublishableKey; ?>');
        const checkoutButton = document.getElementById('checkout-button');

        checkoutButton.addEventListener('click', function () {
            checkoutButton.disabled = true;
            checkoutButton.innerHTML = 'Reindirizzamento in corso...';
            stripe.redirectToCheckout({
                sessionId: '<?php echo $checkout_session_id; ?>'
            }).then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                    checkoutButton.disabled = false;
                    checkoutButton.innerHTML = 'Paga Ora €<?php echo number_format($package['price'], 2); ?>';
                }
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>