<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["user_id"])){
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

// Include database connection
require_once "../config/db.php";

// Check if ID parameter exists
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Invalid coupon ID"]);
    exit;
}

$coupon_id = intval($_GET['id']);

// Handle database connection errors
if ($db_connection_error) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Database connection error: " . $db_error_message]);
    exit;
}

try {
    // Get coupon data (make sure it belongs to the current user or is shared with them)
    $stmt = $pdo->prepare("
        SELECT * FROM coupons 
        WHERE id = :coupon_id 
        AND (user_id = :user_id 
            OR id IN (SELECT coupon_id FROM shared_coupons WHERE recipient_id = :user_id))
    ");
    $stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt->execute();
    
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($coupon) {
        // Return coupon data as JSON
        header('Content-Type: application/json');
        echo json_encode(["success" => true, "coupon" => $coupon]);
    } else {
        // No coupon found or user doesn't have access
        header('Content-Type: application/json');
        echo json_encode(["success" => false, "message" => "Coupon not found or you don't have permission to access it"]);
    }
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
