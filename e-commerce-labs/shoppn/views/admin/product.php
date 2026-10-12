<?php

require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$productController = new ProductController();

$categories = $productController->getAllCategories();
$brands = $productController->getAllBrands();
$products = $productController->getAllProducts();

$editMode = false;
$editProduct = null;

if (isset($_GET['edit_id'])) {

    $edit_id = filter_input(
        INPUT_GET,
        'edit_id',
        FILTER_VALIDATE_INT
    );

    if ($edit_id && $edit_id > 0) {

        $editProduct = $productController->getProductById($edit_id);

        if ($editProduct) {
            $editMode = true;
        } else {
            $_SESSION['error'] = 'Product not found.';
        }
    }
}

?>

<?php require_once __DIR__ . '/../layout/header.php'; ?>

<style>
.product-admin {
    width: 100%;
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 20px;
    font-family: Arial, Helvetica, sans-serif;
    box-sizing: border-box;
}

.product-admin .page-title {
    margin: 0 0 30px;
    color: #733236;
    font-size: 36px;
    font-weight: bold;
    text-align: center;
}

.product-message {
    padding: 14px 18px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-weight: bold;
}

.product-success {
    background-color: #e7eee9;
    color: #3d5d4c;
    border-left: 5px solid #9CB2A5;
}

.product-error {
    background-color: #f4e4e5;
    color: #733236;
    border-left: 5px solid #9D7982;
}

.product-form-card,
.product-table-card {
    padding: 30px;
    margin-bottom: 30px;
    border-radius: 14px;
    background-color: #ffffff;
    box-shadow: 0 8px 25px rgba(115, 50, 54, 0.12);
}

.product-form-card {
    border-top: 6px solid #9CB2A5;
}

.product-form-card h2,
.product-table-card h2 {
    margin: 0 0 25px;
    color: #733236;
    font-size: 25px;
}

.product-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.product-field {
    min-width: 0;
}

.product-field.full-width {
    grid-column: 1 / -1;
}

.product-field label {
    display: block;
    margin-bottom: 8px;
    color: #3f3a3b;
    font-weight: bold;
}

.product-field input,
.product-field select,
.product-field textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #d8ccca;
    border-radius: 7px;
    background-color: #faf8f7;
    color: #3f3a3b;
    font-size: 15px;
    box-sizing: border-box;
}

.product-field textarea {
    min-height: 110px;
    resize: vertical;
}

.product-field input:focus,
.product-field select:focus,
.product-field textarea:focus {
    outline: none;
    border-color: #8BA4B3;
}

.product-help {
    display: block;
    margin-top: 7px;
    color: #6b6262;
    font-size: 13px;
}

.product-preview {
    display: block;
    width: 130px;
    height: 130px;
    margin: 10px 0 15px;
    border: 1px solid #d8ccca;
    border-radius: 8px;
    object-fit: contain;
    background: #faf8f7;
}

.product-primary-button,
.product-cancel-button,
.product-edit-button,
.product-delete-button {
    display: inline-block;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    cursor: pointer;
}

.product-primary-button {
    margin-top: 22px;
    background-color: #733236;
    color: #ffffff;
}

.product-primary-button:hover {
    background-color: #9D7982;
}

.product-cancel-button {
    margin-top: 22px;
    margin-left: 8px;
    background-color: #C8B7C9;
    color: #3f3a3b;
}

.product-edit-button {
    background-color: #8BA4B3;
    color: #ffffff;
    margin: 2px;
}

.product-delete-button {
    background-color: #9D7982;
    color: #ffffff;
    margin: 2px;
}

.product-edit-button:hover,
.product-delete-button:hover {
    background-color: #733236;
    color: #ffffff;
}

.product-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.product-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
}

.product-table thead {
    background-color: #9CB2A5;
}

.product-table th,
.product-table td {
    padding: 13px;
    border: 1px solid #d8ccca;
    color: #3f3a3b;
    text-align: left;
    vertical-align: middle;
}

.product-table th {
    font-size: 14px;
}

.product-table td {
    font-size: 14px;
}

.product-table tbody tr:nth-child(even) {
    background-color: #faf8f7;
}

.product-table tbody tr:hover {
    background-color: #f1ebe8;
}

.product-table-image {
    width: 65px;
    height: 65px;
    object-fit: contain;
    border-radius: 6px;
    background-color: #ffffff;
}

.product-empty {
    padding: 25px;
    border: 1px dashed #d8ccca;
    border-radius: 8px;
    background-color: #f8f5f3;
    text-align: center;
    color: #3f3a3b;
}

.product-delete-form {
    display: inline;
}

@media (max-width: 700px) {
    .product-admin {
        margin: 25px auto;
        padding: 0 12px;
    }

    .product-admin .page-title {
        font-size: 29px;
    }

    .product-form-card,
    .product-table-card {
        padding: 20px 15px;
    }

    .product-form-grid {
        grid-template-columns: 1fr;
    }

    .product-field.full-width {
        grid-column: auto;
    }
}
</style>


