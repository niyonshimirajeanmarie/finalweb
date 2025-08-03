<?php
require_once 'config.php';

// CRITICAL: Check if user is logged in - redirect if not
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login first to access your profile!";
    header('Location: login.php');
    exit();
}

// Get fresh user data from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If user not found in database, destroy session and redirect
if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - jm management system </title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
     <header>
        <div class="container">
            <div class="header-content">
                <div class="logo"><img src="profile.jpg" alt="" width="70" height="50"></div>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="signup.php">Sign Up</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="profile.php">Profile</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="message success">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="message error">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-container">
                <div class="profile-header">
                  
                    <h2>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h2>
                    <p>Your account dashboard</p>
                </div>
                <div class="profile-content">
                    <article class="profile-info">
                        <div class="info-card">
                            <h4>Full Name</h4>
                            <p><?php echo htmlspecialchars($user['fullname']); ?></p>
                        </div>
                        <div class="info-card">
                            <h4>Email</h4>
                            <p><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="info-card">
                            <h4>Username</h4>
                            <p><?php echo htmlspecialchars($user['username']); ?></p>
                        </div>
                        <div class="info-card">
                            <h4>Member Since</h4>
                            <p><?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                        </div>
                    </article>
                    <div class="profile-actions">
                        <a href="edit_profile.php" class="btn">Edit Profile</a>
                        <a href="delete_profile.php" class="btn">Delete profile</a>
                        <a href="logout.php" class="btn">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 jm management system . All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
