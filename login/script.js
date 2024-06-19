document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    form.addEventListener("submit", function(event) {
        const password = document.querySelector("#password").value;
        if (password.length < 6) {
            event.preventDefault();
            alert("Password must be at least 6 characters long.");
        }
    });
});
