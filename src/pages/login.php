<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to dashboard
if(isset($_SESSION["user_id"])){
    header("location: dashboard.php");
    exit;
}

// Include database connection
require_once "../config/db.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, name, email, password FROM users WHERE email = :email";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if email exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $name = $row["name"];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["user_id"] = $id;
                            $_SESSION["name"] = $name;
                            $_SESSION["email"] = $email;
                            
                            // Redirect user to dashboard
                            header("location: dashboard.php");
                            exit;
                        } else{
                            // Password is not valid
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else{
                    // Email doesn't exist
                    $login_err = "Invalid email or password.";
                }
            } else{
                $login_err = "Oops! Something went wrong. Please try again later.";
            }
            
            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digital Coupon Organizer</title>
    <link href="../output.css" rel="stylesheet">
    <!-- Add a version parameter to prevent caching -->
    <!-- <link href="../output.css?v=<?php echo time(); ?>" rel="stylesheet"> -->
    <style>
        input, select, textarea {
            color-scheme: dark;
        }
    </style>
        <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-black text-white">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 lg:p-20 ">
        <div class="max-w-md w-full space-y-8 bg-neutral-900 rounded-3xl border border-white/10 p-8">
            <div class="text-center">
                <a href="../index.php">
                    <img class="h-12 mx-auto mb-6" src="../assets/images/logo.svg" alt="Digital Coupon Organizer">
                </a>
                <h2 class="text-3xl font-medium">Welcome back</h2>
                <p class="text-white/50 mt-3 mb-6">Sign in to access your coupons</p>
            </div>
            
            <?php if(!empty($login_err)): ?>
                <div class="bg-red-500/20 border border-red-500 text-white px-4 py-3 rounded-lg mb-6">
                    <?php echo $login_err; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-lime-400 <?php echo (!empty($email_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $email; ?>">
                    <?php if(!empty($email_err)): ?>
                        <p class="text-red-500 text-sm mt-2"><?php echo $email_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium">Password</label>
                        <!-- <a href="#" class="text-sm text-lime-400 hover:underline">Forgot password?</a> -->
                    </div>
                    <input type="password" id="password" name="password" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-lime-400 <?php echo (!empty($password_err)) ? 'border-red-500' : ''; ?>">
                    <?php if(!empty($password_err)): ?>
                        <p class="text-red-500 text-sm mt-2"><?php echo $password_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full bg-lime-400 text-black font-medium py-3 rounded-lg hover:bg-lime-500 transition-colors">Sign In</button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-white/50">Don't have an account? <a href="register.php" class="text-lime-400 hover:underline">Sign up</a></p>
            </div>
            
            <div class="mt-6 text-center">
                <a href="../index.php" class="text-white/50 hover:text-white inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
