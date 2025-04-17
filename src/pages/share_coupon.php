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

// Define variables
$coupon_id = "";
$recipient_email = "";
$error = "";
$success = "";

// Process data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate coupon ID
    if(empty(trim($_POST["coupon_id"]))){
        $error = "Invalid coupon.";
    } else{
        $coupon_id = trim($_POST["coupon_id"]);
    }
    
    // Validate recipient email
    if(empty(trim($_POST["recipient_email"]))){
        $error = "Please enter recipient email.";
    } else{
        $recipient_email = trim($_POST["recipient_email"]);
        
        // Check if email format is valid
        if(!filter_var($recipient_email, FILTER_VALIDATE_EMAIL)){
            $error = "Invalid email format.";
        }
    }
    
    // Check if errors exist before continuing
    if(empty($error)){
        // Verify that the coupon belongs to the current user
        $sql = "SELECT * FROM coupons WHERE id = :coupon_id AND user_id = :user_id";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
            $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    // Coupon belongs to user, now check if recipient exists
                    $sql = "SELECT id, name FROM users WHERE email = :email";
                    
                    if($stmt = $pdo->prepare($sql)){
                        $stmt->bindParam(":email", $recipient_email, PDO::PARAM_STR);
                        
                        if($stmt->execute()){
                            if($stmt->rowCount() == 1){
                                // Recipient exists, get their ID
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $recipient_id = $row["id"];
                                $recipient_name = $row["name"];
                                
                                // Check if already shared with this user
                                $sql = "SELECT * FROM shared_coupons WHERE coupon_id = :coupon_id AND recipient_id = :recipient_id";
                                if($stmt = $pdo->prepare($sql)){
                                    $stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
                                    $stmt->bindParam(":recipient_id", $recipient_id, PDO::PARAM_INT);
                                    
                                    if($stmt->execute()){
                                        if($stmt->rowCount() > 0){
                                            $error = "You've already shared this coupon with this user.";
                                        } else {
                                            // Get coupon details for notification
                                            $coupon_sql = "SELECT coupon_name FROM coupons WHERE id = :coupon_id";
                                            $coupon_stmt = $pdo->prepare($coupon_sql);
                                            $coupon_stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
                                            $coupon_stmt->execute();
                                            $coupon_data = $coupon_stmt->fetch(PDO::FETCH_ASSOC);
                                            $coupon_name = $coupon_data['coupon_name'];
                                            
                                            // Share the coupon
                                            $sql = "INSERT INTO shared_coupons (coupon_id, user_id, recipient_id, shared_at) VALUES (:coupon_id, :user_id, :recipient_id, NOW())";
                                            
                                            if($stmt = $pdo->prepare($sql)){
                                                $stmt->bindParam(":coupon_id", $coupon_id, PDO::PARAM_INT);
                                                $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
                                                $stmt->bindParam(":recipient_id", $recipient_id, PDO::PARAM_INT);
                                                
                                                if($stmt->execute()){
                                                    // Add notification for recipient
                                                    $notif_sql = "INSERT INTO notifications (user_id, type, title, content, is_read, created_at) 
                                                                 VALUES (:user_id, 'shared_coupon', 'New Coupon Shared', :content, 0, NOW())";
                                                    $notif_stmt = $pdo->prepare($notif_sql);
                                                    $notif_stmt->bindParam(":user_id", $recipient_id, PDO::PARAM_INT);
                                                    $notif_content = $_SESSION["name"] . " shared a coupon with you: " . $coupon_name;
                                                    $notif_stmt->bindParam(":content", $notif_content, PDO::PARAM_STR);
                                                    $notif_stmt->execute();
                                                    
                                                    // Success, redirect to dashboard
                                                    header("location: dashboard.php?success=coupon_shared");
                                                    exit();
                                                } else{
                                                    $error = "Something went wrong. Please try again later.";
                                                }
                                            }
                                        }
                                    } else{
                                        $error = "Oops! Something went wrong. Please try again later.";
                                    }
                                }
                            } else{
                                $error = "No user found with that email.";
                            }
                        } else{
                            $error = "Oops! Something went wrong. Please try again later.";
                        }
                    }
                } else{
                    // Coupon doesn't belong to user
                    $error = "You can only share coupons that belong to you.";
                }
            } else{
                $error = "Oops! Something went wrong. Please try again later.";
            }
        }
    }
    
    // If we have an error, redirect back to dashboard with error
    if(!empty($error)){
        header("location: dashboard.php?error=" . urlencode($error));
        exit();
    }
} else{
    // Not a POST request, redirect to dashboard
    header("location: dashboard.php");
    exit;
}
?>
