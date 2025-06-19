<?php
/**
 * Database Connection Handler
 * Centralized database connection for the Vitapets Animal Clinic website
 */

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "vitapetsanimalclinic";

/**
 * Get database connection
 * @return mysqli - Returns a new database connection
 */
function getConnection() {
    global $host, $username, $password, $database;
    
    // Create connection
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        // Log error
        error_log("Database connection failed: " . $conn->connect_error);
        return false;
    }
    
    return $conn;
}

/**
 * Close database connection safely
 * @param mysqli $conn - The connection to close
 * @return bool - Returns true if closed successfully
 */
function closeConnection($conn) {
    if ($conn instanceof mysqli) {
        $conn->close();
        return true;
    }
    return false;
}
?>
