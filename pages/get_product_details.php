<?php
include_once '../includes/config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode([]);
    }
}
?>
