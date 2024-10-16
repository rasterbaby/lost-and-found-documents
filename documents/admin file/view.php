<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "documents"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search variables
$username = $email = "";

// Build SQL query to fetch all records by default
$sql = "SELECT id, email, username, created_at FROM users WHERE 1=1"; // Default query to fetch all records

// Check if form is submitted for search
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);

    // Ensure that at least one field is filled
    if (empty($username) && empty($email)) {
        echo "<p style='color: red;'>Please fill in at least one field to search.</p>";
    } else {
        // Modify SQL query to add search conditions
        if (!empty($username)) {
            $sql .= " AND username LIKE '%$username%'";
        }
        if (!empty($email)) {
            $sql .= " AND email LIKE '%$email%'";
        }
    }
}

// Execute the query
$result = $conn->query($sql);

// Check if query was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <header>
        <div class="container">
            <h1>View Users</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Back to Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>User List</h2>

            <!-- Search Form -->
            <form method="POST" action="">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div>
                    <button type="submit">Search</button>
                </div>
            </form>

            <!-- Display Table -->
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>

            <?php $conn->close(); ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Admin Dashboard. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
