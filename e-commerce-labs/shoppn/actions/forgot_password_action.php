<?php

require_once '../core/core.php';
require_once '../controllers/CustomerController.php';


// ==========================================
// ONLY ALLOW POST REQUEST
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('../views/forgot_password.php');

}


// ==========================================
// GET EMAIL
// ==========================================

$email = trim($_POST['email'] ?? '');


// ==========================================
// CHECK EMAIL
// ==========================================

if ($email === '') {

    $_SESSION['error'] =
        'Please enter your email address.';

    redirect('../views/forgot_password.php');
}


// ==========================================
// VALIDATE EMAIL
// ==========================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['error'] =
        'Please enter a valid email address.';

    redirect('../views/forgot_password.php');
}


// ==========================================
// CREATE RESET REQUEST
// ==========================================

$controller = new CustomerController();

$result =
    $controller->createPasswordReset($email);


// ==========================================
// RESET REQUEST FAILED
// ==========================================

if (!$result['success']) {

    $_SESSION['error'] =
        $result['error'];

    redirect('../views/forgot_password.php');
}


// ==========================================
// GET RESET TOKEN
// ==========================================

$token = $result['token'];


// ==========================================
// CREATE RESET LINK
// ==========================================

$resetLink =
    '/xampp/E_commerce_class/shoppn/views/reset_password.php?token='
    . urlencode($token);


// ==========================================
// TEMPORARY LOCAL TESTING
// ==========================================

$_SESSION['reset_link'] = $resetLink;

$_SESSION['success'] =
    'A password reset link has been created.';

redirect('../views/forgot_password.php');

?>