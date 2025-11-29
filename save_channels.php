<?php
// Define the path to the JSON file
$file_path = 'channels.json';

// --- Simple Security Check (CHANGE THIS PASSWORD!) ---
$expected_password = 'YOUR_ADMIN_PASSWORD'; // <--- CHANGE THIS PASSWORD!

if (!isset($_POST['password']) || $_POST['password'] !== $expected_password) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}
// --------------------------------------------------------

// Check if the JSON data was sent
if (isset($_POST['data'])) {
    $json_data = $_POST['data'];
    
    // Validate if the received data is valid JSON
    $decoded_data = json_decode($json_data);
    if ($decoded_data === null) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid JSON format received.']);
        exit;
    }

    // Attempt to save the data to the file
    // LOCK_EX ensures atomic write
    if (file_put_contents($file_path, $json_data, LOCK_EX) !== false) {
        // Success response
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Channels and Marquee text updated successfully.']);
    } else {
        // Error response (e.g., permission denied)
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to write to file. Check file permissions (e.g., set channels.json to 666 or 777).']);
    }
} else {
    // Error response for missing data
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No data received.']);
}
?>
