<?php
require_once 'config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($fullname)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if username or email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username or email already exists";
        }
    }
    
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, username, password, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$fullname, $email, $username, $hashed_password])) {
            $success = "Account created successfully! You can now login.";
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign Up - UserManager</title>

    <style>
        /* Basic reset */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #5494e4ff;
            color: #333;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 0 auto;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-weight: 700;
            font-size: 24px;
            color: #007bff;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }
        nav ul li a {
            font-weight: 600;
        }

        /* Main layout with form and image side */
        .form-layout {
            display: flex;
            max-width: 1100px;
            margin: 40px auto 60px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            overflow: hidden;
            min-height: 500px;
        }
        .form-side {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .image-side {
            flex: 1;
            background: url('https://www.miniorange.com/images/user-management/user-management-features.webp') center center no-repeat;
            background-size: cover;
            background-position: center;
            min-height: 200px;
        }

        /* Form styles */
        .form-header h2 {
            margin-bottom: 5px;
            font-size: 28px;
            color: #222;
        }
        .form-header p {
            margin-top: 0;
            color: #666;
            font-size: 15px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #444;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-submit {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: 700;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-submit:hover {
            background-color: #0056b3;
        }
        .form-link {
            margin-top: 20px;
            text-align: center;
            font-size: 15px;
        }

        /* Messages */
        .message {
            margin-bottom: 25px;
            padding: 15px 20px;
            border-radius: 6px;
            font-weight: 600;
        }
        .message.error {
            background-color: #f8d7da;
            color: #842029;
            border: 1.5px solid #f5c2c7;
        }
        .message.success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1.5px solid #badbcc;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .form-layout {
                flex-direction: column;
                min-height: auto;
            }
            .image-side {
                min-height: 200px;
                background: url('https://www.miniorange.com/images/user-management/user-management-features.webp') center center no-repeat;
                background-size: cover;
            }
        }
    </style>
</head>
<body>
    <main>
        <div class="form-layout">
            <div class="form-side">
                <div class="form-container">
                    <div class="form-header">
                      <a href="index.php">Home</a>
                        <h2>Create Your Account</h2>
                        <p>Join us today and start managing your profile</p>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <div class="message error">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="message success">
                            <p><?php echo htmlspecialchars($success); ?></p>
                            <p><a href="login.php">Click here to login</a></p>
                        </div>
                    <?php endif; ?>

                    <form id="signupForm" action="signup.php" method="POST" novalidate>
                        <div class="form-group">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" required />
                            <span id="fullnameError" style="color:red;"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
                            <span id="emailError" style="color:red;"></span>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required />
                            <span id="usernameError" style="color:red;"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required />
                            <span id="passwordError" style="color:red;"></span>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required />
                            <span id="confirm_passwordError" style="color:red;"></span>
                        </div>
                        <button type="submit" class="form-submit">Create Account</button>
                    </form>

                    <div class="form-link">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>

            <div class="image-side"></div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 jm management system . All rights reserved.</p>
        </div>
    </footer>

    <script>
    document.getElementById('signupForm').addEventListener('submit', function(event) {
        let hasError = false;

        // Full name validation
        const fullname = document.getElementById('fullname').value.trim();
        const fullnameError = document.getElementById('fullnameError');
        if (fullname === '') {
            fullnameError.textContent = "Full Name is required";
            hasError = true;
        } else {
            fullnameError.textContent = "";
        }

        // Email validation
        const email = document.getElementById('email').value.trim();
        const emailError = document.getElementById('emailError');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
            emailError.textContent = "Email is required";
            hasError = true;
        } else if (!emailPattern.test(email)) {
            emailError.textContent = "Please enter a valid email address";
            hasError = true;
        } else {
            emailError.textContent = "";
        }

        // Username validation
        const username = document.getElementById('username').value.trim();
        const usernameError = document.getElementById('usernameError');
        if (username.length < 3) {
            usernameError.textContent = "Username must be at least 3 characters";
            hasError = true;
        } else {
            usernameError.textContent = "";
        }

        // Password validation
        const password = document.getElementById('password').value;
        const passwordError = document.getElementById('passwordError');
        if (password.length < 6) {
            passwordError.textContent = "Password must be at least 6 characters";
            hasError = true;
        } else {
            passwordError.textContent = "";
        }

        // Confirm password validation
        const confirmPassword = document.getElementById('confirm_password').value;
        const confirmPasswordError = document.getElementById('confirm_passwordError');
        if (confirmPassword !== password) {
            confirmPasswordError.textContent = "Passwords do not match";
            hasError = true;
        } else {
            confirmPasswordError.textContent = "";
        }

        if (hasError) {
            event.preventDefault(); // Prevent form submission if errors exist
        }
    });
    </script>

</body>
</html>
