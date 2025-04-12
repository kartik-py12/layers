<?php
// Script to check for coupons that are about to expire and send email notifications
// This should be executed by a cron job daily

// Include database connection
require_once __DIR__ . "/../config/db.php";

// Function to send email notification
function sendExpiryNotification($user_email, $user_name, $coupons) {
    // Set email headers
    $to = $user_email;
    $subject = "Your coupons are about to expire!";
    
    // Create email body with HTML formatting
    $message = "
    <html>
    <head>
        <title>Coupon Expiry Notification</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #a3e635; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
            .content { padding: 20px; background-color: #f9f9f9; border-radius: 0 0 5px 5px; }
            .coupon { margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #fff; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
            .button { display: inline-block; padding: 10px 20px; background-color: #a3e635; color: #000; text-decoration: none; border-radius: 5px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Coupon Expiry Alert</h1>
            </div>
            <div class='content'>
                <p>Hello $user_name,</p>
                <p>The following coupons in your account are about to expire in the next 2 days:</p>";
    
    // Add each coupon to the email
    foreach($coupons as $coupon) {
        $expiry_date = date("F j, Y", strtotime($coupon['expiry_date']));
        $message .= "
                <div class='coupon'>
                    <h3>{$coupon['coupon_name']} ({$coupon['discount']}% Off)</h3>
                    <p><strong>Store:</strong> {$coupon['store']}</p>
                    <p><strong>Code:</strong> {$coupon['coupon_code']}</p>
                    <p><strong>Expires:</strong> $expiry_date</p>
                </div>";
    }
    
    // Add call to action and footer
    $message .= "
                <p>Don't miss out on these savings!</p>
                <p><a href='http://localhost/layers/src/pages/dashboard.php' class='button'>View Your Coupons</a></p>
            </div>
            <div class='footer'>
                <p>This is an automated message from Digital Coupon Organizer.</p>
                <p>© " . date("Y") . " Digital Coupon Organizer. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>";
    
    // Set email headers for HTML content
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Digital Coupon Organizer <noreply@couponorganizer.com>" . "\r\n";
    
    // Send the email
    return mail($to, $subject, $message, $headers);
}

try {
    // Get current date
    $current_date = date('Y-m-d');
    
    // Calculate date 2 days from now
    $expiry_threshold = date('Y-m-d', strtotime('+2 days'));
    
    // Get all users with coupons expiring in the next 2 days
    $users_stmt = $pdo->prepare("
        SELECT DISTINCT u.id, u.name, u.email 
        FROM users u
        JOIN coupons c ON u.id = c.user_id
        WHERE c.expiry_date BETWEEN :current_date AND :expiry_threshold
        AND c.is_used = 0
    ");
    
    $users_stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $users_stmt->bindParam(':expiry_threshold', $expiry_threshold, PDO::PARAM_STR);
    $users_stmt->execute();
    
    $users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // For each user with expiring coupons
    foreach($users as $user) {
        // Get all expiring coupons for this user
        $coupons_stmt = $pdo->prepare("
            SELECT * FROM coupons 
            WHERE user_id = :user_id 
            AND expiry_date BETWEEN :current_date AND :expiry_threshold
            AND is_used = 0
        ");
        
        $coupons_stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
        $coupons_stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
        $coupons_stmt->bindParam(':expiry_threshold', $expiry_threshold, PDO::PARAM_STR);
        $coupons_stmt->execute();
        
        $expiring_coupons = $coupons_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Send email notification
        if(count($expiring_coupons) > 0) {
            $email_sent = sendExpiryNotification($user['email'], $user['name'], $expiring_coupons);
            
            // Log the result
            $log_message = date('Y-m-d H:i:s') . " - Email notification " . 
                          ($email_sent ? "sent" : "failed") . 
                          " for user ID: " . $user['id'] . 
                          " (" . count($expiring_coupons) . " expiring coupons)";
            
            // Write to log file
            file_put_contents(__DIR__ . '/email_log.txt', $log_message . PHP_EOL, FILE_APPEND);
        }
    }
    
    echo "Coupon expiry check completed successfully.";
    
} catch(PDOException $e) {
    // Log error
    $error_message = date('Y-m-d H:i:s') . " - ERROR: " . $e->getMessage();
    file_put_contents(__DIR__ . '/email_log.txt', $error_message . PHP_EOL, FILE_APPEND);
    
    echo "Error executing coupon expiry check: " . $e->getMessage();
}
?>
