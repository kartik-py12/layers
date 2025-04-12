<?php
  // Start session
  session_start();
  
  // Include header component
//   include_once 'components/header.php';
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
                                <div class="size-8 rounded-full bg-lime-400 flex items-center justify-center text-black font-medium">
                                    <?php echo substr($_SESSION["name"], 0, 1); ?>
                                </div>
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

            <form class="flex border border-white/15 rounded-full p-2 justify-between mt-8 max-w-2xl mx-auto">
                <input class="bg-transparent px-4 text-sm md:text-lg " type="text" placeholder="Enter your email">
                <button class="border md:mr-2 md:text-[16px] md:px-6 md:h-14  text-black bg-lime-400 border-lime-400 h-10 rounded-full px-3 text-sm font-medium whitespace-nowrap" type="submit">Sign Up</button>
            </form>
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
        <div class="max-w-5xl mx-auto px-4 lg:px-16  font-[var(--font-inter)] relative">
            <div class="flex justify-center">
                <div class="inline-flex border border-lime-400 gap-2 text-lime-400 px-3 py-1 rounded-full uppercase items-center">
                    <span class="text-sm">&#10038</span>
                    <span>Introducing Layers</span>
                </div>
            </div>
            <div class="text-4xl md:text-5xl lg:text-6xl text-center font-medium mt-10">
                <span class="">Keep all your coupons in one place.</span>
                <span class="text-white/15">effortlessly sorted by category, expiry date, and priority. Get smart reminders before they expire and find the best deals instantly.</span>
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
                <div class="mt-12 grid grid-cols-1 md:grid-cols-4 lg:grid-cols-3  gap-8 ">

                    <div class="bg-neutral-900 border md:col-span-2 lg:col-span-1 border-white/10 p-6 rounded-3xl">
                        <div class="aspect-video flex items-center justify-center ">
                            <img class=" z-40 size-20 rounded-full overflow-hidden border-4 border-blue-500 p-1 bg-neutral-900" src="assets/images/avatar-ashwin-santiago.jpg" alt="">
                            <img class="z-30 size-20 rounded-full overflow-hidden border-4 border-indigo-500 p-1 -ml-6 bg-neutral-900" src="assets/images/avatar-lula-meyers.jpg" alt="">
                            <img class="z-20 size-20 rounded-full overflow-hidden border-4 border-amber-500 p-1 -ml-6 bg-neutral-900" src="assets/images/avatar-florence-shaw.jpg" alt="">
                            <div class="size-20 rounded-full overflow-hidden border-transparent p-1 bg-neutral-900 -ml-6">
                                <!-- <img class="size-20 rounded-full overflow-hidden border-4 border-transparent p-1 -ml-6 bg-neutral-900" src="" alt=""> -->
                                <div class="size-full bg-neutral-700 rounded-full inline-flex items-center justify-center gap-1">
                                    <span class="size-1.5 rounded-full bg-white"></span>
                                    <span class="size-1.5 rounded-full bg-white"></span>
                                    <span class="size-1.5 rounded-full bg-white"></span>
                                    
                                </div>
                            </div>
                            
                        </div>
                        <div class="">
                            <h3 class="text-3xl font-medium mt-6">Expiry Date Alert</h3>
                            <p class="text-white/50 mt-2">Get notified before your coupons expire and never miss a deal again!</p>
                        </div>
                    </div>
                    
                    <div class="bg-neutral-900 border md:col-span-2 lg:col-span-1 border-white/10 p-6 rounded-3xl">
                        <div class="aspect-video flex items-center justify-center ">
                            <p class="text-4xl font-extrabold text-white/20 text-center">We've made <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">incredible</span> savings easier than ever!</p>
                        </div>


                        <div class="">
                            <h3 class="text-3xl font-medium mt-6">Advanced Filters</h3>
                            <p class="text-white/50 mt-2">Find the right coupon instantly with powerful search and filter options.</p>
                        </div>
                    </div>

                    <div class="bg-neutral-900 border md:col-span-2 md:col-start-2 lg:col-start-auto lg:col-span-1 border-white/10 p-6 rounded-3xl">
                        <div class="aspect-video flex items-center justify-center gap-4 flex-wrap">
                            <div class="size-14 bg-neutral-300 inline-flex items-center justify-center rounded-2xl text-xl text-neutral-950 font-medium w-max p-3">Electronics</div>
                            <div class="size-14 bg-neutral-300 inline-flex items-center justify-center rounded-2xl text-xl text-neutral-950 font-medium  w-max p-3">Groceries</div>
                            <div class="size-14 bg-neutral-300 inline-flex items-center justify-center rounded-2xl text-xl text-neutral-950 font-medium  w-max p-3">Lifestyle</div>
                        </div>
                        <div class="">
                            <h3 class="text-3xl font-medium mt-6">Smart Categories</h3>
                            <p class="text-white/50 mt-2">Organize your coupons by category for quick and easy access.</p>
                        </div>
                    </div>
                </div>


                <div class="mt-8 md:mt-10 flex flex-wrap gap-3 justify-center">
                    <!-- 1 -->
                    <div class="bg-neutral-900 border border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl ">&#10038</span>
                        <span class="font-medium">Expiry Alerts</span>
                    </div>

                    <!-- 2 -->
                    <div class="bg-neutral-900 border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Advanced Filters</span>
                    </div>

                    <!-- 3 -->
                    <div class="bg-neutral-900 border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">One-Tap Access</span>
                    </div>

                    <!-- 4 -->
                    <div class="bg-neutral-900 border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Custom Tags</span>
                    </div>

                    <!-- 5 -->
                    <div class="bg-neutral-900 border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Auto-Sort</span>
                    </div>

                    <!-- 6 -->
                    <div class="bg-neutral-900 border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Cloud Sync</span>
                    </div>
                    <!-- 7 -->
                    <div class="bg-neutral-900 border-white/10 inline-flex px-3 py-1.5 md:px-5 md:py-2 md:text-lg rounded-2xl gap-3 items-center">
                        <span class="bg-lime-400 text-neutral-950 size-5 rounded-full inline-flex items-center justify-center text-xl">&#10038</span>
                        <span class="font-medium">Deal Tracker </span>
                    </div>
                </div>



        </div>
    </section>

    <!-- intigrations -->

     <section class="py-24 overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 lg:px-16  font-[var(--font-inter)] relative">
            <div class="grid lg:grid-cols-2 items-center lg:gap-16">
                <div class="">
                        <!-- div intergation -->
                        <div class="flex ">
                            <div class="inline-flex border border-lime-400 gap-2 text-lime-400 px-3 py-1 rounded-full uppercase items-center">
                                <span class="text-sm">&#10038</span>
                                <span>Integrations</span>
                            </div>
                        </div>
                    
                        <h2 class="text-6xl font-medium mt-6 ">All Your Coupons, One Place. <span class="text-lime-400"> One Click.</span></h2>
                        <p class="text-white/50 mt-4 text-lg">Save time and money by organizing coupons from your favorite brands in one easy-to-access hub.</p>
                </div>

                <div class="">
                        <div class="h-[400px] lg:h-[800px] overflow-hidden grid md:grid-cols-2 gap-4 [mask-image:linear-gradient(to_bottom,transparent,black_10%,black_90%,transparent)] mt-8 lg:mt-0">
                            <div class="">
                            
                                <div class="flex flex-col gap-4 pb-4 ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 " src="assets/images/amazon-pay-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Amazon</h3>
                                        <p class="text-center text-white/50 mt-2">"Amazon brings you endless deals—organize your coupons and save big on every purchase."</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-4 pb-4 ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 " src="assets/images/flipkart-logo-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Flipkart</h3>
                                        <p class="text-center text-white/50 mt-2">"Unlock the best Flipkart discounts with ease—store, track, and redeem your coupons hassle-free."</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-4 pb-4 ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 w-40 text-white " src="assets/images/myntra-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Myntra</h3>
                                        <p class="text-center text-white/50 mt-2">"Style meets savings! Keep your Myntra coupons organized and never miss out on fashion deals."</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-4 pb-4 ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 w-40 " src="assets/images/zara-logo-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Zara</h3>
                                        <p class="text-center text-white/50 mt-2">"Stay trendy while saving smart—track and use your Zara coupons effortlessly."</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-4 pb-4 ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 w-40 " src="assets/images/brand-nike-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Nike
                                        </h3>
                                        <p class="text-center text-white/50 mt-2">"Run towards savings! Manage your Nike coupons and get the best deals on sportswear."</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-4 pb-4 ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 w-40 " src="assets/images/adidas-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Adidas</h3>
                                        <p class="text-center text-white/50 mt-2">"Score big on Adidas gear! Keep all your coupons in one place and save on every purchase."</p>
                                    </div>
                                </div>
                            </div>


                                <!-- for reverse -->
                            <div class="hidden md:block ">
                            
                                    <div class="flex flex-col gap-4 pb-4  ">
                                        <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                            <div class="flex justify-center">
                                                <img class="size-24 w-40 " src="assets/images/adidas-svgrepo-com.svg" alt="">
                                            </div>
                                            <h3 class="text-3xl text-center mt-6">Adidas</h3>
                                            <p class="text-center text-white/50 mt-2">"Score big on Adidas gear! Keep all your coupons in one place and save on every purchase."</p>
                                        </div>
                                    </div>
                                
                                
                                    <div class="flex flex-col gap-4 pb-4  ">
                                        <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                            <div class="flex justify-center">
                                                <img class="size-24 w-40 " src="assets/images/brand-nike-svgrepo-com.svg" alt="">
                                            </div>
                                            <h3 class="text-3xl text-center mt-6">Nike
                                            </h3>
                                            <p class="text-center text-white/50 mt-2">"Run towards savings! Manage your Nike coupons and get the best deals on sportswear."</p>
                                        </div>
                                    </div>
                                
                                    <div class="flex flex-col gap-4 pb-4  ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 w-40 " src="assets/images/zara-logo-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Zara</h3>
                                        <p class="text-center text-white/50 mt-2">"Stay trendy while saving smart—track and use your Zara coupons effortlessly."</p>
                                    </div>
                                </div>
                            
                                <div class="flex flex-col gap-4 pb-4  ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 w-40 text-white " src="assets/images/myntra-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Myntra</h3>
                                        <p class="text-center text-white/50 mt-2">"Style meets savings! Keep your Myntra coupons organized and never miss out on fashion deals."</p>
                                    </div>
                                </div>
                            
                                <div class="flex flex-col gap-4 pb-4  ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                            <img class="size-24 " src="assets/images/flipkart-logo-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Flipkart</h3>
                                        <p class="text-center text-white/50 mt-2">"Unlock the best Flipkart discounts with ease—store, track, and redeem your coupons hassle-free."</p>
                                    </div>
                                </div>
                            
                            
                                <div class="flex flex-col gap-4 pb-4  ">
                                    <div class="bg-neutral-900 border border-white/10 rounded-3xl p-6">
                                        <div class="flex justify-center">
                                        <img class="size-24 " src="assets/images/amazon-pay-svgrepo-com.svg" alt="">
                                        </div>
                                        <h3 class="text-3xl text-center mt-6">Amazon</h3>
                                        <p class="text-center text-white/50 mt-2">"Amazon brings you endless deals—organize your coupons and save big on every purchase."</p>
                                    </div>
                                </div>
                            </div>
                        </div>        
                </div>
            
            </div>
    

        </div>
     </section>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha512-onMTRKJBKz8M1TnqqDuGBlowlH0ohFzMXYRNebz+yOcc5TQr/zAKsthzhuv0hiyUKEiQEQXEynnXCvNTOk50dg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="script.js"></script>

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
    
<?php
  // Include footer component
  include_once 'components/footer.php';
?>