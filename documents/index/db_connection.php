<?php
$servername = "localhost";
$username = "root";
$password = "";  // Set your MySQL password here
$dbname = "documents";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
