<?php
include_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_name = htmlspecialchars($_POST['product_name']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $image = htmlspecialchars($_POST['current_image']); // Use a hidden field to get the current image value.

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_temp = $_FILES['image']['tmp_name'];
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($image_extension, $allowed_extensions)) {
            $image_new_name = uniqid('', true) . '.' . $image_extension;
            $image_folder = "../uploads/" . $image_new_name;

            if (move_uploaded_file($image_temp, $image_folder)) {
                $image = $image_new_name; // Overwrite `$image` with the new image name
            } else {
                echo "Error uploading image.";
                exit;
            }
        } else {
            echo "Invalid image file format.";
            exit;
        }
    }

    // Prepare query to update the product
    $query = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ?";
    if (isset($image) && !empty($image)) {
        $query .= ", image = ?";
    }
    $query .= " WHERE id = ?";

    $stmt = $conn->prepare($query);
    if (isset($image) && !empty($image)) {
        $stmt->bind_param("ssdisi", $product_name, $description, $price, $quantity, $image, $product_id);
    } else {
        $stmt->bind_param("ssdis", $product_name, $description, $price, $quantity, $product_id);
    }

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}
?>
