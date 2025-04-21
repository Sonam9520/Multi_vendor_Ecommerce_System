<?php
session_start();  // Ensure session is started
include_once '../includes/header.php';
include_once '../includes/config.php';

// Check if user is logged in as a vendor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor') {
    // header("Location: login.php");
    // exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Retrieve vendor_id based on user_id
$vendor_id = get_vendor_id($user_id);

// Handle product submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && $vendor_id) {
    handle_product_submission($vendor_id);
}

// Fetch vendor products
$products = get_vendor_products($vendor_id);

function get_vendor_id($user_id) {
    global $conn;
    $query = "SELECT v.id FROM vendors v JOIN users u ON u.id = v.user_id WHERE u.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($vendor_id);
        $stmt->fetch();
        return $vendor_id;
    } else {
        return null;
    }
}

function handle_product_submission($vendor_id) {
    global $conn;
    
    $product_name = htmlspecialchars($_POST['product_name']);
    $description = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    
    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif','webp'];

    if (!in_array($image_extension, $allowed_extensions)) {
        return "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }

    $image_new_name = uniqid('', true) . '.' . $image_extension;
    $image_folder = "../uploads/" . $image_new_name;

    if (!is_dir('../uploads')) {
        mkdir('../uploads', 0777, true);  // Create folder if it doesn't exist
    }

    if (move_uploaded_file($image_temp, $image_folder)) {
        $query = "INSERT INTO products (vendor_id, name, description, price, quantity, image, status) 
                  VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issdis", $vendor_id, $product_name, $description, $price, $quantity, $image_new_name);

        if ($stmt->execute()) {
            $success_message = "Product added successfully and is pending approval!";
        } else {
            $error_message = "Error adding product. Please try again.";
        }
    } else {
        $error_message = "Error uploading the image. Please try again.";
    }
}

function get_vendor_products($vendor_id) {
    global $conn;
    $query = "SELECT * FROM products WHERE vendor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $vendor_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Add product</h2>

    <!-- Display success or error messages -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Add Product Form -->
    <h3 class="mb-3">Add Product</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" placeholder="Enter product description" required></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="Enter product price" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter product quantity" required>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" class="form-control-file" id="image" name="image" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block mb-2 my-4" >Add Product</button>
    </form>

    </div>
<?php include '../includes/footer.php'; ?>
