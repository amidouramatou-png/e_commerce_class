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
// GET FORM DATA
// ==========================================

$token =
    $_POST['token'] ?? '';

$new_password =
    $_POST['new_password'] ?? '';

$confirm_password =
    $_POST['confirm_password'] ?? '';


// ==========================================
// CHECK REQUIRED FIELDS
// ==========================================

if (
    $token === '' ||
    $new_password === '' ||
    $confirm_password === ''
) {

    $_SESSION['error'] =
        'Please fill in all fields.';

    redirect(
        '../views/reset_password.php?token='
        . urlencode($token)
    );

}


// ==========================================
// CHECK PASSWORD MATCH
// ==========================================

if ($new_password !== $confirm_password) {

    $_SESSION['error'] =
        'Passwords do not match.';

    redirect(
        '../views/reset_password.php?token='
        . urlencode($token)
    );

}


// ==========================================
// CHECK PASSWORD LENGTH
// ==========================================

if (strlen($new_password) < 8) {

    $_SESSION['error'] =
        'Password must contain at least 8 characters.';

    redirect(
        '../views/reset_password.php?token='
        . urlencode($token)
    );

}


// ==========================================
// CHECK FOR NUMBER
// ==========================================

if (!preg_match('/\d/', $new_password)) {

    $_SESSION['error'] =
        'Password must contain at least one number.';

    redirect(
        '../views/reset_password.php?token='
        . urlencode($token)
    );

}


// ==========================================
// FIND CUSTOMER USING TOKEN
// ==========================================

$controller =
    new CustomerController();

$customer =
    $controller->getCustomerByResetToken($token);


// ==========================================
// CHECK TOKEN
// ==========================================

if (!$customer) {

    $_SESSION['error'] =
        'This password reset link is invalid or has expired.';

    redirect('../views/forgot_password.php');

}


// ==========================================
// RESET PASSWORD
// ==========================================

$result =
    $controller->resetPassword(
        $customer['customer_id'],
        $new_password
    );


// ==========================================
// CHECK RESULT
// ==========================================

if ($result['success']) {

    $_SESSION['success'] =
        'Your password has been reset successfully. Please login.';

    redirect('../views/login.php');

}


// ==========================================
// RESET FAILED
// ==========================================

$_SESSION['error'] =
    $result['error'];

redirect(
    '../views/reset_password.php?token='
    . urlencode($token)
);

?>