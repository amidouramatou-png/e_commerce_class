<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('../views/admin/brand.php');
}


$brand_id = filter_input(
    INPUT_POST,
    'brand_id',
    FILTER_VALIDATE_INT
);

$brand_name = trim($_POST['brand_name'] ?? '');


if (!$brand_id || $brand_id <= 0) {

    $_SESSION['error'] = 'Invalid brand ID.';

    redirect('../views/admin/brand.php');
}


if ($brand_name === '') {

    $_SESSION['error'] = 'Brand name is required.';

    redirect(
        '../views/admin/brand.php?edit_id='
        . $brand_id
    );
}


$productController = new ProductController();


if ($productController->updateBrand($brand_id, $brand_name)) {

    $_SESSION['success'] = 'Brand updated.';

} else {

    $_SESSION['error'] = 'Failed to update brand.';
}


redirect('../views/admin/brand.php');

?>