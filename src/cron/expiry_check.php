<?php
/**
 * This script checks for coupons that are about to expire and sends notifications
 * It should be run daily via cron job or scheduled task
 */

// Include database connection
require_once "../config/db.php";

// Set up our date thresholds
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$threeDays = date('Y-m-d', strtotime('+3 days'));
$oneWeek = date('Y-m-d', strtotime('+7 days'));

// Check for each threshold
checkExpiringCoupons($pdo, $tomorrow, 'tomorrow');
checkExpiringCoupons($pdo, $threeDays, '3 days');
checkExpiringCoupons($pdo, $oneWeek, 'one week');

/**
 * Checks for coupons expiring at the given date and creates notifications
 */
function checkExpiringCoupons($pdo, $date, $timeframe) {
    try {
        // Get coupons expiring on the target date
        $sql = "SELECT c.id, c.user_id, c.coupon_name, c.store, c.expiry_date, u.name 
                FROM coupons c 
                JOIN users u ON c.user_id = u.id
                WHERE c.expiry_date = :expiry_date 
                AND c.is_used = 0
                AND NOT EXISTS (
                    SELECT 1 FROM notifications n 
                    WHERE n.user_id = c.user_id 
                    AND n.type = 'expiry_reminder'
                    AND n.related_id = c.id
                    AND n.created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)
                )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':expiry_date', $date, PDO::PARAM_STR);
        $stmt->execute();
        
        $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Create notifications for each coupon
        foreach ($coupons as $coupon) {
            $title = "Coupon Expiring Soon";
            $content = "Your coupon '{$coupon['coupon_name']}' from {$coupon['store']} will expire in $timeframe (on " . date('M d, Y', strtotime($coupon['expiry_date'])) . ").";
            
            $notif_sql = "INSERT INTO notifications (user_id, type, title, content, related_id, is_read, created_at) 
                           VALUES (:user_id, 'expiry_reminder', :title, :content, :related_id, 0, NOW())";
            $notif_stmt = $pdo->prepare($notif_sql);
            $notif_stmt->bindParam(':user_id', $coupon['user_id'], PDO::PARAM_INT);
            $notif_stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $notif_stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $notif_stmt->bindParam(':related_id', $coupon['id'], PDO::PARAM_INT);
            $notif_stmt->execute();
            
            echo "Created notification for {$coupon['name']} about {$coupon['coupon_name']} expiring in $timeframe\n";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "Expiry check completed successfully\n";
