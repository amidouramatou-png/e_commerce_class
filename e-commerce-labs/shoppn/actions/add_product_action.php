<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();


// =====================================
// CHECK REQUEST METHOD
// =====================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/product.php');
}


// =====================================
// GET FORM DATA
// =====================================

$cat = filter_input(INPUT_POST, 'product_cat', FILTER_VALIDATE_INT);
$brand = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT);

$title = trim($_POST['product_title'] ?? '');
$price = $_POST['product_price'] ?? '';
$desc = trim($_POST['product_desc'] ?? '');
$keywords = trim($_POST['product_keywords'] ?? '');


// =====================================
// VALIDATE FORM DATA
// =====================================

if (
    !$cat || $cat <= 0 ||
    !$brand || $brand <= 0 ||
    $title === '' ||
    !is_numeric($price) ||
    (float)$price < 0 ||
    $desc === '' ||
    $keywords === ''
) {
    $_SESSION['error'] = 'Please complete all product fields correctly.';
    redirect('../views/admin/product.php');
}


// =====================================
// CHECK IMAGE UPLOAD
// =====================================

if (
    !isset($_FILES['product_image']) ||
    $_FILES['product_image']['error'] !== UPLOAD_ERR_OK
) {
    $_SESSION['error'] = 'Please select a product image.';
    redirect('../views/admin/product.php');
}


// Maximum image size: 2 MB
$maxSize = 2 * 1024 * 1024;

if ($_FILES['product_image']['size'] > $maxSize) {
    $_SESSION['error'] = 'The image must not be larger than 2 MB.';
    redirect('../views/admin/product.php');
}


// Validate actual image type
$imageInfo = getimagesize($_FILES['product_image']['tmp_name']);

if ($imageInfo === false) {
    $_SESSION['error'] = 'The uploaded file is not a valid image.';
    redirect('../views/admin/product.php');
}


// Check allowed image formats
$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp'
];

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($_FILES['product_image']['tmp_name']);

if (!isset($allowedTypes[$mimeType])) {
    $_SESSION['error'] = 'Only JPG, PNG, GIF, and WEBP images are allowed.';
    redirect('../views/admin/product.php');
}


// =====================================
// SAVE IMAGE
// =====================================

$imageDirectory = __DIR__ . '/../images/products/';

if (!is_dir($imageDirectory)) {
    $_SESSION['error'] = 'The product image folder does not exist.';
    redirect('../views/admin/product.php');
}

$filename = uniqid('product_', true) . '.' . $allowedTypes[$mimeType];

$imagePath = $imageDirectory . $filename;

if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $imagePath)) {
    $_SESSION['error'] = 'Unable to save the uploaded image.';
    redirect('../views/admin/product.php');
}


// =====================================
// SAVE PRODUCT IN DATABASE
// =====================================

$productController = new ProductController();

$result = $productController->addProduct(
    $cat,
    $brand,
    $title,
    (float)$price,
    $desc,
    $filename,
    $keywords
);


// =====================================
// RESULT MESSAGE
// =====================================

if ($result) {

    $_SESSION['success'] = 'Product added successfully.';

    redirect('../views/admin/product.php');

} else {

    // Remove the uploaded image if database insertion fails.
    if (is_file($imagePath)) {
        unlink($imagePath);
    }

    $_SESSION['error'] = 'Failed to add product. Please check the product information.';

    redirect('../views/admin/product.php');
}

?>
