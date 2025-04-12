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
$success_message = $error_message = "";
$current_password = $new_password = $confirm_password = "";
$current_password_err = $new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate current password
    if(empty(trim($_POST["current_password"]))){
        $current_password_err = "Please enter your current password.";
    } else {
        $current_password = trim($_POST["current_password"]);
    }
    
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter a new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have at least 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Passwords did not match.";
        }
    }
    
    // Check input errors before updating the database
    if(empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)){
        // Verify current password
        try {
            $sql = "SELECT password FROM users WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id", $_SESSION["user_id"], PDO::PARAM_INT);
            $stmt->execute();
            
            if($row = $stmt->fetch()){
                if(password_verify($current_password, $row["password"])){
                    // Current password is correct, update with new password
                    $sql = "UPDATE users SET password = :password WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
                    $stmt->bindParam(":id", $_SESSION["user_id"], PDO::PARAM_INT);
                    
                    if($stmt->execute()){
                        $success_message = "Password changed successfully!";
                        // Clear input fields
                        $current_password = $new_password = $confirm_password = "";
                    } else {
                        $error_message = "Something went wrong. Please try again later.";
                    }
                } else {
                    $current_password_err = "Current password is incorrect.";
                }
            }
        } catch(PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Digital Coupon Organizer</title>
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
                            <a href="profile.php" class="block px-4 py-2 text-sm hover:bg-white/5">Profile</a>
                            <a href="settings.php" class="block px-4 py-2 text-sm text-lime-400 hover:bg-white/5">Settings</a>
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
        <div class="mb-8">
            <h1 class="text-3xl font-medium text-lime-400">Settings</h1>
            <p class="text-white/50 mt-1">Manage your account settings</p>
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
        
        <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
            <h2 class="text-xl font-medium mb-6">Change Password</h2>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium mb-2">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="w-full bg-neutral-800 border <?php echo !empty($current_password_err) ? 'border-red-500' : 'border-white/10'; ?> rounded-lg px-4 py-3" required>
                    <?php if(!empty($current_password_err)): ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo $current_password_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="new_password" class="block text-sm font-medium mb-2">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="w-full bg-neutral-800 border <?php echo !empty($new_password_err) ? 'border-red-500' : 'border-white/10'; ?> rounded-lg px-4 py-3" required>
                    <?php if(!empty($new_password_err)): ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo $new_password_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium mb-2">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full bg-neutral-800 border <?php echo !empty($confirm_password_err) ? 'border-red-500' : 'border-white/10'; ?> rounded-lg px-4 py-3" required>
                    <?php if(!empty($confirm_password_err)): ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo $confirm_password_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="bg-lime-400 text-black font-medium py-3 px-6 rounded-lg hover:bg-lime-500 transition-colors">Change Password</button>
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
