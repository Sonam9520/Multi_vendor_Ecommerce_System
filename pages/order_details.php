<?php
// include 'includes/config.php';
// include 'includes/header.php';
include '../includes/header.php';
include '../includes/config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view order details.";
    exit;
}

$order_id = $_GET['order_id'];
$orderController = new OrderController($conn);
$orderDetails = $orderController->getOrderDetails($order_id);
?>

<h2>Order Details</h2>
<table>
    <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>

    <?php
    $total_price = 0;
    while ($item = mysqli_fetch_assoc($orderDetails)) {
        $item_total = $item['price'] * $item['quantity'];
        $total_price += $item_total;
    ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td>$<?php echo $item['price']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo $item_total; ?></td>
        </tr>
    <?php } ?>

</table>

<h3>Total Price: $<?php echo $total_price; ?></h3>

<?php
//  include 'includes/footer.php'; 


include '../includes/footer.php'; 
?>
