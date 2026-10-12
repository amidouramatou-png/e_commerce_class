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

}
?>