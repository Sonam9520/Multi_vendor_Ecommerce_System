<?php
session_start();

// Start output buffering at the very beginning of the script
ob_start();

include '../includes/header.php';  
include '../includes/config.php';  // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'customer';  // Assuming customer role for now

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        $error_message = "The email address is already registered. Please use a different email.";
    } else {
        // Insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password

        $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $hashed_password, $role);  // Bind parameters
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Registration successful
            header("Location: login.php");  // Redirect to the login page
            exit();
        } else {
            // Something went wrong
            $error_message = "There was an error during registration. Please try again.";
        }
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Multi-Vendor E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Register</h2>

    <?php
    if (isset($error_message)) {
        echo '<div class="alert alert-danger">' . $error_message . '</div>';
    }
    ?>

    <form method="POST" class="mt-4">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <button type="button" class="btn btn-secondary"><a href="login.php" style="color: white;">login</a></button>
    </form>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Flush the output buffer
ob_end_flush();
?>
