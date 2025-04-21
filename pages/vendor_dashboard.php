<?php
session_start();  // Ensure session is started
ob_start();  
include_once '../includes/header.php';
include_once '../includes/config.php';

// Check if user is logged in as a vendor
if (!isset($_SESSION['user_id']) && $_SESSION['role'] !== 'vendor') { 
    header("Location: login.php");
    exit();
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
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container mt-2">
    <h2 class="text-center mb-4">Vendor Dashboard</h2>

    <!-- Display success or error messages -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Add Product Form -->
    <h3 class="mb-3">Add New Product</h3>
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
            <input type="hidden" name="action" value="add_product">

        <button type="submit" class="btn btn-primary btn-block">Add Product</button>
    </form>

    <!-- Display Vendor Products -->
    <h3 class="mt-5 mb-3">Your Products</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewProductModal" onclick="viewProduct(<?php echo $row['id']; ?>)">View</button>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editProductModal" onclick="editProduct(<?php echo $row['id']; ?>)">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(<?php echo $row['id']; ?>)">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal for viewing product details -->
<div class="modal fade" id="viewProductModal" tabindex="-1" role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProductModalLabel">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modalProductName"></span></p>
                <p><strong>Description:</strong> <span id="modalProductDescription"></span></p>
                <p><strong>Price:</strong> $<span id="modalProductPrice"></span></p>
                <p><strong>Quantity:</strong> <span id="modalProductQuantity"></span></p>
                <p><strong>Status:</strong> <span id="modalProductStatus"></span></p>
                <img id="modalProductImage" src="" alt="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="editProductForm" enctype="multipart/form-data">
                    <input type="hidden" id="product_id" name="product_id">
                    <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" id="product_name1" name="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description1" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price1" name="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity1" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                        <div id="current-image" class="mt-2">
                            <!-- Existing image will be shown here -->
                            <img id="imagePreview" src="default_image_path.jpg" alt="Product Image Preview" style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>

                    <input type="hidden" name="action" value="update_product">
                    <button type="submit" class="btn btn-primary btn-block">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // JavaScript function to load product details in view modal
    function viewProduct(productId) {
        fetch('get_product_details.php?id=' + productId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalProductName').innerText = data.name;
                document.getElementById('modalProductDescription').innerText = data.description;
                document.getElementById('modalProductPrice').innerText = data.price;
                document.getElementById('modalProductQuantity').innerText = data.quantity;
                document.getElementById('modalProductStatus').innerText = data.status;
                document.getElementById('modalProductImage').src = '../uploads/' + data.image;
            });
    }
    function editProduct(productId) {
    console.log("Fetching product with ID:", productId);

    fetch('edit_product.php?id=' + productId)
        .then(response => response.json())  // Parse the response as JSON
        .then(data => {
            if (data.error) {
                console.log("Error from server:", data.error);  // Handle any error messages
                return;
            }

            // Debugging: Log the received product data
            console.log('Product Data:', data);

            // Get the form fields
            const productIdField = document.getElementById('product_id');
            const productNameField = document.getElementById('product_name1');
            const descriptionField = document.getElementById('description1');
            const priceField = document.getElementById('price1');
            const quantityField = document.getElementById('quantity1');
            const imagePreview = document.getElementById('imagePreview'); // <img> tag for image preview

            // Check if the form elements are found
            if (productIdField && productNameField && descriptionField && priceField && quantityField) {
                // Populate the fields with data
                productIdField.value = data.id;
                productNameField.value = data.name;
                descriptionField.value = data.description;
                priceField.value = data.price;
                quantityField.value = data.quantity;

                // Display the image in the image preview
                if (imagePreview) {
                    imagePreview.src = data.image ? '../uploads/' + data.image : 'default_image_path.jpg'; // Fallback image if no image exists
                }
            } else {
                console.error('One or more form elements not found');
            }
        })
        .catch(error => {
            console.error('Error fetching product data:', error);  // Improved error logging
        });
}


    // JavaScript function to delete product
    function deleteProduct(productId) {
        if (confirm("Are you sure you want to delete this product?")) {
            window.location.href = "delete_product.php?id=" + productId;
        }
    }

    $(document).ready(function() {
    // Open Edit Product Modal and Fetch Product Details
    $(".edit-btn").on("click", function() {
        var productId = $(this).data("id");

        // Fetch product data from the server
        $.ajax({
            url: 'get_product_details.php', // PHP file to fetch product details
            type: 'GET',
            data: { id: productId },
            dataType: 'json',
            success: function(data) {
                // Populate modal form with the data
                if (data) {
                    $('#product_id').val(data.id);
                    $('#product_name').val(data.name);
                    $('#description').val(data.description);
                    $('#price').val(data.price);
                    $('#quantity').val(data.quantity);
                    if (data.image) {
                        $('#current-image').html('<img src="../uploads/' + data.image + '" alt="Product Image" class="img-fluid" style="max-width: 200px;">');
                    }
                }
            }
        });
    });

    // Handle form submission for updating product
    $("#editProductForm").on("submit", function(e) {
        e.preventDefault();
        console.log('sdoif io')

        var formData = new FormData(this);  // Use FormData to handle file uploads

        $.ajax({
            url: 'update_product.php',  // PHP file to update the product
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // On success, close the modal and refresh the page
                $('#editProductModal').modal('hide');
                alert('Data Updated Successfully');
                location.reload();
            },
            error: function() {
                alert('Error updating product. Please try again.');
            }
        });
    });
});


</script>

<!-- Add Bootstrap and custom CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    /* Custom CSS for dashboard */
    .container {
        margin-top: 30px;
        max-width: 1200px;
    }
    .alert {
        margin-top: 20px;
    }
    .modal-content {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }


    /* Global Styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc; /* Lighter background */
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        margin-top: 30px;
        max-width: 1200px;
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        animation: slideInFromBottom 0.8s ease-out forwards; /* Animation for container */
    }

    .container h3 {
        font-size: 2.2rem; /* Increased font size for better prominence */
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
        text-align: center;
        opacity: 0;
        animation: fadeIn 1s ease-out forwards; /* Animation for header */
    }

    /* Alert styles */
    .alert {
        padding: 20px;
        border-radius: 10px;
        font-size: 1rem;
        margin-top: 25px;
        display: flex;
        align-items: center;
        opacity: 0;
        animation: fadeInAlert 0.8s ease-out forwards; /* Smooth fade-in for alerts */
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-icon {
        margin-right: 15px;
        font-size: 1.4rem;
    }

    /* Form styles */
    .form-group {
        margin-bottom: 10px;
    }

    
    /* Modal styles */
    .modal-content {
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        animation: modalSlideIn 0.6s ease-out forwards; /* Modal slide in animation */
    }

    .modal-header {
        border-bottom: 1px solid #ddd;
        margin-bottom: 0px;
    }

    .modal-title {
        font-size: 1.7rem;
        font-weight: 600;
        color: #333;
    }

    /* Image preview styles */
    .img-fluid {
        max-width: 250px;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease; /* Smooth zoom effect */
    }

    .img-fluid:hover {
        transform: scale(1.05); /* Zoom effect on hover */
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInFromBottom {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInAlert {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Responsive Design for Mobile */
    @media (max-width: 767px) {
        .container {
            margin: 15px;
            padding: 20px;
        }

        .container h3 {
            font-size: 1.8rem;
        }

        .btn {
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control-file {
            padding: 10px;
        }
    }
</style>


