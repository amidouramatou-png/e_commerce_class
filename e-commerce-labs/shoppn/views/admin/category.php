<?php

require_once '../../core/core.php';
require_once '../../controllers/ProductController.php';

require_admin();

$productController = new ProductController();

$editMode = false;
$editCategory = null;

if (isset($_GET['edit_id'])) {

    $edit_id = filter_input(
        INPUT_GET,
        'edit_id',
        FILTER_VALIDATE_INT
    );

    if ($edit_id && $edit_id > 0) {

        $editCategory =
            $productController->getCategoryById($edit_id);

        if ($editCategory) {
            $editMode = true;
        }
    }
}

$categories =
    $productController->getAllCategories();

?>

<?php require_once '../layout/header.php'; ?>


<style>

/* =========================================
   CATEGORY ADMIN PAGE
   ========================================= */

.category-admin {

    width: 100%;

    max-width: 1050px;

    margin: 50px auto;

    padding: 0 25px;

    font-family: Arial, Helvetica, sans-serif;
}


/* PAGE TITLE */

.category-admin .page-title {

    margin: 0 0 35px 0;

    color: #733236;

    font-size: 38px;

    font-weight: bold;

    text-align: center;
}


/* =========================================
   MESSAGE
   ========================================= */

.category-message {

    width: 100%;

    padding: 15px 20px;

    margin-bottom: 25px;

    border-radius: 8px;

    font-weight: bold;

}

.category-success {

    background-color: #e7eee9;

    color: #3d5d4c;

    border-left: 5px solid #9CB2A5;

}

.category-error {

    background-color: #f4e4e5;

    color: #733236;

    border-left: 5px solid #9D7982;

}


/* =========================================
   ADD / EDIT CARD
   ========================================= */

.category-form-card {

    background-color: #ffffff;

    padding: 35px;

    margin-bottom: 35px;

    border-radius: 14px;

    border-top: 6px solid #9CB2A5;

    box-shadow:
        0 8px 25px rgba(115, 50, 54, 0.12);

}

.category-form-card h2 {

    margin: 0 0 25px 0;

    color: #733236;

    font-size: 27px;

    text-align: left;

}


/* LABEL */

.category-form-card label {

    display: block;

    margin-bottom: 9px;

    color: #3f3a3b;

    font-size: 16px;

    font-weight: bold;

}


/* INPUT */

.category-form-card input[type="text"] {

    width: 100%;

    padding: 15px 16px;

    margin-bottom: 20px;

    border: 1px solid #d8ccca;

    border-radius: 8px;

    background-color: #fafafa;

    color: #3f3a3b;

    font-size: 16px;

    outline: none;

}

.category-form-card input[type="text"]:focus {

    background-color: #ffffff;

    border-color: #8BA4B3;

    box-shadow:
        0 0 0 3px rgba(139, 164, 179, 0.18);

}


/* BUTTON */

.category-primary-button {

    display: inline-block;

    padding: 13px 25px;

    border: none;

    border-radius: 7px;

    background-color: #733236;

    color: #ffffff;

    font-size: 15px;

    font-weight: bold;

    cursor: pointer;

}

.category-primary-button:hover {

    background-color: #9D7982;

}


/* CANCEL */

.category-cancel-button {

    display: inline-block;

    margin-left: 10px;

    padding: 13px 23px;

    border-radius: 7px;

    background-color: #C8B7C9;

    color: #3f3a3b;

    text-decoration: none;

    font-weight: bold;

}

.category-cancel-button:hover {

    background-color: #8BA4B3;

    color: #ffffff;

}


/* =========================================
   TABLE CARD
   ========================================= */

.category-table-card {

    background-color: #ffffff;

    padding: 35px;

    border-radius: 14px;

    box-shadow:
        0 8px 25px rgba(115, 50, 54, 0.12);

}

.category-table-card h2 {

    margin: 0 0 25px 0;

    color: #733236;

    font-size: 27px;

    text-align: left;

}


/* TABLE */

.category-table {

    width: 100%;

    border-collapse: collapse;

    border: 1px solid #d8ccca;

    border-radius: 8px;

    overflow: hidden;

}


/* HEADER */

.category-table thead {

    background-color: #9CB2A5;

}

.category-table th {

    padding: 16px;

    border: 1px solid #8BA4B3;

    color: #3f3a3b;

    font-size: 15px;

    font-weight: bold;

    text-align: left;

}


/* CELLS */

.category-table td {

    padding: 16px;

    border: 1px solid #d8ccca;

    color: #3f3a3b;

    font-size: 15px;

}


/* ALTERNATING ROWS */

.category-table tbody tr:nth-child(even) {

    background-color: #faf8f7;

}


/* HOVER */

.category-table tbody tr:hover {

    background-color: #f1ebe8;

}


/* DISPLAY NUMBER */

.category-table th:first-child,
.category-table td:first-child {

    width: 100px;

    text-align: center;

}


/* ACTION */

.category-table th:last-child,
.category-table td:last-child {

    width: 220px;

    text-align: center;

}


