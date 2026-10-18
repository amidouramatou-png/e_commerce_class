<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cartPage = '../views/cart.php';


// ==========================================
// ONLY ALLOW POST REQUEST
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($cartPage);
}


// ==========================================
// GET THE PRODUCT IDS TO REMOVE
// remove[] comes from the checkboxes in cart.php.
// product_id is also accepted for a single item.
// ==========================================

$submittedIds = $_POST['remove'] ?? [];

if (!is_array($submittedIds)) {
    $submittedIds = [$submittedIds];
}

if (isset($_POST['product_id'])) {
    $submittedIds[] = $_POST['product_id'];
}


// ==========================================
// VALIDATE: EACH ID MUST BE A POSITIVE INTEGER
// ==========================================

$productIds = [];

foreach ($submittedIds as $id) {

    $validId = filter_var(
        $id,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($validId !== false) {
        $productIds[] = $validId;
    }
}

$productIds = array_unique($productIds);

if (empty($productIds)) {
    $_SESSION['cart_error'] = 'Please select at least one item to remove.';
    redirect($cartPage);
}


// ==========================================
// REMOVE THE ITEMS
// ==========================================

$ip = get_ip();
$cartController = new CartController();

$removed = 0;

foreach ($productIds as $productId) {

    if ($cartController->removeFromCart($productId, $ip)) {
        $removed++;
    }
}


// ==========================================
// RESULT MESSAGE
// ==========================================

if ($removed > 0) {
    $_SESSION['cart_message'] = $removed === 1
        ? 'The item was removed from your cart.'
        : $removed . ' items were removed from your cart.';
} else {
    $_SESSION['cart_error'] = 'Sorry, the item could not be removed.';
}

redirect($cartPage);