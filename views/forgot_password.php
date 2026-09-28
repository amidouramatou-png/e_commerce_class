<?php

require_once '../core/core.php';
require_once 'layout/header.php';

?>

<main class="account-page">

    <h2>Forgot Password?</h2>

    <p>
        Enter the email address connected to your account.
        We will use it to start the password reset process.
    </p>


    <?php if (isset($_SESSION['error'])): ?>

        <div class="message error-message">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <?php if (isset($_SESSION['success'])): ?>

        <div class="message success-message">

            <?= htmlspecialchars($_SESSION['success']) ?>

        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <form
        action="../actions/forgot_password_action.php"
        method="POST"
    >

        <div class="form-group">

            <label for="email">
                Email Address:
            </label>

            <br>

            <input
                type="email"
                id="email"
                name="email"
                required
                placeholder="Enter your email"
            >

        </div>


        <br>


        <button type="submit">
            Reset Password
        </button>

    </form>


    <?php if (isset($_SESSION['reset_link'])): ?>

        <div class="message success-message">

            <p>
                <strong>
                    Local testing link:
                </strong>
            </p>

            <p>
                <a
                    href="<?= htmlspecialchars($_SESSION['reset_link']) ?>"
                >
                    Click here to reset your password
                </a>
            </p>

        </div>

        <?php unset($_SESSION['reset_link']); ?>

    <?php endif; ?>


    <p>

        Remember your password?

        <a href="login.php">
            Login
        </a>

    </p>

</main>


<?php

require_once 'layout/footer.php';

?>