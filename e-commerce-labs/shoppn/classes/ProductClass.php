<?php

require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database
{
    // =====================================
    // BRAND METHODS
    // =====================================

    public function addBrand($name)
    {
        $sql = "INSERT INTO brands (brand_title) VALUES (?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);

        return $stmt->execute();
    }

    public function getAllBrands()
    {
        $sql = "SELECT * FROM brands ORDER BY brand_id ASC";

        $result = $this->conn->query($sql);
        $brands = [];

        while ($row = $result->fetch_assoc()) {
            $brands[] = $row;
        }

        return $brands;
    }

    public function getBrandById($id)
    {
        $sql = "SELECT * FROM brands WHERE brand_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function updateBrand($id, $name)
    {
        $sql = "UPDATE brands
                SET brand_title = ?
                WHERE brand_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);

        return $stmt->execute();
    }

    public function deleteBrand($id)
    {
        $sql = "DELETE FROM brands WHERE brand_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }


    // =====================================
    // CATEGORY METHODS
    // =====================================

    public function addCategory($name)
    {
        $sql = "INSERT INTO categories (cat_name) VALUES (?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);

        return $stmt->execute();
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";

        $result = $this->conn->query($sql);
        $categories = [];

        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE cat_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function updateCategory($id, $name)
    {
        $sql = "UPDATE categories
                SET cat_name = ?
                WHERE cat_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);

        return $stmt->execute();
    }

    public function deleteCategory($id)
    {
        $sql = "DELETE FROM categories WHERE cat_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }


    // =====================================
    // PRODUCT METHODS — TASK 9
    // =====================================

    // ADD PRODUCT

    public function addProduct(
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $image_filename,
        $keywords
    ) {
        $sql = "INSERT INTO products
                (
                    product_cat,
                    product_brand,
                    product_title,
                    product_price,
                    product_desc,
                    product_image,
                    product_keywords
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "iisdsss",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords
        );

        return $stmt->execute();
    }


    // GET ONE PRODUCT BY ID
    // Used by the admin edit form and Task 10 product details.

    public function getProductById($id)
    {
        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_title
                FROM products p
                LEFT JOIN categories c
                    ON p.product_cat = c.cat_id
                LEFT JOIN brands b
                    ON p.product_brand = b.brand_id
                WHERE p.product_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }


    // UPDATE PRODUCT

    public function updateProduct(
        $id,
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $image_filename,
        $keywords
    ) {
        $sql = "UPDATE products
                SET
                    product_cat = ?,
                    product_brand = ?,
                    product_title = ?,
                    product_price = ?,
                    product_desc = ?,
                    product_image = ?,
                    product_keywords = ?
                WHERE product_id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "iisdsssi",
            $cat,
            $brand,
            $title,
            $price,
            $desc,
            $image_filename,
            $keywords,
            $id
        );

        return $stmt->execute();
    }


    // GET ALL PRODUCTS
    // Used by the admin product management page.

    public function getAllProducts()
    {
        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_title
                FROM products p
                LEFT JOIN categories c
                    ON p.product_cat = c.cat_id
                LEFT JOIN brands b
                    ON p.product_brand = b.brand_id
                ORDER BY p.product_id ASC";

        $result = $this->conn->query($sql);
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }


    // DELETE PRODUCT

    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE product_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }


    // =====================================
    // TASK 10 — CUSTOMER PRODUCT DISPLAY
    // =====================================

    // 1. GET FEATURED PRODUCTS
    // Returns up to six products for the homepage.

    public function getFeaturedProducts($limit = 6)
    {
        $limit = (int) $limit;

        if ($limit < 1) {
            $limit = 6;
        }

        // LIMIT is bound as an integer.
        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_title
                FROM products p
                LEFT JOIN categories c
                    ON p.product_cat = c.cat_id
                LEFT JOIN brands b
                    ON p.product_brand = b.brand_id
                ORDER BY RAND()
                LIMIT ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }


    // 2. GET PRODUCTS BY CATEGORY

    public function getProductsByCategory($cat_id)
    {
        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_title
                FROM products p
                LEFT JOIN categories c
                    ON p.product_cat = c.cat_id
                LEFT JOIN brands b
                    ON p.product_brand = b.brand_id
                WHERE p.product_cat = ?
                ORDER BY p.product_title ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }


    // 3. GET PRODUCTS BY BRAND

    public function getProductsByBrand($brand_id)
    {
        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_title
                FROM products p
                LEFT JOIN categories c
                    ON p.product_cat = c.cat_id
                LEFT JOIN brands b
                    ON p.product_brand = b.brand_id
                WHERE p.product_brand = ?
                ORDER BY p.product_title ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }


    // 4. SEARCH PRODUCTS BY TITLE OR KEYWORDS

    public function searchProducts($query)
    {
        $query = trim($query);

        if ($query === '') {
            return [];
        }

        $searchTerm = '%' . $query . '%';

        $sql = "SELECT
                    p.*,
                    c.cat_name,
                    b.brand_title
                FROM products p
                LEFT JOIN categories c
                    ON p.product_cat = c.cat_id
                LEFT JOIN brands b
                    ON p.product_brand = b.brand_id
                WHERE p.product_title LIKE ?
                   OR p.product_keywords LIKE ?
                ORDER BY p.product_title ASC";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ss",
            $searchTerm,
            $searchTerm
        );

        $stmt->execute();

        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        return $products;
    }
}
?>
