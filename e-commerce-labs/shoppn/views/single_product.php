<?php

require_once __DIR__ . '/../controllers/ProductController.php';

$productController = new ProductController();

// Get the product ID from the URL.
$productId = filter_input(INPUT_GET, 'pro_id', FILTER_VALIDATE_INT);

// Redirect to the homepage if the ID is missing or invalid.
if ($productId === false || $productId === null || $productId <= 0) {
    header('Location: ../index.php');
    exit;
}

// Get the selected product.
$product = $productController->getProductById($productId);

// Redirect if the product does not exist.
if (!$product) {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__ . '/layout/header.php';
?>

<main class="single-product-page">

    <h2>Product Details</h2>

    <div class="single-product-container">

        <div class="single-product-image">
            <img
                src="../images/products/<?= rawurlencode(basename($product['product_image'] ?? '')) ?>"
                alt="<?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="single-product-info">

            <h3>
                <?= htmlspecialchars(
                    $product['product_title'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h3>

            <p>
                <strong>Category:</strong>
                <?= htmlspecialchars(
                    $product['cat_name'] ?? 'Not available',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

            <p>
                <strong>Brand:</strong>
                <?= htmlspecialchars(
                    $product['brand_title'] ?? 'Not available',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

            <!-- PRODUCT PRICE WITH CURRENCY -->
            <p class="product-price">
                <strong>Price:</strong>
                GHS <?= number_format(
                    (float) $product['product_price'],
                    2,
                    '.',
                    ','
                ) ?>
            </p>

            <p>
                <strong>Description:</strong>
                <?= nl2br(htmlspecialchars(
                    $product['product_desc'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                )) ?>
            </p>

            <p>
                <strong>Keywords:</strong>
                <?= htmlspecialchars(
                    $product['product_keywords'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

            <a
                href="../actions/add_to_cart_action.php?add_cart=<?= (int) $product['product_id'] ?>"
                class="add-to-cart-button"
            >
                Add to Cart
            </a>

            <p>
                <a href="../index.php">Back to Products</a>
            </p>

        </div>

    </div>

</main>

<?php
require_once __DIR__ . '/layout/footer.php';
?>
