function updateDocumentNames() {
    const documentType = document.getElementById("document_type").value;
    const documentName = document.getElementById("document_name");

    // Clear the existing options
    documentName.innerHTML = "";

    let options = [];

    // Dynamically populate the document names based on the document type
    if (documentType === "Identity") {
        options = ["National ID", "Military ID", "Students ID", "Passport", "Driver's License", "Voter's Card", "Other Identity Document"];
    } else if (documentType === "Registry") {
        options = ["Birth Certificate", "Death Certificate", "Marriage Certificate", "Other Registry Document"];
    } else if (documentType === "Letters") {
        options = ["Letter of Employment", "Appointment Letter", "Offer Letter", "Other Letters"];
    } else if (documentType === "Certificates") {
        options = ["Degree Certificate", "Diploma Certificate", "Training Certificate", "Other Certificates"];
    } else if (documentType === "Licenses") {
        options = ["Business License", "Driver's License", "Health License", "Professional License", "Other Licenses"];
    } else {
        options = ["Other Document"];
    }

    // Populate the new options
    options.forEach(function(option) {
        const opt = document.createElement("option");
        opt.value = option;
        opt.innerHTML = option;
        documentName.appendChild(opt);
    });
}

// Call updateDocumentNames() when the page loads to ensure the default selection is populated
window.onload = function() {
    updateDocumentNames();
};
