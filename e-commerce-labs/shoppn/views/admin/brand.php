<?php

require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$productController = new ProductController();

$editMode = false;
$editBrand = null;

/*
|--------------------------------------------------------------------------
| EDIT MODE
|--------------------------------------------------------------------------
*/

if (isset($_GET['edit_id'])) {

    $edit_id = filter_input(
        INPUT_GET,
        'edit_id',
        FILTER_VALIDATE_INT
    );

    if ($edit_id && $edit_id > 0) {

        $editBrand = $productController->getBrandById($edit_id);

        if ($editBrand) {
            $editMode = true;
        }
    }
}

/*
|--------------------------------------------------------------------------
| GET ALL BRANDS
|--------------------------------------------------------------------------
*/

$brands = $productController->getAllBrands();

?>

<?php require_once __DIR__ . '/../layout/header.php'; ?>


<style>

/* =========================================================
   BRAND ADMIN PAGE
   ========================================================= */

.brand-admin {

    width: 100%;
    max-width: 850px;

    margin: 40px auto;

    padding: 0 20px;

}


/* =========================================================
   PAGE TITLE
   ========================================================= */

.brand-admin .page-title {

    margin-bottom: 30px;

    color: #733236;

    font-size: 34px;

    font-weight: bold;

    text-align: center;

}


/* =========================================================
   MESSAGE
   ========================================================= */

.brand-success {

    margin-bottom: 20px;

    padding: 14px 18px;

    border-radius: 8px;

    background-color: #9CB2A5;

    color: #ffffff;

    font-weight: bold;

    text-align: center;

}


.brand-error {

    margin-bottom: 20px;

    padding: 14px 18px;

    border-radius: 8px;

    background-color: #9D7982;

    color: #ffffff;

    font-weight: bold;

    text-align: center;

}


/* =========================================================
   FORM CARD
   ========================================================= */

.brand-form-card {

    margin-bottom: 30px;

    padding: 30px;

    border-radius: 14px;

    background-color: #ffffff;

    box-shadow: 0 8px 25px rgba(115, 50, 54, 0.12);

}


.brand-form-card h2 {

    margin: 0 0 25px 0;

    color: #733236;

    font-size: 25px;

    text-align: left;

}


/* =========================================================
   FORM
   ========================================================= */

.brand-form-card label {

    display: block;

    margin-bottom: 8px;

    color: #3f3a3b;

    font-size: 15px;

    font-weight: bold;

}


.brand-form-card input[type="text"] {

    width: 100%;

    padding: 13px 15px;

    margin-bottom: 18px;

    border: 1px solid #d8ccca;

    border-radius: 7px;

    background-color: #faf8f7;

    color: #3f3a3b;

    font-size: 15px;

    box-sizing: border-box;

}


.brand-form-card input[type="text"]:focus {

    outline: none;

    border-color: #8BA4B3;

}


/* =========================================================
   PRIMARY BUTTON
   ========================================================= */

.brand-primary-button {

    display: inline-block;

    padding: 10px 22px;

    border: none;

    border-radius: 6px;

    background-color: #8BA4B3;

    color: #ffffff;

    font-size: 14px;

    font-weight: bold;

    cursor: pointer;

}


.brand-primary-button:hover {

    background-color: #733236;

}


/* =========================================================
   CANCEL BUTTON
   ========================================================= */

.brand-cancel-button {

    display: inline-block;

    margin-left: 8px;

    padding: 10px 22px;

    border-radius: 6px;

    background-color: #C8B7C9;

    color: #3f3a3b;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;

}


.brand-cancel-button:hover {

    background-color: #8BA4B3;

    color: #ffffff;

}


/* =========================================================
   TABLE CARD
   ========================================================= */

.brand-table-card {

    padding: 30px;

    border-radius: 14px;

    background-color: #ffffff;

    box-shadow: 0 8px 25px rgba(115, 50, 54, 0.12);

}


.brand-table-card h2 {

    margin: 0 0 25px 0;

    color: #733236;

    font-size: 25px;

    text-align: left;

}


/* =========================================================
   TABLE
   ========================================================= */

.brand-table-wrapper {

    width: 100%;

    overflow-x: auto;

}


.brand-table {

    width: 100%;

    border-collapse: separate;

    border-spacing: 0;

    border: 1px solid #d8ccca;

    border-radius: 10px;

    overflow: hidden;

    background-color: #ffffff;

}


.brand-table thead {

    background-color: #9CB2A5;

}


.brand-table th {

    padding: 16px;

    border-bottom: 2px solid #8BA4B3;

    color: #3f3a3b;

    font-size: 15px;

    font-weight: bold;

    text-align: left;

}


.brand-table td {

    padding: 16px;

    border: 1px solid #d8ccca;

    color: #3f3a3b;

    font-size: 15px;

    vertical-align: middle;

}


.brand-table tbody tr:nth-child(even) {

    background-color: #faf8f7;

}


.brand-table tbody tr:hover {

    background-color: #f1ebe8;

}


/* =========================================================
   DISPLAY NUMBER COLUMN
   ========================================================= */

.brand-table th:first-child,
.brand-table td:first-child {

    width: 80px;

    text-align: center;

}


/* =========================================================
   ACTION COLUMN
   ========================================================= */

.brand-table th:last-child,
.brand-table td:last-child {

    width: 220px;

    text-align: center;

}


/* =========================================================
   EDIT BUTTON
   ========================================================= */

