<?php
include '../includes/config.php';  // Include database connection

// Check if 'id' is passed
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Debugging: Ensure that the ID is correct
    error_log("Fetching product with ID: " . $productId);

    // Fetch product details based on the product ID
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the product was found
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            echo json_encode($product);  // Return product data as JSON
        } else {
            // If no product found, return an error message
            echo json_encode(["error" => "Product not found"]);
        }
    } else {
        // Error preparing the query
        echo json_encode(["error" => "Failed to prepare the query"]);
    }
} else {
    // No ID provided in the request
    echo json_encode(["error" => "No product ID provided"]);
}
?>
