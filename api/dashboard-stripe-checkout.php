<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once '../includes/database_mysql.php';

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    header('Location: ../user-auth.php');
    exit;
}

$db = new Database();
$business_id = $_SESSION['business_id'] ?? null;
if (!$business_id) {
    header('Location: ../user-auth.php?error=session_expired');
    exit;
}

$operation_type = $_SESSION['payment_operation'] ?? null;
$package_id = $_SESSION['payment_package_id'] ?? null;

if (!$operation_type || !$package_id) {
    header('Location: ../user-dashboard.php?error=invalid_payment_request');
    exit;
}

try {
    $stmt = $db->pdo->prepare("SELECT * FROM businesses WHERE id = ?");
    $stmt->execute([$business_id]);
    $business = $stmt->fetch();
    
    $stmt = $db->pdo->prepare("SELECT * FROM business_packages WHERE id = ?");
    $stmt->execute([$package_id]);
    $package = $stmt->fetch();
    
    if (!$business || !$package || $package['price'] == 0) {
        header('Location: ../user-dashboard.php');
        exit;
    }
} catch (Exception $e) {
    header('Location: ../user-dashboard.php?error=' . urlencode($e->getMessage()));
    exit;
}

$stripePublishableKey = $db->getSetting('stripe_publishable_key');
$stripeSecretKey = $db->getSetting('stripe_secret_key');
$isStripeConfigured = !empty($stripePublishableKey) && !empty($stripeSecretKey);
$checkout_session_id = null;
$error = '';

if ($isStripeConfigured) {
    try {
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        // Determina la modalità di pagamento: 'payment' per acquisti singoli (crediti), 'subscription' per abbonamenti
        $payment_mode = ($operation_type === 'buy_credits') ? 'payment' : 'subscription';
        
        $line_items = [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => ['name' => htmlspecialchars($package['name'])],
                'unit_amount' => $package['price'] * 100,
            ],
            'quantity' => 1,
        ]];

        // Se è un abbonamento, specifica l'intervallo di ricorrenza (es. annuale)
        if ($payment_mode === 'subscription') {
            $line_items[0]['price_data']['recurring'] = ['interval' => 'year'];
        }

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => $payment_mode,
            'success_url' => SITE_URL . '/user-dashboard.php?payment_status=success',
            'cancel_url' => SITE_URL . '/user-dashboard.php?payment_status=cancelled',
            'metadata' => [
                'business_id' => $business_id,
                'package_id' => $package_id,
                'operation_type' => $operation_type,
            ],
            'customer_email' => $business['email'],
        ]);
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
    <title>Conferma Pagamento - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-50">
    <?php include '../includes/header.php'; ?>
    <main class="py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
             <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h1 class="text-2xl font-bold">Riepilogo Acquisto</h1>
                <div class="mt-4 flex justify-between items-center">
                    <p class="text-lg font-semibold"><?php echo htmlspecialchars($package['name']); ?></p>
                    <p class="text-2xl font-bold">€<?php echo number_format($package['price'], 2); ?></p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6 text-center">
                <?php if ($error): ?>
                    <div class="bg-red-100 text-red-700 p-4 rounded mb-6"><?php echo htmlspecialchars($error); ?></div>
                    <a href="../user-dashboard.php" class="text-blue-600 hover:underline">Torna alla Dashboard</a>
                <?php elseif ($isStripeConfigured && $checkout_session_id): ?>
                    <h2 class="text-xl font-semibold mb-4">Procedi al pagamento</h2>
                    <p class="text-gray-600 mb-6">Verrai reindirizzato al portale sicuro di Stripe per completare la transazione.</p>
                    <button id="checkout-button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg">
                        Paga €<?php echo number_format($package['price'], 2); ?>
                    </button>
                <?php else: ?>
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded">Impossibile connettersi a Stripe.</div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script>
        lucide.createIcons();
        <?php if ($isStripeConfigured && $checkout_session_id): ?>
        const stripe = Stripe('<?php echo $stripePublishableKey; ?>');
        document.getElementById('checkout-button').addEventListener('click', function () {
            this.disabled = true;
            this.innerHTML = 'Reindirizzamento...';
            stripe.redirectToCheckout({ sessionId: '<?php echo $checkout_session_id; ?>' });
        });
        <?php endif; ?>
    </script>
</body>
</html>