<?php
// include 'includes/config.php';
// include 'includes/header.php';
include '../includes/header.php';
include '../includes/config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your order history.";
    exit;
}

$user_id = $_SESSION['user_id'];
$orderController = new OrderController($conn);
$orders = $orderController->getOrdersByUser($user_id);
?>

<h2>Your Order History</h2>
<table>
    <tr>
        <th>Order ID</th>
        <th>Total Price</th>
        <th>Order Date</th>
        <th>Details</th>
    </tr>

    <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td>$<?php echo $order['total_price']; ?></td>
            <td><?php echo $order['order_date']; ?></td>
            <td><a href="order_details.php?order_id=<?php echo $order['id']; ?>">View Details</a></td>
        </tr>
    <?php } ?>

</table>

<?php 

// include 'includes/footer.php'; 

include '../includes/footer.php'; 
?>
