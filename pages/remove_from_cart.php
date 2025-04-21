<?php
// include 'includes/config.php';
// include 'http://localhost/multi-vendor-ecommerce-system/includes/header.php';
include '../includes/config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to remove items from your cart.";
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'];

$cartController = new CartController($conn);
$cartController->removeFromCart($user_id, $product_id);

header("Location: cart.php");
exit;
?>
