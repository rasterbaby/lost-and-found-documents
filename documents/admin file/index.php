<?php
// Start the session
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login/adminlog");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css"> <!-- External CSS for styling -->
</head>
<body>

    <!-- Admin Dashboard Header with Navigation Links -->
    <header>
        <div class="container">
            <h1>Admin Dashboard</h1>
            <nav>
                <ul>
                    <li><a href="view.php">View Users</a></li>
                    <li><a href="view_documents.php">View all found Documents</a></li>
                    <li><a href="view_claimes.php">View claimes</a></li>
                    <li><a href="matched_documents.php">matched Reports</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Body Section -->
    <main>
        <div class="container">
            <h2>Welcome to the Lost & Found Document Portal</h2>
            <p>Welcome admin</p>

            <div class="image-gallery">
                <!-- Image placeholders -->
                <img src="../images/image7.jpg" alt="Lost Document Image 2">
                <img src="../images/image6.jpg" alt="Lost Document Image 1">
                <img src="../images/image8.jpg" alt="Lost Document Image 2">
                <img src="../images/image5.jpg" alt="Lost Document Image 3">
            </div>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Admin Dashboard. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
