<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

$redirectPage = '../views/admin/product.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($redirectPage);
}

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

// Confirm that the product exists.
$currentProduct = $productController->getProductById($productId);

if (!$currentProduct) {
    $_SESSION['error'] = 'Product not found.';
    redirect($redirectPage);
}

// Read and validate the submitted fields.
$cat = filter_input(INPUT_POST, 'product_cat', FILTER_VALIDATE_INT);
$brand = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT);

$title = trim($_POST['product_title'] ?? '');
$price = trim((string)($_POST['product_price'] ?? ''));
$desc = trim($_POST['product_desc'] ?? '');
$keywords = trim($_POST['product_keywords'] ?? '');

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
    redirect($redirectPage . '?edit_id=' . $productId);
}

// Keep the current database image unless a new image is uploaded.
$imageFilename = $currentProduct['product_image'] ?? '';
$newImagePath = null;
$oldImageFilename = $imageFilename;

if (
    !isset($_FILES['product_image']) ||
    $_FILES['product_image']['error'] === UPLOAD_ERR_NO_FILE
) {
    // No new image selected: keep the existing image.
    if ($imageFilename === '') {
        $_SESSION['error'] = 'Please select a product image.';
        redirect($redirectPage . '?edit_id=' . $productId);
    }
} else {

    // Check for upload errors.
    if ($_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'There was a problem uploading the image.';
        redirect($redirectPage . '?edit_id=' . $productId);
    }

    // Maximum image size: 2 MB.
    $maxSize = 2 * 1024 * 1024;

    if ($_FILES['product_image']['size'] > $maxSize) {
        $_SESSION['error'] = 'The image must not be larger than 2 MB.';
        redirect($redirectPage . '?edit_id=' . $productId);
    }

    $temporaryFile = $_FILES['product_image']['tmp_name'];

    // Confirm that the uploaded file is a valid image.
    $imageInfo = getimagesize($temporaryFile);

    if ($imageInfo === false) {
        $_SESSION['error'] = 'The uploaded file is not a valid image.';
        redirect($redirectPage . '?edit_id=' . $productId);
    }

    // Check the actual MIME type, not just the filename.
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($temporaryFile);

    if (!isset($allowedTypes[$mimeType])) {
        $_SESSION['error'] = 'Only JPG, PNG, GIF, and WEBP images are allowed.';
        redirect($redirectPage . '?edit_id=' . $productId);
    }

    // Confirm that the image folder exists.
    $imageDirectory = __DIR__ . '/../images/products/';

    if (!is_dir($imageDirectory)) {
        $_SESSION['error'] = 'The product image folder does not exist.';
        redirect($redirectPage . '?edit_id=' . $productId);
    }

    // Generate a unique filename.
    $imageFilename = uniqid('product_', true)
        . '.'
        . $allowedTypes[$mimeType];

    $newImagePath = $imageDirectory . $imageFilename;

    // Move the uploaded image into the products folder.
    if (!move_uploaded_file($temporaryFile, $newImagePath)) {
        $_SESSION['error'] = 'Unable to save the uploaded image.';
        redirect($redirectPage . '?edit_id=' . $productId);
    }
}

// Update the product in the database.
$result = $productController->updateProduct(
    $productId,
    $cat,
    $brand,
    $title,
    (float)$price,
    $desc,
    $imageFilename,
    $keywords
);

if ($result) {

    // Delete the old image only after the database update succeeds.
    if (
        $newImagePath !== null &&
        $oldImageFilename !== '' &&
        $oldImageFilename !== $imageFilename
    ) {
        $oldImagePath = __DIR__
            . '/../images/products/'
            . basename($oldImageFilename);

        if (is_file($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    $_SESSION['success'] = 'Product updated successfully.';
    redirect($redirectPage);

} else {

    // Remove the newly uploaded image if the database update fails.
    if ($newImagePath !== null && is_file($newImagePath)) {
        unlink($newImagePath);
    }

    $_SESSION['error'] = 'Failed to update the product. Please try again.';
    redirect($redirectPage . '?edit_id=' . $productId);
}

?>
