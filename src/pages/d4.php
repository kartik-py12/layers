<?php
// Include the auth header at the top of the file
require_once "../includes/auth_header.php";

// Initialize the session (if not already done in auth_header)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["user_id"])){
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../config/db.php";

// Define variables
$coupons = [];
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'expiry_asc';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Handle database connection errors
if ($db_connection_error) {
    $error_message = "Database connection error: " . $db_error_message;
} else {
    // Prepare query based on filters
    // $sql = "SELECT * FROM coupons WHERE user_id = :user_id";
    $sql = "SELECT c.*, 
    CASE WHEN c.user_id = :user_id THEN 0 ELSE 1 END AS is_shared,
    CASE WHEN c.user_id != :user_id THEN (SELECT name FROM users WHERE id = c.user_id) ELSE NULL END AS shared_by
    FROM coupons c 
    WHERE c.user_id = :user_id OR c.id IN (
        SELECT coupon_id FROM shared_coupons WHERE recipient_id = :user_id
    )";

    // Add search condition if provided
    if(!empty($search)) {
        $sql .= " AND (coupon_name LIKE :search OR category LIKE :search OR store LIKE :search OR coupon_code LIKE :search)";
    }

    // Add category filter if provided
    if(!empty($filter)) {
        $sql .= " AND category = :filter";
    }

    // Add sorting
    switch($sort) {
        case 'expiry_desc':
            $sql .= " ORDER BY expiry_date DESC";
            break;
        case 'discount_desc':
            $sql .= " ORDER BY discount DESC";
            break;
        case 'discount_asc':
            $sql .= " ORDER BY discount ASC";
            break;
        case 'expiry_asc':
        default:
            $sql .= " ORDER BY expiry_date ASC";
            break;
    }
    

    // Prepare and execute the query
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        
        if(!empty($search)) {
            $search_param = "%{$search}%";
            $stmt->bindParam(":search", $search_param, PDO::PARAM_STR);
        }
        
        if(!empty($filter)) {
            $stmt->bindParam(":filter", $filter, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error_message = "Error fetching coupons: " . $e->getMessage();
    }
        
    // Get available categories for filter dropdown
    try {
        $cat_stmt = $pdo->prepare("SELECT DISTINCT category FROM coupons WHERE user_id = :user_id");
        $cat_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
        $cat_stmt->execute();
        $categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch(PDOException $e) {
        $categories = [];
    }
    
    // Get all predefined categories from the categories table
    try {
        $all_cat_stmt = $pdo->query("SELECT name FROM categories ORDER BY name");
        $all_categories = $all_cat_stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch(PDOException $e) {
        $all_categories = [];
    }
}

// Get unread notification count
$notification_count = 0;
try {
    $notif_stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = 0");
    $notif_stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
    $notif_stmt->execute();
    $notification_count = $notif_stmt->fetchColumn();
} catch(PDOException $e) {
    // Silently fail
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Digital Coupon Organizer</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .coupon-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .coupon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(163, 230, 53, 0.1);
        }
        .copy-animation {
            animation: copy-pulse 1s ease;
        }
        @keyframes copy-pulse {
            0% { opacity: 0; transform: scale(0.5); }
            50% { opacity: 1; transform: scale(1.2); }
            100% { opacity: 0; transform: scale(1); }
        }
        
        /* Toggle Switch Styling - Fixed Version */
        #show-expired:checked ~ .dot {
            transform: translateX(140%); /* Use a specific pixel value */
            background-color: #84cc16; /* Lime-500 */
        }
        
        #show-expired:checked ~ .block {
            background-color: rgba(132, 204, 22, 0.2); /* Lime-500 with opacity */
        }
        
        .dot {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-black text-white min-h-screen">
    <div id="copy-success" class="fixed top-4 right-4 bg-lime-500 text-black rounded-lg px-4 py-2 z-50 hidden">
        Coupon code copied!
    </div>
    <!-- Navbar -->
    <header class="py-4 lg:py-6 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="../index.php">
                        <img class="h-8 w-auto" src="../assets/images/logo.svg" alt="Digital Coupon Organizer">
                    </a>
                    <nav class="hidden md:flex ml-10 space-x-8">
                        <a href="dashboard.php" class="text-lime-400 hover:text-lime-400">Dashboard</a>
                        <a href="notifications.php" class="text-white/70 hover:text-lime-400 relative">
                            Notifications
                            <?php if($notification_count > 0): ?>
                                <span class="absolute -top-2 -right-2 flex items-center justify-center bg-lime-400 text-black rounded-full w-5 h-5 text-xs font-bold"><?php echo $notification_count; ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="faqs.php" class="text-white/70 hover:text-lime-400">FAQs</a>
                    </nav>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Notification bell (mobile) -->
                    <a href="notifications.php" class="md:hidden relative text-white/70 hover:text-lime-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <?php if($notification_count > 0): ?>
                            <span class="absolute -top-2 -right-2 flex items-center justify-center bg-lime-400 text-black rounded-full w-5 h-5 text-xs font-bold"><?php echo $notification_count; ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center gap-2 text-sm focus:outline-none">
                            <?php if(!empty($_SESSION["profile_image"])): ?>
                                <img src="../<?php echo htmlspecialchars($_SESSION["profile_image"]); ?>" class="w-8 h-8 rounded-full object-cover" alt="Profile">
                            <?php else: ?>
                                <div class="size-8 rounded-full bg-lime-400 flex items-center justify-center text-black font-medium">
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
                    <a href="dashboard.php" class="block px-3 py-2 text-base font-medium text-lime-400 hover:text-lime-400">Dashboard</a>
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

        <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-500/20 border border-red-500 text-white px-4 py-3 rounded-lg mb-6">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['success']) && $_GET['success'] === 'coupon_marked_used'): ?>
            <div class="bg-lime-500/20 border border-lime-500 text-white px-4 py-3 rounded-lg mb-6">
                Coupon has been marked as used successfully.
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['success']) && $_GET['success'] === 'coupon_shared'): ?>
            <div class="bg-blue-500/20 border border-blue-500 text-white px-4 py-3 rounded-lg mb-6 pulsing-blue">
                Coupon has been shared successfully!
            </div>
        <?php endif; ?>
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-medium text-lime-400">Your Coupons</h1>
                <p class="text-white/50 mt-1">Manage all your discount coupons in one place</p>
            </div>
            <button id="add-coupon-btn" class="mt-4 md:mt-0 bg-lime-400 text-black font-medium py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-lime-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add New Coupon
            </button>
        </div>

        <!-- Filters and Search -->
        <div class="bg-neutral-900 rounded-xl border border-white/10 p-4 mb-8">
            <form id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium mb-2">Search</label>
                    <input type="text" id="search" name="search" placeholder="Search coupons..." value="<?php echo htmlspecialchars($search); ?>" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-3 py-2">
                </div>
                
                <div>
                    <label for="filter" class="block text-sm font-medium mb-2">Category</label>
                    <select id="filter" name="filter" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-3 py-2 appearance-none">
                        <option value="">All Categories</option>
                        <?php if (isset($categories)): ?>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($filter === $category) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label for="sort" class="block text-sm font-medium mb-2">Sort By</label>
                    <select id="sort" name="sort" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-3 py-2 appearance-none">
                        <option value="expiry_asc" <?php echo ($sort === 'expiry_asc') ? 'selected' : ''; ?>>Expiry Date (Nearest First)</option>
                        <option value="expiry_desc" <?php echo ($sort === 'expiry_desc') ? 'selected' : ''; ?>>Expiry Date (Furthest First)</option>
                        <option value="discount_desc" <?php echo ($sort === 'discount_desc') ? 'selected' : ''; ?>>Discount (Highest First)</option>
                        <option value="discount_asc" <?php echo ($sort === 'discount_asc') ? 'selected' : ''; ?>>Discount (Lowest First)</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-white/10 hover:bg-white/15 text-white font-medium py-2 rounded-lg transition-colors">Apply Filters</button>
                </div>
            </form>
            
            <!-- Expired Coupons Toggle -->
            <div class="mt-4 flex items-center justify-end">
                <label for="show-expired" class="flex items-center cursor-pointer">
                    <span class="text-sm mr-3">Show Expired Coupons</span>
                    <div class="relative">
                        <input type="checkbox" id="show-expired" class="sr-only">
                        <div class="block bg-neutral-800 w-14 h-7 rounded-full"></div>
                        <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition"></div>
                    </div>
                </label>
            </div>
        </div>
        
        <!-- Coupons Grid -->
        <div id="coupons-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if(!isset($coupons) || empty($coupons)): ?>
                <div class="col-span-3 text-center py-12">
                    <div class="inline-flex items-center justify-center size-20 rounded-full bg-neutral-900 border border-white/10 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="10" rx="2" ry="2"></rect><line x1="17" y1="11" x2="17" y2="13"></line><line x1="12" y1="11" x2="12" y2="13"></line><path d="M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"></path><path d="M7 17v2a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-2"></path></svg>
                    </div>
                    <h3 class="text-xl font-medium">No coupons found</h3>
                    <p class="text-white/50 mt-2">Start adding your coupons to track and manage them</p>
                    <button id="add-first-coupon-btn" class="mt-4 bg-lime-400 text-black font-medium py-2 px-4 rounded-lg flex items-center gap-2 mx-auto hover:bg-lime-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add Your First Coupon
                    </button>
                </div>
            <?php else: ?>
                <?php foreach($coupons as $coupon): ?>
                    <?php 
                    // Improved expiration calculation
                    $expiry_timestamp = strtotime($coupon['expiry_date'] . ' 23:59:59'); // Set to end of expiry day
                    $current_timestamp = time();
                    $seconds_left = $expiry_timestamp - $current_timestamp;
                    $days_left = floor($seconds_left / (60 * 60 * 24));
                    $hours_left = floor(($seconds_left % (60 * 60 * 24)) / 3600);
                    
                    $is_expired = $seconds_left < 0;
                    $is_used = $coupon['is_used'] == 1;
                    $is_shared = isset($coupon['is_shared']) && $coupon['is_shared'] == 1;
                    $border_class = $is_expired ? 'border-red-500/50' : ($is_used ? 'border-gray-500/50 opacity-60' : 'border-white/10');
                    $highlight_color = $is_expired ? 'red' : ($is_used ? 'gray' : 'lime');
                    
                    // More precise expiration message
                    $expiry_message = '';
                    if ($is_expired) {
                        $expiry_message = 'Expired';
                    } else if ($is_used) {
                        $expiry_message = 'Used';
                    } else if ($days_left == 0) {
                        $expiry_message = $hours_left > 0 ? "Expires in {$hours_left}h" : "Expires in <1h";
                    } else if ($days_left == 1) {
                        $expiry_message = "Expires tomorrow";
                    } else {
                        $expiry_message = "{$days_left} days left";
                    }
                    ?>
                    <div class="coupon-card bg-neutral-900 rounded-xl border <?php echo $border_class; ?> overflow-hidden group">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-white/10">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center size-10 rounded-lg bg-<?php echo $highlight_color; ?>-400/10 text-<?php echo $highlight_color; ?>-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                </span>
                                <div class="ml-3">
                                    <h3 class="font-medium"><?php echo htmlspecialchars($coupon['coupon_name']); ?></h3>
                                    <p class="text-sm text-white/50"><?php echo htmlspecialchars($coupon['store']); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-<?php echo $highlight_color; ?>-400"><?php echo $coupon['discount']; ?>%</div>
                                <div class="text-xs text-white/50"><?php echo $expiry_message; ?></div>
                            </div>
                        </div>
                        <div class="p-4 <?php echo $is_shared ? 'bg-gradient-to-b from-neutral-900 to-blue-950/30' : ''; ?>">
                            <div class="flex justify-between items-center mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/10"><?php echo htmlspecialchars($coupon['category']); ?></span>
                                <span class="text-sm text-white/50">Expires: <?php echo date('M d, Y', strtotime($coupon['expiry_date'])); ?></span>
                            </div>
                            <div class="relative">
                                <div class="bg-neutral-800 p-2 rounded text-center font-mono text-lg select-all"><?php echo htmlspecialchars($coupon['coupon_code']); ?></div>
                                <button class="copy-btn absolute right-2 top-2 text-white/50 hover:text-white" data-code="<?php echo htmlspecialchars($coupon['coupon_code']); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                </button>
                            </div>
                            <?php if(!empty($coupon['description'])): ?>
                                <p class="mt-3 text-sm text-white/70"><?php echo htmlspecialchars($coupon['description']); ?></p>
                            <?php endif; ?>
                            <!-- Add expiry alert status information -->
                            <?php if(!$is_expired && !$is_used && $days_left <= 2): ?>
                                <div class="mt-2 flex items-center text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-500 mr-1"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                    <span class="text-yellow-500">
                                        <?php 
                                        if ($days_left == 0) {
                                            echo $hours_left > 0 ? "Expires in {$hours_left} hours" : "Expires very soon";
                                        } else if ($days_left == 1) {
                                            echo "Expires tomorrow" . (($hours_left > 12) ? "" : " - Email alert sent");
                                        } else {
                                            echo "Expires in {$days_left} days";
                                        }
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="mt-4 pt-3 border-t border-white/10 flex justify-between">
                                <div class="flex space-x-2">
                                    <button class="edit-coupon-btn text-white/70 hover:text-white" data-id="<?php echo $coupon['id']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </button>
                                    <button class="delete-coupon-btn text-white/70 hover:text-red-500" data-id="<?php echo $coupon['id']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                    <button class="share-coupon-btn text-white/70 hover:text-blue-400" data-id="<?php echo $coupon['id']; ?>" data-name="<?php echo htmlspecialchars($coupon['coupon_name']); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                                    </button>
                                </div>
                                <?php if(!$is_expired && !$is_used): ?>
                                    <form action="mark_used.php" method="post" class="inline">
                                        <input type="hidden" name="coupon_id" value="<?php echo $coupon['id']; ?>">
                                        <button type="submit" class="mark-used-btn text-white/70 hover:text-lime-400 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Mark as Used
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Add/Edit Coupon Modal -->
    <div id="coupon-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="absolute inset-0 bg-black/70"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-neutral-900 rounded-2xl border border-white/10 p-6 w-full max-w-md relative mx-auto my-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="modal-title" class="text-2xl font-medium">Add New Coupon</h3>
                    <button id="close-modal" class="text-white/50 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <form id="coupon-form" action="add_coupon.php" method="post" class="space-y-4">
                    <input type="hidden" id="coupon_id" name="coupon_id" value="">
                    
                    <div>
                        <label for="coupon_name" class="block text-sm font-medium mb-2">Coupon Name</label>
                        <input type="text" id="coupon_name" name="coupon_name" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3" required>
                    </div>
                    
                    <div>
                        <label for="coupon_code" class="block text-sm font-medium mb-2">Coupon Code</label>
                        <input type="text" id="coupon_code" name="coupon_code" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3" required>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="discount" class="block text-sm font-medium mb-2">Discount (%)</label>
                            <input type="number" id="discount" name="discount" min="0" max="100" step="0.01" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3" required>
                        </div>
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium mb-2">Expiry Date</label>
                            <input type="date" id="expiry_date" name="expiry_date" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3 appearance-none" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="store" class="block text-sm font-medium mb-2">Store</label>
                        <input type="text" id="store" name="store" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3" required>
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium mb-2">Category</label>
                        <select id="category" name="category" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3 text-white appearance-none" required>
                            <option value="">Select Category</option>
                            <?php if (isset($all_categories)): ?>
                                <?php foreach($all_categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="Electronics">Electronics</option>
                                <option value="Fashion">Fashion</option>
                                <option value="Food">Food</option>
                                <option value="Travel">Travel</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Beauty">Beauty</option>
                                <option value="Home">Home</option>
                                <option value="Other">Other (Custom)</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div id="custom-category-container" class="hidden">
                        <label for="custom_category" class="block text-sm font-medium mb-2">Enter Custom Category</label>
                        <input type="text" id="custom_category" name="custom_category" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium mb-2">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3"></textarea>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="w-full bg-lime-400 text-black font-medium py-3 rounded-lg hover:bg-lime-500 transition-colors">Save Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="absolute inset-0 bg-black/70"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-neutral-900 rounded-2xl border border-white/10 p-6 w-full max-w-md relative mx-auto my-8">
                <h3 class="text-xl font-medium mb-4">Delete Coupon</h3>
                <p class="text-white/70 mb-6">Are you sure you want to delete this coupon? This action cannot be undone.</p>
                <form id="delete-form" action="delete_coupon.php" method="post" class="flex flex-col sm:flex-row gap-4">
                    <input type="hidden" id="delete_coupon_id" name="coupon_id">
                    <button id="cancel-delete" class="flex-1 bg-white/10 text-white font-medium py-3 rounded-lg hover:bg-white/15 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 bg-red-500 text-white font-medium py-3 rounded-lg hover:bg-red-600 transition-colors">Delete</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Share Coupon Modal -->
    <div id="share-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="absolute inset-0 bg-black/70"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-neutral-900 rounded-2xl border border-blue-500/30 shadow-[0_0_20px_rgba(59,130,246,0.3)] p-6 w-full max-w-md relative mx-auto my-8">
                <h3 class="text-xl font-medium mb-4">Share Coupon</h3>
                <p id="share-coupon-name" class="text-blue-400 mb-6"></p>
                <form id="share-form" action="share_coupon.php" method="post" class="space-y-4">
                    <input type="hidden" id="share_coupon_id" name="coupon_id">
                    <div>
                        <label for="recipient_email" class="block text-sm font-medium mb-2">Recipient's Email</label>
                        <input type="email" id="recipient_email" name="recipient_email" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3" placeholder="Enter email address" required>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="button" id="cancel-share" class="flex-1 bg-white/10 text-white font-medium py-3 rounded-lg hover:bg-white/15 transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 bg-blue-500 text-white font-medium py-3 rounded-lg hover:bg-blue-600 transition-colors">Share Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        /* Fix select background and input styling */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 16px;
        }
        input[type="date"] {
            color-scheme: dark;
        }
    </style>

    <script>
        // Modal functionality
        const couponModal = document.getElementById('coupon-modal');
        const deleteModal = document.getElementById('delete-modal');
        const shareModal = document.getElementById('share-modal');
        const customCategoryContainer = document.getElementById('custom-category-container');
        const addCouponBtn = document.getElementById('add-coupon-btn');
        const addFirstCouponBtn = document.getElementById('add-first-coupon-btn');
        const closeModalBtn = document.getElementById('close-modal');
        const cancelDeleteBtn = document.getElementById('cancel-delete');
        const cancelShareBtn = document.getElementById('cancel-share');
        const categorySelect = document.getElementById('category');
        const customCategoryInput = document.getElementById('custom_category');
        const searchInput = document.getElementById('search');
        const filterInput = document.getElementById('filter');
        const sortInput = document.getElementById('sort');
        const couponForm = document.getElementById('coupon-form');
        const showExpiredToggle = document.getElementById('show-expired');

        // Set today as the min date for expiry date
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('expiry_date').min = today;

            // Setup instant search functionality
            setupInstantSearch();

            // Initialize the expired coupons toggle
            initializeExpiredToggle();

            // Initialize event listeners for edit, delete and copy buttons
            initializeEventListeners();

            // Setup category select change handler
            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    if (this.value === 'Other') {
                        customCategoryContainer.classList.remove('hidden');
                        customCategoryInput.setAttribute('required', 'required');
                    } else {
                        customCategoryContainer.classList.add('hidden');
                        customCategoryInput.removeAttribute('required');
                    }
                });
            }

            // Setup form submission to handle custom category
            if (couponForm) {
                couponForm.addEventListener('submit', function(e) {
                    if (categorySelect.value === 'Other' && customCategoryInput.value.trim() !== '') {
                        // Use the custom category instead of "Other"
                        const customCategoryValue = customCategoryInput.value.trim();

                        // Create hidden input to send the actual category value
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'category';
                        hiddenInput.value = customCategoryValue;

                        // Replace the select value with our custom value
                        this.appendChild(hiddenInput);
                    }
                });
            }
        });

        // Setup instant search functionality
        function setupInstantSearch() {
            let debounceTimeout;

            // Search input handler - fix form submission
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        performSearch();
                    }, 300); // Wait 300ms after user stops typing
                });
            }

            // Fix form submission to use our search instead of default behavior
            const filterForm = document.getElementById('filter-form');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent standard form submission
                    performSearch();
                });
            }
            
            // Filter and sort change handlers
            if (filterInput) {
                filterInput.addEventListener('change', performSearch);
            }
            
            if (sortInput) {
                sortInput.addEventListener('change', performSearch);
            }
        }

        function performSearch() {
            const searchValue = searchInput ? searchInput.value : '';
            const filterValue = filterInput ? filterInput.value : '';
            const sortValue = sortInput ? sortInput.value : 'expiry_asc';
            const showExpired = showExpiredToggle ? showExpiredToggle.checked : true;

            console.log("Performing search with:", { search: searchValue, filter: filterValue, sort: sortValue, showExpired: showExpired });

            // Create URL with parameters
            const url = `dashboard.php?search=${encodeURIComponent(searchValue)}&filter=${encodeURIComponent(filterValue)}&sort=${encodeURIComponent(sortValue)}`;
            
            // Show loading indicator
            const couponsContainer = document.getElementById('coupons-container');
            if (couponsContainer) {
                couponsContainer.classList.add('opacity-50');
                couponsContainer.innerHTML += `
                    <div id="search-loader" class="col-span-3 flex justify-center items-center">
                        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-lime-400"></div>
                    </div>`;
            }
            
            // Use AJAX to fetch results without page reload
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    try {
                        // Parse the HTML response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Replace just the coupons container with the new content
                        const newCouponsContainer = doc.getElementById('coupons-container');
                        if (newCouponsContainer && couponsContainer) {
                            couponsContainer.innerHTML = newCouponsContainer.innerHTML;
                            couponsContainer.classList.remove('opacity-50');
                            
                            // Update URL without page reload for bookmarking/sharing
                            history.pushState({}, '', url);
                            
                            // Apply expired coupon filter to the new content
                            filterExpiredCoupons(showExpired);
                            
                            // Reinitialize event listeners for the new content
                            initializeEventListeners();
                        } else {
                            console.error("Could not find coupon containers");
                            window.location.href = url; // Fallback to full page reload
                        }
                    } catch (e) {
                        console.error("Error processing response:", e);
                        window.location.href = url; // Fallback to full page reload
                    }
                })
                .catch(error => {
                    console.error('Error during search:', error);
                    window.location.href = url; // Fallback to full page reload
                });
        }
        
        function initializeEventListeners() {
            // Reinitialize event listeners for dynamic content
            document.querySelectorAll('.copy-btn').forEach(initializeCopyButton);
            document.querySelectorAll('.edit-coupon-btn').forEach(initializeEditButton);
            document.querySelectorAll('.delete-coupon-btn').forEach(initializeDeleteButton);
            document.querySelectorAll('.share-coupon-btn').forEach(initializeShareButton);
        }

        // Initialize the expired coupons toggle from localStorage
        function initializeExpiredToggle() {
            if (showExpiredToggle) {
                // Get saved preference from localStorage (default to false/off)
                const showExpired = localStorage.getItem('showExpiredCoupons') === 'true';
                
                // Set the toggle to match saved preference
                showExpiredToggle.checked = showExpired;
                
                // Add event listener for toggle changes
                showExpiredToggle.addEventListener('change', function() {
                    // Save preference to localStorage
                    localStorage.setItem('showExpiredCoupons', this.checked);
                    
                    // Update display
                    performSearch();
                });
                
                // Apply filter on initial load
                filterExpiredCoupons(showExpired);
            }
        }
        
        // Filter expired coupons in the UI
        function filterExpiredCoupons(showExpired) {
            const couponCards = document.querySelectorAll('.coupon-card');
            
            couponCards.forEach(card => {
                const isExpired = card.classList.contains('border-red-500/50');
                
                if (isExpired && !showExpired) {
                    card.classList.add('hidden');
                } else {
                    card.classList.remove('hidden');
                }
            });
        }
        
        // Open modal for adding new coupon
        if (addCouponBtn) {
            addCouponBtn.addEventListener('click', function() {
                document.getElementById('modal-title').textContent = 'Add New Coupon';
                document.getElementById('coupon-form').reset();
                document.getElementById('coupon_id').value = '';
                document.getElementById('coupon-form').action = 'add_coupon.php';
                customCategoryContainer.classList.add('hidden');
                couponModal.classList.remove('hidden');
            });
        }
        
        // Open modal for adding first coupon
        if (addFirstCouponBtn) {
            addFirstCouponBtn.addEventListener('click', function() {
                document.getElementById('modal-title').textContent = 'Add New Coupon';
                document.getElementById('coupon-form').reset();
                document.getElementById('coupon_id').value = '';
                document.getElementById('coupon-form').action = 'add_coupon.php';
                customCategoryContainer.classList.add('hidden');
                couponModal.classList.remove('hidden');
            });
        }
        
        // Close modal
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function() {
                couponModal.classList.add('hidden');
            });
        }

        // Initialize edit buttons
        function initializeEditButton(button) {
            button.addEventListener('click', function() {
                const couponId = this.getAttribute('data-id');
                
                // Fetch coupon data from the server
                fetch(`get_coupon.php?id=${couponId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Populate the form with the coupon data
                            document.getElementById('coupon_name').value = data.coupon.coupon_name;
                            document.getElementById('coupon_code').value = data.coupon.coupon_code;
                            document.getElementById('discount').value = data.coupon.discount;
                            document.getElementById('expiry_date').value = data.coupon.expiry_date;
                            document.getElementById('store').value = data.coupon.store;
                            document.getElementById('description').value = data.coupon.description || '';
                            
                            // Set the category
                            const categorySelect = document.getElementById('category');
                            const categoryOptions = Array.from(categorySelect.options);
                            const categoryOption = categoryOptions.find(option => option.value === data.coupon.category);
                            
                            if (categoryOption) {
                                categorySelect.value = data.coupon.category;
                            } else {
                                // If category not in list, select "Other" and set custom category
                                const otherOption = categoryOptions.find(option => option.value === 'Other');
                                if (otherOption) {
                                    categorySelect.value = 'Other';
                                    document.getElementById('custom_category').value = data.coupon.category;
                                    document.getElementById('custom-category-container').classList.remove('hidden');
                                }
                            }
                            
                            // Update the modal title and form action
                            document.getElementById('modal-title').textContent = 'Edit Coupon';
                            document.getElementById('coupon_id').value = couponId;
                            document.getElementById('coupon-form').action = 'update_coupon.php';
                            
                            // Show the modal
                            couponModal.classList.remove('hidden');
                        } else {
                            alert('Failed to load coupon data: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching coupon data:', error);
                        alert('Failed to load coupon data. Please try again.');
                    });
            });
        }

        // Initialize delete buttons
        function initializeDeleteButton(button) {
            button.addEventListener('click', function() {
                const couponId = this.getAttribute('data-id');
                document.getElementById('delete_coupon_id').value = couponId;
                deleteModal.classList.remove('hidden');
            });
        }
        
        // Initialize copy buttons
        function initializeCopyButton(button) {
            button.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    // Show success message
                    const copySuccess = document.getElementById('copy-success');
                    copySuccess.classList.remove('hidden');
                    
                    // Add animation to button
                    this.classList.add('copy-animation');
                    
                    setTimeout(() => {
                        copySuccess.classList.add('hidden');
                    }, 2000);
                    
                    setTimeout(() => {
                        this.classList.remove('copy-animation');
                    }, 1000);
                });
            });
        }
        
        // Initialize share buttons - FIXED VERSION
        function initializeShareButton(button) {
            button.addEventListener('click', function(e) {
                // Prevent default behavior and stop event propagation
                e.preventDefault();
                e.stopPropagation();
                
                // Get coupon info from data attributes
                const couponId = this.getAttribute('data-id');
                const couponName = this.getAttribute('data-name');
                
                // Update modal
                document.getElementById('share-coupon-name').textContent = 'Share "' + couponName + '" with someone';
                document.getElementById('share_coupon_id').value = couponId;
                
                // Show modal
                if (shareModal) {
                    shareModal.classList.remove('hidden');
                } else {
                    console.error('Share modal not found in DOM!');
                }
            });
        }

        // Make sure to apply event listeners to any existing share buttons
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - initializing share buttons');
            const shareButtons = document.querySelectorAll('.share-coupon-btn');
            console.log('Found', shareButtons.length, 'share buttons');
            shareButtons.forEach(initializeShareButton);
        });

        // Make sure all modals are properly initialized
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        }
        
        if (cancelShareBtn) {
            cancelShareBtn.addEventListener('click', function() {
                document.getElementById('share-form').reset();
                shareModal.classList.add('hidden');
            });
        }

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
