<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

require_login();

$orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);

if (!$orderId || $orderId <= 0) {
    redirect('../index.php');
}

$cartController = new CartController();

// Only returns the order if it belongs to the logged-in customer.
$invoice = $cartController->getOrderById(
    $orderId,
    (int) $_SESSION['customer_id']
);

if (!$invoice) {
    redirect('../index.php');
}

$order = $invoice['order'];
$items = $invoice['items'];
$total = $invoice['total'];

require_once __DIR__ . '/layout/header.php';
?>

<main class="cart-page checkout-page">

    <section class="payment-result no-print">

        <div class="payment-result-icon" aria-hidden="true">✅</div>

        <h3>Thank you! Your payment was successful.</h3>

        <p>Your order has been placed. Here is your invoice.</p>

    </section>


    <!-- INVOICE -->

    <section class="invoice-card">

        <div class="invoice-header">

            <div>
                <h3>Invoice #<?= (int) $order['invoice_no'] ?></h3>

                <p>
                    <strong>Billed to:</strong>
                    <?= htmlspecialchars(
                        $order['customer_name'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

                <p>
                    <?= htmlspecialchars(
                        $order['customer_email'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>
            </div>

            <div class="invoice-meta">
                <p><strong>Order no:</strong> <?= (int) $order['order_id'] ?></p>

                <p>
                    <strong>Date:</strong>
                    <?= htmlspecialchars(
                        date('j F Y', strtotime($order['order_date'])),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

                <p>
                    <strong>Payment ref:</strong>
                    <?= htmlspecialchars(
                        $order['paystack_reference'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

                <p>
                    <span class="status-badge">
                        <?= htmlspecialchars(
                            $order['order_status'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>
                </p>
            </div>

        </div>


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

                    <?php foreach ($items as $item): ?>

                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    $item['product_title'] ?? 'Product no longer available',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td>
                                GHS <?= number_format((float) $item['price'], 2) ?>
                            </td>

                            <td>
                                <?= (int) $item['qty'] ?>
                            </td>

                            <td>
                                GHS <?= number_format(
                                    (float) $item['price'] * (int) $item['qty'],
                                    2
                                ) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="3">Total paid</th>
                        <th>GHS <?= number_format($total, 2) ?></th>
                    </tr>
                </tfoot>

            </table>

        </div>

    </section>


    <div class="cart-actions no-print">

        <a href="../index.php" class="product-details-button">
            <span aria-hidden="true">🛍️</span>
            Continue Shopping
        </a>

        <button
            type="button"
            class="pay-button"
            onclick="window.print()"
        >
            <span aria-hidden="true">🖨️</span>
            Print Invoice
        </button>

    </div>

</main>

<?php
require_once __DIR__ . '/layout/footer.php';
?>