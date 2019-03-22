function showPasswordField() {
    var passwordField = document.getElementById("password-field");
    var changePassword = document.getElementById("change-password");

    passwordField.classList.remove("hidden");
    changePassword.classList.add("hidden");
}