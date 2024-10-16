// Show the login form after successful registration
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('registered') === 'true') {
    document.querySelector('.form-container').style.display = 'none'; // Hide registration form
    document.querySelector('.login-container').style.display = 'block'; // Show login form
    document.getElementById('message').innerText = 'Registration successful! You can now log in.';
}

// Redirect to the login form
document.getElementById("redirect-button").onclick = function() {
    document.querySelector('.form-container').style.display = 'none'; // Hide registration form
    document.querySelector('.login-container').style.display = 'block'; // Show login form
};

// Back to registration form
document.getElementById("back-button").onclick = function() {
    document.querySelector('.login-container').style.display = 'none'; // Hide login form
    document.querySelector('.form-container').style.display = 'block'; // Show registration form
};