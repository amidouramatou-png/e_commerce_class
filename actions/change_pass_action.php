<?php

require_once '../core/core.php';
require_once '../controllers/CustomerController.php';


require_login();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/account/change_pass.php');
}


$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';


if (
    $current_password === '' ||
    $new_password === '' ||
    $confirm_password === ''
) {
    $_SESSION['error'] = 'Please fill in all password fields.';
    redirect('../views/account/change_pass.php');
}


if ($new_password !== $confirm_password) {
    $_SESSION['error'] = 'New passwords do not match.';
    redirect('../views/account/change_pass.php');
}


if (strlen($new_password) < 8) {
    $_SESSION['error'] =
        'New password must contain at least 8 characters.';
    redirect('../views/account/change_pass.php');
}


if (!preg_match('/\d/', $new_password)) {
    $_SESSION['error'] =
        'New password must contain at least one number.';
    redirect('../views/account/change_pass.php');
}


if ($current_password === $new_password) {
    $_SESSION['error'] =
        'New password must be different from your current password.';
    redirect('../views/account/change_pass.php');
}


$controller = new CustomerController();


$result = $controller->changePassword(
    $_SESSION['customer_id'],
    $current_password,
    $new_password
);


if ($result['success']) {

    $_SESSION['success'] =
        'Your password has been changed successfully.';

    redirect('../views/account/my_account.php');
}


$_SESSION['error'] = $result['error'];

redirect('../views/account/change_pass.php');