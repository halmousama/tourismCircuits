
document.forms[0].addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent the form from submitting
    const username = this.username.value; // Get the username value
    localStorage.setItem('username', username); // Store the username in localStorage
    this.submit(); 
});