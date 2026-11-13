<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Paystack needs the customer's email, so checkout requires login.
require_login();

$cartController = new CartController();
$ip = get_ip();

$cartItems = $cartController->getCartItems($ip);
$cartTotal = $cartController->getCartTotal($ip);
$cartCount = $cartController->getCartCount($ip);

// Nothing to check out.
if (empty($cartItems)) {
    $_SESSION['cart_error'] = 'Your cart is empty. Add a product before checking out.';
    redirect('cart.php');
}

require_once __DIR__ . '/layout/header.php';
?>

<main class="cart-page checkout-page">

    <h2>
        <span aria-hidden="true">🧾</span>
        Checkout
    </h2>

    <ol class="checkout-steps">
        <li class="done">1. Cart</li>
        <li class="current">2. Checkout</li>
        <li>3. Payment</li>
    </ol>


    <!-- ORDER SUMMARY -->

    <h3 class="checkout-section-title">Order Summary</h3>

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
                            <div class="cart-product">

                                <img
                                    src="../images/products/<?= rawurlencode(
                                        basename($item['product_image'] ?? '')
                                    ) ?>"
                                    alt="<?= htmlspecialchars(
                                        $item['product_title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="cart-product-image"
                                >

                                <span>
                                    <?= htmlspecialchars(
                                        $item['product_title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>

                            </div>
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
                    <th colspan="3">Total (<?= (int) $cartCount ?> item(s))</th>
                    <th>GHS <?= number_format($cartTotal, 2) ?></th>
                </tr>
            </tfoot>

        </table>

    </div>


    <!-- CUSTOMER DETAILS -->

    <h3 class="checkout-section-title">Your Details</h3>

    <section class="checkout-card">

        <dl class="checkout-details">

            <dt>Name</dt>
            <dd>
                <?= htmlspecialchars(
                    $_SESSION['customer_name'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </dd>

            <dt>Email</dt>
            <dd>
                <?= htmlspecialchars(
                    $_SESSION['customer_email'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </dd>

        </dl>

        <p class="checkout-note">
            Your payment will be made with this email address.
            To change it, update your details in
            <a href="account/edit_account.php">My Account</a>.
        </p>

    </section>


    <!-- BUTTONS -->

    <div class="cart-actions">

        <a href="cart.php" class="product-details-button">
            <span aria-hidden="true">←</span>
            Back to Cart
        </a>

        <a href="payment.php" class="add-to-cart-button">
            Proceed to Payment
            <span aria-hidden="true">→</span>
        </a>

    </div>

</main>

<?php
require_once __DIR__ . '/layout/footer.php';
?>