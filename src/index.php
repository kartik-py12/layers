<?php
// Start session
session_start();

// Include the auth header at the top of the file
require_once "includes/auth_header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <title>Digital Coupon Organizer - Home</title>

</head>
<body class="bg-black text-white">

<!-- Navbar -->
    <header class="py-4 lg:py-6 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="index.php">
                        <img class="h-8 w-auto" src="assets/images/logo.svg" alt="Digital Coupon Organizer">
                    </a>
                    <nav class="hidden md:flex ml-10 space-x-8">
                        <?php if(isset($_SESSION["user_id"])): ?>
                            <a href="pages/dashboard.php" class="text-white/70 hover:text-lime-400">Dashboard</a>
                        <?php endif; ?>
                        <a href="pages/faqs.php" class="text-white/70 hover:text-lime-400">FAQs</a>
                    </nav>
                </div>
                
                <div class="flex items-center gap-6">
                    <!-- Mobile menu button -->
              
                    
                    <?php if(isset($_SESSION["user_id"])): ?>
                        <button id="mobile-menu-button" class="md:hidden text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                        <div class="relative">
                            <button id="user-menu-button" class="flex items-center gap-2 text-sm focus:outline-none">
                                <?php if(!empty($_SESSION["profile_image"])): ?>
                                    <img src="<?php echo htmlspecialchars($_SESSION["profile_image"]); ?>" class="w-8 h-8 rounded-full object-cover" alt="Profile">
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
                                <a href="pages/profile.php" class="block px-4 py-2 text-sm hover:bg-white/5">Profile</a>
                                <a href="pages/settings.php" class="block px-4 py-2 text-sm hover:bg-white/5">Settings</a>
                                <div class="border-t border-white/10"></div>
                                <a href="pages/logout.php" class="block px-4 py-2 text-sm text-red-400 hover:bg-white/5 hover:text-red-500">Sign out</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-4">
                            <a href="pages/login.php" class="text-white/70 hover:text-lime-400 text-sm md:text-md">Log in</a>
                            <a href="pages/register.php" class="bg-lime-400 text-black font-medium text-sm md:text-md py-2 px-4 rounded-lg hover:bg-lime-500 transition-colors">Sign up</a>
                        </div>
                        <button id="mobile-menu-button" class="md:hidden text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden hidden pt-4 pb-3 border-t border-white/10 mt-4">
                <div class="space-y-1">
                    <?php if(isset($_SESSION["user_id"])): ?>
                        <a href="pages/dashboard.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">Dashboard</a>
                    <?php endif; ?>
                    <a href="pages/faqs.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">FAQs</a>
                    <?php if(!isset($_SESSION["user_id"])): ?>
                        <a href="pages/login.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400 text-sm ">Log in</a>
                        <a href="pages/register.php" class="block px-3 py-2 text-base font-medium text-white/70 hover:text-lime-400">Sign up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero -->
     <section class="py-24 overflow-x-clip">
         <div class="max-w-5xl mx-auto px-4 lg:px-16  font-[var(--font-inter)] relative">
            <div class="hidden lg:block">
                
                <!-- cursor pointer -->
                    <div class="absolute">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mouse-pointer">
                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                        <path d="M13 13l6 6"></path>
                        </svg> 
                        <div class="absolute left-full">
                            <div class="inline-flex rounded-full font-bold text-sm bg-blue-500 px-2 rounded-tl-none">Kartik</div>
                        </div>
                    </div>
                </div>

                <!-- cursor pointer -->
                <div class="absolute -bottom-8 left-30">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mouse-pointer">
                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                        <path d="M13 13l6 6"></path>
                        </svg> 
                        <div class="absolute left-full">
                            <div class="inline-flex rounded-full font-bold text-sm bg-red-500 px-2 rounded-tl-none">Ayush</div>
                        </div>
                    </div>
                </div>

                <!-- cursor pointer -->
                <div class="absolute right-20 -top-15">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mouse-pointer">
                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                        <path d="M13 13l6 6"></path>
                        </svg> 
                        <div class="absolute left-full">
                            <div class="inline-flex rounded-full font-bold text-sm bg-blue-800 px-2 rounded-tl-none">Kanha</div>
                        </div>
                    </div>
                </div>
                <!-- cursor pointer -->
                <div class="absolute bottom-20 right-30">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mouse-pointer">
                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                        <path d="M13 13l6 6"></path>
                        </svg> 
                        <div class="absolute left-full">
                            <div class="inline-flex rounded-full font-bold text-sm bg-red-800 px-2 rounded-tl-none">Manish</div>
                        </div>
                    </div>
                </div>
            
                <div class="absolute -left-102 top-16">
                    <!-- <img  src="assets/images/Container 6 (1).jpg " alt=""> -->
                    <img class="w-[400px]" src="assets/images/desing2.png" alt="">
                </div>
                <div class="absolute -right-112 -top-16">
                    <!-- <img src="assets/images/Container 6 (2).jpg" alt=""> -->
                    <img class="w-[400px]" src="assets/images/design.png" alt="">
                </div>
            </div>
            
            <div class="flex justify-center">
                <div class="inline-flex py-1 px-3 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full text-neutral-950 font-semibold">✨Smart Savings, Simplified!</div>
            </div>
            <h1 class="text-6xl font-medium text-center mt-6 md:text-7xl lg:text-8xl">Oraganize coupon effortlessly</h1>
            <p class="text-center text-xl text-white/50 mt-8 max-w-2xl mx-auto">Unlock unbeatable deals with ease! Our coupon organizer helps you save time and 
            money by keeping all your discounts in one place. Never miss a deal again—shop smarter, 
            save bigger!
            </p>

            <form id="signup-form" class="flex border border-white/15 rounded-full p-2 justify-between mt-8 max-w-2xl mx-auto">
                <input id="signup-email" class="bg-transparent px-4 text-sm md:text-lg flex-grow" type="email" placeholder="Enter your email" required>
                <button id="signup-btn" type="submit" class="border md:mr-2 md:text-[16px] md:px-6 md:h-14 text-black bg-lime-400 border-lime-400 h-10 rounded-full px-3 text-sm font-medium whitespace-nowrap">Sign Up</button>
            </form>
            <div id="signup-success" class="hidden text-center mt-4 text-lime-400">Thanks for signing up! We'll be in touch soon.</div>
            <div id="signup-error" class="hidden text-center mt-4 text-red-400">Please enter a valid email address.</div>
         </div>
    </section>

    <!-- Text Slider -->
    <section class="mt-2 md:mt-24 md:text-[146px] text-[70px]">
        <!-- <div class="max-w-5xl mx-auto px-4 lg:px-16  font-[var(--font-inter)] relative"> -->
            <div id="scroller">
                <div id="scroller-in">
                  <h4 onclick="c(this)">All Coupons</h4>
                  <h4>By Category</h4>
                  <h4>Expiry Reminders</h4>
                  <h4>Smart Filters</h4>
                  <h4>Best Deals</h4>
                </div>
                <div id="scroller-in">
                  <h4>All Coupons</h4>
                  <h4>By Category</h4>
                  <h4>Expiry Reminders</h4>
                  <h4>Smart Filters</h4>
                  <h4>Best Deals</h4>
                </div>
            </div>
            
        <!-- </div> -->
    </section>

    <!-- logo-ticker

    <section class="py-12 md:py-24 overflow-x-clip">
        <div class="max-w-5xl mx-auto px-4 lg:px-16  font-[var(--font-inter)] relative">
            <h3 class="text-center text-white/50 text-xl">Save From Any</h3>
            <div class="overflow-hidden mt-12 [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]">
                <div class="flex gap-24 pr-24 ">
                    <img src="assets/images/quantum.svg" alt="">
                    <img src="assets/images/acme-corp.svg" alt="">
                    <img src="assets/images/echo-valley.svg" alt="">
                    <img src="assets/images/pulse.svg" alt="">
                    <img src="assets/images/outside.svg" alt="">
                    <img src="assets/images/apex.svg" alt="">
                </div>
            </div>
        </div>

    </section> -->

    <!-- Introduction -->

    <section class="py-12 md:py-28 lg:py-40">
        <div class="max-w-5xl mx-auto px-4 lg:px-16 font-[var(--font-inter)] relative">
            <div class="flex justify-center">
                <div class="inline-flex border border-lime-400 gap-2 text-lime-400 px-3 py-1 rounded-full uppercase items-center">
                    <span class="text-sm">&#10038</span>
                    <span>Introducing Layers</span>
                </div>
            </div>
            <div id="animated-text-container" class="text-4xl md:text-5xl lg:text-6xl text-center font-medium mt-10">
                <span class="">Keep all your coupons in one place.</span>
                <div id="animated-text" class="inline">
                    <span class="word-highlight text-white/15 transition-colors duration-300">effortlessly </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">sorted </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">by </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">category, </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">expiry </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">date, </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">and </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">priority. </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">Get </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">smart </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">reminders </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">before </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">they </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">expire </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">and </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">find </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">the </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">best </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">deals </span>
                    <span class="word-highlight text-white/15 transition-colors duration-300">instantly.</span>
                </div>
                <span class="text-lime-400">Say goodbye to clutter and hello to smarter savings!</span>
            </div>
        </div>
    </section>




    <!-- Features -->

    <section class="py-24 ">
        <div class="max-w-5xl mx-auto px-4 lg:px-16  font-[var(--font-inter)] relative">
            <div class="flex justify-center">
                <div class="inline-flex border border-lime-400 gap-2 text-lime-400 px-3 py-1 rounded-full uppercase items-center">
                    <span class="text-sm">&#10038</span>
                    <span>Features</span>
                </div>
            </div>


                <h2 class="text-6xl max-w-2xl mx-auto font-medium text-center mt-6">Where power meets <span class="text-lime-400">simplicity</span></h2>
                <!-- Changed grid layout to give more width to all cards -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <div class="bg-neutral-900 border border-white/10 p-6 rounded-3xl feature-card transition-all duration-300 hover:border-lime-400/50 hover:shadow-[0_0_15px_rgba(163,230,53,0.2)] transform hover:-translate-y-1">
                        <div class="aspect-video flex items-center  justify-center">
                            <!-- Widened floating dock container -->
                            <div id="floating-dock-container" class="relative flex items-center justify-center w-full h-full mx-auto gap-x-10">
                                <img class="floating-dock-item absolute z-40 size-20 rounded-full overflow-hidden border-4 border-blue-500 p-1 bg-neutral-900 transition-all duration-300" 
                                     src="assets/images/avatar-ashwin-santiago.jpg" alt="" 
                                     style="transform: translateX(-60px);">
                                <img class="floating-dock-item absolute z-30 size-20 rounded-full overflow-hidden border-4 border-indigo-500 p-1 bg-neutral-900 transition-all duration-300" 
                                     src="assets/images/avatar-lula-meyers.jpg" alt=""
                                     style="transform: translateX(-20px);">
                                <img class="floating-dock-item absolute z-20 size-20 rounded-full overflow-hidden border-4 border-amber-500 p-1 bg-neutral-900 transition-all duration-300" 
                                     src="assets/images/avatar-florence-shaw.jpg" alt=""
                                     style="transform: translateX(20px);">
                                <div class="floating-dock-item absolute z-10 size-20 rounded-full overflow-hidden border-transparent p-1 bg-neutral-900 transition-all duration-300"
                                     style="transform: translateX(60px);">
                                    <div class="size-full bg-neutral-700 rounded-full inline-flex items-center justify-center gap-1">
                                        <span class="size-1.5 rounded-full bg-white"></span>
                                        <span class="size-1.5 rounded-full bg-white"></span>
                                        <span class="size-1.5 rounded-full bg-white"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <h3 class="text-3xl font-medium mt-6 transition-colors duration-300 group-hover:text-lime-400">Expiry Date Alert</h3>
                            <p class="text-white/50 mt-2">Get notified before your coupons expire and never miss a deal again!</p>
                        </div>
                    </div>
                    
                    <div id="incredible-card" class="bg-neutral-900 border border-white/10 p-6 rounded-3xl feature-card transition-all duration-300 hover:border-lime-400/50 hover:shadow-[0_0_15px_rgba(163,230,53,0.2)] transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute inset-0 bg-cover bg-center opacity-0 transition-opacity duration-300 z-0" id="gif-background"></div>
                        <div class="aspect-video flex items-center justify-center relative z-10">
                            <p class="text-4xl font-extrabold text-white/20 text-center">We've made <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent animated-gradient">incredible</span> savings easier than ever!</p>
                        </div>

                        <div class="relative z-10">
                            <h3 class="text-3xl font-medium mt-6 transition-colors duration-300">Advanced Filters</h3>
                            <p class="text-white/50 mt-2">Find the right coupon instantly with powerful search and filter options.</p>
                        </div>
                    </div>

                    <div class="bg-neutral-900 border border-white/10 p-6 rounded-3xl feature-card transition-all duration-300 hover:border-lime-400/50 hover:shadow-[0_0_15px_rgba(163,230,53,0.2)] transform hover:-translate-y-1">
                        <div class="aspect-video flex items-center justify-center gap-4 flex-wrap">
                            <div class="magnetic-btn relative bg-neutral-300 inline-flex items-center justify-center rounded-2xl text-xl text-neutral-950 font-medium w-max p-3 transition-all">
                                <span class="relative z-10">Electronics</span>
                                <div class="btn-light-effect"></div>
                            </div>
                            <div class="magnetic-btn relative bg-neutral-300 inline-flex items-center justify-center rounded-2xl text-xl text-neutral-950 font-medium w-max p-3 transition-all">
                                <span class="relative z-10">Groceries</span>
                                <div class="btn-light-effect"></div>
                            </div>
                            <div class="magnetic-btn relative bg-neutral-300 inline-flex items-center justify-center rounded-2xl text-xl text-neutral-950 font-medium w-max p-3 transition-all">
                                <span class="relative z-10">Lifestyle</span>
                                <div class="btn-light-effect"></div>
                            </div>
                        </div>
                        <div class="">
                            <h3 class="text-3xl font-medium mt-6 transition-colors duration-300">Smart Categories</h3>
                            <p class="text-white/50 mt-2">Organize your coupons by category for quick and easy access.</p>
                        </div>
                    </div>  
                </div>          
                <div class="mt-8 md:mt-10 flex flex-wrap gap-3 justify-center">
                    <!-- 1 -->
                    <div class="hover-border-gradient bg-neutral-900 border border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center transition-all duration-300 hover:border-transparent hover:bg-black/40 transform hover:-translate-y-1">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl ">&#10038</span>
                        <span class="font-medium">Expiry Alerts</span>
                    </div>
                    <!-- 2 -->
                    <div class="hover-border-gradient bg-neutral-900 border border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center transition-all duration-300 hover:border-transparent hover:bg-black/40 transform hover:-translate-y-1">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Advanced Filters</span>
                    </div>
                    <!-- 3 -->
                    <div class="hover-border-gradient bg-neutral-900 border border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center transition-all duration-300 hover:border-transparent hover:bg-black/40 transform hover:-translate-y-1">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">One-Tap Access</span>
                    </div>
                    <!-- 4 -->
                    <div class="hover-border-gradient bg-neutral-900 border border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center transition-all duration-300 hover:border-transparent hover:bg-black/40 transform hover:-translate-y-1">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Custom Tags</span>
                    </div>
                    <!-- 5 -->
                    <div class="hover-border-gradient bg-neutral-900 border border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center transition-all duration-300 hover:border-transparent hover:bg-black/40 transform hover:-translate-y-1">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Auto-Sort</span>
                    </div>
                    <!-- 6 -->
                    <div class="hover-border-gradient bg-neutral-900 border border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center transition-all duration-300 hover:border-transparent hover:bg-black/40 transform hover:-translate-y-1">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Cloud Sync</span>
                    </div>
                    <!-- 7 -->
                    <div class="hover-border-gradient bg-neutral-900 border border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center transition-all duration-300 hover:border-transparent hover:bg-black/40 transform hover:-translate-y-1">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Deal Tracker </span>
                    </div>
                </div>
                <style>
                    @keyframes gradient {
                        0% {
                            background-position: 0% 50%;
                        }
                        50% {
                            background-position: 100% 50%;
                        }
                        100% {
                            background-position: 0% 50%;
                        }
                    }
                    .animated-gradient {
                        background-size: 200% 200%;
                        animation: gradient 3s ease infinite;
                    }
                    .feature-card:hover h3 {
                        color: #a3e635; /* lime-400 */
                    }
                    
                    /* Floating dock styles */
                    #floating-dock-container {
                        height: 100px;
                        cursor: pointer;
                    }
                    .floating-dock-item {
                        will-change: transform;
                        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                    }
                    #floating-dock-container:hover .floating-dock-item {
                        transform: translateY(-10px);
                    }
                    
                    /* Hover Border Gradient Effect */
                    .hover-border-gradient {
                        position: relative;
                        cursor: pointer;
                        border: 1px solid transparent;
                        background-clip: padding-box;
                        transition: border-color 0.3s ease;
                    }
                    
                    .hover-border-gradient::before {
                        content: '';
                        position: absolute;
                        top: -2px;
                        left: -2px;
                        right: -2px;
                        bottom: -2px;
                        border-radius: inherit;
                        background: linear-gradient(45deg, #a3e635, #3b82f6, #ec4899, #a3e635);
                        background-size: 400% 400%;
                        opacity: 0;
                        z-index: -1;
                        transition: opacity 0.3s ease;
                    }
                    .hover-border-gradient:hover::before {
                        opacity: 1;
                        animation: border-gradient-animation 3s ease alternate infinite;
                    }
                    
                    @keyframes border-gradient-animation {
                        0% {
                            background-position: 0% 50%;
                        }
                        50% {
                            background-position: 100% 50%;
                        }
                        100% {
                            background-position: 0% 50%;
                        }
                    }
                    
                    /* Magnetic Button Effects */
                    .magnetic-btn {
                        cursor: pointer;
                        overflow: hidden;
                        transform-style: preserve-3d;
                        transform: perspective(800px);
                        transition: transform 0.2s, background-color 0.3s;
                        background-position: center;
                        border: 1px solid rgba(0, 0, 0, 0.1);
                        box-shadow: 
                            0 4px 10px rgba(0, 0, 0, 0.05),
                            0 0 1px rgba(0, 0, 0, 0.1);
                    }
                    
                    .magnetic-btn:hover {
                        background-color: #a3e635;
                        box-shadow: 
                            0 6px 20px rgba(163, 230, 53, 0.25),
                            0 0 0 1px rgba(163, 230, 53, 0.1);
                    }
                    
                    .btn-light-effect {
                        position: absolute;
                        top: -50%;
                        left: -50%;
                        width: 200%;
                        height: 200%;
                        background: radial-gradient(
                            circle at center,
                            rgba(255, 255, 255, 0.3) 0%,
                            rgba(255, 255, 255, 0) 70%
                        );
                        opacity: 0;
                        pointer-events: none;
                        transition: opacity 0.3s;
                        z-index: 5;
                        mix-blend-mode: overlay;
                    }
                    
                    .magnetic-btn:hover .btn-light-effect {
                        opacity: 1;
                    }
                </style>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // GIF effect for the incredible card
                        const incredibleCard = document.getElementById('incredible-card');
                        const gifBackground = document.getElementById('gif-background');
                        // Don't set background image on page load
                        
                        incredibleCard.addEventListener('mouseenter', function() {
                            // Only set the GIF URL when hovering
                            gifBackground.style.backgroundImage = "url('https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExMnA2b25vcnJqc2pyaXc4NDIzbmVmOHo1bGt0NG9heWszMXBkNXJldSZlcD12MV9pbnRlcm5naWZfYnlfaWQmY3Q9Zw/s2qXK8wAvkHTO/giphy.gif')";
                            gifBackground.style.opacity = '0.15'; // Slightly visible so text is still readable
                            
                            // Create a sparkle effect
                            const sparkleCount = 20;
                            for (let i = 0; i < sparkleCount; i++) {
                                createSparkle(incredibleCard);
                            }
                        });
                        
                        incredibleCard.addEventListener('mouseleave', function() {
                            gifBackground.style.opacity = '0';
                            // Remove the GIF URL completely when mouse leaves
                            setTimeout(() => {
                                gifBackground.style.backgroundImage = "none";
                            }, 300); // Wait for fade out to complete
                        });
                        
                        // Function to create sparkle effect
                        function createSparkle(parent) {
                            const sparkle = document.createElement('div');
                            sparkle.classList.add('sparkle');
                            sparkle.style.position = 'absolute';
                            sparkle.style.width = '5px';
                            sparkle.style.height = '5px';
                            sparkle.style.borderRadius = '50%';
                            sparkle.style.backgroundColor = getRandomColor();
                            sparkle.style.boxShadow = `0 0 10px ${getRandomColor()}`;
                            sparkle.style.top = `${Math.random() * 100}%`;
                            sparkle.style.left = `${Math.random() * 100}%`;
                            sparkle.style.opacity = '0';
                            sparkle.style.zIndex = '5';
                            sparkle.style.transition = 'all 1s ease';
                            
                            parent.appendChild(sparkle);
                            
                            // Animate the sparkle
                            setTimeout(() => {
                                sparkle.style.opacity = '1';
                                sparkle.style.transform = `translate(${Math.random() * 50 - 25}px, ${Math.random() * 50 - 25}px)`;
                            }, 10);
                            
                            // Remove the sparkle after animation
                            setTimeout(() => {
                                sparkle.remove();
                            }, 1000);
                        }
                        
                        function getRandomColor() {
                            const colors = ['#a3e635', '#9333ea', '#ec4899', '#3b82f6'];
                            return colors[Math.floor(Math.random() * colors.length)];
                        }
                        
                        // Floating dock effect
                        const dockContainer = document.getElementById('floating-dock-container');
                        const dockItems = document.querySelectorAll('.floating-dock-item');
                        
                        if (dockContainer && dockItems.length) {
                            dockContainer.addEventListener('mousemove', function(e) {
                                const containerRect = dockContainer.getBoundingClientRect();
                                const mouseX = e.clientX - containerRect.left;
                                
                                dockItems.forEach((item, index) => {
                                    const itemRect = item.getBoundingClientRect();
                                    const itemX = itemRect.left + itemRect.width / 2 - containerRect.left;
                                    
                                    // Calculate distance between mouse and item center
                                    const distanceFromMouse = Math.abs(mouseX - itemX);
                                    
                                    // Maximum distance to apply effect
                                    const maxDistance = 120; // Increased from 100
                                    
                                    if (distanceFromMouse < maxDistance) {
                                        // Calculate scale and elevation based on proximity to mouse
                                        const proximity = 1 - (distanceFromMouse / maxDistance);
                                        const scale = 1 + (proximity * 0.3); // Scale from 1 to 1.3
                                        const translateY = -20 * proximity; // Move up to -20px
                                                
                                        // Original horizontal position with wider spacing
                                        const originalX = (index - 1.5) * 40; // -60, -20, 20, 60
                                        
                                        // Apply transformations
                                        item.style.transform = `translateX(${originalX}px) translateY(${translateY}px) scale(${scale})`;
                                        item.style.zIndex = Math.floor(50 + proximity * 10); // Bring to front
                                    } else {
                                        // Return to original position with original X offset
                                        const originalX = (index - 1.5) * 40; // -60, -20, 20, 60
                                        item.style.transform = `translateX(${originalX}px) translateY(0)`;
                                        item.style.zIndex = 40 - index * 10; // Original z-index
                                    }
                                });
                            });
                            
                            // Reset when mouse leaves the container
                            dockContainer.addEventListener('mouseleave', function() {
                                dockItems.forEach((item, index) => {
                                    const originalX = (index - 1.5) * 40; // -60, -20, 20, 60
                                    item.style.transform = `translateX(${originalX}px) translateY(0)`;
                                    item.style.zIndex = 40 - index * 10; // Original z-index
                                });
                            });
                        }
                        
                        // Add hover effects for category tags
                        const categoryTags = document.querySelectorAll('.aspect-video .bg-neutral-300');
                        categoryTags.forEach(tag => {
                            tag.addEventListener('mouseenter', function() {
                                this.classList.add('scale-110', 'bg-lime-400');
                            });
                            tag.addEventListener('mouseleave', function() {
                                this.classList.remove('scale-110', 'bg-lime-400');
                            });
                        });
                        
                        // Initialize hover border gradient effect
                        const gradientElements = document.querySelectorAll('.hover-border-gradient');
                        gradientElements.forEach(element => {
                            element.addEventListener('mouseenter', function() {
                                this.style.borderColor = 'transparent';
                            });
                            
                            element.addEventListener('mouseleave', function() {
                                setTimeout(() => {
                                    if (!this.matches(':hover')) {
                                        this.style.borderColor = 'rgba(255, 255, 255, 0.1)';
                                    }
                                }, 50);
                            });
                        });

                        // Magnetic button effect
                        const magneticButtons = document.querySelectorAll('.magnetic-btn');
                        
                        magneticButtons.forEach(btn => {
                            const lightEffect = btn.querySelector('.btn-light-effect');
                            
                            btn.addEventListener('mousemove', function(e) {
                                const rect = this.getBoundingClientRect();
                                const x = e.clientX - rect.left;
                                const y = e.clientY - rect.top;
                                
                                // Calculate position relative to center
                                const centerX = rect.width / 2;
                                const centerY = rect.height / 2;
                                
                                // Calculate distance from center (0 to 1)
                                const distanceX = (x - centerX) / centerX;
                                const distanceY = (y - centerY) / centerY;
                                
                                // Magnetic effect - move slightly toward cursor
                                const moveX = distanceX * 6; // Max movement in pixels
                                const moveY = distanceY * 6;
                                
                                // 3D tilt effect based on cursor position
                                const tiltX = distanceY * 10; // Max tilt angle
                                const tiltY = -distanceX * 10;
                                
                                // Apply transformations
                                this.style.transform = `
                                    perspective(800px)
                                    translate3d(${moveX}px, ${moveY}px, 0)
                                    rotateX(${tiltX}deg)
                                    rotateY(${tiltY}deg)
                                    scale3d(1.03, 1.03, 1.03)
                                `;
                                
                                // Move light effect relative to cursor
                                if (lightEffect) {
                                    lightEffect.style.opacity = '1';
                                    lightEffect.style.transform = `translate(${x}px, ${y}px) translate(-50%, -50%)`;
                                }
                            });
                            
                            // Reset on mouse leave
                            btn.addEventListener('mouseleave', function() {
                                this.style.transform = 'perspective(800px) translate3d(0, 0, 0) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
                                if (lightEffect) {
                                    lightEffect.style.opacity = '0';
                                }
                            });
                            
                            // Add subtle click animation
                            btn.addEventListener('mousedown', function() {
                                this.style.transform = 'perspective(800px) translate3d(0, 2px, 0) rotateX(0) rotateY(0) scale3d(0.98, 0.98, 0.98)';
                            });
                            
                            btn.addEventListener('mouseup', function() {
                                const rect = this.getBoundingClientRect();
                                const event = new MouseEvent('mousemove', {
                                    clientX: rect.left + rect.width / 2,
                                    clientY: rect.top + rect.height / 2
                                });
                                this.dispatchEvent(event);
                            });
                        });
                    });
                </script>
        </div>
    </section>

    <!-- Integrations -->

    <section class="py-24 overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 lg:px-16  font-[var(--font-inter)] relative">
            <div class="grid lg:grid-cols-2 items-center lg:gap-16">
                <div class="">
                    <div class="flex ">
                        <div class="inline-flex border border-lime-400 gap-2 text-lime-400 px-3 py-1 rounded-full uppercase items-center">
                            <span class="text-sm">&#10038</span>
                            <span>Integrations</span>
                        </div>
                    </div>
                
                    <h2 class="text-6xl font-medium mt-6 ">All Your Coupons, One Place. <span class="text-lime-400"> One Click.</span></h2>
                    <p class="text-white/50 mt-4 text-lg">Save time and money by organizing coupons from your favorite brands in one easy-to-access hub.</p>
                </div>

                <div class="h-[400px] lg:h-[800px] overflow-hidden grid md:grid-cols-2 gap-4 [mask-image:linear-gradient(to_bottom,transparent,black_10%,black_90%,transparent)] mt-8 lg:mt-0">
                    <div class="flex flex-col gap-4 pb-4 ">
                        <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                            <div class="flex justify-center">
                                <img class="size-24 " src="assets/images/amazon-pay-svgrepo-com.svg" alt="">
                            </div>
                            <h3 class="text-3xl text-center mt-6">Amazon</h3>
                            <p class="text-center text-white/50 mt-2">"Amazon brings you endless deals—organize your coupons and save big on every purchase."</p>
                        </div>
                        <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                            <div class="flex justify-center">
                                <img class="size-24 " src="assets/images/flipkart-logo-svgrepo-com.svg" alt="">
                            </div>
                            <h3 class="text-3xl text-center mt-6">Flipkart</h3>
                            <p class="text-center text-white/50 mt-2">"Unlock the best Flipkart discounts with ease—store, track, and redeem your coupons hassle-free."</p>
                        </div>
                        <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                            <div class="flex justify-center">
                                <img class="size-24 w-40 text-white " src="assets/images/myntra-svgrepo-com.svg" alt="">
                            </div>
                            <h3 class="text-3xl text-center mt-6">Myntra</h3>
                            <p class="text-center text-white/50 mt-2">"Style meets savings! Keep your Myntra coupons organized and never miss out on fashion deals."</p>
                        </div>
                    </div>
                    <div class="hidden md:block ">
                        <div class="flex flex-col gap-4 pb-4  ">
                            <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                <div class="flex justify-center">
                                    <img class="size-24 w-40 " src="assets/images/adidas-svgrepo-com.svg" alt="">
                                </div>
                                <h3 class="text-3xl text-center mt-6">Adidas</h3>
                                <p class="text-center text-white/50 mt-2">"Score big on Adidas gear! Keep all your coupons in one place and save on every purchase."</p>
                            </div>
                            <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                <div class="flex justify-center">
                                    <img class="size-24 w-40 " src="assets/images/brand-nike-svgrepo-com.svg" alt="">
                                </div>
                                <h3 class="text-3xl text-center mt-6">Nike</h3>
                                <p class="text-center text-white/50 mt-2">"Run towards savings! Manage your Nike coupons and get the best deals on sportswear."</p>
                            </div>
                            <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                <div class="flex justify-center">
                                    <img class="size-24 w-40 " src="assets/images/zara-logo-svgrepo-com.svg" alt="">
                                </div>
                                <h3 class="text-3xl text-center mt-6">Zara</h3>
                                <p class="text-center text-white/50 mt-2">"Stay trendy while saving smart—track and use your Zara coupons effortlessly."</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha512-onMTRKJBKz8M1TnqqDuGBlowlH0ohFzMXYRNebz+yOcc5TQr/zAKsthzhuv0hiyUKEiQEQXEynnXCvNTOk50dg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

