<?php

require_once '../../core/core.php';

require_login();

require_once '../layout/header.php';

?>

<main class="account-page">

    <h2>Delete Account</h2>


    <?php if (isset($_SESSION['error'])): ?>

        <div class="message error-message">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <section class="account-card">

        <h3>
            Are you sure you want to delete your account?
        </h3>

        <p>
            This action will permanently delete your
            account and your account information.
        </p>

        <p>
            <strong>
                This action cannot be undone.
            </strong>
        </p>


        <form
            action="../../actions/delete_account_action.php"
            method="POST"
            onsubmit="
                return confirm(
                    'Are you sure you want to permanently delete your account?'
                );
            "
        >

            <button
                type="submit"
                class="delete-button"
            >
                Yes, Delete My Account
            </button>

        </form>


        <br>


        <a
            href="my_account.php"
            class="account-button"
        >
            Cancel
        </a>

    </section>

</main>


<?php

require_once '../layout/footer.php';

?>