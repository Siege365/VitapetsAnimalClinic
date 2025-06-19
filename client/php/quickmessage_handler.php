<?php
session_start();

// Include database connection
require_once 'db_connection.php';
$conn = getConnection();

if (!$conn) {
    // Return JSON response for AJAX handling
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Only allow logged-in users
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'You must be logged in to submit a message']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $client_id = $_SESSION['user_id'];
    $pet_name = $conn->real_escape_string($_POST['pet_name'] ?? '');
    $message = $conn->real_escape_string($_POST['message'] ?? '');
    
    // Get additional user info from session if needed
    $first_name = $_SESSION['first_name'] ?? '';
    $last_name = $_SESSION['last_name'] ?? '';
    $phone_number = $_SESSION['phone_number'] ?? '';
    $email = $_SESSION['email'] ?? '';

    // Validate required fields
    if (empty($pet_name) || empty($message)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
        exit;
    }

    // Insert into quick_message table
    $stmt = $conn->prepare("INSERT INTO quick_message (client_id, pet_name, message, status) VALUES (?, ?, ?, 'unread')");
    $stmt->bind_param("iss", $client_id, $pet_name, $message);

    if ($stmt->execute()) {
        // Success response for AJAX
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully!']);
    } else {
        // Error response for AJAX
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to save your message. Please try again.']);
    }
    
    $stmt->close();
    $conn->close();
    exit;
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}
?>