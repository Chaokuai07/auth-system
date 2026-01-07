const resetPwd = document.getElementById("reset-password");
const resetPwdIcon = document.getElementById("reset-password-icon");

if (resetPwd && resetPwdIcon) {
    resetPwd.addEventListener("input", () => {
        if (resetPwd.value.length > 0) {
            resetPwdIcon.classList.remove("fa-lock");
            resetPwdIcon.classList.add("fa-eye");
        } else {
            resetPwdIcon.classList.remove("fa-eye", "fa-eye-slash");
            resetPwdIcon.classList.add("fa-lock");
            resetPwd.type = "password";
        }
    });

    resetPwdIcon.addEventListener("click", () => {
        if (resetPwd.value.length === 0) return;

        if (resetPwd.type === "password") {
            resetPwd.type = "text";
            resetPwdIcon.classList.remove("fa-eye");
            resetPwdIcon.classList.add("fa-eye-slash");
        } else {
            resetPwd.type = "password";
            resetPwdIcon.classList.remove("fa-eye-slash");
            resetPwdIcon.classList.add("fa-eye");
        }
    });
}

const resetConfirm = document.getElementById("reset-confirm");
const resetConfirmIcon = document.getElementById("reset-confirm-icon");

if (resetConfirm && resetConfirmIcon) {
    resetConfirm.addEventListener("input", () => {
        if (resetConfirm.value.length > 0) {
            resetConfirmIcon.classList.remove("fa-lock");
            resetConfirmIcon.classList.add("fa-eye");
        } else {
            resetConfirmIcon.classList.remove("fa-eye", "fa-eye-slash");
            resetConfirmIcon.classList.add("fa-lock");
            resetConfirm.type = "password";
        }
    });

    resetConfirmIcon.addEventListener("click", () => {
        if (resetConfirm.value.length === 0) return;

        if (resetConfirm.type === "password") {
            resetConfirm.type = "text";
            resetConfirmIcon.classList.remove("fa-eye");
            resetConfirmIcon.classList.add("fa-eye-slash");
        } else {
            resetConfirm.type = "password";
            resetConfirmIcon.classList.remove("fa-eye-slash");
            resetConfirmIcon.classList.add("fa-eye");
        }
    });
}

const pwd = document.getElementById("reset-password");

const rLen = document.getElementById("r-length");
const rUpper = document.getElementById("r-upper");
const rLower = document.getElementById("r-lower");
const rNumber = document.getElementById("r-number");
const rSpecial = document.getElementById("r-special");

pwd.addEventListener("input", () => {
    const val = pwd.value;

    checkRule(rLen, val.length >= 8);
    checkRule(rUpper, /[A-Z]/.test(val));
    checkRule(rLower, /[a-z]/.test(val));
    checkRule(rNumber, /[0-9]/.test(val));
    checkRule(rSpecial, /[@$!%*?&.#_-]/.test(val));
});

function checkRule(element, condition) {
    if (condition) {
        element.classList.add("valid");
        element.classList.remove("invalid");
    } else {
        element.classList.add("invalid");
        element.classList.remove("valid");
    }
}