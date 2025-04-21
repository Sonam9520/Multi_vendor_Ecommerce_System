<?php

session_start();
include '../includes/header.php';
include '../includes/config.php';


if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger text-center'>Please log in to checkout.</div>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items directly using basic PHP and MySQL
$query = "SELECT products.name, products.price, cart.quantity 
          FROM cart
          INNER JOIN products ON cart.product_id = products.id
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$cartItems = $stmt->get_result();

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding-top: 30px;
        }

        .checkout-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }

        table th, table td {
            text-align: center;
            padding: 12px;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-confirm {
            width: 100%;
            background-color: #28a745;
            border: none;
            color: white;
            padding: 15px;
            font-size: 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-confirm:hover {
            background-color: #218838;
        }

        /* Animation for the checkout container */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h2 class="text-center mb-4">Checkout</h2>
    <form method="POST" action="order_confirmation.php">
        <h3>Items:</h3>
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cart_item = $cartItems->fetch_assoc()) {
                    $item_total = $cart_item['price'] * $cart_item['quantity'];
                    $total_price += $item_total;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cart_item['name']); ?></td>
                        <td>$<?php echo number_format($cart_item['price'], 2); ?></td>
                        <td><?php echo $cart_item['quantity']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="total-price">
            <p>Total Price: $<?php echo number_format($total_price, 2); ?></p>
        </div>

        <input type="submit" value="Confirm Order" class="btn-confirm">
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
include '../includes/footer.php';
?>
