<?php
// Simulate a web request to citta-dettaglio.php
$_GET['id'] = 1; // Use a valid city ID from the database
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/citta-dettaglio.php?id=1';
$_SERVER['SCRIPT_NAME'] = '/citta-dettaglio.php';

// Capture output to prevent it from flooding the console
ob_start();

try {
    include 'citta-dettaglio.php';
    // If we get here, no fatal errors occurred.
    $output = ob_get_clean();
    echo "✅ citta-dettaglio.php executed successfully.\n";
    // You can optionally check the output for specific content if needed
    // if (strpos($output, 'Error') !== false) {
    //     echo "⚠️  Warning: The page executed but contains the word 'Error'.\n";
    // }
} catch (Exception $e) {
    // Catch any exceptions that might be thrown
    ob_end_clean();
    echo "❌ ERROR: Exception caught while executing citta-dettaglio.php: " . $e->getMessage() . "\n";
}
