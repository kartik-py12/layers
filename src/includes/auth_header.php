<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and profile image is not yet loaded
if (isset($_SESSION["user_id"]) && !isset($_SESSION["profile_image"])) {
    // Include database connection if not already included
    if (!isset($pdo)) {
        require_once dirname(__FILE__) . "/../config/db.php";
    }
    
    // Get profile image from database
    try {
        $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = :id");
        $stmt->bindParam(":id", $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->execute();
        $profile_image = $stmt->fetchColumn();
        
        // Store in session for use across the site
        $_SESSION["profile_image"] = $profile_image;
    } catch(PDOException $e) {
        // Silently fail, will use default avatar
    }
}
?>
