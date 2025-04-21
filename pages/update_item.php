<?php
// Include database connection
include '../includes/config.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$table = isset($_POST['table']) ? $_POST['table'] : '';

// Check if the ID and table are provided
if ($id && $table) {
    // Sanitize the table name to prevent SQL injection
    $table = mysqli_real_escape_string($conn, $table);

    // Start constructing the SET clause
    $setClause = '';

    // Loop through all the POST data, excluding 'id' and 'table' as they are not fields for updating
    foreach ($_POST as $key => $value) {
        // Exclude sensitive fields like 'id', 'table', 'password', 'image'
        if ($key !== 'id' && $key !== 'table' && $key !== 'password' && $key !== 'image') {
            // Sanitize the value before appending to the SET clause
            $setClause .= "$key = '" . mysqli_real_escape_string($conn, $value) . "', ";
        }
    }

    // Handle image upload (if a new image is provided)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Ensure the uploaded file is an image
        $allowedExtensions =['jpg', 'jpeg', 'png', 'gif','webp'];
        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid image format. Only JPG, JPEG, PNG, GIF are allowed.']);
            exit;
        }

        // Generate a unique name for the uploaded image
        $imagePath = '../uploads/' . uniqid('img_', true) . '.' . $fileExtension;

        // Ensure that the file is moved to the desired location
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            // Add the image to the SET clause
            $setClause .= "image = '" . mysqli_real_escape_string($conn, $imagePath) . "', ";
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit;
        }
    }

    // Handle password update (if provided)
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        // Hash the password before saving it
        $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $setClause .= "password = '$passwordHash', ";
    }

    // Handle role update (if provided)
    if (isset($_POST['role'])) {
        // Sanitize and update the role field
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $setClause .= "role = '$role', ";
    }

    // Clean up trailing commas from the SET clause
    $setClause = rtrim($setClause, ', ');

    // Build the full update query
    $query = "UPDATE $table SET $setClause WHERE id = $id";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Return success response
        echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
    } else {
        // Return error response
        echo json_encode(['success' => false, 'message' => 'Failed to update record: ' . mysqli_error($conn)]);
    }
} else {
    // Return error if invalid parameters
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
}
?>
