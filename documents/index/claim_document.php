<?php
// Database connection
$servername = "localhost"; // Update if different
$username = "root"; // Update if different
$password = ""; // Update if different
$dbname = "documents"; // Update if this is not your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = ""; 
$contact_info = ""; // Initialize contact_info variable

// Check if document_id is set
if (isset($_POST['document_id'])) {
    $document_id = intval($_POST['document_id']); // Get document ID from form

    // Fetch document details from the found_documents table
    $sql = "SELECT * FROM found_documents WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $document_id);
    
    // Execute the query and check for errors
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Fetch the document details
        $doc = $result->fetch_assoc();
        $contact_info = $doc['contact_info']; // Store contact info for WhatsApp link

        // Prepare the insert query for the requested_documents table
        $insert_sql = "INSERT INTO requested_documents (document_type, document_name, first_name, middle_name, last_name, found_location, found_date, image_path, contact_info) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insert_stmt = $conn->prepare($insert_sql);
        if (!$insert_stmt) {
            die("Error preparing insert statement: " . $conn->error);
        }

        // Bind parameters and execute the insert
        $insert_stmt->bind_param(
            "sssssssss",
            $doc['document_type'],
            $doc['document_name'],
            $doc['first_name'],
            $doc['middle_name'],
            $doc['last_name'],
            $doc['found_location'],
            $doc['found_date'],
            $doc['image_path'],
            $contact_info // Include contact info
        );

        // Execute the insert query and check for success
        if ($insert_stmt->execute()) {
            // Prepare the WhatsApp message
            $message = "Your document has been claimed successfully!";
            $whatsappMessage = urlencode($message);
            $whatsappLink = "https://wa.me/{$contact_info}?text={$whatsappMessage}";
            
            // Redirect to WhatsApp link
            header("Location: $whatsappLink");
            exit(); // Ensure no further code is executed after redirection
        } else {
            $message = "Error inserting into requested_documents: " . $insert_stmt->error;
        }
    } else {
        $message = "No document found with that ID.";
    }

    // Close statements
    $stmt->close();
} else {
    $message = "No document ID provided.";
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Found Document</title>
</head>
<body>

    <div class="container">
        <h1>Claim Found Document</h1>
        <form method="POST" id="claimForm">
            <label for="document_id">Enter Document ID:</label>
            <input type="number" id="document_id" name="document_id" required>
            <button type="submit">Claim Document</button>
        </form>
        <?php if ($message): ?>
            <div class="message" style="color: red;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
