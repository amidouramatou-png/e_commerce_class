<?php

require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database
{
    // Check if the product is already in the cart.
    public function isInCart($product_id, $ip)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM cart
                WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $product_id, $ip);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int) $row['total'] > 0;
    }

    // Add a product to the cart.
    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        $sql = "INSERT INTO cart (p_id, ip_add, qty)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isi", $product_id, $ip, $qty);

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    // Get cart items and their product details.
    public function getCartItems($ip)
    {
        $sql = "SELECT
                    cart.*,
                    products.product_title,
                    products.product_price,
                    products.product_image
                FROM cart
                INNER JOIN products
                    ON cart.p_id = products.product_id
                WHERE cart.ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $ip);
        $stmt->execute();

        $result = $stmt->get_result();
        $items = [];

        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        $stmt->close();

        return $items;
    }

    // Count the products in the cart.
    public function getCartCount($ip)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM cart
                WHERE ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $ip);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int) $row['total'];
    }

    // Calculate the total price of the cart.
    public function getCartTotal($ip)
    {
        $items = $this->getCartItems($ip);
        $total = 0;

        foreach ($items as $item) {
            $total += (float) $item['product_price']
                    * (int) $item['qty'];
        }

        return $total;
    }


    // =====================================
    // TASK 12 — CART MANAGEMENT
    // =====================================

    // Remove one product from this visitor's cart.
    public function removeFromCart($product_id, $ip)
    {
        $sql = "DELETE FROM cart
                WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $product_id, $ip);

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    // Change the quantity of one product in this visitor's cart.
    public function updateQty($product_id, $new_qty, $ip)
    {
        $new_qty = (int) $new_qty;

        // Quantity must be at least 1.
        if ($new_qty < 1) {
            return false;
        }

        $sql = "UPDATE cart
                SET qty = ?
                WHERE p_id = ? AND ip_add = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $new_qty, $product_id, $ip);

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }
}