<?php
session_start();
ob_start(); // Start output buffering

include '../includes/config.php';
include '../includes/header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to complete your order.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items from the database (including product price)
$query = "SELECT c.product_id, c.quantity, p.price 
          FROM cart c 
          JOIN products p ON c.product_id = p.id
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the cart is empty
if ($result->num_rows == 0) {
    echo "Your cart is empty. Please add items to your cart.";
    exit;
}

$cartItems = $result->fetch_all(MYSQLI_ASSOC);

// Calculate the total price of the items in the cart
$total_price = 0;
foreach ($cartItems as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Insert the order into the orders table
$orderQuery = "INSERT INTO orders (user_id, total_price, status, created_at) 
               VALUES (?, ?, ?, NOW())";
$status = 'pending'; // Set the order status to 'pending' initially
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("ids", $user_id, $total_price, $status);

if (!$stmt->execute()) {
    echo "Error placing order. Please try again.";
    exit;
}

// Get the last inserted order_id
$order_id = $stmt->insert_id;

// Step 1: Insert order items into the order_items table
foreach ($cartItems as $item) {
    // Insert each item into the order_items table
    $itemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                  VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($itemQuery);
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    
    if (!$stmt->execute()) {
        echo "Error inserting order items. Please try again.";
        exit;
    }
}

// Step 2: Clear the cart after placing the order
$clearCartQuery = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($clearCartQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Display success message with an attractive check mark and delivery details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            margin-top: 50px;
        }
        .success-message {
            background-color: #28a745;
            color: white;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
            text-align: center;
        }
        .success-message h2 {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .success-message p {
            font-size: 1.2em;
            margin: 15px 0;
        }
        .check-icon {
            font-size: 3em;
            margin-bottom: 20px;
        }
        .order-details {
            margin-top: 30px;
            font-size: 1.1em;
        }
        .order-details b {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="success-message">
    <div class="check-icon">
        <i class="fas fa-check-circle"></i> <!-- Font Awesome check icon -->
    </div>
    <h2>Order Placed Successfully!</h2>
    <p>Thank you for shopping with us! Your order is now being processed.</p>
    <p>You'll receive an email with the delivery details shortly.</p>
    <div class="order-details">
        <p><b>Order ID:</b> <?php echo $order_id; ?></p>
        <p><b>Total Amount:</b> $<?php echo number_format($total_price, 2); ?></p>
        <p><b>Order Status:</b> Pending</p>
    </div>
</div>

</body>
</html>

<?php
include '../includes/footer.php';
ob_end_flush(); // Flush the output buffer and turn off output buffering
?>
