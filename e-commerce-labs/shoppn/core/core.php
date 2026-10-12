<?php

session_start();

date_default_timezone_set('Africa/Accra');

require_once __DIR__ . '/db_class.php';


// ==========================================
// GET USER IP ADDRESS
// ==========================================

function get_ip() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

        return $_SERVER['HTTP_CLIENT_IP'];

    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

        return $_SERVER['HTTP_X_FORWARDED_FOR'];

    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}


// ==========================================
// REDIRECT
// ==========================================

function redirect($url) {

    header("Location: $url");

    exit();

}


// ==========================================
// CHECK LOGIN
// ==========================================

function is_logged_in() {

    return isset($_SESSION['customer_id']);

}


// ==========================================
// CHECK ADMIN
// ==========================================

function is_admin() {

    return isset($_SESSION['user_role'])
        && (int) $_SESSION['user_role'] === 1;

}


// ==========================================
// REQUIRE LOGIN
// ==========================================

function require_login() {

    if (!is_logged_in()) {

        $_SESSION['error'] =
            'Please login first.';

        redirect(
            '/xampp/E_commerce_class/e-commerce-labs/shoppn/views/login.php'
        );

    }

}


// ==========================================
// REQUIRE ADMIN
// ==========================================

function require_admin() {

    if (!is_admin()) {

        $_SESSION['error'] =
            'Access denied.';

        redirect(
            '/xampp/E_commerce_class/e-commerce-labs/shoppn/index.php'
        );

    }

}

?>