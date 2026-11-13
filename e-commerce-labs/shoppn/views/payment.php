<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/paystack_cred.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login();

$cartController = new CartController();
$ip = get_ip();

$cartItems = $cartController->getCartItems($ip);
$total = $cartController->getCartTotal($ip);

if (empty($cartItems)) {
    $_SESSION['cart_error'] = 'Your cart is empty. Add a product before paying.';
    redirect('cart.php');
}

// Paystack expects the amount in pesewas (GHS × 100) as a whole number.
$amountInPesewas = (int) round($total * 100);

$keysAreSet = strpos(PAYSTACK_PUBLIC_KEY, 'REPLACE') === false;

require_once __DIR__ . '/layout/header.php';
?>

<main class="cart-page checkout-page">

    <h2>
        <span aria-hidden="true">💳</span>
        Payment
    </h2>

    <ol class="checkout-steps">
        <li class="done">1. Cart</li>
        <li class="done">2. Checkout</li>
        <li class="current">3. Payment</li>
    </ol>


    <!-- CART SUMMARY -->

    <div class="cart-table-wrapper">

        <table class="cart-table">

            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($cartItems as $item): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars(
                                $item['product_title'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            GHS <?= number_format((float) $item['product_price'], 2) ?>
                        </td>

                        <td>
                            <?= (int) $item['qty'] ?>
                        </td>

                        <td>
                            GHS <?= number_format(
                                (float) $item['product_price'] * (int) $item['qty'],
                                2
                            ) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

            <tfoot>
                <tr>
                    <th colspan="3">Total to pay</th>
                    <th>GHS <?= number_format($total, 2) ?></th>
                </tr>
            </tfoot>

        </table>

    </div>


    <!-- PAY BOX -->

    <section class="pay-box">

        <p class="pay-total">
            GHS <?= number_format($total, 2) ?>
        </p>

        <p class="checkout-note">
            Paying as
            <strong>
                <?= htmlspecialchars(
                    $_SESSION['customer_email'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </strong>
        </p>

        <p class="cart-message cart-error" id="paymentJsError" hidden></p>

        <?php if ($keysAreSet): ?>

            <button
                type="button"
                id="payButton"
                class="pay-button"
                onclick="payWithPaystack()"
            >
                <span aria-hidden="true">🔒</span>
                Pay with Paystack
            </button>

        <?php else: ?>

            <p class="cart-message cart-error">
                Paystack is not set up yet. Add your test keys to
                core/paystack_cred.php.
            </p>

        <?php endif; ?>

    </section>


    <div class="cart-actions">

        <a href="checkout.php" class="product-details-button">
            <span aria-hidden="true">←</span>
            Back to Checkout
        </a>

    </div>

</main>


<!-- PAYSTACK INLINE JS -->

<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
    const payButton = document.getElementById("payButton");
    const paymentJsError = document.getElementById("paymentJsError");

    function showPaymentError(message) {
        paymentJsError.textContent = message;
        paymentJsError.hidden = false;
    }

    function resetPayButton() {
        payButton.disabled = false;
        payButton.textContent = "🔒 Pay with Paystack";
    }

    function payWithPaystack() {

        paymentJsError.hidden = true;

        // The Paystack script did not load (offline or blocked).
        if (typeof PaystackPop === "undefined") {
            showPaymentError(
                "The Paystack payment window could not load. " +
                "Check your internet connection and try again."
            );
            return;
        }

        payButton.disabled = true;
        payButton.textContent = "Opening payment window...";

        const handler = PaystackPop.setup({
            key: <?= json_encode(PAYSTACK_PUBLIC_KEY) ?>,
            email: <?= json_encode(
                $_SESSION['customer_email'] ?? '',
                JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
            ) ?>,
            amount: <?= $amountInPesewas ?>,
            currency: "GHS",

            // Paid: send the reference to the server to be verified.
            callback: function (response) {
                window.location.href =
                    "../actions/process_payment_action.php?ref=" +
                    encodeURIComponent(response.reference);
            },

            // Closed without paying.
            onClose: function () {
                resetPayButton();
                alert("Payment cancelled.");
            }
        });

        handler.openIframe();
    }
</script>

<?php
require_once __DIR__ . '/layout/footer.php';
?>