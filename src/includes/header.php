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
    <!-- ... existing code ... -->
</div>
