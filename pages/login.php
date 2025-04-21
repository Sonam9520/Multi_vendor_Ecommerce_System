<?php
session_start(); 
// Include necessary files
include '../includes/config.php';  // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if user exists with the provided email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // Bind the email parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables on successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Assuming 'role' column contains values like 'admin', 'vendor', or 'customer'

            // Redirect user based on their role (optional)
            if ($user['role'] == 'admin') {      
                session_start(); 
                header("Location: admin_dashboard.php");  // Redirect admin to admin dashboard
            } elseif ($user['role'] == 'vendor') {
                session_start(); 
                header("Location: vendor_dashboard.php");  // Redirect vendor to vendor dashboard
            } else {
                session_start();  // Start the session at the beginning of the file
                header("Location: index.php");  // Redirect normal user to home page
            }
            exit();
        } else {
            $error = "Invalid login credentials.";
        }
    } else {
        $error = "No user found with that email.";
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
    <title>Login - Multi-Vendor E-Commerce</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Login</h2>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <button type="button" class="btn btn-secondary"><a href="register.php" style="color: white;">Register</a></button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Include jQuery, Popper.js, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
