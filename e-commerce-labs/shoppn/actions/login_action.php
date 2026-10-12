<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';


// ==========================================
// ONLY ALLOW POST REQUEST
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('../views/login.php');

}


// ==========================================
// GET FORM DATA
// ==========================================

$email = trim($_POST['email'] ?? '');

$pass = $_POST['password'] ?? '';


// ==========================================
// CHECK REQUIRED FIELDS
// ==========================================

if ($email === '' || $pass === '') {

    $_SESSION['error'] = 'Please enter your email and password.';

    redirect('../views/login.php');

}


// ==========================================
// VALIDATE EMAIL
// ==========================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['error'] = 'Please enter a valid email address.';

    redirect('../views/login.php');

}


// ==========================================
// LOGIN
// ==========================================

$controller = new CustomerController();

$result = $controller->login(
    $email,
    $pass
);


// ==========================================
// LOGIN SUCCESS
// ==========================================

if (
    isset($result['customer_id']) &&
    isset($result['customer_email'])
) {

    $_SESSION['customer_id'] =
        $result['customer_id'];

    $_SESSION['customer_name'] =
        $result['customer_name'];

    $_SESSION['customer_email'] =
        $result['customer_email'];

    $_SESSION['user_role'] =
        $result['user_role'];


    // ==========================================
    // REDIRECT BASED ON USER ROLE
    // ==========================================

    $role = $result['user_role'] ?? null;

    // Admin goes to the Admin Dashboard.
    if ((int) $role === 1) {

        redirect('../views/admin/dashboard.php');

    }

    // Customers go to the ShopN homepage.
    redirect('../index.php');
}


// ==========================================
// LOGIN FAILED
// ==========================================

$_SESSION['error'] =
    $result['error'] ?? 'Invalid email or password.';

redirect('../views/login.php');

?>
