<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/paystack_cred.php';
require_once __DIR__ . '/../controllers/CartController.php';

// The order needs a customer_id, so the customer must be logged in.
require_login();

$successPage = '../views/payment_success.php';


// ==========================================
// SEND THE CUSTOMER TO THE FAILED PAGE
// ==========================================

function fail_payment($message, $reference = '')
{
    $_SESSION['payment_error'] = $message;
    $_SESSION['payment_reference'] = $reference;

    redirect('../views/payment_failed.php');
}


// ==========================================
// GET AND VALIDATE THE PAYSTACK REFERENCE
// ==========================================

$reference = trim((string) ($_GET['ref'] ?? ''));

if (!preg_match('/^[A-Za-z0-9._=\-]{1,100}$/', $reference)) {
    fail_payment('No valid payment reference was received.');
}

$customerId = (int) $_SESSION['customer_id'];
$ip = get_ip();
$cartController = new CartController();


// ==========================================
// ALREADY RECORDED? (e.g. the page was refreshed)
// ==========================================

$existingOrderId = $cartController->getOrderIdByReference($reference);

if ($existingOrderId !== null) {
    redirect($successPage . '?order_id=' . $existingOrderId);
}


// ==========================================
// CHECK THE SETUP
// ==========================================

if (strpos(PAYSTACK_SECRET_KEY, 'REPLACE') !== false) {
    fail_payment(
        'The Paystack secret key has not been set in core/paystack_cred.php.',
        $reference
    );
}

if (!function_exists('curl_init')) {
    fail_payment(
        'The PHP cURL extension is not enabled, so the payment could not be verified.',
        $reference
    );
}


// ==========================================
// VERIFY THE PAYMENT WITH PAYSTACK 

$ch = curl_init(
    'https://api.paystack.co/transaction/verify/' . rawurlencode($reference)
);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
        'Cache-Control: no-cache'
    ],
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);

if ($response === false) {
    error_log('Paystack verify cURL error: ' . $curlError);

    fail_payment(
        'We could not reach Paystack to confirm your payment. '
        . 'Please contact us with your payment reference.',
        $reference
    );
}

$result = json_decode($response, true);

if (
    !is_array($result) ||
    empty($result['status']) ||
    !isset($result['data']) ||
    !is_array($result['data'])
) {
    error_log('Paystack verify bad response: ' . $response);

    fail_payment('Paystack could not confirm this payment.', $reference);
}

$data = $result['data'];


// ==========================================
// CHECK WHAT PAYSTACK CONFIRMED
// ==========================================

// 1. The payment really succeeded.
if (($data['status'] ?? '') !== 'success') {
    fail_payment(
        'The payment was not completed (Paystack status: '
        . ($data['status'] ?? 'unknown') . ').',
        $reference
    );
}

// 2. It is the payment we asked about.
if (($data['reference'] ?? '') !== $reference) {
    fail_payment('The payment reference does not match.', $reference);
}

// 3. It was paid in Ghana cedis.
if (($data['currency'] ?? '') !== 'GHS') {
    fail_payment('The payment was not made in GHS.', $reference);
}

// 4. The cart still has items.
$cartItems = $cartController->getCartItems($ip);

if (empty($cartItems)) {
    fail_payment(
        'Your cart is empty, so no order could be created. '
        . 'If you were charged, please contact us with your payment reference.',
        $reference
    );
}

// 5. The amount paid matches the cart total .
$expectedAmount = (int) round($cartController->getCartTotal($ip) * 100);
$paidAmount = (int) ($data['amount'] ?? 0);

if ($paidAmount !== $expectedAmount) {
    fail_payment(
        'The amount paid does not match your cart total. '
        . 'Please contact us with your payment reference.',
        $reference
    );
}

// 6. It was paid with this customer's email.
$paidEmail = $data['customer']['email'] ?? '';

if (strcasecmp($paidEmail, $_SESSION['customer_email'] ?? '') !== 0) {
    fail_payment(
        'This payment was made with a different email address.',
        $reference
    );
}


// ==========================================
// RECORD THE ORDER
// ==========================================

$orderId = $cartController->processPayment(
    $customerId,
    $ip,
    $reference,
    $paidAmount / 100
);

if (!$orderId) {

    // Another request may have recorded it a moment ago.
    $existingOrderId = $cartController->getOrderIdByReference($reference);

    if ($existingOrderId !== null) {
        redirect($successPage . '?order_id=' . $existingOrderId);
    }

    fail_payment(
        'Your payment was confirmed, but we could not save your order. '
        . 'Please contact us with your payment reference.',
        $reference
    );
}

redirect($successPage . '?order_id=' . (int) $orderId);