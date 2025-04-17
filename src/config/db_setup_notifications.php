<?php
/**
 * Additional setup for notifications functionality
 */
function setupNotificationsTable($pdo) {
    try {
        // Create notifications table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `notifications` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `type` VARCHAR(50) NOT NULL,
            `title` VARCHAR(100) NOT NULL,
            `content` TEXT NOT NULL,
            `is_read` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Execute the setup if this file is run directly
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    require_once __DIR__ . '/db.php';
    if (!$db_connection_error) {
        setupNotificationsTable($pdo);
        echo "Notifications table setup complete!";
    } else {
        echo "Database connection error. Could not set up notifications table.";
    }
}
?>
