<?php
/**
 * Test script for checking coupon expiry alerts
 * 
 * This script provides several ways to test if the coupon expiry alerts
 * are working correctly.
 */

// Required to show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Use absolute paths to avoid path issues
$root_dir = __DIR__; // This is the src directory

// Function to show output in browser and also log it
function log_output($message) {
    echo $message . "<br>";
    $log_dir = dirname(__FILE__) . '/logs';
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    file_put_contents($log_dir . '/test_cron.log', date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
}

// Output directory structure for debugging
log_output("Root directory: " . $root_dir);
log_output("Config path: " . $root_dir . "/config/db.php");
log_output("Checking if config file exists: " . (file_exists($root_dir . "/config/db.php") ? "Yes" : "No"));

// Include database connection
require_once $root_dir . "/config/db.php";

// Handle actions
$action = isset($_GET['action']) ? $_GET['action'] : 'status';

switch ($action) {
    case 'run':
        // Run the expiry alert script with test mode
        log_output("Running expiry alert script in test mode");
        include_once $root_dir . "/includes/expiry_alert.php";
        exit;
        
    case 'create_test':
        // Create a test coupon expiring tomorrow
        try {
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            
            // Get the first user from the database
            $user_stmt = $pdo->query("SELECT id, email FROM users LIMIT 1");
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                log_output("Error: No users found in the database. Please create a user first.");
                break;
            }
            
            // Create a test coupon
            $stmt = $pdo->prepare("
                INSERT INTO coupons (user_id, coupon_name, coupon_code, discount, store, category, expiry_date, description, created_at)
                VALUES (:user_id, :name, :code, :discount, :store, :category, :expiry, :description, NOW())
            ");
            
            $stmt->execute([
                ':user_id' => $user['id'],
                ':name' => 'TEST COUPON - Will expire tomorrow',
                ':code' => 'TEST' . rand(1000, 9999),
                ':discount' => 25,
                ':store' => 'Test Store',
                ':category' => 'Test',
                ':expiry' => $tomorrow,
                ':description' => 'This is a test coupon created to test the expiry alert system'
            ]);
            
            $coupon_id = $pdo->lastInsertId();
            
            log_output("Test coupon created successfully (ID: $coupon_id) for user {$user['email']} with expiry date $tomorrow");
            
        } catch (PDOException $e) {
            log_output("Error creating test coupon: " . $e->getMessage());
        }
        break;
        
    case 'view_log':
        // Display the expiry alert log
        $log_file = $root_dir . '/logs/expiry_alerts.log';
        if (file_exists($log_file)) {
            echo "<pre>";
            echo htmlspecialchars(file_get_contents($log_file));
            echo "</pre>";
        } else {
            log_output("Log file not found. Run the script first to generate logs.");
        }
        exit;
        
    case 'status':
    default:
        // Show test options
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Coupon Expiry Alert Test Tool</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
                .card { background-color: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                .btn { display: inline-block; background-color: #a3e635; color: #000; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; margin-bottom: 10px; }
                .btn-secondary { background-color: #6c757d; color: white; }
                h1, h2 { color: #333; }
                pre { background-color: #f0f0f0; padding: 10px; border-radius: 5px; overflow-x: auto; }
            </style>
        </head>
        <body>
            <h1>Coupon Expiry Alert Test Tool</h1>
            
            <div class="card">
                <h2>Test Options</h2>
                <p>Use these options to test if your coupon expiry alert system is working correctly.</p>
                
                <a href="?action=create_test" class="btn">Create Test Coupon (Expires Tomorrow)</a>
                <a href="?action=run" class="btn">Run Expiry Check Now</a>
                <a href="?action=view_log" class="btn btn-secondary">View Logs</a>
            </div>
            
            <div class="card">
                <h2>About Cron Jobs</h2>
                <p>The coupon expiry alert system relies on a scheduled task (cron job) to run automatically. Here's how to verify it's working:</p>
                
                <ol>
                    <li>Create a test coupon that expires tomorrow using the button above</li>
                    <li>Run the expiry check manually using the button above</li>
                    <li>Check the logs to see if the system found your test coupon and sent an email</li>
                    <li>Check your email to confirm you received the alert</li>
                </ol>
                
                <p>If you've set up the Windows Task Scheduler correctly, this process will run automatically at your scheduled time.</p>
                
                <h3>Scheduled Task Verification</h3>
                <p>To verify your Windows Task Scheduler setup:</p>
                <ol>
                    <li>Open Task Scheduler</li>
                    <li>Locate your "Coupon Expiry Check" task</li>
                    <li>Right-click and select "Run" to test it immediately</li>
                    <li>Check the "Last Run Result" column - it should show "0x0" if successful</li>
                    <li>Check the logs using the "View Logs" button above</li>
                </ol>
            </div>
        </body>
        </html>
        <?php
        break;
}
?>
