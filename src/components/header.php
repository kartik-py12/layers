<?php
    // Include database configuration
    require_once __DIR__ . '/../config/db.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../output.css" rel="stylesheet">
  <!-- When included in root files, use relative path -->
  <!-- <link href="./src/output.css" rel="stylesheet"> -->

  <title>Digital Coupon Organizer</title>
  <style>
    #cursor {
      height: 20px;
      width: 20px;
      border-radius: 50%;
      position: fixed;
      background-color: #a3e635;
      z-index: 99999;
      transform: translate(-50%, -50%);
      pointer-events: none;
    }
    
    #cursor-blur {
      height: 500px;
      width: 500px;
      border-radius: 50%;
      position: fixed;
      background-color: rgba(163, 230, 53, 0.1);
      filter: blur(80px);
      z-index: 9;
      transform: translate(-50%, -50%);
      pointer-events: none;
    }
  </style>
  <!-- Add cursor elements -->
  <div id="cursor" class="hidden lg:block"></div>
  <div id="cursor-blur" class="hidden lg:block"></div>
</head>
<body class="bg-black text-white">
    <?php if ($db_connection_error): ?>
    <div class="bg-red-600 text-white py-2 px-4 text-center">
        <strong>Database connection error:</strong> 
        <?php echo htmlspecialchars($db_error_message); ?>
        <br>
        <a href="#" class="underline" onclick="window.location.reload()">Try again</a>
    </div>
    <?php endif; ?>

          <!-- Nav bar -->
          <section class="py-4 lg:py-8">
        <!-- container -->
         <div class="max-w-5xl mx-auto px-4 md:px-8 lg:px-16  font-[var(--font-inter)] ">
             <div class="grid grid-cols-2 lg:grid-cols-3 border border-white/15 rounded-full p-2 px-4 md:px-2  items-center">
                 
                <div class="">
                     <img class="h-9 w-auto md:h-auto" src="assets/images/logo.svg" alt="">
                </div>

                <div class="hidden lg:block text-white ">
                    <nav class="gap-6 flex   font-medium justify-center items-center">
                        <a href="../index.php">Home</a>
                        <a href="#features">Features</a>
                        <a href="#integrations">Integrations</a>
                        <a href="#faqs">FAQS</a>
                    </nav>
                </div>
                    
                <div class="flex justify-end gap-4">
                    <svg   xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="White"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu md:hidden">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="pages/dashboard.php">
                        <button class="border hidden md:block text-white border-white h-12 rounded-full px-6 font-medium ">Dashboard</button>
                    </a>
                    <a href="pages/logout.php">
                        <button class="border hidden md:block text-black bg-lime-400 border-lime-400 h-12 rounded-full px-6 font-medium">logout</button>
                    </a>
                    <?php else: ?>
                        <a href="pages/login.php">
                        <button class="border hidden md:block text-white border-white h-12 rounded-full px-6 font-medium ">login</button>
                    </a>
                    <a href="pages/register.php">
                        <button class="border hidden md:block text-black bg-lime-400 border-lime-400 h-12 rounded-full px-6 font-medium">Sign Up</button>
                    </a>
                    <?php endif; ?>


                </div>
                
            </div>
         </div>
         
     </section>
</body>
</html>
