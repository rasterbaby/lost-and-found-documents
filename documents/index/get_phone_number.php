<?php
// Database connection
$servername = "localhost"; // Update if different
$username = "root"; // Update if different
$password = ""; // Update if different
$dbname = "documents"; // Update if this is not your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Check if id is set
if (isset($_GET['id'])) {
    $document_id = intval($_GET['id']); // Get document ID from URL

    // Fetch the contact info from the found_documents table
    $sql = "SELECT contact_info FROM found_documents WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => "Error preparing statement: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("i", $document_id);

    // Execute the query and check for errors
    if (!$stmt->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => "Error executing statement: " . $stmt->error]);
        exit();
    }

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $doc = $result->fetch_assoc();
        // Return contact_info as JSON
        echo json_encode(['contact_info' => $doc['contact_info']]);
    } else {
        echo json_encode(['contact_info' => null]); // Return null if not found
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Document ID not provided']);
}

// Close connection
$conn->close();
?>
