<?php
// api/capture_lead.php
ini_set('display_errors', 0);
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Method not allowed", 405);
    }

    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../models/Lead.php';

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || empty($input['name']) || empty($input['email']) || empty($input['phone'])) {
        throw new Exception("All fields (Name, Email, Phone) are required.");
    }

    $lead = new Lead();
    $leadId = $lead->create($input['name'], $input['email'], $input['phone'], 'chatbot_preform');

    if ($leadId) {
        echo json_encode(['success' => true, 'lead_id' => $leadId]);
    } else {
        throw new Exception("Failed to save lead.");
    }

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>