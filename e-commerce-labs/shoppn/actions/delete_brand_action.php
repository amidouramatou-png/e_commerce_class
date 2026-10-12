<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();


// Only allow POST requests

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('../views/admin/brand.php');
}


// Get brand ID

$brand_id = filter_input(
    INPUT_POST,
    'brand_id',
    FILTER_VALIDATE_INT
);


// Validate brand ID

if (!$brand_id || $brand_id <= 0) {

    $_SESSION['error'] = 'Invalid brand ID.';

    redirect('../views/admin/brand.php');
}


// Delete brand

$productController = new ProductController();

if ($productController->deleteBrand($brand_id)) {

    $_SESSION['success'] = 'Brand deleted successfully.';

} else {

    $_SESSION['error'] = 'Failed to delete brand.';
}


// Return to brand page

redirect('../views/admin/brand.php');

?>