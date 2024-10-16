<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "documents";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the document type from the request
$document_type = $_GET['type'] ?? '';

// Prepare the statement
$stmt = $conn->prepare("SELECT DISTINCT document_name FROM lost_document WHERE document_type = ?");
$stmt->bind_param("s", $document_type);
$stmt->execute();
$result = $stmt->get_result();

$document_names = [];
while ($row = $result->fetch_assoc()) {
    $document_names[] = $row['document_name'];
}

// Return document names as JSON
header('Content-Type: application/json');
echo json_encode($document_names);

// Close connection
$stmt->close();
$conn->close();
?>
