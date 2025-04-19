<?php
// Script to send email alerts for coupons expiring in 24 hours

// Fix path issues by using __DIR__ to get absolute paths
$root_dir = dirname(dirname(__FILE__)); // This gets the parent directory of the current file (src folder)
require_once $root_dir . "/config/db.php";

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Fix the path to autoload.php - point to project root vendor directory
$project_root = dirname($root_dir); // Go up one more level to get to project root
require $project_root . '/vendor/autoload.php'; // Look for vendor at project root

// Email configuration
$mail_config = [
    'host' => 'smtp.gmail.com', // Change to your SMTP server
    'username' => 'kartikevergreen4@gmail.com', // Change to your email
    'password' => 'iqfq exbb mudd soof', // Change to your email password or app password
    'port' => 587,
    'from_email' => 'kartikevergreen4@gmail.com',
    'from_name' => 'Layers - Coupon Organizer'
];

// Check if this is a test run
$is_test = isset($_GET['test']) && $_GET['test'] == 1;

// Get coupons expiring in 24 hours
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$today = date('Y-m-d');

// If this is a test run, we can use specific dates for testing
if ($is_test) {
    // Create a log directory if it doesn't exist
    if (!is_dir($root_dir . '/logs')) {
        mkdir($root_dir . '/logs', 0755, true);
    }
    
    // Log test execution
    file_put_contents($root_dir . '/logs/expiry_alerts.log', date('Y-m-d H:i:s') . " - TEST RUN STARTED\n", FILE_APPEND);
    
    // Check if a specific test date is specified
    if (isset($_GET['date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date'])) {
        $tomorrow = $_GET['date'];
        file_put_contents($root_dir . '/logs/expiry_alerts.log', date('Y-m-d H:i:s') . " - Using test date: $tomorrow\n", FILE_APPEND);
    }
}

try {
    // Find all coupons expiring tomorrow that belong to active users and are not yet used
    $stmt = $pdo->prepare("
        SELECT c.*, u.email, u.name 
        FROM coupons c
        JOIN users u ON c.user_id = u.id
        WHERE c.expiry_date = :tomorrow 
        AND c.is_used = 0
    ");
    $stmt->bindParam(':tomorrow', $tomorrow);
    $stmt->execute();
    $expiring_coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Log activity
    file_put_contents($root_dir . '/logs/expiry_alerts.log', date('Y-m-d H:i:s') . " - Found " . count($expiring_coupons) . " coupons expiring tomorrow.\n", FILE_APPEND);
    
    // Process each coupon and send email
    foreach ($expiring_coupons as $coupon) {
        if (!empty($coupon['email'])) {
            sendExpiryAlert($coupon, $mail_config);
        }
    }
    
} catch (PDOException $e) {
    // Log error
    file_put_contents($root_dir . '/logs/expiry_alerts.log', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
}

/**
 * Send expiry alert email to user
 */
function sendExpiryAlert($coupon, $mail_config) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
        $mail->isSMTP();
        $mail->Host = $mail_config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $mail_config['username'];
        $mail->Password = $mail_config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $mail_config['port'];
        
        // Recipients
        $mail->setFrom($mail_config['from_email'], $mail_config['from_name']);
        $mail->addAddress($coupon['email'], $coupon['name']);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Coupon Expires Tomorrow - Don\'t Miss Out!';
        
        // Create HTML email body
        $email_body = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #0a0a0a; color: #ffffff; border-radius: 10px; border: 1px solid rgba(255,255,255,0.1);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: #a3e635;">Coupon Expiring Tomorrow!</h1>
                <p style="color: rgba(255,255,255,0.7);">Don\'t miss your chance to save.</p>
            </div>
            
            <div style="background-color: #171717; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.1);">
                <h2 style="margin-top: 0; color: #ffffff;">' . htmlspecialchars($coupon['coupon_name']) . '</h2>
                <p style="font-size: 14px; color: rgba(255,255,255,0.7);">Store: ' . htmlspecialchars($coupon['store']) . '</p>
                <p style="font-size: 14px; color: rgba(255,255,255,0.7);">Category: ' . htmlspecialchars($coupon['category']) . '</p>
                
                <div style="background-color: #262626; padding: 10px; text-align: center; border-radius: 4px; margin: 15px 0; font-family: monospace; font-size: 18px;">
                    ' . htmlspecialchars($coupon['coupon_code']) . '
                </div>
                
                <p style="font-size: 16px; font-weight: bold; color: #a3e635;">Discount: ' . $coupon['discount'] . '%</p>
                <p style="color: #ef4444; font-weight: bold;">Expires: ' . date('F j, Y', strtotime($coupon['expiry_date'])) . ' (Tomorrow)</p>
            </div>
            
            <div style="text-align: center;">
                <a href="' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . '/layers/src/pages/dashboard.php" style="display: inline-block; background-color: #a3e635; color: #000000; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">View in Dashboard</a>
            </div>
            
            <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; text-align: center; font-size: 12px; color: rgba(255,255,255,0.5);">
                <p>This is an automated message from Layers - Your Coupon Organizer.</p>
                <p>If you no longer wish to receive these notifications, you can update your preferences in your account settings.</p>
            </div>
        </div>
        ';
        
        $mail->Body = $email_body;
        $mail->AltBody = "Your coupon '{$coupon['coupon_name']}' for {$coupon['store']} expires tomorrow! Coupon code: {$coupon['coupon_code']}. Log in to your account to use it before it expires.";
        
        $mail->send();
        
        // Log successful email
        file_put_contents($root_dir . '/logs/expiry_alerts.log', date('Y-m-d H:i:s') . " - Email sent to {$coupon['email']} for coupon ID: {$coupon['id']}\n", FILE_APPEND);
        
    } catch (Exception $e) {
        // Log mail sending error
        file_put_contents($root_dir . '/logs/expiry_alerts.log', date('Y-m-d H:i:s') . " - Failed to send email to {$coupon['email']} for coupon ID: {$coupon['id']}. Error: {$mail->ErrorInfo}\n", FILE_APPEND);
    }
}
?>
