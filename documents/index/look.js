document.getElementById('claimButton').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default form submission

    const documentId = /* The ID of the document you want to claim */;
    const messageInput = document.getElementById('whatsappMessage');
    const message = messageInput.value; // Get the message input

    // Fetch the phone number from the database
    fetch(`get_phone_number.php?id=${documentId}`)
        .then(response => response.json())
        .then(phoneNumber => {
            if (phoneNumber) {
                const encodedMessage = encodeURIComponent(message);

                // Create WhatsApp link
                const whatsappLink = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;

                // Open WhatsApp or WhatsApp Web
                window.open(whatsappLink, '_blank');
            } else {
                alert('Phone number not found for this document.');
            }
        })
        .catch(error => console.error('Error fetching phone number:', error));
});
