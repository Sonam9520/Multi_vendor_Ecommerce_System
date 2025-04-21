<?php
session_start();
include '../includes/config.php';

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "Access denied. Admins only."]);
    exit;
}

// Check if ID and table are provided
if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = (int)$_GET['id'];
    $table = mysqli_real_escape_string($conn, $_GET['table']);

    // Fetch the record details
    $query = "SELECT * FROM $table WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Return the record as JSON for the frontend
        echo json_encode($row);
    } else {
        echo json_encode(["success" => false, "message" => "Record not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid parameters."]);
}
?>
