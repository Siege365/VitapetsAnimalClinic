<?php
// Start session to preserve form data on errors
session_start();

// Include database connection file
require_once 'db_connection.php';

// Process only POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store form data in session to preserve across redirects
    $_SESSION['form_data'] = [
        'email' => $_POST['email'] ?? '',
        'first_name' => $_POST['first_name'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'phone' => $_POST['phone'] ?? ''
    ];
    
    // Get data from form
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $phoneNumber = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validate required fields
    if (empty($email) || empty($firstName) || empty($lastName) || empty($phoneNumber) || empty($password)) {
        returnWithError("All fields are required");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        returnWithError("Invalid email format");
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        returnWithError("Passwords do not match");
    }
    
    // Validate password strength
    if (strlen($password) < 8) {
        returnWithError("Password must be at least 8 characters long");
    }
      // Validate phone number format (basic check)
    if (!preg_match("/^[0-9\-\(\)\/\+\s]*$/", $phoneNumber)) {
        returnWithError("Invalid phone number format");
    }
    
    try {
        // Get database connection
        $conn = getConnection();
        
        // Check connection
        if (!$conn) {
            throw new Exception("Database connection failed");
        }
        
        // Check if clients table exists
        $result = $conn->query("SHOW TABLES LIKE 'clients'");
        if ($result->num_rows == 0) {
            // Create clients table
            $sql = "CREATE TABLE clients (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(100) NOT NULL UNIQUE,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                phone_number VARCHAR(20) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            if (!$conn->query($sql)) {
                throw new Exception("Could not create table: " . $conn->error);
            }
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists
        $checkEmail = $conn->prepare("SELECT email FROM clients WHERE email = ?");
        if (!$checkEmail) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();
        
        if ($result->num_rows > 0) {
            $checkEmail->close();
            returnWithError("Email already exists");
        }
        
        // Close the email check statement
        $checkEmail->close();
        
        // Prepare SQL statement to insert data with password
        $stmt = $conn->prepare("INSERT INTO clients (email, first_name, last_name, phone_number, password) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("sssss", $email, $firstName, $lastName, $phoneNumber, $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            // Clear form data from session on success
            unset($_SESSION['form_data']);
            // Redirect to login page after successful registration
            redirectWithSuccess( "Registration successful! Please log in.");
        } else {
            throw new Exception("Registration failed: " . $stmt->error);
        }

        // Close statement
        $stmt->close();
        
    } catch (Exception $e) {
        // Log the error
        error_log("Registration error: " . $e->getMessage());
        returnWithError("An error occurred: " . $e->getMessage());
    } finally {
        // Close connection if it exists
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
    }
} else {
    returnWithError("Invalid request method");
}

// Helper function to handle errors
function returnWithError($errorMessage) {
    $_SESSION['error_message'] = $errorMessage;
    header("Location: ../html/register.php");   
    exit;
}

// Helper function for success messages
function redirectWithSuccess($message) {
    $_SESSION['success_message'] = $message;
    // Redirect back to register.php with success parameter
    header("Location: ../html/register.php?success=" . urlencode($message));
    exit;
}
?>