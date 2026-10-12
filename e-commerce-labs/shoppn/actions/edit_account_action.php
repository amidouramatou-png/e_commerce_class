<?php

require_once '../core/core.php';
require_once '../classes/CustomerClass.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/account/edit_account.php');
}


$name = trim(strip_tags($_POST['name'] ?? ''));
$email = trim($_POST['email'] ?? '');
$country = trim(strip_tags($_POST['country'] ?? ''));
$city = trim(strip_tags($_POST['city'] ?? ''));
$contact = trim(strip_tags($_POST['contact'] ?? ''));


if (
    $name === '' ||
    $email === '' ||
    $country === '' ||
    $city === '' ||
    $contact === ''
) {

    $_SESSION['error'] =
        'Please fill in all required fields.';

    redirect('../views/account/edit_account.php');
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['error'] =
        'Please enter a valid email address.';

    redirect('../views/account/edit_account.php');
}


$customerModel = new CustomerClass();

$customer =
    $customerModel->getCustomerById(
        $_SESSION['customer_id']
    );


if (!$customer) {

    $_SESSION['error'] =
        'Customer account not found.';

    redirect('../views/account/my_account.php');
}


// Check if another customer already uses the email

if ($email !== $customer['customer_email']) {

    if ($customerModel->emailExists($email)) {

        $_SESSION['error'] =
            'This email address is already registered.';

        redirect('../views/account/edit_account.php');
    }
}




$stmt = $customerModel->updateCustomer(
    $_SESSION['customer_id'],
    $name,
    $email,
    $country,
    $city,
    $contact
);


if ($stmt) {

    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;

    $_SESSION['success'] =
        'Your account has been updated successfully.';

    redirect('../views/account/my_account.php');
}


$_SESSION['error'] =
    'Your account could not be updated.';

redirect('../views/account/edit_account.php');

?>