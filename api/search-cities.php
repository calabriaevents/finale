<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../includes/database_mysql.php';

try {
    $db = new Database();
    $query = $_GET['q'] ?? '';
    
    if (empty($query)) {
        echo json_encode([]);
        exit;
    }
    
    // Search cities with autocomplete
    $stmt = $db->connection->prepare("
        SELECT c.id, c.name, p.name as province_name 
        FROM cities c 
        JOIN provinces p ON c.province_id = p.id 
        WHERE c.name LIKE ? 
        ORDER BY c.name ASC 
        LIMIT 10
    ");
    
    $searchTerm = $query . '%';
    $stmt->bind_param('s', $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'province' => $row['province_name'],
            'display' => $row['name'] . ' (' . $row['province_name'] . ')'
        ];
    }
    
    echo json_encode($cities);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Errore nella ricerca delle città']);
}
?>