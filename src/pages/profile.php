<?php
// Initialize the session
session_start();

// Check if the user is logged in
if(!isset($_SESSION["user_id"])){
    header("location: login.php");
    exit;
}

// Include database connection
require_once "../config/db.php";

// Define variables and initialize with empty values
$name = $_SESSION["name"];
$email = $_SESSION["email"];
$success_message = $error_message = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate new name
    if(empty(trim($_POST["name"]))){
        $error_message = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }
    
    // Check if there are no errors
    if(empty($error_message)){
        try {
            // Update user information
            $sql = "UPDATE users SET name = :name WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":id", $_SESSION["user_id"], PDO::PARAM_INT);
            
            if($stmt->execute()){
                // Update session variables
                $_SESSION["name"] = $name;
                $success_message = "Profile updated successfully!";
            } else {
                $error_message = "Something went wrong. Please try again later.";
            }
        } catch(PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}

// Get user stats
try {
    // Total coupons
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM coupons WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt->execute();
    $total_coupons = $stmt->fetchColumn();
    
    // Used coupons
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM coupons WHERE user_id = :user_id AND is_used = 1");
    $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt->execute();
    $used_coupons = $stmt->fetchColumn();
    
    // Expired coupons
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM coupons WHERE user_id = :user_id AND expiry_date < CURDATE()");
    $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt->execute();
    $expired_coupons = $stmt->fetchColumn();
    
    // Active coupons
    $active_coupons = $total_coupons - $used_coupons - $expired_coupons;
    
} catch(PDOException $e) {
    $total_coupons = $used_coupons = $expired_coupons = $active_coupons = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Digital Coupon Organizer</title>
    <link href="../output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
                            <div class="size-8 rounded-full bg-lime-400 flex items-center justify-center text-black font-medium">
                                <?php echo substr($_SESSION["name"], 0, 1); ?>
                            </div>
                            <span class="hidden md:block"><?php echo htmlspecialchars($_SESSION["name"]); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 rounded-lg bg-neutral-900 border border-white/10 shadow-lg py-1 z-10">
                            <a href="profile.php" class="block px-4 py-2 text-sm text-lime-400 hover:bg-white/5">Profile</a>
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
                    <a href="faqs.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">FAQs</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center mb-8 gap-4">
            <div class="size-16 md:size-20 rounded-full bg-lime-400 flex items-center justify-center text-black text-3xl font-medium">
                <?php echo substr($_SESSION["name"], 0, 1); ?>
            </div>
            <div>
                <h1 class="text-3xl font-medium text-lime-400">My Profile</h1>
                <p class="text-white/50 mt-1">Manage your account information</p>
            </div>
        </div>
        
        <?php if(!empty($success_message)): ?>
            <div class="bg-lime-500/20 border border-lime-500 text-white px-4 py-3 rounded-lg mb-6">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($error_message)): ?>
            <div class="bg-red-500/20 border border-red-500 text-white px-4 py-3 rounded-lg mb-6">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6 flex flex-col items-center">
                <div class="text-4xl font-bold text-lime-400"><?php echo $active_coupons; ?></div>
                <div class="mt-2 text-sm text-white/70">Active Coupons</div>
            </div>
            
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6 flex flex-col items-center">
                <div class="text-4xl font-bold text-lime-400"><?php echo $used_coupons; ?></div>
                <div class="mt-2 text-sm text-white/70">Used Coupons</div>
            </div>
            
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6 flex flex-col items-center">
                <div class="text-4xl font-bold text-lime-400"><?php echo $total_coupons; ?></div>
                <div class="mt-2 text-sm text-white/70">Total Coupons</div>
            </div>
        </div>
        
        <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
            <h2 class="text-xl font-medium mb-6">Account Information</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3" required>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3" disabled>
                    <p class="text-white/50 text-sm mt-1">Email cannot be changed</p>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="bg-lime-400 text-black font-medium py-3 px-6 rounded-lg hover:bg-lime-500 transition-colors">Save Changes</button>
                </div>
            </form>
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
