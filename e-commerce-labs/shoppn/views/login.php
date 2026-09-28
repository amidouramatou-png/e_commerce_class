<?php

require_once '../core/core.php';
require_once 'layout/header.php';

?>

<main>

    <h2>Login</h2>


    <?php if (isset($_SESSION['error'])): ?>

        <p style="color:red;">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </p>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <?php if (isset($_SESSION['success'])): ?>

        <p style="color:green;">

            <?= htmlspecialchars($_SESSION['success']) ?>

        </p>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <form
        action="../actions/login_action.php"
        method="POST"
        id="loginForm"
    >

        <label for="email">
            Email:
        </label>

        <br>

        <input
            type="email"
            name="email"
            id="email"
            required
        >

        <br><br>


        <label for="loginPassword">
            Password:
        </label>

        <br>

        <input
            type="password"
            name="password"
            id="loginPassword"
            required
        >

        <button
            type="button"
            id="showLoginPassword"
        >
            Show
        </button>

        <br><br>


        <button type="submit">
            Login
        </button>

    </form>


    <p>

        Don't have an account?

        <a href="register.php">
            Register
        </a>

    </p>


    <p>

        <a href="forgot_password.php">
            Forgot Password?
        </a>

    </p>

</main>


<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const password =
            document.getElementById(
                "loginPassword"
            );

        const showButton =
            document.getElementById(
                "showLoginPassword"
            );


        showButton.addEventListener(
            "click",
            function () {

                if (
                    password.type ===
                    "password"
                ) {

                    password.type = "text";

                    showButton.textContent =
                        "Hide";

                } else {

                    password.type =
                        "password";

                    showButton.textContent =
                        "Show";

                }

            }
        );

    }
);

</script>


<?php

require_once 'layout/footer.php';

?>