<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartPage = '../views/cart.php';

// Highest quantity allowed for one product.
$maxQty = 99;


// ==========================================
// ONLY ALLOW POST REQUEST
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($cartPage);
}


// ==========================================
// GET THE SUBMITTED ARRAYS
// update[] = product IDs, qty[] = new quantities.
// They are in the same order, so update[0] goes with qty[0].
// ==========================================

$productIds = $_POST['update'] ?? [];
$quantities = $_POST['qty'] ?? [];

if (
    !is_array($productIds) ||
    !is_array($quantities) ||
    empty($productIds) ||
    count($productIds) !== count($quantities)
) {
    $_SESSION['cart_error'] = 'Your cart could not be updated. Please try again.';
    redirect($cartPage);
}


// ==========================================
// VALIDATE AND UPDATE EACH ITEM
// ==========================================

$ip = get_ip();
$cartController = new CartController();

$updated = 0;
$invalid = 0;

foreach ($productIds as $index => $id) {

    $productId = filter_var(
        $id,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    $newQty = filter_var(
        trim((string) ($quantities[$index] ?? '')),
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => $maxQty]]
    );

    // Skip anything that is not a valid product ID and quantity.
    if ($productId === false || $newQty === false) {
        $invalid++;
        continue;
    }

    if ($cartController->updateQty($productId, $newQty, $ip)) {
        $updated++;
    }
}


// ==========================================
// RESULT MESSAGE
// ==========================================

if ($invalid > 0) {
    $_SESSION['cart_error'] =
        'Quantities must be whole numbers from 1 to ' . $maxQty . '. '
        . 'Invalid quantities were not saved.';
}

if ($updated > 0) {
    $_SESSION['cart_message'] = 'Your cart has been updated.';
}

redirect($cartPage);