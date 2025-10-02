<?php
// Carica le dipendenze necessarie
require_once '../vendor/autoload.php';
require_once '../includes/config.php';
require_once '../includes/database_mysql.php';

// Impostazioni di sicurezza
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

$db = new Database();

// Recupera le impostazioni di Stripe dal database
$stripeWebhookSecret = $db->getSetting('stripe_webhook_secret');
if (!$stripeWebhookSecret) {
    http_response_code(500);
    echo json_encode(['error' => 'Segreto del webhook non configurato sul server.']);
    exit;
}

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
$event = null;

try {
    // Verifica che l'evento provenga da Stripe usando il segreto del webhook
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $stripeWebhookSecret
    );
} catch(\UnexpectedValueException $e) {
    // Payload JSON non valido
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Firma del webhook non valida
    http_response_code(403);
    exit();
}

// Gestisci l'evento solo se è quello che ci interessa
if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;

    // Recupera i metadati che abbiamo inviato durante la creazione della sessione
    $business_id = $session->metadata->business_id ?? null;
    $package_id = $session->metadata->package_id ?? null;
    $subscription_id = $session->subscription; // ID dell'abbonamento Stripe
    $amount_total = $session->amount_total / 100; // Stripe invia l'importo in centesimi

    if ($business_id && $package_id && $subscription_id) {
        try {
            // Controlla se l'abbonamento è già stato processato
            $stmt = $db->pdo->prepare("SELECT id FROM subscriptions WHERE stripe_subscription_id = ?");
            $stmt->execute([$subscription_id]);
            if ($stmt->fetch()) {
                // Già processato, esci tranquillamente
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Abbonamento già registrato.']);
                exit();
            }

            // Recupera la durata del pacchetto dal nostro database
            $stmt = $db->pdo->prepare("SELECT duration_months FROM business_packages WHERE id = ?");
            $stmt->execute([$package_id]);
            $package_duration = $stmt->fetchColumn() ?? 12;

            // Inserisci il nuovo abbonamento nel database
            $stmt = $db->pdo->prepare("
                INSERT INTO subscriptions (
                    business_id, package_id, status, start_date, end_date, amount,
                    created_at, stripe_subscription_id
                ) VALUES (?, ?, 'active', NOW(), DATE_ADD(NOW(), INTERVAL ? MONTH), ?, NOW(), ?)
            ");
            $stmt->execute([
                $business_id,
                $package_id,
                $package_duration,
                $amount_total,
                $subscription_id
            ]);

            // Aggiorna lo stato del business ad "approvato"
            $stmt = $db->pdo->prepare("UPDATE businesses SET status = 'approved' WHERE id = ?");
            $stmt->execute([$business_id]);

        } catch (Exception $e) {
            // In caso di errore del database, loggalo e rispondi con un errore server
            error_log('Webhook Database Error: ' . $e->getMessage());
            http_response_code(500);
            exit();
        }
    } else {
        error_log('Webhook Error: Metadati mancanti nella sessione ' . $session->id);
    }
}

// Rispondi a Stripe con un successo
http_response_code(200);
echo json_encode(['status' => 'success']);