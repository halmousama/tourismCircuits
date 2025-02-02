document.addEventListener('DOMContentLoaded', function () {
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const form = document.querySelector('form'); // Select the signup form

    // Add keydown event listeners for each input
    usernameInput.addEventListener('keyup', validateUsername);
    emailInput.addEventListener('keyup', validateEmailField);
    passwordInput.addEventListener('keyup', validatePassword);

    function validateUsername(event) {
        clearErrors(); // Clear previous error messages
        if (usernameInput.value.trim() === '') {
            showError(usernameInput, 'Username is required.');
        } else if (usernameInput.value.length > 150) {
            showError(usernameInput, 'Username must be less than 150 characters.');
        }
    }

    function validateEmailField(event) {
        clearErrors(); // Clear previous error messages
        if (emailInput.value.trim() === '') {
            showError(emailInput, 'Email is required.');
        } else if (!validateEmail(emailInput.value)) {
            showError(emailInput, 'Invalid email format.');
        }
    }

    function validatePassword(event) {
        clearErrors(); // Clear previous error messages
        if (passwordInput.value.trim() === '') {
            showError(passwordInput, 'Password is required.');
        } else if (!/[0-9]/.test(passwordInput.value)) {
            showError(passwordInput, 'Password must contain at least one number.');
        } else if (!/[!@#$%^&*.<>]/.test(passwordInput.value)) {
            showError(passwordInput, 'Password must contain at least one symbol.');
        } else if (passwordInput.value.length < 8) {
            showError(passwordInput, 'Password must be at least 8 characters long.');
        }
    }

    function showError(input, message) {
        const error = document.createElement('div');
        error.className = 'error';
        error.textContent = message;
        error.style.position = 'absolute';
        error.style.left = (input.getBoundingClientRect().right + window.scrollX + 10) + 'px';
        error.style.top = (input.getBoundingClientRect().top + window.scrollY) + 'px';
        document.body.appendChild(error);
    }

    function clearErrors() {
        const errors = document.querySelectorAll('.error');
        errors.forEach(error => error.remove());
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

}); // Closing brace for DOMContentLoaded event listener