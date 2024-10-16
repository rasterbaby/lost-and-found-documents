// Function to dynamically update document names based on the selected document type
function updateDocumentNames() {
    const documentType = document.getElementById("document_type").value;
    const documentName = document.getElementById("document_name");

    // Clear the existing options
    documentName.innerHTML = "";

    let options = [];

    // Dynamically populate the document names based on the document type
    if (documentType === "Identity") {
        options = [
            "National ID", 
            "Military ID", 
            "Student ID", 
            "Passport", 
            "Driver's License", 
            "Voter's Card", 
            "Other Identity Document"
        ];
    } else if (documentType === "Registry") {
        options = [
            "Birth Certificate", 
            "Death Certificate", 
            "Marriage Certificate", 
            "Adoption Certificate", 
            "Divorce Certificate", 
            "Certificate of Good Conduct", 
            "Other Registry Document"
        ];
    } else if (documentType === "Letters") {
        options = [
            "Letter of Employment", 
            "Appointment Letter", 
            "Offer Letter", 
            "Reference Letter", 
            "Resignation Letter", 
            "Termination Letter", 
            "Other Letters"
        ];
    } else if (documentType === "Certificates") {
        options = [
            "Degree Certificate",  
            "Diploma Certificate",  
            "Training Certificate",  
            "Completion Certificate", 
            "Course Certificate", 
            "Professional Certificate", 
            "Other Certificates"
        ];
    } else if (documentType === "Licenses") {
        options = [
            "Business License", 
            "Driver's License", 
            "Professional License", 
            "Hunting License", 
            "Fishing License", 
            "Alcohol License", 
            "Other Licenses"
        ];
    } else {
        options = ["Other"];
    }

    // Populate the new options
    options.forEach(function(option) {
        const opt = document.createElement("option");
        opt.value = option;
        opt.innerHTML = option;
        documentName.appendChild(opt);
    });
}

// Call updateDocumentNames() when the page loads to ensure default selection is populated
window.onload = function() {
    updateDocumentNames();

    // Check if 'success' parameter is present in the URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'true') {
        alert('Document posted successfully!');
    }
};


document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the form from submitting the default way

        const formData = new FormData(form); // Create a FormData object with the form data

        fetch("post_found_documents.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes("Document posted successfully!")) {
                alert(data); // Show success message
                form.reset(); // Reset the form
            } else {
                alert("Error: " + data); // Show error message if submission fails
            }
        })
        .catch(error => {
            console.error("Error:", error); // Log any errors to the console
            alert("An error occurred. Please try again."); // Show a generic error message
        });
    });
});
