<?php
// This script provides instructions on how to set up the cron job for expiring coupon checks

// Get the full path to the check_expiring_coupons.php script
$script_path = realpath(__DIR__ . '/check_expiring_coupons.php');

// Check if the path exists
if(!file_exists($script_path)) {
    echo "Error: Could not find the check_expiring_coupons.php script.";
    exit;
}

// Determine the PHP executable path
$php_path = PHP_BINARY;
if(empty($php_path)) {
    $php_path = '/usr/bin/php'; // Default path, may need adjustment
}

// Generate the cron job command
$cron_command = "0 9 * * * $php_path $script_path";

// Output instructions
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cron Job Setup Instructions</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
        code { background-color: #f4f4f4; padding: 2px 5px; border-radius: 3px; font-family: monospace; }
        pre { background-color: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .note { background-color: #ffffd9; border-left: 4px solid #e7c000; padding: 10px; margin: 15px 0; }
    </style>
</head>
<body>
    <h1>Setup Cron Job for Expiring Coupon Notifications</h1>
    
    <p>To set up automatic checking for expiring coupons and send email notifications, you need to create a cron job on your server.</p>
    
    <h2>Instructions for Linux/Unix Servers:</h2>
    
    <ol>
        <li>
            <p>Open your terminal and edit your crontab file with:</p>
            <pre><code>crontab -e</code></pre>
        </li>
        
        <li>
            <p>Add the following line to run the script daily at 9:00 AM:</p>
            <pre><code><?php echo $cron_command; ?></code></pre>
        </li>
        
        <li>
            <p>Save and exit the editor</p>
        </li>
    </ol>
    
    <h2>Instructions for Windows Servers:</h2>
    
    <ol>
        <li>
            <p>Open Task Scheduler from the Start menu</p>
        </li>
        
        <li>
            <p>Create a new Basic Task and follow the wizard:</p>
            <ul>
                <li>Name: "Coupon Expiry Check"</li>
                <li>Trigger: Daily at 9:00 AM</li>
                <li>Action: Start a program</li>
                <li>Program/script: <code>C:\xampp\php\php.exe</code></li>
                <li>Add arguments: <code>C:\xampp\htdocs\layers\cron.php</code></li>
                <li>Start in: <code>C:\xampp\htdocs\layers</code></li>
            </ul>
        </li>
    </ol>
    
    <div class="note">
        <h3>Important Notes:</h3>
        <ul>
            <li>Make sure the PHP path is correct for your system</li>
            <li>Ensure your web server has permission to send emails</li>
            <li>You may need to configure your PHP mail settings in php.ini</li>
            <li>For testing, you can run the script manually by visiting this URL: <a href="../cron/check_expiring_coupons.php">Run Expiry Check Now</a></li>
        </ul>
    </div>
    
    <p><strong>Script Path:</strong> <code><?php echo $script_path; ?></code></p>
    <p><strong>PHP Path:</strong> <code><?php echo $php_path; ?></code></p>
</body>
</html>
