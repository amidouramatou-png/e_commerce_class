<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Shoppn</title>

    <link
        rel="stylesheet"
        href="/xampp/E_commerce_class/e-commerce-labs/shoppn/css/style.css?v=3"
    >

</head>


<body>


<header>

    <!-- LOGO -->

    <a
        href="/xampp/E_commerce_class/e-commerce-labs/shoppn/index.php"
        class="logo-link"
    >

        <img
            src="/xampp/E_commerce_class/e-commerce-labs/shoppn/images/logo.png"
            alt="ShopN"
            class="site-logo"
        >

    </a>


    <!-- NAVIGATION -->

    <nav>

        <a
            href="/xampp/E_commerce_class/e-commerce-labs/shoppn/index.php"
        >
            Home
        </a>


        <?php if (is_logged_in()): ?>

            <span>

                Welcome
                <?= htmlspecialchars(
                    $_SESSION['customer_name'] ?? ''
                ) ?>

            </span>


            <a
                href="/xampp/E_commerce_class/e-commerce-labs/shoppn/views/account/my_account.php"
            >
                My Account
            </a>


            <a
                href="/xampp/E_commerce_class/e-commerce-labs/shoppn/logout.php"
            >
                Logout
            </a>


        <?php else: ?>

            <a
                href="/xampp/E_commerce_class/e-commerce-labs/shoppn/views/register.php"
            >
                Register
            </a>


            <a
                href="/xampp/E_commerce_class/e-commerce-labs/shoppn/views/login.php"
            >
                Login
            </a>

        <?php endif; ?>


        <?php if (is_admin()): ?>

            <a
                href="/xampp/E_commerce_class/e-commerce-labs/shoppn/views/admin/brand.php"
            >
                Brand
            </a>


            <a
                href="/xampp/E_commerce_class/e-commerce-labs/shoppn/views/admin/category.php"
            >
                Category
            </a>


            <a
                href="/xampp/E_commerce_class/e-commerce-labs/shoppn/views/admin/product.php"
            >
                Product
            </a>

        <?php endif; ?>

    </nav>


    <!-- SEARCH -->

    <form
        action="/xampp/E_commerce_class/e-commerce-labs/shoppn/views/search_results.php"
        method="GET"
    >

        <input
            type="text"
            name="user_query"
            placeholder="Search products"
        >

        <button type="submit">
            Search
        </button>

    </form>


</header>