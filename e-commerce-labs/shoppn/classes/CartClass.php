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


    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        $items = $this->getCartItems($ip);

        if (empty($items)) {
            return false;
        }

        $customer_id = (int) $customer_id;
        $amount = (float) $amount;

        $this->conn->begin_transaction();

        try {
            // 1. Generate an invoice number.
            $invoice_no = mt_rand(100000, 999999);
            $status = 'paid';

            // 2. Create the order.
            $stmt = $this->conn->prepare(
                "INSERT INTO orders
                    (customer_id, invoice_no, order_date, order_status)
                 VALUES (?, ?, CURDATE(), ?)"
            );
            $stmt->bind_param("iis", $customer_id, $invoice_no, $status);

            if (!$stmt->execute()) {
                throw new Exception('Could not create the order.');
            }

            $order_id = (int) $this->conn->insert_id;
            $stmt->close();

            // 3. Copy each cart row into orderdetails.
            $stmt = $this->conn->prepare(
                "INSERT INTO orderdetails (order_id, product_id, qty, price)
                 VALUES (?, ?, ?, ?)"
            );

            foreach ($items as $item) {
                $product_id = (int) $item['p_id'];
                $qty = (int) $item['qty'];
                $price = (float) $item['product_price'];

                $stmt->bind_param("iiid", $order_id, $product_id, $qty, $price);

                if (!$stmt->execute()) {
                    throw new Exception('Could not save the order details.');
                }
            }

            $stmt->close();

            // Record the Paystack payment.
            $currency = 'GHS';

            $stmt = $this->conn->prepare(
                "INSERT INTO payment
                    (amt, customer_id, order_id, currency, payment_date, paystack_reference)
                 VALUES (?, ?, ?, ?, CURDATE(), ?)"
            );
            $stmt->bind_param(
                "diiss",
                $amount,
                $customer_id,
                $order_id,
                $currency,
                $reference
            );

            if (!$stmt->execute()) {
                throw new Exception('Could not save the payment.');
            }

            $stmt->close();

            // 4. Empty this visitor's cart.
            $stmt = $this->conn->prepare(
                "DELETE FROM cart WHERE ip_add = ?"
            );
            $stmt->bind_param("s", $ip);

            if (!$stmt->execute()) {
                throw new Exception('Could not empty the cart.');
            }

            $stmt->close();

            $this->conn->commit();

            // 5. Return the new order ID.
            return $order_id;

        } catch (Throwable $e) {

            $this->conn->rollback();

            error_log('processPayment failed: ' . $e->getMessage());

            return false;
        }
    }

    // Get one order with its payment and products, for the invoice.
    // When $customer_id is given, only that customer's order is returned,
    // so nobody can view another customer's invoice by changing the URL.
    public function getOrderById($order_id, $customer_id = null)
    {
        $sql = "SELECT
                    o.order_id,
                    o.customer_id,
                    o.invoice_no,
                    o.order_date,
                    o.order_status,
                    c.customer_name,
                    c.customer_email,
                    p.amt,
                    p.currency,
                    p.paystack_reference
                FROM orders o
                LEFT JOIN customer c
                    ON o.customer_id = c.customer_id
                LEFT JOIN payment p
                    ON p.order_id = o.order_id
                WHERE o.order_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $order = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        if (!$order) {
            return false;
        }

        if (
            $customer_id !== null &&
            (int) $order['customer_id'] !== (int) $customer_id
        ) {
            return false;
        }

        $sql = "SELECT
                    od.product_id,
                    od.qty,
                    od.price,
                    pr.product_title,
                    pr.product_image
                FROM orderdetails od
                LEFT JOIN products pr
                    ON od.product_id = pr.product_id
                WHERE od.order_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $items = [];
        $total = 0;

        while ($row = $result->fetch_assoc()) {
            $total += (float) $row['price'] * (int) $row['qty'];
            $items[] = $row;
        }

        $stmt->close();

        return [
            'order' => $order,
            'items' => $items,
            'total' => $total
        ];
    }

    // Find the order already created for a Paystack reference.
    // Returns the order_id, or null if this payment is not recorded yet.
    public function getOrderIdByReference($reference)
    {
        $sql = "SELECT order_id
                FROM payment
                WHERE paystack_reference = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $reference);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $row ? (int) $row['order_id'] : null;
    }
}