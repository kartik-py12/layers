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

// Define variables
$notifications = [];
$unread_count = 0;

// Mark notification as read if requested
if(isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $notif_id = intval($_GET['mark_read']);
    try {
        $mark_stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id");
        $mark_stmt->bindParam(":id", $notif_id, PDO::PARAM_INT);
        $mark_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $mark_stmt->execute();
        
        // Redirect to remove the query string
        header("Location: notifications.php");
        exit;
    } catch(PDOException $e) {
        $error_message = "Error updating notification: " . $e->getMessage();
    }
}

// Mark all as read if requested
if(isset($_GET['mark_all_read'])) {
    try {
        $mark_all_stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :user_id");
        $mark_all_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $mark_all_stmt->execute();
        
        // Redirect to remove the query string
        header("Location: notifications.php");
        exit;
    } catch(PDOException $e) {
        $error_message = "Error updating notifications: " . $e->getMessage();
    }
}

// Handle database connection errors
if ($db_connection_error) {
    $error_message = "Database connection error: " . $db_error_message;
} else {
    // Get notifications for the user
    try {
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->execute();
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get unread count
        $unread_stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = 0");
        $unread_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $unread_stmt->execute();
        $unread_count = $unread_stmt->fetchColumn();
        
        // Check for expiring coupons and create notifications if needed
        $current_date = date('Y-m-d');
        $expiry_date = date('Y-m-d', strtotime('+3 days'));
        
        $check_expiry_sql = "SELECT * FROM coupons 
                            WHERE user_id = :user_id 
                            AND expiry_date BETWEEN :current_date AND :expiry_date
                            AND is_used = 0
                            AND id NOT IN (
                                SELECT SUBSTRING_INDEX(content, ':', 1) 
                                FROM notifications 
                                WHERE user_id = :user_id 
                                AND type = 'expiry_alert'
                                AND created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)
                            )";
        
        $check_stmt = $pdo->prepare($check_expiry_sql);
        $check_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $check_stmt->bindParam(":current_date", $current_date, PDO::PARAM_STR);
        $check_stmt->bindParam(":expiry_date", $expiry_date, PDO::PARAM_STR);
        $check_stmt->execute();
        
        $expiring_coupons = $check_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Create notifications for expiring coupons
        if(!empty($expiring_coupons)) {
            $insert_notif_sql = "INSERT INTO notifications (user_id, type, title, content, is_read, created_at) 
                                VALUES (:user_id, 'expiry_alert', 'Coupon Expiring Soon', :content, 0, NOW())";
            
            foreach($expiring_coupons as $coupon) {
                $days_left = floor((strtotime($coupon['expiry_date']) - strtotime($current_date)) / (60 * 60 * 24));
                $content = $coupon['id'] . ": Your coupon \"" . $coupon['coupon_name'] . "\" expires in " . $days_left . " days (on " . date('M d, Y', strtotime($coupon['expiry_date'])) . ")";
                
                $insert_stmt = $pdo->prepare($insert_notif_sql);
                $insert_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
                $insert_stmt->bindParam(":content", $content, PDO::PARAM_STR);
                $insert_stmt->execute();
            }
            
            // Refresh notifications after adding new ones
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Update unread count
            $unread_stmt->execute();
            $unread_count = $unread_stmt->fetchColumn();
        }
        
    } catch(PDOException $e) {
        $error_message = "Error fetching notifications: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Digital Coupon Organizer</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .notification-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -5px rgba(59, 130, 246, 0.1);
        }
        .notification-unread {
            border-left: 4px solid #3b82f6;
        }
        @keyframes pulse-blue {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }
        .pulse-blue {
            animation: pulse-blue 2s infinite;
        }
        .notification-expiry {
            border-left: 4px solid #ef4444;
        }
        .notification-share {
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen">
    <!-- Navbar -->
    <header class="py-4 lg:py-6 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="../index.php">
                        <img class="h-8 w-auto" src="../assets/images/logo.svg" alt="Digital Coupon Organizer">
                    </a>
                    <nav class="hidden md:flex ml-10 space-x-8">
                        <a href="dashboard.php" class="text-white/70 hover:text-lime-400">Dashboard</a>
                        <a href="notifications.php" class="text-lime-400 hover:text-lime-400">
                            Notifications
                            <?php if($unread_count > 0): ?>
                                <span class="inline-flex items-center justify-center size-5 ml-1 rounded-full bg-lime-400 text-black text-xs font-medium"><?php echo $unread_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="faqs.php" class="text-white/70 hover:text-lime-400">FAQs</a>
                    </nav>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                    
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center gap-2 text-sm focus:outline-none">
    <?php if(!empty($_SESSION["profile_image"])): ?>
        <img src="../<?php echo htmlspecialchars($_SESSION["profile_image"]); ?>" class="w-8 h-8 rounded-full object-cover" alt="Profile">
    <?php else: ?>
        <div class="w-8 h-8 rounded-full bg-lime-400 flex items-center justify-center text-black font-medium">
            <?php echo substr($_SESSION["name"], 0, 1); ?>
        </div>
    <?php endif; ?>
    <span class="hidden md:block"><?php echo htmlspecialchars($_SESSION["name"]); ?></span>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
</button>
                        <!-- Dropdown menu -->
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 rounded-lg bg-neutral-900 border border-white/10 shadow-lg py-1 z-10">
                            <a href="profile.php" class="block px-4 py-2 text-sm hover:bg-white/5">Profile</a>
                            <a href="settings.php" class="block px-4 py-2 text-sm hover:bg-white/5">Settings</a>
                            <div class="border-t border-white/10"></div>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-red-400 hover:bg-white/5 hover:text-red-500">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden hidden pt-4 pb-3 border-t border-white/10 mt-4">
                <div class="space-y-1">
                    <a href="dashboard.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">Dashboard</a>
                    <a href="notifications.php" class="block px-3 py-2 text-base font-medium text-lime-400 hover:text-lime-400">
                        Notifications
                        <?php if($unread_count > 0): ?>
                            <span class="inline-flex items-center justify-center size-5 ml-1 rounded-full bg-lime-400 text-black text-xs font-medium"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="faqs.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">FAQs</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 lg:px-8 py-8">
        <?php if(isset($error_message)): ?>
            <div class="bg-red-500/20 border border-red-500 text-white px-4 py-3 rounded-lg mb-6">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-medium text-lime-400">Notifications</h1>
                <p class="text-white/50 mt-1">Stay updated with your coupons and activities</p>
            </div>
            
            <?php if(count($notifications) > 0): ?>
                <a href="?mark_all_read=1" class="mt-4 md:mt-0 bg-white/10 text-white font-medium py-2 px-4 rounded-lg hover:bg-white/15 transition-colors">
                    Mark All as Read
                </a>
            <?php endif; ?>
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            <?php if(empty($notifications)): ?>
                <div class="bg-neutral-900 rounded-xl border border-white/10 p-6 text-center">
                    <div class="inline-flex items-center justify-center size-20 rounded-full bg-neutral-800 border border-white/10 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    </div>
                    <h3 class="text-xl font-medium">No notifications</h3>
                    <p class="text-white/50 mt-2">You're all caught up! Check back later for updates about your coupons.</p>
                </div>
            <?php else: ?>
                <?php foreach($notifications as $notification): ?>
                    <?php 
                    $is_unread = !$notification['is_read'];
                    $notification_class = $is_unread ? 'notification-unread ' : '';
                    
                    if($notification['type'] === 'expiry_alert') {
                        $notification_class .= 'notification-expiry ';
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-500"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                    } else if($notification['type'] === 'shared_coupon') {
                        $notification_class .= 'notification-share ';
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>';
                    } else {
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lime-400"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>';
                    }
                    ?>
                    <div class="notification-card bg-neutral-900 rounded-xl border border-white/10 p-4 <?php echo $notification_class; ?> <?php echo $is_unread ? 'pulse-blue' : ''; ?>">
                        <div class="flex items-start">
                            <div class="shrink-0 mr-4">
                                <?php echo $icon; ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-medium"><?php echo htmlspecialchars($notification['title']); ?></h3>
                                    <div class="text-xs text-white/50">
                                        <?php echo date('M d, g:i A', strtotime($notification['created_at'])); ?>
                                    </div>
                                </div>
                                <p class="text-white/70 mt-1">
                                    <?php echo htmlspecialchars($notification['content']); ?>
                                </p>
                                <?php if($is_unread): ?>
                                    <div class="mt-3 flex justify-end">
                                        <a href="?mark_read=<?php echo $notification['id']; ?>" class="text-sm text-blue-400 hover:text-blue-300">Mark as read</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // User dropdown
        const userMenuButton = document.getElementById('user-menu-button');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function() {
                userDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }
        
        // Mobile menu
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
