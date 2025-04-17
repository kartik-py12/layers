<?php
/**
 * Database setup script to create necessary tables for the coupon organizer
 */
function setupDatabase($pdo) {
    try {
        // Create users table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        // Create coupons table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `coupons` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `coupon_name` VARCHAR(100) NOT NULL,
            `coupon_code` VARCHAR(50) NOT NULL,
            `discount` DECIMAL(5,2) NOT NULL,
            `store` VARCHAR(100) NOT NULL,
            `category` VARCHAR(50) NOT NULL,
            `expiry_date` DATE NOT NULL,
            `description` TEXT,
            `is_used` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        // Insert some sample categories if needed
        $pdo->exec("CREATE TABLE IF NOT EXISTS `categories` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL UNIQUE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        // Create shared_coupons table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `shared_coupons` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `coupon_id` INT NOT NULL,
            `user_id` INT NOT NULL,
            `recipient_id` INT NOT NULL,
            `message` TEXT,
            `shared_at` DATETIME NOT NULL,
            FOREIGN KEY (`coupon_id`) REFERENCES `coupons`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`recipient_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        // Create notifications table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `notifications` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `type` VARCHAR(50) NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `content` TEXT NOT NULL,
            `related_id` INT NULL,
            `is_read` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        
        // Add default categories
        $categories = ['Electronics', 'Fashion', 'Food', 'Travel', 'Entertainment', 'Beauty', 'Home', 'Other'];
        $stmt = $pdo->prepare("INSERT IGNORE INTO `categories` (`name`) VALUES (?)");
        
        foreach ($categories as $category) {
            $stmt->execute([$category]);
        }
        
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>
