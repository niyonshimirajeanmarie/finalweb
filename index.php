<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jm management system </title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .toggle-btn {
            margin: 10px 0;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .toggle-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo"><img src="logo.png" alt="" width="70" height="50"></div>
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
        <section class="first-section">
            <div class="container">
                <h1>Welcome to Our jm Management System</h1>
                <p id="greeting" style="font-size: 18px; color: #333;"></p>

                <div class="cta-buttons">
                    <a href="signup.php" class="btn">Get Started to the journey </a>
                    <a href="login.php" class="btn btn-primary">Login</a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2>Core Features</h2>
                <div class="features-grid">
                    <article class="feature-card">
                        <h3>Secure Registration company</h3>
                        <p>Safe and secure user registration with password encryption and data validation for your company.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Profiles Management</h3>
                        <p>Complete profile management system to secure data storage.</p>
                    </article>
                    <article class="feature-card">
                        <h3>Management Style</h3>
                        <p>Team size and experience, organizational culture, nature of the task, urgency and stakes of decision-making.</p>
                    </article>
                </div>
            </div>
        </section>

        <div class="container">
            <button class="toggle-btn" onclick="toggleTips()">Show/Hide Tips</button>
            <aside id="tips-section">
                <h3>Tips to success using our system</h3>
                <ul>
                    <li>Use a strong password with at least 8 characters</li>
                    <li>Keep your profile information up to date</li>
                    <li>Never share your login credentials to everyone</li>
                    <li>Log out when using shared computers like in outside companies</li>
                    <li>Contact support if you encounter any issues</li>
                    <li>Remember your username and your password</li>
                </ul>
            </aside>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 jmmanagement system. All rights reserved. | Let's build the future together</p>
        </div>
    </footer>

    <script>
        // Show greeting based on time
        function showGreeting() {
            const greetingElement = document.getElementById("greeting");
            const now = new Date();
            const hour = now.getHours();
            let greetingText;

            if (hour < 12) {
                greetingText = "Good morning, Jeanmarie!";
            } else if (hour < 18) {
                greetingText = "Good afternoon, Jeanmarie!";
            } else {
                greetingText = "Good evening, Jeanmarie!";
            }

            greetingElement.textContent = greetingText;
        }

        // Toggle tips visibility
        function toggleTips() {
            const tips = document.getElementById("tips-section");
            if (tips.style.display === "none") {
                tips.style.display = "block";
            } else {
                tips.style.display = "none";
            }
        }

        // On page load
        window.onload = function () {
            showGreeting();
            // Hide tips by default
            document.getElementById("tips-section").style.display = "none";
        };
    </script>
</body>
</html>
