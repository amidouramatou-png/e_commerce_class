<?php

require_once __DIR__ . '/../controllers/ProductController.php';

$productController = new ProductController();

// Get the search term from the URL.
$searchQuery = trim($_GET['user_query'] ?? '');

// Search for products.
if ($searchQuery !== '') {
    $products = $productController->searchProducts($searchQuery);
} else {
    $products = [];
}

require_once __DIR__ . '/layout/header.php';
?>

<main class="search-results-page">

    <h2>Search Results</h2>

    <?php if ($searchQuery !== ''): ?>

        <p>
            Results for:
            <strong>
                <?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>
            </strong>
        </p>

    <?php else: ?>

        <p>Please enter a product name to search.</p>

    <?php endif; ?>

    <?php if (empty($products)): ?>

        <p class="no-products">
            No products found. Please try another search.
        </p>

    <?php else: ?>

        <div class="product-grid">

            <?php foreach ($products as $product): ?>

                <article class="product-card">

                    <a
                        href="single_product.php?pro_id=<?= (int) $product['product_id'] ?>"
                        class="product-image-link"
                    >
                        <img
                            src="../images/products/<?= rawurlencode(basename($product['product_image'] ?? '')) ?>"
                            alt="<?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>"
                            class="product-image"
                            loading="lazy"
                        >
                    </a>

                    <div class="product-card-content">

                        <h3>
                            <?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>
                        </h3>

                        <p class="product-price">
                            GHS <?= number_format((float) $product['product_price'], 2) ?>
                        </p>

                        <a
                            href="single_product.php?pro_id=<?= (int) $product['product_id'] ?>"
                            class="product-details-button"
                        >
                            Details
                        </a>

                        <a
                            href="../actions/add_to_cart_action.php?add_cart=<?= (int) $product['product_id'] ?>"
                            class="add-to-cart-button"
                        >
                            Add to Cart
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <p>
        <a href="../index.php">Back to Home</a>
    </p>

</main>

<?php
require_once __DIR__ . '/layout/footer.php';
?>