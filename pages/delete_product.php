<?php
include_once '../includes/config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $query = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        header("Location: vendor_dashboard.php");
        exit();
    }
}
?>
