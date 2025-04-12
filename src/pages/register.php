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
$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

// Pre-fill email if coming from homepage form
if(isset($_POST['email']) && $_SERVER["REQUEST_METHOD"] != "POST") {
    $email = trim($_POST['email']);
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter your name.";
    } else{
        $name = trim($_POST["name"]);
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = :email";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            // Close statement
            unset($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Get the newly created user ID
                $user_id = $pdo->lastInsertId();
                
                // Store data in session variables
                $_SESSION["user_id"] = $user_id;
                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;
                
                // Redirect to dashboard
                header("location: dashboard.php");
                exit;
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>Register - Digital Coupon Organizer</title>
    <link href="../output.css" rel="stylesheet">
    <style>
        input, select, textarea {
            color-scheme: dark;
        }
    </style>
        <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-black text-white">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-neutral-900 rounded-3xl border border-white/10 p-8">
            <div class="text-center">
                <a href="../index.php">
                    <img class="h-12 mx-auto mb-6" src="../assets/images/logo.svg" alt="Digital Coupon Organizer">
                </a>
                <h2 class="text-3xl font-medium">Create your account</h2>
                <p class="text-white/50 mt-3 mb-6">Start organizing your coupons today</p>
            </div>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" id="name" name="name" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-lime-400 <?php echo (!empty($name_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $name; ?>">
                    <?php if(!empty($name_err)): ?>
                        <p class="text-red-500 text-sm mt-2"><?php echo $name_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-lime-400 <?php echo (!empty($email_err)) ? 'border-red-500' : ''; ?>" value="<?php echo $email; ?>">
                    <?php if(!empty($email_err)): ?>
                        <p class="text-red-500 text-sm mt-2"><?php echo $email_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-lime-400 <?php echo (!empty($password_err)) ? 'border-red-500' : ''; ?>">
                    <?php if(!empty($password_err)): ?>
                        <p class="text-red-500 text-sm mt-2"><?php echo $password_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full bg-neutral-800 border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-lime-400 <?php echo (!empty($confirm_password_err)) ? 'border-red-500' : ''; ?>">
                    <?php if(!empty($confirm_password_err)): ?>
                        <p class="text-red-500 text-sm mt-2"><?php echo $confirm_password_err; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full bg-lime-400 text-black font-medium py-3 rounded-lg hover:bg-lime-500 transition-colors">Sign Up</button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <p class="text-white/50">Already have an account? <a href="login.php" class="text-lime-400 hover:underline">Sign in</a></p>
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
