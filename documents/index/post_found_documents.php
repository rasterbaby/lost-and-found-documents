<?php
// Start the session
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $document_type = $_POST['document_type'];
    $document_name = $_POST['document_name'];
    $first_name = $_POST['first_name'];
    $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : null; // Handle optional middle name
    $last_name = $_POST['last_name'];
    $found_location = $_POST['found_location'];
    $found_date = $_POST['found_date'];
    $contact_info = $_POST['contact_info'];
    $founder_name = $_POST['founder_name']; // Corrected line
    $image_path = '';

    // Handle image upload
    if (isset($_FILES['document_image']) && $_FILES['document_image']['error'] == 0) {
        $target_dir = "../images/doc_images/";
        
        // Ensure uploads directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["document_image"]["name"]);
        $image_path = $target_file;

        if (!move_uploaded_file($_FILES["document_image"]["tmp_name"], $target_file)) {
            echo "Error uploading your file.";
            exit; // Exit to prevent further processing if the file upload fails
        }
    }

    // Prepare to insert data into the found_documents table
    $stmt = $conn->prepare("INSERT INTO found_documents (document_type, document_name, first_name, middle_name, last_name, found_location, found_date, contact_info, founder_name, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssssssss", $document_type, $document_name, $first_name, $middle_name, $last_name, $found_location, $found_date, $contact_info, $founder_name, $image_path);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // Set success message in session and redirect to the same page
            $_SESSION['success'] = "Document posted successfully!";
            header("Location: post_found_documents.php");
            exit(); // Ensure no further code runs after redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
}

// Check if 'success' parameter is set in session
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : '';
// Clear the success message from session
if ($successMessage) {
    unset($_SESSION['success']);
}

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Found Document</title>
    <link rel="stylesheet" href="found_documents.css">
    <script src="post.js"></script> <!-- Link to the external JavaScript file -->
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Found Documents Portal</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="post_found_documents.php">Post Found Document</a></li>
             
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h2>Post a Found Document</h2>

        <?php if ($successMessage): ?>
            <script>
                alert('<?php echo $successMessage; ?>');
            </script>
        <?php endif; ?>

        <form action="post_found_documents.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="document_type">Document Type</label>
                <select id="document_type" name="document_type" onchange="updateDocumentNames()" required>
                    <option value="Identity">Identity</option>
                    <option value="Registry">Registry</option>
                    <option value="Letters">Letters</option>
                    <option value="Certificates">Certificates</option>
                    <option value="Licenses">Licenses</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="form-group">
                <label for="document_name">Document Name</label>
                <select id="document_name" name="document_name" required>
                    <!-- Options will be populated based on document type -->
                </select>
            </div>

            <div class="form-group">
             <label for="first_name">First Name (as in document)</label>
             <input type="text" id="first_name" name="first_name" placeholder="First" 
           pattern="[A-Za-z\s]+" title="Please enter only letters and spaces." required>
           </div>


            <div class="form-group">
                <label for="middle_name">Middle Name (as in document)</label>
                <input type="text" id="middle_name" name="middle_name" placeholder="Middle"
                pattern="[A-Za-z\s]+" title="Please enter only letters and spaces." required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name (as in document)</label>
                <input type="text" id="last_name" name="last_name" placeholder="Last" required
                pattern="[A-Za-z\s]+" title="Please enter only letters and spaces." required>
            </div>


            <div class="form-group">
               <label for="found_location">Found Location</label>
               <input type="text" id="found_location" name="found_location" 
                  pattern="[A-Za-z0-9\s,\.]+" 
                  title="Please use only letters, numbers, spaces, commas, and periods." 
                  placeholder="e.g., narobi,Uhuru park" required>
            </div>


            <div class="form-group">
                 <label for="found_date">Found Date</label>
                <input type="date" id="found_date" name="found_date" max="<?php echo date('Y-m-d'); ?>" required>
            </div>


            <div class="form-group">
                <label for="contact_info">founders information (mobile number)</label>
                <input type="text" id="contact_info" name="contact_info" required placeholder="0712345678">
            </div>

            <div class="form-group">
                <label for="founder_name">names of the founder </label>
                <input type="text" id="founder_name" name="founder_name" placeholder="john doe"
                pattern="[A-Za-z\s]+" title="Please enter only letters and spaces." required>
            </div>

            <div class="form-group">
                <label for="document_image">Upload Image of the document (optional)</label>
                <input type="file" id="document_image" name="document_image">
            </div>

            <div class="form-group">
                <button type="submit">Post Document</button>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Found Documents Portal</p>
    </footer>

</body>
</html>
