<?php

require_once '../core/core.php';
require_once '../controllers/CustomerController.php';


// ==========================================
// GET RESET TOKEN
// ==========================================

$token = $_GET['token'] ?? '';


// ==========================================
// CHECK TOKEN
// ==========================================

if ($token === '') {

    $_SESSION['error'] =
        'Invalid or missing password reset link.';

    redirect('forgot_password.php');
}


// ==========================================
// FIND CUSTOMER USING TOKEN
// ==========================================

$controller = new CustomerController();

$customer =
    $controller->getCustomerByResetToken($token);


// ==========================================
// CHECK TOKEN VALIDITY
// ==========================================

if (!$customer) {

    $_SESSION['error'] =
        'This password reset link is invalid or has expired.';

    redirect('forgot_password.php');
}


require_once 'layout/header.php';

?>


<main class="account-page">

    <h2>Reset Password</h2>

    <p>
        Create a new password for your account.
    </p>


    <?php if (isset($_SESSION['error'])): ?>

        <div class="message error-message">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <form
        action="../actions/reset_password_action.php"
        method="POST"
        id="resetPasswordForm"
    >

        <!-- HIDDEN TOKEN -->

        <input
            type="hidden"
            name="token"
            value="<?= htmlspecialchars($token) ?>"
        >


        <!-- NEW PASSWORD -->

        <div class="form-group">

            <label for="new_password">
                New Password:
            </label>

            <br>

            <input
                type="password"
                id="new_password"
                name="new_password"
                required
            >

            <button
                type="button"
                onclick="togglePassword(
                    'new_password',
                    this
                )"
            >
                Show
            </button>

            <p id="passwordStrength"></p>

        </div>


        <br>


        <!-- CONFIRM PASSWORD -->

        <div class="form-group">

            <label for="confirm_password">
                Confirm New Password:
            </label>

            <br>

            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                required
            >

            <button
                type="button"
                onclick="togglePassword(
                    'confirm_password',
                    this
                )"
            >
                Show
            </button>

            <p id="passwordMatch"></p>

        </div>


        <br>


        <button type="submit">
            Reset Password
        </button>

    </form>


    <p>

        <a href="login.php">
            Back to Login
        </a>

    </p>

</main>


<script>

function togglePassword(fieldId, button) {

    const field =
        document.getElementById(fieldId);


    if (field.type === "password") {

        field.type = "text";

        button.textContent = "Hide";

    } else {

        field.type = "password";

        button.textContent = "Show";

    }

}


const newPassword =
    document.getElementById("new_password");

const confirmPassword =
    document.getElementById("confirm_password");

const passwordStrength =
    document.getElementById("passwordStrength");

const passwordMatch =
    document.getElementById("passwordMatch");


newPassword.addEventListener(
    "input",
    function () {

        const password =
            newPassword.value;


        if (password.length === 0) {

            passwordStrength.textContent = "";

        }

        else if (password.length < 8) {

            passwordStrength.textContent =
                "Weak password";

            passwordStrength.style.color =
                "red";

        }

        else if (!/\d/.test(password)) {

            passwordStrength.textContent =
                "Medium password - add a number";

            passwordStrength.style.color =
                "orange";

        }

        else if (password.length >= 12) {

            passwordStrength.textContent =
                "Strong password";

            passwordStrength.style.color =
                "green";

        }

        else {

            passwordStrength.textContent =
                "Good password";

            passwordStrength.style.color =
                "green";

        }


        checkPasswordMatch();

    }
);


confirmPassword.addEventListener(
    "input",
    checkPasswordMatch
);


function checkPasswordMatch() {

    if (confirmPassword.value === "") {

        passwordMatch.textContent = "";

        return;

    }


    if (
        newPassword.value ===
        confirmPassword.value
    ) {

        passwordMatch.textContent =
            "Passwords match";

        passwordMatch.style.color =
            "green";

    } else {

        passwordMatch.textContent =
            "Passwords do not match";

        passwordMatch.style.color =
            "red";

    }

}

</script>


<?php

require_once 'layout/footer.php';

?>