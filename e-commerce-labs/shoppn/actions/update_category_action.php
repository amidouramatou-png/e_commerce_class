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

$category_name = trim($_POST['category_name'] ?? '');


if (!$cat_id || $cat_id <= 0) {

    $_SESSION['error'] = 'Invalid category ID.';

    redirect('../views/admin/category.php');
}


if ($category_name === '') {

    $_SESSION['error'] = 'Category name is required.';

    redirect(
        '../views/admin/category.php?edit_id='
        . $cat_id
    );
}


$productController = new ProductController();


if (
    $productController->updateCategory(
        $cat_id,
        $category_name
    )
) {

    $_SESSION['success'] = 'Category updated.';

} else {

    $_SESSION['error'] = 'Failed to update category.';
}


redirect('../views/admin/category.php');

?>