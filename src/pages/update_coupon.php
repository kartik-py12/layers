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

// Process form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate input
    if(empty($_POST["coupon_id"])){
        header("location: dashboard.php?error=Invalid coupon ID");
        exit;
    }
    
    $coupon_id = trim($_POST["coupon_id"]);
    $coupon_name = trim($_POST["coupon_name"]);
    $coupon_code = trim($_POST["coupon_code"]);
    $discount = trim($_POST["discount"]);
    $store = trim($_POST["store"]);
    $category = trim($_POST["category"]);
    $expiry_date = trim($_POST["expiry_date"]);
    $description = trim($_POST["description"]);
    
    // Simple validation
    if(empty($coupon_name) || empty($coupon_code) || empty($discount) || empty($store) || empty($category) || empty($expiry_date)){
        // Redirect back with error
        header("location: dashboard.php?error=All fields are required");
        exit;
    }
    
    // Check if the coupon belongs to the user
    $check_sql = "SELECT id FROM coupons WHERE id = :coupon_id AND user_id = :user_id";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
    $check_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
    $check_stmt->execute();
    
    if($check_stmt->rowCount() == 0){
        // Coupon doesn't belong to user
        header("location: dashboard.php?error=You don't have permission to update this coupon");
        exit;
    }
    
    // Update coupon in database
    $sql = "UPDATE coupons 
            SET coupon_name = :coupon_name, coupon_code = :coupon_code, discount = :discount, 
                store = :store, category = :category, expiry_date = :expiry_date, description = :description 
            WHERE id = :coupon_id AND user_id = :user_id";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind parameters
        $stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->bindParam(":coupon_name", $coupon_name, PDO::PARAM_STR);
        $stmt->bindParam(":coupon_code", $coupon_code, PDO::PARAM_STR);
        $stmt->bindParam(":discount", $discount, PDO::PARAM_STR);
        $stmt->bindParam(":store", $store, PDO::PARAM_STR);
        $stmt->bindParam(":category", $category, PDO::PARAM_STR);
        $stmt->bindParam(":expiry_date", $expiry_date, PDO::PARAM_STR);
        $stmt->bindParam(":description", $description, PDO::PARAM_STR);
        
        // Execute the prepared statement
        if($stmt->execute()){
            // Coupon updated successfully, redirect to dashboard
            header("location: dashboard.php?success=Coupon updated successfully");
            exit;
        } else{
            // Redirect back with error
            header("location: dashboard.php?error=Something went wrong. Please try again later.");
            exit;
        }
        
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
} else{
    // Not a POST request, redirect to dashboard
    header("location: dashboard.php");
    exit;
}
?>
