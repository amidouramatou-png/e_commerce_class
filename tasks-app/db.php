<?php

$host = "localhost";
$db_user = "ramatou.amidou";
$db_pass = "E-com@26";
$db_name = "ecommerce_2026A_ramatou_amidou";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
