<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$name = $data['name'] ?? '';
$photo = $data['photo'] ?? '';

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'No email provided']);
    exit;
}

require_once 'db_connection.php';
$conn = getConnection();
if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'DB error']);
    exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT id, first_name, last_name, email FROM clients WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // User exists
    $user_id = $row['id'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
} else {
    // User does not exist, insert
    $nameParts = explode(' ', $name, 2);
    $first_name = $nameParts[0] ?? '';
    $last_name = $nameParts[1] ?? '';
    $insert = $conn->prepare("INSERT INTO clients (first_name, last_name, email) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $first_name, $last_name, $email);
    $insert->execute();
    $user_id = $insert->insert_id;
    $insert->close();
}

$stmt->close();
$conn->close();

// Set session
$_SESSION['user_id'] = $user_id;
$_SESSION['email'] = $email;
$_SESSION['user_name'] = trim($first_name . ' ' . $last_name);
$_SESSION['avatar_url'] = $photo; // $photo is the Google avatar URL
$_SESSION['logged_in'] = true;

echo json_encode(['status' => 'success']);
exit;