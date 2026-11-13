<?php

require_once __DIR__ . '/../classes/CartClass.php';

class CartController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartClass();
    }

    public function isInCart($product_id, $ip)
    {
        return $this->cartModel->isInCart($product_id, $ip);
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        return $this->cartModel->addToCart(
            $product_id,
            $qty,
            $ip,
            $customer_id
        );
    }

    public function getCartItems($ip)
    {
        return $this->cartModel->getCartItems($ip);
    }

    public function getCartCount($ip)
    {
        return $this->cartModel->getCartCount($ip);
    }

    public function getCartTotal($ip)
    {
        return $this->cartModel->getCartTotal($ip);
    }


    // =====================================
    // TASK 12 — CART MANAGEMENT
    // =====================================

    public function removeFromCart($product_id, $ip)
    {
        return $this->cartModel->removeFromCart($product_id, $ip);
    }

    public function updateQty($product_id, $new_qty, $ip)
    {
        return $this->cartModel->updateQty($product_id, $new_qty, $ip);
    }


    // =====================================
    // TASK 13 — PAYMENT AND ORDERS
    // =====================================

    // Save the paid cart as an order. Returns the new order_id or false.
    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        return $this->cartModel->processPayment(
            $customer_id,
            $ip,
            $reference,
            $amount
        );
    }

    // Get an order and its products for the invoice page.
    public function getOrderById($order_id, $customer_id = null)
    {
        return $this->cartModel->getOrderById($order_id, $customer_id);
    }

    // Check whether a Paystack payment has already been recorded.
    public function getOrderIdByReference($reference)
    {
        return $this->cartModel->getOrderIdByReference($reference);
    }
}