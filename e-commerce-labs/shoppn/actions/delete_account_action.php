<?php

require_once '../core/core.php';
require_once '../classes/CustomerClass.php';


// ==========================================
// USER MUST BE LOGGED IN
// ==========================================

require_login();


// ==========================================
// ONLY ALLOW POST REQUEST
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect('../views/account/delete_account.php');

}


// ==========================================
// DELETE CUSTOMER
// ==========================================

$customerModel = new CustomerClass();

$success =
    $customerModel->deleteCustomer(
        $_SESSION['customer_id']
    );


// ==========================================
// CHECK RESULT
// ==========================================

if ($success) {

    // Clear all session information

    $_SESSION = array();

    session_destroy();


    // Send user back to home page

    header(
        "Location: ../index.php"
    );

    exit();
}


// ==========================================
// DELETE FAILED
// ==========================================

$_SESSION['error'] =
    'Your account could not be deleted.';

redirect('../views/account/delete_account.php');

?>