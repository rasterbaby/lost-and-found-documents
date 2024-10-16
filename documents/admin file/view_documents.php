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
$document_name = isset($_POST['document_name']) ? $_POST['document_name'] : '';
$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';

// Default SQL query
$sql = "SELECT id, document_type, document_name, first_name, middle_name, last_name, found_location, found_date, contact_info, founder_name, image_path, admin_decision, created_at 
        FROM found_documents
        WHERE 1=1";

// Check if form is submitted for search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $document_name = $conn->real_escape_string($document_name);
    $first_name = $conn->real_escape_string($first_name);
    $last_name = $conn->real_escape_string($last_name);

    if (!empty($document_name)) {
        $sql .= " AND document_name LIKE '%$document_name%'";
    }
    if (!empty($first_name)) {
        $sql .= " AND first_name LIKE '%$first_name%'";
    }
    if (!empty($last_name)) {
        $sql .= " AND last_name LIKE '%$last_name%'";
    }
}

// Execute the query
$result = $conn->query($sql);

// Handle admin decision updates via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['decision'])) {
    $document_id = $conn->real_escape_string($_POST['document_id']);
    $decision = $conn->real_escape_string($_POST['decision']);

    // Update the new decision in the database
    $update_sql = "UPDATE found_documents SET admin_decision = '$decision' WHERE id = '$document_id'";

    if ($conn->query($update_sql) === false) {
        echo json_encode(['success' => false, 'message' => "Error updating decision: " . $conn->error]);
        exit;
    } else {
        echo json_encode(['success' => true, 'decision' => $decision, 'id' => $document_id]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Found Documents</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <header>
        <div class="container">
            <h1>View all found Found Documents</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Back to Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Found Document List</h2>

            <!-- Search Form -->
            <form method="POST" action="">
                <div>
                    <label for="document_name">Document Name:</label>
                    <input type="text" name="document_name" id="document_name" value="<?php echo htmlspecialchars($document_name); ?>">
                </div>
                <div>
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                </div>
                <div>
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                </div>
                <div>
                    <button type="submit" name="search">Search</button>
                </div>
            </form>

            <!-- Display Table -->
            <?php if ($result->num_rows > 0): ?>
                <table id="documentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Document Type</th>
                            <th>Document Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Found Location</th>
                            <th>Found Date</th>
                            <th>Contact Info</th>
                            <th>Founder Name</th>
                            <th>Image</th>
                            <th>Admin Decision</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr data-id="<?php echo htmlspecialchars($row['id']); ?>">
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['document_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['document_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['middle_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['found_location']); ?></td>
                                <td><?php echo htmlspecialchars($row['found_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                                <td><?php echo htmlspecialchars($row['founder_name']); ?></td>
                                <td>
                                  <?php if (!empty($row['image_path'])): ?>
                                  <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Found Document Image" style="max-width: 100px; max-height: 100px;">
                                  <?php else: ?>
                                  No Image
                                  <?php endif; ?>
                                </td>
                                <td class="admin-decision"><?php echo htmlspecialchars($row['admin_decision']); ?></td>
                                <td>
                                    <button class="decision-button" data-decision="Reunited">Reunited</button>
                                    <button class="decision-button" data-decision="Pending">Pending</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No documents found.</p>
            <?php endif; ?>

            <?php $conn->close(); ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Admin Dashboard. All rights reserved.</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            $('.decision-button').click(function() {
                var decision = $(this).data('decision');
                var documentRow = $(this).closest('tr');
                var documentId = documentRow.data('id');

                if (confirm("Are you sure you want to set this document as " + decision + "?")) {
                    $.ajax({
                        url: '', // Send the request to the same PHP file
                        type: 'POST',
                        data: {
                            decision: decision,
                            document_id: documentId
                        },
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                documentRow.find('.admin-decision').text(response.decision);
                                alert("Document status updated to " + response.decision);
                            } else {
                                alert("Error: " + response.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert("AJAX Error: " + textStatus + " : " + errorThrown);
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>
