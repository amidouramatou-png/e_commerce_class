<?php

require_once __DIR__ . '/../../controllers/ProductController.php';

$productController = new ProductController();

$categories = $productController->getAllCategories();
$brands = $productController->getAllBrands();

?>

<aside class="sidebar">

    <section class="sidebar-section">

        <h3>Categories</h3>

        <?php if (empty($categories)): ?>

            <p>No categories available.</p>

        <?php else: ?>

            <ul>

                <?php foreach ($categories as $category): ?>

                    <li>
                        <a href="/xampp/E_commerce_Class/e-commerce-labs/shoppn/index.php?cat=<?= (int) $category['cat_id'] ?>">

                        
                            <?= htmlspecialchars($category['cat_name'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>

        <?php endif; ?>

    </section>

    <section class="sidebar-section">

        <h3>Brands</h3>

        <?php if (empty($brands)): ?>

            <p>No brands available.</p>

        <?php else: ?>

            <ul>

                <?php foreach ($brands as $brand): ?>

                    <li>
                        
                        <a href="/xampp/E_commerce_Class/e-commerce-labs/shoppn/index.php?brand=<?= (int) $brand['brand_id'] ?>">
                            <?= htmlspecialchars($brand['brand_title'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>

        <?php endif; ?>

    </section>

</aside>
