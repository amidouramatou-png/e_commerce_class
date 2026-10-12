<?php

require_once __DIR__ . '/../controllers/ProductController.php';

$productController = new ProductController();

// Get products based on category, brand, or homepage selection.
$catId = filter_input(INPUT_GET, 'cat', FILTER_VALIDATE_INT);
$brandId = filter_input(INPUT_GET, 'brand', FILTER_VALIDATE_INT);

if ($catId !== null && $catId !== false && $catId > 0) {
    $products = $productController->getProductsByCategory($catId);
    $pageTitle = 'Products by Category';
} elseif ($brandId !== null && $brandId !== false && $brandId > 0) {
    $products = $productController->getProductsByBrand($brandId);
    $pageTitle = 'Products by Brand';
} else {
    $products = $productController->getFeaturedProducts(6);
    $pageTitle = 'Featured Products';
}

require_once __DIR__ . '/layout/header.php';
?>

<main class="home-page">
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <!-- WELCOME CONTENT -->
    <section class="welcome-content">
        <h2>Welcome to Shoppn</h2>
        <p>Welcome to our online store</p>
    </section>

    <!-- PRODUCT DISPLAY -->
    <section class="product-section" id="products">

        <h2>
            <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>
        </h2>

        <?php if (empty($products)): ?>

            <p class="no-products">
                No products found. Please check again later.
            </p>

        <?php else: ?>

            <div class="product-grid">

                <?php foreach ($products as $product): ?>

                    <article class="product-card">

                        <a
                            href="views/single_product.php?pro_id=<?= (int) $product['product_id'] ?>"
                            class="product-image-link"
                        >
                            <img
                                src="images/products/<?= rawurlencode(basename($product['product_image'] ?? '')) ?>"
                                alt="<?= htmlspecialchars($product['product_title'], ENT_QUOTES, 'UTF-8') ?>"
                                class="product-image"
                                loading="lazy"
                            >
                        </a>

                        <div class="product-card-content">

                            <h3>
                                <?= htmlspecialchars(
                                    $product['product_title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h3>

                            <p class="product-price">
                                GHS
                                <?= number_format(
                                    (float) $product['product_price'],
                                    2
                                ) ?>
                            </p>

                            <a
                                href="views/single_product.php?pro_id=<?= (int) $product['product_id'] ?>"
                                class="product-details-button"
                            >
                                Details
                            </a>

                            <a
                                href="actions/add_to_cart_action.php?add_cart=<?= (int) $product['product_id'] ?>"
                                class="add-to-cart-button"
                            >
                                Add to Cart
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php
require_once __DIR__ . '/layout/footer.php';
?>
