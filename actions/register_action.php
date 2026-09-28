<?php

require_once '../core/core.php';
require_once '../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/register.php');
}

$name = trim(strip_tags($_POST['name'] ?? ''));
$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';
$country = trim(strip_tags($_POST['country'] ?? ''));
$city = trim(strip_tags($_POST['city'] ?? ''));
$contact = trim(strip_tags($_POST['contact'] ?? ''));

if ($name === '' || $email === '' || $pass === '' ||
    $country === '' || $city === '' || $contact === '') {

    $_SESSION['error'] = 'Please fill in all required fields.';
    redirect('../views/register.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email address.';
    redirect('../views/register.php');
}

$data = [
    'name' => $name,
    'email' => $email,
    'pass' => $pass,
    'country' => $country,
    'city' => $city,
    'contact' => $contact
];

$controller = new CustomerController();

$result = $controller->register($data);

if ($result['success']) {
    $_SESSION['success'] = 'Registration successful.';
    redirect('../views/account/my_account.php');
}

$_SESSION['error'] = $result['error'];

redirect('../views/register.php');