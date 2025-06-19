<?php
session_start();

// Include database connection file
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    returnWithError("Invalid request method");
}

// Sanitize input
$emailInput       = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$passwordInputRaw = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($emailInput) || empty($passwordInputRaw)) {
    returnWithError("Email and password are required");
}

// Check for admin credentials
if ($emailInput === 'VitapetsAdminMode' && $passwordInputRaw === 'clinicanimalvitapetsadmin') {
    // Redirect to admin page
    header("Location: /vitapets/admin/admin.html");
    exit;
}

try {
    // Get database connection
    $conn = getConnection();
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // Lookup user by email
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password FROM clients WHERE email = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $emailInput);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        returnWithError("Email not found");
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    // Verify the *entered* password against the hash in DB
    if (!password_verify($passwordInputRaw, $user['password'])) {
        returnWithError("Incorrect password");
    }

    // Success: populate session and redirect
    $_SESSION['user_id'] = $user['id'];      // or whatever you use for user ID
    $_SESSION['email'] = $user['email'];     // set the email in session
    $_SESSION['user_name']     = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['logged_in']     = true;
    $_SESSION['success_message'] = "Login successful! Welcome back, " . $user['first_name'] . "!";

    header("Location: ../html/mainpage.php");
    exit;

} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    returnWithError("An error occurred during login. Please try again.");
} finally {
    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}

// Helper to redirect back with an error
function returnWithError($msg) {
    $_SESSION['error_message'] = $msg;
    header("Location: ../html/login.php");
    exit;
}
