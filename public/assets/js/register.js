document.addEventListener("DOMContentLoaded", function () {
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirm_password");
const passwordError = document.getElementById("passwordError");
const confirmError = document.getElementById("confirmError");
const submitBtn = document.getElementById('submitBtn');
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
    submitBtn.disabled = true;
    submitBtn.classList.remove("bg-blue-600", "hover:bg-blue-700");
    submitBtn.classList.add("bg-gray-600", "hover:bg-gray-700");


    } else {
    confirmError.classList.add("hidden");
    submitBtn.disabled = false;
    submitBtn.classList.remove("bg-gray-600", "hover:bg-gray-700");
    submitBtn.classList.add("bg-blue-600", "hover:bg-blue-700");
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

