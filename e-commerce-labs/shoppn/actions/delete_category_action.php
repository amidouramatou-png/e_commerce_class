<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/category.php');
}

$cat_id = filter_input(
    INPUT_POST,
    'cat_id',
    FILTER_VALIDATE_INT
);

if (!$cat_id || $cat_id <= 0) {
    $_SESSION['error'] = 'Invalid category ID.';
    redirect('../views/admin/category.php');
}

$productController = new ProductController();

if ($productController->deleteCategory($cat_id)) {
    $_SESSION['success'] = 'Category deleted successfully.';
} else {
    $_SESSION['error'] = 'Failed to delete category.';
}

redirect('../views/admin/category.php');

?>