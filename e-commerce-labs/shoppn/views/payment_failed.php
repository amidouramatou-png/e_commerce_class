<?php

require_once __DIR__ . '/../core/core.php';

$errorMessage = $_SESSION['payment_error']
    ?? 'Your payment could not be completed.';

$reference = $_SESSION['payment_reference'] ?? '';

unset($_SESSION['payment_error'], $_SESSION['payment_reference']);

require_once __DIR__ . '/layout/header.php';
?>

<main class="cart-page checkout-page">

    <section class="payment-result payment-result-failed">

        <div class="payment-result-icon" aria-hidden="true">⚠️</div>

        <h3>Payment not completed</h3>

        <p>
            <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <?php if ($reference !== ''): ?>

            <p class="payment-reference">
                Payment reference:
                <code><?= htmlspecialchars($reference, ENT_QUOTES, 'UTF-8') ?></code>
            </p>

        <?php endif; ?>

        <p>No order has been created. Your cart items are still saved.</p>

    </section>


    <div class="cart-actions">

        <a href="cart.php" class="product-details-button">
            <span aria-hidden="true">←</span>
            Back to Cart
        </a>

        <a href="payment.php" class="add-to-cart-button">
            Try Again
        </a>

    </div>

</main>

<?php
require_once __DIR__ . '/layout/footer.php';
?>