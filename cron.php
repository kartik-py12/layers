<?php
/**
 * This file is designed to be called by a cron job or scheduled task
 * It runs the coupon expiry alert system
 * 
 * Example cron job (runs daily at midnight):
 * 0 0 * * * php /path/to/xampp/htdocs/layers/cron.php
 */

// Set working directory
chdir(__DIR__);

// Run the expiry alert script
require_once "src/includes/expiry_alert.php";

echo "Coupon expiry check completed at " . date('Y-m-d H:i:s') . "\n";
?>
