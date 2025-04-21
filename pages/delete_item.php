<?php
include '../includes/config.php';

// Get the ID and table name from the request
$id = $_GET['id'];
$table = $_GET['table'];

// Check if the ID is valid and table exists
if (!isset($id) || !isset($table)) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID or table.']);
    exit;
}

// Delete the record from the database
$query = "DELETE FROM $table WHERE id = $id";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
}
?>
