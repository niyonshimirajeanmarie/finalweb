<?php
require_once 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $errors[] = "Please enter both username and password";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, fullname, email, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['success'] = "Login successful! Welcome back, " . $user['fullname'] . "!";
            
            // Redirect to profile page
            header('Location: profile.php');
            exit();
        } else {
            $errors[] = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - UserManager</title>

    <style>
        /* Basic reset */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f7;
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
            background: url('https://cdni.iconscout.com/illustration/premium/thumb/user-setting-illustration-download-in-svg-png-gif-file-formats--management-preferences-configuration-person-web-design-pack-development-illustrations-3733013.png') center center no-repeat;
            background-size: cover;
            min-height: 500px;
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
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
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

        /* Responsive */
        @media (max-width: 900px) {
            .form-layout {
                flex-direction: column;
                min-height: auto;
            }
            .image-side {
                min-height: 300px;
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
              <a href="index.php">Home</a>
                <div class="form-container">
                    <div class="form-header">
                        <h2>Welcome Back</h2>
                        <p>Login to access your profile</p>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <div class="message error">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="POST" novalidate>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required />
                        </div>
                        <button type="submit" class="form-submit">Login</button>
                    </form>

                    <div class="form-link">
                        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
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
</body>
</html>
