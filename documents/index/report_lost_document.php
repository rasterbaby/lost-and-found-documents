<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Lost Document</title>
    <link rel="stylesheet" href="lost_documents.css">
    <script src="report.js" defer></script> <!-- Link to the new external JavaScript file -->
</head>
<body>
<?php
// Database connection parameters
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

// Initialize variables to hold success or error messages
$success_message = "";
$error_message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data safely using isset to avoid undefined index warnings
    $document_type = mysqli_real_escape_string($conn, $_POST['document_type']);
    $document_name = mysqli_real_escape_string($conn, $_POST['document_name']);
    $first_name = isset($_POST['first_name']) ? mysqli_real_escape_string($conn, $_POST['first_name']) : '';
    $middle_name = isset($_POST['middle_name']) ? mysqli_real_escape_string($conn, $_POST['middle_name']) : ''; // Optional
    $last_name = isset($_POST['last_name']) ? mysqli_real_escape_string($conn, $_POST['last_name']) : '';
    $lost_location = mysqli_real_escape_string($conn, $_POST['lost_location']);
    $lost_date = mysqli_real_escape_string($conn, $_POST['lost_date']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);
    
    // File upload handling
    if (isset($_FILES['document_image']) && $_FILES['document_image']['error'] == 0) {
        $target_dir = "uploads/"; // Directory to store the uploaded images
        $target_file = $target_dir . basename($_FILES["document_image"]["name"]);
        $image_path = mysqli_real_escape_string($conn, $target_file);
        
        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES["document_image"]["tmp_name"], $target_file)) {
            $error_message = "Error uploading the image.";
            $image_path = null;
        }
    } else {
        $image_path = null; // No file uploaded
    }

    // Insert data into the `lost_document` table
    $sql = "INSERT INTO lost_document (document_type, document_name, first_name, middle_name, last_name, lost_location, lost_date, image_path, contact_info, created_at)
        VALUES ('$document_type', '$document_name', '$first_name', '$middle_name', '$last_name', '$lost_location', '$lost_date', '$image_path', '$contact_info', NOW())";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Lost document reported successfully.";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>



<header>
    <h1>Report Lost Document</h1>
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="report_lost_document.php">Report Lost Document</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="container">
        <h2>Fill in the details to report a lost document</h2>

        <!-- Display success or error messages -->
        <?php if (!empty($success_message)): ?>
            <p class="success"><?= $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?= $error_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
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
              <label for="first_name">First Name</label>
               <input type="text" id="first_name" name="first_name" 
               pattern="[A-Za-z\s]+" 
               title="Please enter only letters and spaces." 
              placeholder="First Name" required>
            </div>

            <div class="form-group">
                 <label for="middle_name">Middle Name </label>
                 <input type="text" id="middle_name" name="middle_name" 
                 pattern="[A-Za-z\s]*" 
                 title="Please enter only letters and spaces. (optional)" 
                 placeholder="Middle Name">
            </div>

            <div class="form-group">
                 <label for="last_name">Last Name</label>
                 <input type="text" id="last_name" name="last_name" 
                 pattern="[A-Za-z\s]+" 
                 title="Please enter only letters and spaces." 
                 placeholder="Last Name" required>
            </div>


            <div class="form-group">
                <label for="lost_location">Lost Location</label>
                <input type="text" id="lost_location" name="lost_location" 
                pattern="[A-Za-z0-9\s,\.]+" 
                title="Please use only letters, numbers, spaces, commas, and periods." 
                placeholder="e.g., nyeri, karatina" required>
            </div>

           <div class="form-group">
              <label for="lost_date">Lost Date</label>
              <input type="date" id="lost_date" name="lost_date" 
              max="<?php echo date('Y-m-d'); ?>" 
             required>
            </div>


            <div class="form-group">
                <label for="contact_info">Contact Info</label>
                <input type="text" id="contact_info" name="contact_info" required>
            </div>

            <div class="form-group">
                <label for="document_image">Upload Image on the document  (optional)</label>
                <input type="file" id="document_image" name="document_image">
            </div>

            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2024 Lost & Found Document Portal</p>
</footer>

</body>
</html>
