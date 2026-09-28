<?php

require_once '../../core/core.php';

require_login();

require_once '../layout/header.php';

?>


<main class="account-page">

    <h2>My Account</h2>


    <?php if (isset($_SESSION['success'])): ?>

        <div class="message success-message">

            <?= htmlspecialchars($_SESSION['success']) ?>

        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <?php if (isset($_SESSION['error'])): ?>

        <div class="message error-message">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <section class="account-card">

        <h3>
            Welcome,
            <?= htmlspecialchars($_SESSION['customer_name'] ?? '') ?>
        </h3>


        <p>
            Email:
            <?= htmlspecialchars($_SESSION['customer_email'] ?? '') ?>
        </p>


        <div class="account-actions">

            <a
                href="edit_account.php"
                class="account-button"
            >
                Edit Account
            </a>


            <a
                href="change_pass.php"
                class="account-button"
            >
                Change Password
            </a>


            <a
                href="delete_account.php"
                class="account-button delete-button"
            >
                Delete Account
            </a>

        </div>

    </section>

</main>


<?php

require_once '../layout/footer.php';

?>