document.addEventListener('DOMContentLoaded', function() {
            // Register ScrollTrigger plugin
            gsap.registerPlugin(ScrollTrigger);
            
            // Get all word highlight spans
            const words = document.querySelectorAll('.word-highlight');
            
            if (words.length > 0) {
                // Create the scroll-based animation with staggered word reveals
                const tl = gsap.timeline({
                    scrollTrigger: {
                        trigger: "#animated-text-container",
                        start: "top 80%", // Adjust to trigger earlier
                        end: "bottom 50%",
                        scrub: 1,
                        markers: false, // Enable markers for debugging
                    }
                });
                
                // Add each word to the timeline with staggered animation
                words.forEach((word, index) => {
                    tl.to(word, {
                        className: "word-highlight text-white", // Explicitly set to full white
                        duration: 0.2,
                    }, index * 0.1); // Stagger effect with 0.1s gap between words
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Animation for brand cards
            function animateBrandCards() {
                // Select the first column of brand cards
                const firstColumn = document.querySelector('.h-\\[400px\\].grid > div:first-child');
                // Select the second column of brand cards (visible only on md breakpoint and up)
                const secondColumn = document.querySelector('.h-\\[400px\\].grid > div:nth-child(2)');
                
                if (firstColumn && secondColumn) {
                    // Get the heights for calculations
                    const firstColHeight = firstColumn.scrollHeight;
                    const secondColHeight = secondColumn.scrollHeight;
                    
                    // Create infinite scrolling animations
                    gsap.to(firstColumn, {
                        y: -firstColHeight,
                        duration: 30,
                        ease: "none",
                        repeat: -1,
                        onRepeat: function() {
                            gsap.set(firstColumn, { y: 0 });
                        }
                    });
                    
                    // Second column moves upward (opposite direction)
                    gsap.from(secondColumn, {
                        y: -secondColHeight,
                        duration: 30,
                        ease: "none",
                        repeat: -1,
                        onRepeat: function() {
                            gsap.set(secondColumn, { y: 0 });
                        }
                    });
                }
            }

            // Text scroller animation for the marquee section
            function animateTextScroller() {
                const scrollers = document.querySelectorAll('#scroller-in');
                if (scrollers.length) {
                    scrollers.forEach(scroller => {
                        gsap.to(scroller, {
                            x: "-100%",
                            duration: 20,
                            repeat: -1,
                            ease: "linear"
                        });
                    });
                }
            }

            // Initialize animations
            animateBrandCards();
            animateTextScroller();
            
            // Toggle user dropdown
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');
            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });
            }
            
            // Toggle mobile menu
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Handle email signup form
            const signupForm = document.getElementById('signup-form');
            const signupEmail = document.getElementById('signup-email');
            const signupSuccess = document.getElementById('signup-success');
            const signupError = document.getElementById('signup-error');
            
            if (signupForm) {
                signupForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (signupEmail.value && signupEmail.value.includes('@')) {
                        signupSuccess.classList.remove('hidden');
                        signupError.classList.add('hidden');
                        signupEmail.value = '';
                    } else {
                        signupError.classList.remove('hidden');
                        signupSuccess.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
