<?php
session_start();
include '../includes/header.php';
include '../includes/config.php';
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "
    <div style='text-align: center; margin-top: 50px;'>
        <div style='display: inline-block; background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 10px; border: 1px solid #f5c6cb;'>
            <h3 style='font-size: 1.5em;'>Oops! You're not logged in.</h3>
            <p style='font-size: 1.1em;'>Please <a href='login.php' style='color: #004085; text-decoration: underline;'>log in</a> to add items to your cart.</p>
        </div>
    </div>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle adding product to cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Check if product exists in the database
    $product_query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        // Check if product is already in the cart
        $cart_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $cart_stmt = $conn->prepare($cart_query);
        $cart_stmt->bind_param('ii', $user_id, $product_id);
        $cart_stmt->execute();
        $cart_item = $cart_stmt->get_result()->fetch_assoc();

        if ($cart_item) {
            // Update quantity if product already in cart
            $new_quantity = $cart_item['quantity'] + 1;
            $update_query = "UPDATE cart SET quantity = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('ii', $new_quantity, $cart_item['id']);
            $update_stmt->execute();
        } else {
            // Add new product to cart
            $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param('ii', $user_id, $product_id);
            $insert_stmt->execute();
        }
    }
}

// Fetch cart items
$query = "SELECT cart.id AS cart_id, products.name, products.price, cart.quantity 
          FROM cart 
          INNER JOIN products ON cart.product_id = products.id 
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$cartItems = $stmt->get_result();

// Update or remove items from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    if ($quantity > 0) {
        $update_query = "UPDATE cart SET quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('ii', $quantity, $cart_id);
        $stmt->execute();
    } else {
        $delete_query = "DELETE FROM cart WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
    }
}

// Calculate total price
$total_price = 0;
$cartItemsArray = [];
while ($cart_item = $cartItems->fetch_assoc()) {
    $item_total = $cart_item['price'] * $cart_item['quantity'];
    $total_price += $item_total;
    $cartItemsArray[] = $cart_item;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 10px;
        }
        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .total {
            text-align: right;
            padding-right: 10%;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .product {
            width: 250px;
            padding: 10px;
            margin: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        .product p {
            margin: 5px 0;
        }
        .availability {
            color: #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Your Shopping Cart</h2>
<table>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <!-- <th>Quantity</th> -->
        <th>Total</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($cartItemsArray as $cart_item) { 
        $item_total = $cart_item['price'] * $cart_item['quantity'];
        ?>
        <tr>
            <td><?php echo htmlspecialchars($cart_item['name']); ?></td>
            <td>$<?php echo number_format($cart_item['price'], 2); ?></td>
            
            <td>$<?php echo number_format($item_total, 2); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="cart_id" value="<?php echo $cart_item['cart_id']; ?>">
                    <!-- <input type="hidden" name="quantity" value="0"> -->
                    <button type="submit" name="update_quantity">Remove</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<div class="total">
    <p>Total Price: $<?php echo number_format($total_price, 2); ?></p>
</div>

<form method="POST" action="checkout.php" style="text-align: center;">
    <button type="submit">Proceed to Checkout</button>
</form>

<hr>

<h3 style='text-align:center;margin-top:50px;font-weight:600;'>Available Products</h3>
<div class="products">
    <?php
    $product_query = "SELECT * FROM products";
    $stmt = $conn->prepare($product_query);
    $stmt->execute();
    $products = $stmt->get_result();

    while ($product = $products->fetch_assoc()) {
        ?>
        <div class="product">
            <p><?php echo htmlspecialchars($product['name']); ?></p>
            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
            <p class="availability">Hurry up! Only <?= rand(1, 20); ?> items left</p>

            <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn">Add to Cart</a>
        </div>
        <?php
    }
    ?>
</div>

</body>
</html>
