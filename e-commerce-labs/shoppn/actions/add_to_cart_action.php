
<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Get the product ID from the URL.
$productId = filter_input(INPUT_GET, 'add_cart', FILTER_VALIDATE_INT);

// Stop if the product ID is invalid.
if ($productId === false || $productId === null || $productId <= 0) {
    header('Location: ../index.php');
    exit;
}

// Get the customer's IP address.
$ip = get_ip();

// Create the cart controller.
$cartController = new CartController();

// Check whether the product exists.
require_once __DIR__ . '/../controllers/ProductController.php';

$productController = new ProductController();
$product = $productController->getProductById($productId);

if (!$product) {
    header('Location: ../index.php');
    exit;
}

// Prevent duplicate products in the cart.
if ($cartController->isInCart($productId, $ip)) {
    $_SESSION['cart_message'] =
        'This item is already in your cart. Go to your cart to update the quantity.';
} else {
    // Add the product with quantity 1.
    $added = $cartController->addToCart($productId, 1, $ip);

    if ($added) {
        $_SESSION['cart_message'] = 'Product added to your cart successfully!';
    } else {
        $_SESSION['cart_message'] = 'Sorry, we could not add this product to your cart.';
    }
}

// Return to the previous page when possible.
$previousPage = $_SERVER['HTTP_REFERER'] ?? '../index.php';

// Only allow redirects to pages on this website.
$previousHost = parse_url($previousPage, PHP_URL_HOST);
$currentHost = $_SERVER['HTTP_HOST'] ?? '';

if ($previousHost !== null && $previousHost !== $currentHost) {
    $previousPage = '../index.php';
}

header('Location: ' . $previousPage);
exit;
