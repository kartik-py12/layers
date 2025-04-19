<?php
/**
 * Database update script - run this to update your database schema
 */
// Include database connection
require_once "config/db.php";

echo "<h1>Database Update for Profile Image</h1>";

// Check connection
if (isset($db_connection_error) && $db_connection_error) {
    die("Database connection failed: " . $db_error_message);
}

try {
    // Check if column exists
    $result = $pdo->query("SHOW COLUMNS FROM users LIKE 'profile_image'");
    $columnExists = ($result && $result->rowCount() > 0);
    
    // Add the column if it doesn't exist
    if (!$columnExists) {
        $pdo->exec("ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL");
        echo "<p style='color:green'>SUCCESS: The profile_image column has been added to the users table.</p>";
    } else {
        echo "<p style='color:blue'>INFO: The profile_image column already exists in the users table.</p>";
    }
    
    // Verify the column exists now
    $result = $pdo->query("SHOW COLUMNS FROM users LIKE 'profile_image'");
    if ($result && $result->rowCount() > 0) {
        echo "<p>Column verification successful! You can now upload profile images.</p>";
    } else {
        echo "<p style='color:red'>ERROR: Something went wrong. The column wasn't created.</p>";
    }
    
    echo "Database update completed successfully!";
} catch (PDOException $e) {
    echo "<p style='color:red'>ERROR: " . $e->getMessage() . "</p>";
}
?>
<p><a href="pages/profile.php">Return to profile page</a></p>
