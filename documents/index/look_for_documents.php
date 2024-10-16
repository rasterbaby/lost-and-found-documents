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

// Initialize variables for search criteria
$first_name = '';
$last_name = '';
$search_type = '';
$found_documents = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $search_type = $_POST['search_type'] ?? '';

    // Prepare SQL query for search in found_documents
    $sql = "SELECT * FROM found_documents WHERE 1=1";

    // Adjust the query to search by first and last name
    if (!empty($first_name)) {
        $first_name = $conn->real_escape_string($first_name);
        $sql .= " AND first_name LIKE '%$first_name%'";
    }

    if (!empty($last_name)) {
        $last_name = $conn->real_escape_string($last_name);
        $sql .= " AND last_name LIKE '%$last_name%'";
    }

    // Filter by document type if specified
    if (!empty($search_type)) {
        $search_type = $conn->real_escape_string($search_type);
        $sql .= " AND document_type = '$search_type'";
    }

    // Execute the query
    $result = $conn->query($sql);
    
    // Check if any results were returned
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $found_documents[] = $row;
        }
    }
}

// Fetch distinct document types for dropdown
$document_types = ['Identity', 'Registry', 'Letters', 'Certificates', 'Licenses', 'Others'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Look for Found Documents</title>
    <link rel="stylesheet" href="look_documents.css">
    <script src="look.js" defer></script>
</head>
<body>

    <header>
        <h1>Look for Found Documents</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="look_for_documents.php">Look for Documents</a></li>
            </ul>
        </nav>
    </header>

    <main>
    <div class="container">
        <h2>Search for Found Documents</h2>

        <form action="look_for_documents.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" 
                       value="<?php echo htmlspecialchars($first_name); ?>" 
                       pattern="[A-Za-z\s]+" 
                       title="Please enter only letters and spaces." 
                       required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" 
                       value="<?php echo htmlspecialchars($last_name); ?>" 
                       pattern="[A-Za-z\s]+" 
                       title="Please enter only letters and spaces." 
                       required>
            </div>

            <div class="form-group">
                <label for="search_type">Document Type</label>
                <select id="search_type" name="search_type">
                    <option value="">All</option>
                    <?php foreach ($document_types as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($type === $search_type) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit">Search</button>
            </div>
        </form>
    </div>
</main>


            <?php if (!empty($found_documents)): ?>
                <h3>Search Results</h3>
                <table>
                    <tr>
                        <th>Full Name</th>
                        <th>Document Type</th>
                        <th>Document Name</th>
                        <th>Found Location</th>
                        <th>Found Date</th>
                        <th>Action</th> <!-- Keep the Action Column for the claim button -->
                    </tr>
                    <?php foreach ($found_documents as $doc): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($doc['first_name'] . ' ' . $doc['middle_name'] . ' ' . $doc['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($doc['document_type']); ?></td>
                            <td><?php echo htmlspecialchars($doc['document_name']); ?></td>
                            <td><?php echo htmlspecialchars($doc['found_location']); ?></td>
                            <td><?php echo htmlspecialchars($doc['found_date']); ?></td>
                            <td>
                                <form action="claim_document.php" method="POST">
                                    <input type="hidden" name="document_id" value="<?php echo htmlspecialchars($doc['id']); ?>">
                                    <button type="submit" id="claimButton">Claim</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No documents found matching your criteria.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Lost & Found Document Portal</p>
    </footer>

    <!-- Email prompt modal -->
    <div id="emailModal" style="display:none;">
        <h3>Claim Your Document</h3>
        <label for="emailMessage">Write your message:</label>
        <textarea id="emailMessage" rows="4" cols="50" placeholder="Write your message here..."></textarea>
        <br>
        <button id="sendEmailButton">Send Email</button>
        <button id="closeModalButton">Close</button>
    </div>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
