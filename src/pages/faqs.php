<?php
// Initialize the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - Digital Coupon Organizer</title>
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
                        <?php if(isset($_SESSION["user_id"])): ?>
                            <a href="dashboard.php" class="text-white/70 hover:text-lime-400">Dashboard</a>
                        <?php endif; ?>
                        <a href="faqs.php" class="text-lime-400 hover:text-lime-400">FAQs</a>
                    </nav>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                    
                    <?php if(isset($_SESSION["user_id"])): ?>
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
                    <?php else: ?>
                        <div class="flex items-center gap-4">
                            <a href="login.php" class="text-white/70 hover:text-lime-400">Log in</a>
                            <a href="register.php" class="bg-lime-400 text-black font-medium py-2 px-4 rounded-lg hover:bg-lime-500 transition-colors">Sign up</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden hidden pt-4 pb-3 border-t border-white/10 mt-4">
                <div class="space-y-1">
                    <?php if(isset($_SESSION["user_id"])): ?>
                        <a href="dashboard.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">Dashboard</a>
                    <?php endif; ?>
                    <a href="faqs.php" class="block px-3 py-2 text-base font-medium text-lime-400 hover:text-lime-400">FAQs</a>
                    <?php if(!isset($_SESSION["user_id"])): ?>
                        <a href="login.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">Log in</a>
                        <a href="register.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">Sign up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-lime-400 mb-4">Frequently Asked Questions</h1>
            <p class="text-white/70 max-w-2xl mx-auto">Find answers to common questions about the Digital Coupon Organizer</p>
        </div>
        
        <div class="space-y-6">
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
                <button class="faq-toggle w-full flex justify-between items-center">
                    <h3 class="text-xl font-medium">What is Digital Coupon Organizer?</h3>
                    <svg class="faq-icon transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="faq-content hidden mt-4 text-white/70">
                    <p>Digital Coupon Organizer is a platform designed to help you store, organize, and manage all your discount coupons in one place. It helps you keep track of expiry dates and ensures you never miss out on savings opportunities.</p>
                </div>
            </div>
            
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
                <button class="faq-toggle w-full flex justify-between items-center">
                    <h3 class="text-xl font-medium">How do I add a new coupon?</h3>
                    <svg class="faq-icon transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="faq-content hidden mt-4 text-white/70">
                    <p>To add a new coupon, follow these steps:</p>
                    <ol class="list-decimal pl-6 mt-2 space-y-1">
                        <li>Log in to your account</li>
                        <li>Go to your Dashboard</li>
                        <li>Click the "Add New Coupon" button</li>
                        <li>Fill in the coupon details (name, code, discount, expiry date, etc.)</li>
                        <li>Click "Save Coupon"</li>
                    </ol>
                </div>
            </div>
            
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
                <button class="faq-toggle w-full flex justify-between items-center">
                    <h3 class="text-xl font-medium">Can I edit or delete a coupon?</h3>
                    <svg class="faq-icon transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="faq-content hidden mt-4 text-white/70">
                    <p>Yes, you can edit or delete any coupon you've added. On your Dashboard, each coupon card has edit and delete buttons at the bottom. Click the edit button to update the coupon details or the delete button to remove it.</p>
                </div>
            </div>
            
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
                <button class="faq-toggle w-full flex justify-between items-center">
                    <h3 class="text-xl font-medium">How do I mark a coupon as used?</h3>
                    <svg class="faq-icon transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="faq-content hidden mt-4 text-white/70">
                    <p>When you use a coupon, you can mark it as "used" to keep your active coupons organized. Simply click the "Mark as Used" button at the bottom of the coupon card on your Dashboard. Used coupons will be visually distinguished from your active coupons.</p>
                </div>
            </div>
            
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
                <button class="faq-toggle w-full flex justify-between items-center">
                    <h3 class="text-xl font-medium">How do I search for specific coupons?</h3>
                    <svg class="faq-icon transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="faq-content hidden mt-4 text-white/70">
                    <p>You can search for coupons using the search box at the top of your Dashboard. Type in any keyword related to the coupon name, store, category, or code, and the results will update instantly to show matching coupons.</p>
                </div>
            </div>
            
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
                <button class="faq-toggle w-full flex justify-between items-center">
                    <h3 class="text-xl font-medium">How do I filter coupons by category?</h3>
                    <svg class="faq-icon transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="faq-content hidden mt-4 text-white/70">
                    <p>To filter coupons by category, use the Category dropdown in the filter section on your Dashboard. Select the desired category from the list to see only coupons in that category. You can also add custom categories when creating or editing a coupon.</p>
                </div>
            </div>
            
            <div class="bg-neutral-900 rounded-xl border border-white/10 p-6">
                <button class="faq-toggle w-full flex justify-between items-center">
                    <h3 class="text-xl font-medium">Is my coupon data secure?</h3>
                    <svg class="faq-icon transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="faq-content hidden mt-4 text-white/70">
                    <p>Yes, we take data security seriously. Your coupon information is stored securely, and only you can access your coupons when logged into your account. We implement security best practices to protect your data.</p>
                </div>
            </div>
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
        
        // FAQ toggles
        document.querySelectorAll('.faq-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                // Toggle content visibility
                const content = this.nextElementSibling;
                content.classList.toggle('hidden');
                
                // Rotate the icon
                const icon = this.querySelector('.faq-icon');
                if (content.classList.contains('hidden')) {
                    icon.classList.remove('rotate-180');
                } else {
                    icon.classList.add('rotate-180');
                }
            });
        });
    </script>
</body>
</html>
