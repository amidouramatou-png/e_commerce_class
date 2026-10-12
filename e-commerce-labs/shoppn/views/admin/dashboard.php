<?php

require_once __DIR__ . '/../../core/core.php';

require_admin();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Shoppn</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: Arial, Helvetica, sans-serif;
            background: #f8f5f3;
            color: #3f3a3b;
        }

        /* HEADER */

        .dashboard-header {
            background: #733236;
            color: white;
            padding: 18px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .brand-logo img {
            display: block;
            width: 200px;
            height: 90px;
            max-width: 100%;
            object-fit: contain;
            background: #f8f5f3;
            border-radius: 5px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .dashboard-header h1 {
            margin: 0;
            font-size: 25px;
        }

        .dashboard-header p {
            margin: 7px 0 0;
            color: #f0dfe0;
        }

        .logout-link {
            color: white;
            text-decoration: none;
            border: 1px solid white;
            padding: 10px 18px;
            border-radius: 7px;
            white-space: nowrap;
        }

        .logout-link:hover {
            background: #9D7982;
        }

        /* MAIN CONTENT */

        .dashboard-container {
            width: 100%;
            max-width: 1150px;
            margin: 0 auto;
            padding: 45px 22px;
            flex: 1;
        }

        .welcome-section {
            margin-bottom: 35px;
        }

        .welcome-section h2 {
            color: #733236;
            font-size: 32px;
            margin: 0 0 10px;
        }

        .welcome-section p {
            color: #6b6262;
            line-height: 1.6;
        }

        /* DASHBOARD CARDS */

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 25px;
        }

        .dashboard-card {
            display: block;
            background: white;
            padding: 30px 25px;
            border-radius: 14px;
            text-decoration: none;
            color: #3f3a3b;
            border: 1px solid #eee4e2;
            box-shadow: 0 6px 20px rgba(115, 50, 54, 0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(115, 50, 54, 0.16);
        }

        .dashboard-icon {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1e7e5;
            border-radius: 12px;
            font-size: 32px;
            margin-bottom: 22px;
        }

        .dashboard-card h3 {
            color: #733236;
            font-size: 23px;
            margin: 0 0 12px;
        }

        .dashboard-card p {
            color: #6b6262;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .card-link {
            color: #733236;
            font-weight: bold;
        }

        /* FOOTER */

        .dashboard-footer {
            width: 100%;
            background-color: #733236;
            color: #ffffff;
            text-align: center;
            padding: 24px 15px;
            margin-top: auto;
            flex-shrink: 0;
        }

        .dashboard-footer p {
            margin: 0;
            font-size: 15px;
            line-height: 1.5;
        }

        /* RESPONSIVE DESIGN */

        @media (max-width: 800px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-container {
                padding: 30px 16px;
            }

            .welcome-section h2 {
                font-size: 27px;
            }

            .brand-logo img {
                width: 150px;
                height: 70px;
            }

            .dashboard-header {
                padding: 18px 20px;
            }

            .header-right {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER WITH SHOPN LOGO -->

    <header class="dashboard-header">

        <a href="../../index.php" class="brand-logo">
            <img
                src="../../images/logo.png"
                alt="ShopN Logo"
            >
        </a>

        <div class="header-right">

            <div>
                <h1>Admin Dashboard</h1>
                <p>Store Management</p>
            </div>

            <a href="../../logout.php" class="logout-link">
                Logout
            </a>

        </div>

    </header>


    <!-- MAIN DASHBOARD -->

    <main class="dashboard-container">

        <section class="welcome-section">

            <h2>Welcome to your Dashboard!</h2>

            <p>
                Manage your online store from one place.
                Select a section below to manage your brands,
                categories, or products.
            </p>

        </section>


        <section class="dashboard-grid">

            <!-- BRAND MANAGEMENT -->

            <a href="brand.php" class="dashboard-card">

                <div class="dashboard-icon">🏷️</div>

                <h3>Brands</h3>

                <p>
                    Add new brands, edit existing brands,
                    and remove brands from your store.
                </p>

                <span class="card-link">Manage Brands →</span>

            </a>


            <!-- CATEGORY MANAGEMENT -->

            <a href="category.php" class="dashboard-card">

                <div class="dashboard-icon">🗂️</div>

                <h3>Categories</h3>

                <p>
                    Organize your products into categories.
                    Add, edit, and delete categories.
                </p>

                <span class="card-link">Manage Categories →</span>

            </a>


            <!-- PRODUCT MANAGEMENT -->

            <a href="product.php" class="dashboard-card">

                <div class="dashboard-icon">📦</div>

                <h3>Products</h3>

                <p>
                    Add products with images, update product
                    information, and remove products.
                </p>

                <span class="card-link">Manage Products →</span>

            </a>

        </section>

    </main>


    <!-- FOOTER -->

    <footer class="dashboard-footer">
        <p>
            &copy; <?= date('Y') ?> Shoppn. All rights reserved.
        </p>
    </footer>

</body>

</html>
