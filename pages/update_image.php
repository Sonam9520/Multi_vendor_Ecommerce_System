<?php
session_start();
include '../includes/config.php';

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "Access denied. Admins only."]);
    exit;
}

// Check if the necessary parameters are provided
if (isset($_GET['id']) && isset($_GET['table']) && isset($_GET['new_image_url'])) {
    $id = (int)$_GET['id'];  // Sanitize and cast ID to an integer
    $table = mysqli_real_escape_string($conn, $_GET['table']);  // Sanitize table name
    $new_image_url = mysqli_real_escape_string($conn, $_GET['new_image_url']);  // Sanitize the new image URL

    // Validate the URL (basic validation to ensure it's a proper URL)
    if (!filter_var($new_image_url, FILTER_VALIDATE_URL)) {
        echo json_encode(["success" => false, "message" => "Invalid image URL format."]);
        exit;
    }

    // Prepare the query to update the image URL in the database
    $query = "UPDATE $table SET image = '$new_image_url' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(["success" => true, "message" => "Image updated successfully!"]);
    } else {
        // Add the MySQL error to the message for better debugging
        echo json_encode(["success" => false, "message" => "Error updating image: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid parameters."]);
}
?>
