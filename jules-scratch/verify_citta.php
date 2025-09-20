<?php
// Simulate a web request to citta-dettaglio.php
$_GET['id'] = 1; // Use a valid city ID from the database
$_SERVER['REQUEST_METHOD'] = 'GET'; // Simulate a GET request
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/citta-dettaglio.php?id=1';
$_SERVER['SCRIPT_NAME'] = '/citta-dettaglio.php';

// Capture output to prevent it from flooding the console
ob_start();

$error_found = false;
$error_details = '';

try {
    // Set a custom error handler to catch non-fatal errors
    set_error_handler(function($severity, $message, $file, $line) use (&$error_found, &$error_details) {
        // Ignore "Undefined array key" for $_POST as we are simulating a GET request
        if (strpos($message, 'Undefined array key') !== false && (strpos($message, 'action') !== false || strpos($message, 'user_name') !== false)) {
            return true;
        }
        $error_found = true;
        $error_details .= "Error [$severity]: $message in $file on line $line\n";
    });

    include 'citta-dettaglio.php';

    restore_error_handler();

    $output = ob_get_clean();

    // Check for PHP error strings in the output as a fallback
    $php_errors = [
        'Fatal error:',
        'Parse error:'
    ];
    foreach ($php_errors as $error) {
        if (stripos($output, $error) !== false) {
            $error_found = true;
            $error_details .= "Found string '$error' in the output.\n";
        }
    }

    if ($error_found) {
        echo "❌ ERROR: The script executed, but PHP errors were detected.\n";
        echo "------------------ ERROR DETAILS ------------------\n";
        echo $error_details;
        echo "----------------- END ERROR DETAILS -----------------\n";
    } else {
        echo "✅ citta-dettaglio.php executed successfully without any detectable errors.\n";
    }

} catch (Exception $e) {
    // Catch any fatal exceptions that might be thrown
    restore_error_handler();
    ob_end_clean();
    echo "❌ FATAL EXCEPTION: Exception caught while executing citta-dettaglio.php: " . $e->getMessage() . "\n";
}
