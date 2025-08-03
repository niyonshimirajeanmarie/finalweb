<?php
// Database configuration
$host = 'localhost';
$dbname = 'assignment2';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $user_id]);

        // Log out and destroy session
        session_destroy();
        header("Location: goodbye.php");
        exit;
    } catch (PDOException $e) {
        $message = "<div class='message error'>Error deleting profile: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Profile</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<header>
    <div class="container header-content">
        <div class="logo">MyApp</div>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="form-container">
        <div class="form-header">
            <h2>Delete Your Profile</h2>
            <p style="color:red;">⚠️ This action is irreversible!</p>
        </div>

        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label>Are you sure you want to delete your profile?</label>
            </div>
            <button type="submit" name="confirm_delete" class="form-submit" style="background:#c53030;">Yes, Delete My Profile</button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2025 jm management system . All rights reserved.</p>
</footer>
</body>
</html>
