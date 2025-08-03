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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("UPDATE users SET fullname = :fullname, email = :email WHERE id = :id");
    $stmt->execute([
        ':fullname' => $fullname,
        ':email' => $email,
        ':id' => $user_id
    ]);

    $message = "<div class='message success'>Profile updated successfully.</div>";
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<header>
    <div class="container header-content">
        <div class="logo">MyApp</div>
        <nav>
            <ul>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="form-container">
        <div class="form-header">
            <h2>Edit Profile</h2>
        </div>

        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" name="fullname" id="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <button type="submit" class="form-submit">Save Changes</button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2025 jm management system . All rights reserved.</p>
</footer>
</body>
</html>
