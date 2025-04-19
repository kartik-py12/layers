<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <title>Digital Coupon Organizer - Home</title>

</head>
<body class="bg-black text-white" >

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

    <!-- Add GSAP libraries before the closing body tag if not already included -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    
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
                        start: "top 80%", 
                        end: "bottom 50%",
                        scrub: 1,
                        markers: false, // Set to false to hide the debug markers
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
    </script>
    
</body>
</html>