<?php
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once '../includes/database_mysql.php';

// Disabilita l'output di errori a schermo, ma loggali
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'stripe-webhook-errors.log'); // Salva gli errori in un file di log

header('Content-Type: application/json');

$db = new Database();

$stripeSecretKey = $db->getSetting('stripe_secret_key');
$stripeWebhookSecret = $db->getSetting('stripe_webhook_secret'); // Questa è la chiave segreta del webhook

if (!$stripeSecretKey || !$stripeWebhookSecret) {
    http_response_code(400);
    echo json_encode(['error' => 'Stripe (webhook) non è configurato.']);
    exit;
}

\Stripe\Stripe::setApiKey($stripeSecretKey);

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    // Verifica che l'evento provenga da Stripe usando la chiave segreta del webhook
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $stripeWebhookSecret
    );
} catch(\UnexpectedValueException $e) {
    http_response_code(400); // Payload non valido
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400); // Firma non valida
    exit();
}

// Gestisci solo l'evento 'checkout.session.completed'
if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;
    
    // Recupera i metadati che abbiamo inviato durante la creazione della sessione
    $business_id = $session->metadata->business_id ?? null;
    $package_id = $session->metadata->package_id ?? null;
    $operation_type = $session->metadata->operation_type ?? null;
    
    // Controlla se i dati necessari sono presenti
    if ($business_id && $package_id && $operation_type) {
        try {
            // Esegui l'azione appropriata in base al tipo di operazione
            if ($operation_type === 'new_subscription') {
                $db->pdo->prepare("UPDATE businesses SET status = 'approved' WHERE id = ?")->execute([$business_id]);
                $db->upgradeSubscription($business_id, $package_id, $session->subscription);
            } elseif ($operation_type === 'upgrade' || $operation_type === 'downgrade') {
                $db->upgradeSubscription($business_id, $package_id, $session->subscription);
            } elseif ($operation_type === 'buy_credits') {
                $db->purchaseCreditPackage($business_id, $package_id);
            }
            
        } catch (Exception $e) {
            error_log('Webhook DB Error: ' . $e->getMessage()); // Logga l'errore del database
            http_response_code(500); // Errore interno del server
            echo json_encode(['error' => 'Errore durante l\'aggiornamento del database.']);
            exit();
        }
    } else {
        error_log('Webhook Error: Metadati mancanti nella sessione ' . $session->id);
        http_response_code(400); // Dati mancanti
        echo json_encode(['error' => 'Metadati mancanti.']);
        exit();
    }
}

// Rispondi a Stripe con successo
http_response_code(200);
echo json_encode(['status' => 'success']);