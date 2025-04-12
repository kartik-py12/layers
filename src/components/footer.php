    <!-- Footer section -->
    <footer class="py-12 bg-neutral-900">
        <div class="max-w-5xl mx-auto px-4 lg:px-16 font-[var(--font-inter)]">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <img class="h-9 w-auto mb-4" src="assets/images/logo.svg" alt="Digital Coupon Organizer">
                    <p class="text-white/50 mb-6">Your one-stop solution for organizing all your digital coupons. Save time, save money, and never miss a deal again.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-white/70 hover:text-lime-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </a>
                        <a href="#" class="text-white/70 hover:text-lime-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                        </a>
                        <a href="#" class="text-white/70 hover:text-lime-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-white/70 hover:text-lime-400">Home</a></li>
                        <li><a href="#features" class="text-white/70 hover:text-lime-400">Features</a></li>
                        <li><a href="#integrations" class="text-white/70 hover:text-lime-400">Integrations</a></li>
                        <li><a href="#faqs" class="text-white/70 hover:text-lime-400">FAQs</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-white/70 hover:text-lime-400">Contact Us</a></li>
                        <li><a href="#" class="text-white/70 hover:text-lime-400">Privacy Policy</a></li>
                        <li><a href="#" class="text-white/70 hover:text-lime-400">Terms of Service</a></li>
                        <li><a href="#" class="text-white/70 hover:text-lime-400">Help Center</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 mt-12 pt-6 text-center text-white/50">
                <p>&copy; <?php echo date('Y'); ?> Digital Coupon Organizer. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha512-onMTRKJBKz8M1TnqqDuGBlowlH0ohFzMXYRNebz+yOcc5TQr/zAKsthzhuv0hiyUKEiQEQXEynnXCvNTOk50dg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="script.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
