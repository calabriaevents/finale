<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once '../includes/database_mysql.php';

// Verifica che ci sia una sessione attiva con i dati necessari
if (!isset($_SESSION['business_id']) || !isset($_SESSION['package_id'])) {
    header('Location: ../iscrizione-attivita.php?error=session_expired');
    exit;
}

$db = new Database();
$business_id = $_SESSION['business_id'];
$package_id = $_SESSION['package_id'];

// Recupera le chiavi Stripe
$stripeSecretKey = $db->getSetting('stripe_secret_key');
if (empty($stripeSecretKey)) {
    die('Errore: la configurazione di Stripe non è completa. Contattare l\'amministratore.');
}

// Recupera i dati del pacchetto, incluso l'ID del prezzo di Stripe
try {
    $stmt = $db->pdo->prepare("SELECT name, stripe_price_id FROM business_packages WHERE id = ?");
    $stmt->execute([$package_id]);
    $package = $stmt->fetch();

    if (!$package || empty($package['stripe_price_id'])) {
        throw new Exception('Questo pacchetto non è configurato correttamente per il pagamento online. Contatta il supporto.');
    }
} catch (Exception $e) {
    header('Location: ../iscrizione-attivita.php?error=' . urlencode($e->getMessage()));
    exit;
}

\Stripe\Stripe::setApiKey($stripeSecretKey);

// Definisci gli URL per il successo e l'annullamento
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$successUrl = $protocol . $domainName . '/registrazione-completata.php?session_id={CHECKOUT_SESSION_ID}';
$cancelUrl = $protocol . $domainName . '/iscrizione-attivita.php';

try {
    // Crea la sessione di Checkout di Stripe
    $checkout_session = \Stripe\Checkout\Session::create([
        'line_items' => [[
            'price' => $package['stripe_price_id'],
            'quantity' => 1,
        ]],
        'mode' => 'subscription', // Usa 'payment' per acquisti una tantum
        'success_url' => $successUrl,
        'cancel_url' => $cancelUrl,
        'metadata' => [
            // Includiamo i dati che ci serviranno nel webhook per attivare l'abbonamento
            'business_id' => $business_id,
            'package_id' => $package_id
        ]
    ]);

    // Reindirizza l'utente alla pagina di pagamento di Stripe
    header("HTTP/1.1 303 See Other");
    header("Location: " . $checkout_session->url);
    exit;

} catch (Exception $e) {
    error_log('Stripe Checkout Error: ' . $e->getMessage());
    header('Location: ../iscrizione-attivita.php?error=stripe_error&message=' . urlencode($e->getMessage()));
    exit;
}