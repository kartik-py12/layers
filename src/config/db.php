<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Ram@123'); // Empty password for standard XAMPP/WAMP
define('DB_NAME', 'coupon_organizer');

// Connection variables
$pdo = null;
$db_connection_error = false;
$db_error_message = '';

// Attempt to connect to MySQL database
try {
    // First, connect without database specification to create it if needed
    $temp_pdo = new PDO("mysql:host=" . DB_SERVER, DB_USERNAME, DB_PASSWORD);
    $temp_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $temp_pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    
    // Now connect with the database
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $tablesExist = ($stmt->rowCount() > 0);
    
    // If tables don't exist, create them
    if (!$tablesExist) {
        // Include database setup script
        require_once __DIR__ . '/db_setup.php';
        setupDatabase($pdo);
    }
    
} catch(PDOException $e) {
    $db_connection_error = true;
    $db_error_message = $e->getMessage();
    
    // Try to solve the problem automatically
    if (strpos($db_error_message, "Unknown database") !== false) {
        try {
            // Try to create database again directly
            $temp_pdo = new PDO("mysql:host=" . DB_SERVER, DB_USERNAME, DB_PASSWORD);
            $temp_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $temp_pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
            
            // Connect with the newly created database
            $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create tables
            require_once __DIR__ . '/db_setup.php';
            setupDatabase($pdo);
            
            // Reset error state
            $db_connection_error = false;
            $db_error_message = '';
            
        } catch(PDOException $ex) {
            // Still couldn't solve it
            $db_connection_error = true;
            $db_error_message = $ex->getMessage();
        }
    }
}
?>
