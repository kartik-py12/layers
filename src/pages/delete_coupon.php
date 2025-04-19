<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["user_id"])){
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../config/db.php";

// Check if the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate coupon ID
    if(!isset($_POST['coupon_id']) || !is_numeric($_POST['coupon_id'])) {
        header("location: dashboard.php?error=Invalid coupon ID");
        exit;
    }
    
    $coupon_id = intval($_POST['coupon_id']);
    
    // Handle database connection errors
    if ($db_connection_error) {
        header("location: dashboard.php?error=" . urlencode("Database connection error: " . $db_error_message));
        exit;
    }
    
    try {
        // First verify that the coupon belongs to the current user
        $check_stmt = $pdo->prepare("SELECT id FROM coupons WHERE id = :coupon_id AND user_id = :user_id");
        $check_stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
        $check_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $check_stmt->execute();
        
        if($check_stmt->rowCount() == 0) {
            // Coupon doesn't belong to this user
            header("location: dashboard.php?error=You do not have permission to delete this coupon");
            exit;
        }
        
        // Delete the coupon
        $delete_stmt = $pdo->prepare("DELETE FROM coupons WHERE id = :coupon_id");
        $delete_stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
        $delete_stmt->execute();
        
        // Redirect back to dashboard with success message
        header("location: dashboard.php?success=coupon_deleted");
        exit;
    } catch(PDOException $e) {
        header("location: dashboard.php?error=" . urlencode("Error deleting coupon: " . $e->getMessage()));
        exit;
    }
} else {
    // If not a POST request, redirect to dashboard
    header("location: dashboard.php");
    exit;
}
?>
