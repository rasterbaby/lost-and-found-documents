<?php
// Connect to the database (update with your connection details)
$servername = "localhost";
$dbUsername = "root"; // Your database username
$dbPassword = ""; // Your database password
$dbname = "documents"; // Your database name

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve username and password from POST request, trimming any whitespace
    $username = trim($_POST['username'] ?? '');
    //$email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check if fields are empty
    if (empty($username) ||  empty($password)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    // Prepare and bind to check user credentials
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    
    if (!$stmt) {
        echo "<script>alert('Database query preparation failed: " . $conn->error . "'); window.history.back();</script>";
        exit();
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Redirect to index.html
            header("Location: ../index/index.html");
            exit();
        } else {
            // Invalid password
            echo "<script>alert('Invalid credentials!'); window.history.back();</script>";
        }
    } else {
        // User not found
        echo "<script>alert('Invalid credentials!'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