/* EDIT BUTTON */

.category-edit-button {

    display: inline-block;

    padding: 9px 20px;

    border-radius: 6px;

    background-color: #8BA4B3;

    color: #ffffff;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;

    margin-right: 5px;

}

.category-edit-button:hover {

    background-color: #733236;

    color: #ffffff;

}


/* DELETE BUTTON */

.category-delete-button {

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

.category-delete-button:hover {

    background-color: #733236;

}

.category-delete-form {

    display: inline;

}


/* EMPTY */

.category-empty {

    padding: 25px;

    border: 1px dashed #d8ccca;

    border-radius: 8px;

    background-color: #f8f5f3;

    text-align: center;

}


/* =========================================
   MOBILE
   ========================================= */

@media (max-width: 700px) {

    .category-admin {

        margin: 30px auto;

        padding: 0 15px;

    }

    .category-admin .page-title {

        font-size: 30px;

    }

    .category-form-card,
    .category-table-card {

        padding: 22px 18px;

    }

    .category-table {

        font-size: 14px;

    }

    .category-table th,
    .category-table td {

        padding: 11px 8px;

    }

    .category-primary-button,
    .category-cancel-button {

        display: block;

        width: 100%;

        margin: 8px 0;

        text-align: center;

    }

}

</style>


<div class="category-admin">


    <!-- PAGE TITLE -->

    <h1 class="page-title">

        Manage Categories

    </h1>


    <!-- SUCCESS -->

    <?php if (isset($_SESSION['success'])): ?>

        <div class="category-message category-success">

            <?= htmlspecialchars($_SESSION['success']) ?>

        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>


    <!-- ERROR -->

    <?php if (isset($_SESSION['error'])): ?>

        <div class="category-message category-error">

            <?= htmlspecialchars($_SESSION['error']) ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <!-- =========================================
         ADD / EDIT CATEGORY
         ========================================= -->

    <div class="category-form-card">


        <?php if ($editMode): ?>


            <h2>

                Edit Category

            </h2>


            <form
                action="../../actions/update_category_action.php"
                method="POST"
            >


                <input
                    type="hidden"
                    name="cat_id"
                    value="<?= htmlspecialchars($editCategory['cat_id']) ?>"
                >


                <label for="category_name">

                    Category Name

                </label>


                <input
                    type="text"
                    id="category_name"
                    name="category_name"
                    value="<?= htmlspecialchars($editCategory['cat_name']) ?>"
                    required
                >


                <button
                    type="submit"
                    class="category-primary-button"
                >

                    Update Category

                </button>


                <a
                    href="category.php"
                    class="category-cancel-button"
                >

                    Cancel

                </a>


            </form>


        <?php else: ?>


            <h2>

                Add Category

            </h2>


            <form
                action="../../actions/add_category_action.php"
                method="POST"
            >


                <label for="category_name">

                    Category Name

                </label>


                <input
                    type="text"
                    id="category_name"
                    name="category_name"
                    placeholder="Enter category name"
                    required
                >


                <button
                    type="submit"
                    class="category-primary-button"
                >

                    Add Category

                </button>


            </form>


        <?php endif; ?>


    </div>


    <!-- =========================================
         CATEGORY TABLE
         ========================================= -->

    <div class="category-table-card">


        <h2>

            All Categories

        </h2>


        <?php if (count($categories) > 0): ?>


            <table class="category-table">


                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Category Name
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php foreach ($categories as $index => $category): ?>


                        <tr>


                            <!--
                                DISPLAY NUMBER ONLY

                                This shows:
                                1
                                2
                                3
                                4

                                It does NOT change the
                                real database cat_id.
                            -->

                            <td>

                                <?= $index + 1 ?>

                            </td>


                            <!-- CATEGORY NAME -->

                            <td>

                                <?= htmlspecialchars($category['cat_name']) ?>

                            </td>


                            <td>


                                <!-- EDIT -->

                                <a
                                    href="category.php?edit_id=<?= htmlspecialchars($category['cat_id']) ?>"
                                    class="category-edit-button"
                                >

                                    Edit

                                </a>


                                <!-- DELETE -->

                                <form
                                    action="../../actions/delete_category_action.php"
                                    method="POST"
                                    class="category-delete-form"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');"
                                >


                                    <!--
                                        REAL DATABASE ID

                                        Keep cat_id here.
                                        Do NOT use $index + 1.
                                    -->

                                    <input
                                        type="hidden"
                                        name="cat_id"
                                        value="<?= htmlspecialchars($category['cat_id']) ?>"
                                    >


                                    <button
                                        type="submit"
                                        class="category-delete-button"
                                    >

                                        Delete

                                    </button>


                                </form>


                            </td>


                        </tr>


                    <?php endforeach; ?>


                </tbody>


            </table>


        <?php else: ?>


            <div class="category-empty">

                No categories have been added yet.

            </div>


        <?php endif; ?>


    </div>


</div>


<?php require_once '../layout/footer.php'; ?>