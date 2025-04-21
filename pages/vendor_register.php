<?php
session_start();
// Start output buffering to prevent headers already sent error
ob_start();

include_once '../includes/header.php';  
include_once '../includes/config.php'; // Include the database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email
    $password = $_POST['password'];
    $vendor_name = $_POST['vendor_name'];
    $contact_phone = $_POST['contact_phone'];
    $role = 'vendor'; // Assuming 'vendor' role

    // Password hashing for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Default vendor status (e.g., active or pending)
    $vendor_status = 'pending';

    // Step 1: Check if the vendor already exists by phone number or email
    $check_vendor_sql = "SELECT * FROM vendors WHERE contact_phone = ? OR contact_email = ?";
    if ($stmt = $conn->prepare($check_vendor_sql)) {
        $stmt->bind_param("ss", $contact_phone, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Vendor with this phone or email already exists, show error message
            $error_message = "You are already registered as a vendor with this contact phone or email.";
        } else {
            // Step 2: Insert user details if vendor is not already registered
            $user_sql = "UPDATE users SET email = ?, password = ?, role = ? WHERE email = ?";
            if ($user_stmt = $conn->prepare($user_sql)) {
                $user_stmt->bind_param("ssss", $email, $hashed_password, $role, $email);
                if ($user_stmt->execute()) {
                    // Step 3: Insert the vendor details into the 'vendors' table
                    $vendor_sql = "INSERT INTO vendors (user_id, vendor_name, contact_email, contact_phone, vendor_status)
                                   SELECT id, ?, ?, ?, ? FROM users WHERE email = ?";
                    if ($vendor_stmt = $conn->prepare($vendor_sql)) {
                        $vendor_stmt->bind_param("sssss", $vendor_name, $email, $contact_phone, $vendor_status, $email);
                        if ($vendor_stmt->execute()) {
                            ?>
                            <script>
                                location.reload();
                            </script>
                            
                            <?php
                            // Vendor registration successful, redirect to the vendor dashboard
                            // header("Location: vendor_dashboard.php");  // Redirect to vendor dashboard
                            header("Location: login.php");
                            session_destroy();
                            exit();  // Make sure to exit after header redirect
                        } else {
                            $error_message = "There was an error registering the vendor. Please try again.";
                        }
                        $vendor_stmt->close();
                    } else {
                        $error_message = "Database error: Could not prepare the vendor insert query.";
                    }
                } else {
                    $error_message = "There was an error updating the user. Please try again.";
                }
                $user_stmt->close();
            } else {
                $error_message = "Database error: Could not prepare the user update query.";
            }
        }
        $stmt->close();
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Register as a Vendor</h2>

    <?php
    // Display error message if any
    if (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>

    <form method="POST">
        <div class="form-group">
            <label for="vendor_name">Vendor Name</label>
            <input type="text" class="form-control" id="vendor_name" name="vendor_name" placeholder="Enter vendor name" required>
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
        </div>

        <div class="form-group">
            <label for="contact_phone">Contact Phone</label>
            <input type="text" class="form-control" id="contact_phone" name="contact_phone" placeholder="Enter contact phone" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Register as Vendor</button>
    </form>

    <div class="text-center mt-3">
        <p>Already a vendor? <a href="../pages/login.php">Login here</a></p>
    </div>
</div>

<?php include '../includes/footer.php'; 

// Flush the output buffer
ob_end_flush(); ?>