<div class="product-admin">

    <h1 class="page-title">Manage Products</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="product-message product-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="product-message product-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


    <!-- ADD / EDIT PRODUCT FORM -->

    <div class="product-form-card">

        <h2>
            <?= $editMode ? 'Edit Product' : 'Add Product' ?>
        </h2>

        <form
            action="<?= $editMode
                ? '../../actions/update_product_action.php'
                : '../../actions/add_product_action.php' ?>"
            method="POST"
            enctype="multipart/form-data"
        >

            <?php if ($editMode): ?>
                <input
                    type="hidden"
                    name="product_id"
                    value="<?= (int)$editProduct['product_id'] ?>"
                >

                <input
                    type="hidden"
                    name="existing_image"
                    value="<?= htmlspecialchars($editProduct['product_image'] ?? '') ?>"
                >
            <?php endif; ?>


            <div class="product-form-grid">

                <!-- PRODUCT TITLE -->

                <div class="product-field">
                    <label for="product_title">Product Title</label>

                    <input
                        type="text"
                        id="product_title"
                        name="product_title"
                        value="<?= htmlspecialchars($editProduct['product_title'] ?? '') ?>"
                        maxlength="255"
                        required
                    >
                </div>


                <!-- PRODUCT PRICE -->

                <div class="product-field">
                    <label for="product_price">Price</label>

                    <input
                        type="number"
                        id="product_price"
                        name="product_price"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars((string)($editProduct['product_price'] ?? '')) ?>"
                        required
                    >
                </div>


                <!-- CATEGORY DROPDOWN -->

                <div class="product-field">
                    <label for="product_cat">Category</label>

                    <select
                        id="product_cat"
                        name="product_cat"
                        required
                    >
                        <option value="">Select a category</option>

                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= (int)$category['cat_id'] ?>"
                                <?= $editMode &&
                                    (int)$editProduct['product_cat'] ===
                                    (int)$category['cat_id']
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= htmlspecialchars($category['cat_name']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>


                <!-- BRAND DROPDOWN -->

                <div class="product-field">
                    <label for="product_brand">Brand</label>

                    <select
                        id="product_brand"
                        name="product_brand"
                        required
                    >
                        <option value="">Select a brand</option>

                        <?php foreach ($brands as $brand): ?>
                            <option
                                value="<?= (int)$brand['brand_id'] ?>"
                                <?= $editMode &&
                                    (int)$editProduct['product_brand'] ===
                                    (int)$brand['brand_id']
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= htmlspecialchars($brand['brand_title']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>


                <!-- DESCRIPTION -->

                <div class="product-field full-width">
                    <label for="product_desc">Description</label>

                    <textarea
                        id="product_desc"
                        name="product_desc"
                        required
                    ><?= htmlspecialchars($editProduct['product_desc'] ?? '') ?></textarea>
                </div>


                <!-- KEYWORDS -->

                <div class="product-field full-width">
                    <label for="product_keywords">Keywords</label>

                    <input
                        type="text"
                        id="product_keywords"
                        name="product_keywords"
                        value="<?= htmlspecialchars($editProduct['product_keywords'] ?? '') ?>"
                        placeholder="Example: shoes, fashion, sneakers"
                        required
                    >

                    <small class="product-help">
                        Separate keywords with commas.
                    </small>
                </div>


                <!-- PRODUCT IMAGE -->

                <div class="product-field full-width">
                    <label for="product_image">Product Image</label>

                    <?php if (
                        $editMode &&
                        !empty($editProduct['product_image'])
                    ): ?>
                        <p>Current image:</p>

                        <img
                            class="product-preview"
                            src="../../images/products/<?= rawurlencode(basename($editProduct['product_image'])) ?>"
                            alt="Current product image"
                        >
                    <?php endif; ?>

                    <input
                        type="file"
                        id="product_image"
                        name="product_image"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        <?= $editMode ? '' : 'required' ?>
                    >

                    <small class="product-help">
                        Accepted formats: JPG, PNG, GIF, WEBP. Maximum size: 2 MB.
                        <?= $editMode
                            ? 'Leave empty to keep the current image.'
                            : 'An image is required for a new product.' ?>
                    </small>
                </div>

            </div>


            <button
                type="submit"
                class="product-primary-button"
            >
                <?= $editMode ? 'Update Product' : 'Add Product' ?>
            </button>

            <?php if ($editMode): ?>
                <a
                    href="product.php"
                    class="product-cancel-button"
                >
                    Cancel
                </a>
            <?php endif; ?>

        </form>

    </div>


    <!-- PRODUCT TABLE -->

    <div class="product-table-card">

        <h2>All Products</h2>

        <?php if (!empty($products)): ?>

            <div class="product-table-wrapper">

                <table class="product-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($products as $index => $product): ?>

                            <tr>

                                <td><?= $index + 1 ?></td>

                                <td>
                                    <?php if (!empty($product['product_image'])): ?>
                                        <img
                                            class="product-table-image"
                                            src="../../images/products/<?= rawurlencode(basename($product['product_image'])) ?>"
                                            alt="<?= htmlspecialchars($product['product_title']) ?>"
                                        >
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['product_title']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)$product['product_price']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['cat_name'] ?? 'Unknown') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($product['brand_title'] ?? 'Unknown') ?>
                                </td>

                                <td>

                                    <a
                                        class="product-edit-button"
                                        href="product.php?edit_id=<?= (int)$product['product_id'] ?>"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        class="product-delete-form"
                                        action="../../actions/delete_product_action.php"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this product?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="product_id"
                                            value="<?= (int)$product['product_id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="product-delete-button"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="product-empty">
                No products have been added yet.
            </div>

        <?php endif; ?>

    </div>

</div>


<?php require_once __DIR__ . '/../layout/footer.php'; ?>