<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "documents";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query for documents marked as claimed
$sql = "SELECT * FROM found_documents WHERE status = 'claimed'"; // Ensure 'status' exists in your table
$result = $conn->query($sql);

// Check for SQL errors
if (!$result) {
    die("Query failed: " . $conn->error); // Output the error for debugging
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claimed Documents</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <h1>Claimed Documents</h1>
    </header>

    <main>
        <div class="container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Document Type</th>
                            <th>Document Name</th>
                            <th>Location Found</th>
                            <th>Date Found</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['document_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['document_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['found_location']); ?></td>
                                <td><?php echo htmlspecialchars($row['found_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No claimed documents found.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Lost & Found Document Portal</p>
    </footer>

</body>
</html>

<?php $conn->close(); ?>
