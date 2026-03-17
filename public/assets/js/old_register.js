document.addEventListener("DOMContentLoaded", function () {
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirm_password");
const passwordError = document.getElementById("passwordError");
const confirmError = document.getElementById("confirmError");
const form = document.querySelector("form");

function validatePassword() {
    if (passwordInput.value.length < 8) {
    passwordError.classList.remove("hidden");
    } else {
    passwordError.classList.add("hidden");
    }
    validateConfirmPassword();
}

function validateConfirmPassword() {
    if (passwordInput.value !== confirmPasswordInput.value) {
    confirmError.classList.remove("hidden");
    } else {
    confirmError.classList.add("hidden");
    }
}

passwordInput.addEventListener("input", validatePassword);
confirmPasswordInput.addEventListener("input", validateConfirmPassword);

form.addEventListener("submit", function (e) {
    if (passwordInput.value.length < 8 || passwordInput.value !== confirmPasswordInput.value) {
    e.preventDefault(); // prevent form submit if invalid
    validatePassword();
    validateConfirmPassword();
    }
});
});

