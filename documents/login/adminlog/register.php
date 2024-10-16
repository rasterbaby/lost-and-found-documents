<?php
// Connect to the database (update with your connection details)
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "documents"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // Redirect to index.html with a success parameter
        header("Location: index.html?registered=true");
        exit();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
