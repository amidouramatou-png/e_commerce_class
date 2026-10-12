<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

$redirectPage = '../views/admin/product.php';

// Allow deletion through POST only.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($redirectPage);
}

// Validate the product ID.
$productId = filter_input(
    INPUT_POST,
    'product_id',
    FILTER_VALIDATE_INT
);

if (!$productId || $productId <= 0) {
    $_SESSION['error'] = 'Invalid product ID.';
    redirect($redirectPage);
}

$productController = new ProductController();

// Find the product before deleting it.
$product = $productController->getProductById($productId);

if (!$product) {
    $_SESSION['error'] = 'Product not found.';
    redirect($redirectPage);
}

// Keep the image filename for cleanup after successful deletion.
$imageFilename = $product['product_image'] ?? '';

// Delete the product from the database.
$result = $productController->deleteProduct($productId);

if ($result) {

    // Delete the image only after the database deletion succeeds.
    if ($imageFilename !== '') {

        $imagePath = __DIR__
            . '/../images/products/'
            . basename($imageFilename);

        if (is_file($imagePath)) {
            unlink($imagePath);
        }
    }

    $_SESSION['success'] = 'Product deleted successfully.';

} else {

    $_SESSION['error'] =
        'Failed to delete the product. Please try again.';
}

redirect($redirectPage);

?>
