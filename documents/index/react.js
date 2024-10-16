   // Get the modal
        const modal = document.getElementById("myModal");
        const modalImg = document.getElementById("img01");
        const captionText = document.getElementById("caption");
        
        // Get all images with the class 'zoom'
        const images = document.querySelectorAll('.zoom');

        images.forEach(image => {
            // When an image is clicked, open the modal
            image.addEventListener('click', () => {
                modal.style.display = "block";
                modalImg.src = image.src; // Set the modal image source to the clicked image
                captionText.innerHTML = image.alt; // Set the caption text to the alt attribute
            });
        });

        // Get the <span> element that closes the modal
        const span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = () => {
            modal.style.display = "none";
        }

        // Close the modal if the user clicks anywhere outside of the modal image
        window.onclick = (event) => {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }