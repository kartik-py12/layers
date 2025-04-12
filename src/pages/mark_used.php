<?php
// Initialize the session
session_start();

// Check if the user is logged in
if(!isset($_SESSION["user_id"])){
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../config/db.php";

// Process only POST requests
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["coupon_id"])) {
    $coupon_id = $_POST["coupon_id"];
    
    // Update the coupon's status to "used"
    try {
        $stmt = $pdo->prepare("UPDATE coupons SET is_used = 1 WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(":id", $coupon_id, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->execute();
        
        // Redirect back to dashboard with success message
        header("location: dashboard.php?success=coupon_marked_used");
        exit;
    } catch(PDOException $e) {
        // Redirect back with error
        header("location: dashboard.php?error=update_failed");
        exit;
    }
} else {
    // Invalid request
    header("location: dashboard.php");
    exit;
}
?>
