<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection (adjust parameters as necessary)
$servername = "localhost";
$username = "root";  // Your database username
$password = "";      // Your database password (leave empty if using default XAMPP)
$dbname = "documents";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Execute the query to find matches based on first name and last name
$sql = "SELECT 
            fd.document_type,
            fd.document_name,
            fd.first_name,
            fd.last_name,
            fd.found_location,
            fd.found_date,
            fd.contact_info,
            fd.image_path
        FROM 
            found_documents fd
        JOIN 
            lost_document ld 
        ON 
            fd.first_name = ld.first_name 
        AND 
            fd.last_name = ld.last_name";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matched Found Documents</title>
    <link rel="stylesheet" href="admin.css"> <!-- External CSS file -->
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <h1>Matched Found Documents</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="lost_documents.php">Lost Documents</a></li>
                    <li><a href="found_documents.php">Found Documents</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <h2>Matching Found Documents</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Document Type</th>
                        <th>Document Name</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Found Location</th>
                        <th>Found Date</th>
                        <th>Contact Info</th>
                        <th>Image</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['document_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['document_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['found_location']); ?></td>
                            <td><?php echo htmlspecialchars($row['found_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                            <td>
                                <?php if (!empty($row['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Found Document Image" style="max-width: 100px; max-height: 100px;">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No matching documents found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Lost & Found Document Portal. All rights reserved.</p>
        </div>
    </footer>

    <?php $conn->close(); ?>
</body>
</html>