.brand-edit-button {

    display: inline-block;

    padding: 9px 20px;

    border-radius: 6px;

    background-color: #8BA4B3;

    color: #ffffff;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;

}


.brand-edit-button:hover {

    background-color: #733236;

    color: #ffffff;

}


/* =========================================================
   DELETE BUTTON
   ========================================================= */

.brand-delete-button {

    display: inline-block;

    padding: 9px 20px;

    border: none;

    border-radius: 6px;

    background-color: #9D7982;

    color: #ffffff;

    font-size: 14px;

    font-weight: bold;

    cursor: pointer;

}


.brand-delete-button:hover {

    background-color: #733236;

}


.brand-delete-form {

    display: inline;

}


/* =========================================================
   EMPTY TABLE
   ========================================================= */

.brand-empty {

    padding: 25px;

    border: 1px dashed #d8ccca;

    border-radius: 8px;

    background-color: #f8f5f3;

    color: #3f3a3b;

    text-align: center;

}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 700px) {

    .brand-admin {

        margin: 30px auto;

        padding: 0 15px;

    }


    .brand-admin .page-title {

        font-size: 30px;

    }


    .brand-form-card,
    .brand-table-card {

        padding: 22px 18px;

    }


    .brand-table {

        font-size: 14px;

    }


    .brand-table th,
    .brand-table td {

        padding: 11px 8px;

    }


    .brand-primary-button,
    .brand-cancel-button {

        display: block;

        width: 100%;

        margin: 8px 0;

        text-align: center;

        box-sizing: border-box;

    }


    .brand-edit-button,
    .brand-delete-button {

        margin: 3px;

    }

}

</style>


<div class="brand-admin">


    <!-- =========================================
         PAGE TITLE
         ========================================= -->

    <h1 class="page-title">

        Manage Brands

    </h1>


    <!-- =========================================
         SUCCESS MESSAGE
         ========================================= -->

    <?php if (isset($_SESSION['success'])): ?>

        <div class="brand-success">

            <?= htmlspecialchars($_SESSION['success']) ?>

        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <!-- =========================================
         ERROR MESSAGE
         ========================================= -->

    <?php if (isset($_SESSION['error'])): ?>

        <div class="brand-error">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <!-- =========================================
         BRAND FORM
         ========================================= -->

    <div class="brand-form-card">


        <?php if ($editMode): ?>


            <h2>

                Edit Brand

            </h2>


            <form
                action="../../actions/update_brand_action.php"
                method="POST"
            >


                <input
                    type="hidden"
                    name="brand_id"
                    value="<?= htmlspecialchars($editBrand['brand_id']) ?>"
                >


                <label for="brand_name">

                    Brand Name

                </label>


                <input
                    type="text"
                    id="brand_name"
                    name="brand_name"
                    value="<?= htmlspecialchars($editBrand['brand_title']) ?>"
                    required
                >


                <button
                    type="submit"
                    class="brand-primary-button"
                >

                    Update Brand

                </button>


                <a
                    href="brand.php"
                    class="brand-cancel-button"
                >

                    Cancel

                </a>


            </form>


        <?php else: ?>


            <h2>

                Add Brand

            </h2>


            <form
                action="../../actions/add_brand_action.php"
                method="POST"
            >


                <label for="brand_name">

                    Brand Name

                </label>


                <input
                    type="text"
                    id="brand_name"
                    name="brand_name"
                    placeholder="Enter brand name"
                    required
                >


                <button
                    type="submit"
                    class="brand-primary-button"
                >

                    Add Brand

                </button>


            </form>


        <?php endif; ?>


    </div>


    <!-- =========================================
         BRAND TABLE
         ========================================= -->

    <div class="brand-table-card">


        <h2>

            All Brands

        </h2>


        <?php if (count($brands) > 0): ?>


            <div class="brand-table-wrapper">


                <table class="brand-table">


                    <thead>

                        <tr>

                            <th>

                                ID

                            </th>


                            <th>

                                Brand Name

                            </th>


                            <th>

                                Action

                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php foreach ($brands as $index => $brand): ?>


                            <tr>


                                <!--
                                IMPORTANT:
                                This is only the DISPLAY number.

                                It will always show:
                                1, 2, 3, 4...

                                even if the real database IDs
                                are 1, 4, 5, 8...
                                -->

                                <td>

                                    <?= $index + 1 ?>

                                </td>


                                <!-- REAL BRAND NAME -->

                                <td>

                                    <?= htmlspecialchars($brand['brand_title']) ?>

                                </td>


                                <!-- ACTIONS -->

                                <td>


                                    <!-- EDIT -->

                                    <a
                                        href="brand.php?edit_id=<?= htmlspecialchars($brand['brand_id']) ?>"
                                        class="brand-edit-button"
                                    >

                                        Edit

                                    </a>


                                    <!-- DELETE -->

                                    <form
                                        action="../../actions/delete_brand_action.php"
                                        method="POST"
                                        class="brand-delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete this brand?');"
                                    >


                                        <!--
                                        KEEP THE REAL DATABASE ID HERE.
                                        DO NOT replace this with $index + 1.
                                        -->

                                        <input
                                            type="hidden"
                                            name="brand_id"
                                            value="<?= htmlspecialchars($brand['brand_id']) ?>"
                                        >


                                        <button
                                            type="submit"
                                            class="brand-delete-button"
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


            <div class="brand-empty">

                No brands found.

            </div>


        <?php endif; ?>


    </div>


</div>


<?php require_once __DIR__ . '/../layout/footer.php'; ?>