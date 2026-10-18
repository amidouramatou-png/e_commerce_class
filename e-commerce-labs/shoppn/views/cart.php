<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartController = new CartController();
$ip = get_ip();

$cartItems = $cartController->getCartItems($ip);
$cartTotal = $cartController->getCartTotal($ip);
$cartCount = $cartController->getCartCount($ip);

require_once __DIR__ . '/layout/header.php';
?>

<main class="cart-page">

    <h2>
        <span aria-hidden="true">🛒</span>
        My Shopping Cart
    </h2>

    <!-- SUCCESS MESSAGE -->

    <?php if (isset($_SESSION['cart_message'])): ?>
        <p class="cart-message">
            <?= htmlspecialchars(
                $_SESSION['cart_message'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
        <?php unset($_SESSION['cart_message']); ?>
    <?php endif; ?>

    <!-- ERROR MESSAGE -->

    <?php if (isset($_SESSION['cart_error'])): ?>
        <p class="cart-message cart-error">
            <?= htmlspecialchars(
                $_SESSION['cart_error'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
        <?php unset($_SESSION['cart_error']); ?>
    <?php endif; ?>

    <!-- Client-side validation messages from cart.js -->
    <p class="cart-message cart-error" id="cartJsError" hidden></p>

    <?php if (empty($cartItems)): ?>

        <section class="empty-cart">
            <p>Your shopping cart is empty.</p>

            <a href="../index.php" class="product-details-button">
                <span aria-hidden="true">🛍️</span>
                Continue Shopping
            </a>
        </section>

    <?php else: ?>

        <!--
            Two separate forms, one per action.
            The inputs inside the table join a form using the
            form="..." attribute, so the forms do not need to be
            nested inside the table.
        -->

        <form
            id="removeForm"
            action="../actions/remove_from_cart_action.php"
            method="POST"
        ></form>

        <form
            id="updateForm"
            action="../actions/update_qty_action.php"
            method="POST"
        ></form>

        <div class="cart-table-wrapper">

            <table class="cart-table">

                <thead>
                    <tr>
                        <th>Remove</th>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($cartItems as $item): ?>

                        <?php $productId = (int) $item['p_id']; ?>

                        <tr>

                            <!-- REMOVE CHECKBOX -->
                            <td>
                                <input
                                    type="checkbox"
                                    name="remove[]"
                                    value="<?= $productId ?>"
                                    form="removeForm"
                                    aria-label="Remove <?= htmlspecialchars(
                                        $item['product_title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >
                            </td>

                            <!-- PRODUCT -->
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

                            <!-- UNIT PRICE -->
                            <td>
                                GHS <?= number_format(
                                    (float) $item['product_price'],
                                    2
                                ) ?>
                            </td>

                            <!-- QUANTITY -->
                            <td>
                                <input
                                    type="number"
                                    name="qty[]"
                                    value="<?= (int) $item['qty'] ?>"
                                    min="1"
                                    max="99"
                                    step="1"
                                    form="updateForm"
                                    class="qty-input"
                                    aria-label="Quantity"
                                    required
                                >

                                <input
                                    type="hidden"
                                    name="update[]"
                                    value="<?= $productId ?>"
                                    form="updateForm"
                                >
                            </td>

                            <!-- SUBTOTAL -->
                            <td>
                                GHS <?= number_format(
                                    (float) $item['product_price']
                                    * (int) $item['qty'],
                                    2
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="4">Total (<?= $cartCount ?> item(s))</th>

                        <th>
                            GHS <?= number_format($cartTotal, 2) ?>
                        </th>
                    </tr>
                </tfoot>

            </table>

        </div>

        <!-- REMOVE / UPDATE BUTTONS -->

        <div class="cart-form-buttons">

            <button
                type="submit"
                form="removeForm"
                class="cart-remove-button"
            >
                Remove Selected
            </button>

            <button
                type="submit"
                form="updateForm"
                class="cart-update-button"
            >
                Update Quantity
            </button>

        </div>

        <!-- CONTINUE / CHECKOUT -->

        <div class="cart-actions">

            <a href="../index.php" class="product-details-button">
                <span aria-hidden="true">←</span>
                Continue Shopping
            </a>

            <a href="checkout.php" class="add-to-cart-button">
                <span aria-hidden="true">🔒</span>
                Checkout
            </a>

        </div>

    <?php endif; ?>

</main>

<script src="../js/cart.js"></script>

<?php
require_once __DIR__ . '/layout/footer.php';
?>