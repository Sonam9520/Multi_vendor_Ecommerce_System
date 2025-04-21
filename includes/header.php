<?php 
// session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Vendor E-Commerce</title>

    <!-- Link to the custom CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    

    <!-- Add Bootstrap for responsive layout and styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/YOUR_KIT_KEY.js" crossorigin="anonymous"></script>
    <script src="../assets/js/script.js"></script>

    <!-- Custom styles for header and navigation -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        /* Header Styling */
        header {
            background-color:rgb(0, 123, 255); /* Blue background */
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0px;
            padding-right:10px;
        }

        header h1 {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }

        /* Header container for logo and buttons */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        /* Logo Styling */
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            text-decoration: none;
        }

        /* Right aligned buttons */
        .header-buttons {
            display: flex;
            align-items: center;
        }

        .header-buttons a {
            margin-left: 15px;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-btn, .logout-btn {
            background-color: #007bff; /* Blue background */
            color: white;
        }

        .vendor-btn {
            background-color: #28a745; /* Green background */
            color: white;
        }

        .header-buttons a:hover {
            transform: scale(1.1); /* Slightly enlarge the button */
            background-color: #0056b3; /* Darker blue on hover for login/logout */
            color: white;
        }

        .header-buttons a.vendor-btn:hover {
            background-color: #218838; /* Darker green on hover for vendor button */
        }

        /* Sticky Navbar */
        nav {
            background-color: #ffffff; /* White background */
            position: sticky;
            top: 0;
            z-index: 100;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        nav ul li {
            margin: 0 10px;
            position: relative;
        }

        nav ul li a {
            text-decoration: none; /* Hide underline */
            color: #007bff; /* Blue color for links */
            font-size: 17px;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration:none
        }

        nav ul li a:hover {
            background-color: #0056b3; /* Darker blue on hover */
            color: white;
            transform: translateY(-3px); /* Lift effect */
            text-decoration:none;
        }

        nav ul li a:active {
            transform: translateY(2px); /* Slight push down when clicked */
        }

        /* Footer Styling */
        footer {
            background-color: #007bff;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-top: 40px;
        }

        footer a {
            color: white;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Main Header -->
<header>
    <div class="header-container">
        <!-- Logo Section -->
        <a href="../pages/index.php" class="logo"  style='pointer-events: none; '>E-Commerce Store</a>

        <!-- Login/Logout and Vendor Registration buttons -->
        <div class="header-buttons">
            <?php if (isset($_SESSION['user_id'])  ): ?>
                <a href="../pages/logout.php" class="logout-btn" >Logout</a>
            <?php else: ?>
                <a href="../pages/login.php" class="login-btn">Login</a>
            <?php endif; ?>

            <?php if (!isset($_SESSION['user_id']) || isset($_SESSION['role']) || ($_SESSION['role'] == 'admin' ||$_SESSION['role'] == 'admin'  )): ?>
                <a href="../pages/vendor_register.php" class="vendor-btn" style='pointer-events: none; '>Become a Vendor</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Navigation Bar -->
<nav>
    <ul>
        <li><a href="../pages/index.php">Home</a></li>

        <!-- Customer specific menu items -->
         <?php if (isset($_SESSION['user_id'])): ?>
            <!-- <li><a href="../pages/vendor_register.php">Become a Vendor</a></li> -->
            <li><a href="../pages/services.php" style='pointer-events: none; '>Services</a></li>
        <li><a href="../pages/experts.php" style='pointer-events: none; '>Experts</a></li>

        
        <li><a href="../pages/cart.php" style='pointer-events: none; '>Cart</a></li>
        <li><a href="../pages/checkout.php" style='pointer-events: none; '>Checkout</a></li>

            <?php endif; ?>

        <!-- Admin specific menu items -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li><a href="../pages/admin_dashboard.php">Admin Dashboard</a></li>
            <!-- <li><a href="../pages/manage_vendors.php">Manage Vendors</a></li> -->
            <!-- <li><a href="../pages/manage_products.php">Manage Products</a></li> -->
        <?php endif; ?>

        <!-- Vendor specific menu items -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'vendor'): ?>
            <li><a href="../pages/vendor_dashboard.php">Vendor Dashboard</a></li>
            <!-- <li><a href="../pages/add_product.php">Add Product</a></li> -->
            <!-- <li><a href="../pages/manage_vendor_products.php">Manage Products</a></li> -->
        <?php endif; ?>

        <!-- Conditional Login/Logout -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="../pages/logout.php" class="logout-btn">Logout</a></li>
        <?php else: ?>
            <li><a href="../pages/login.php" class="login-btn" >Login</a></li>
        <?php endif; ?>

        <!-- Vendor Registration Link -->
        <!-- <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="../pages/vendor_register.php" class="vendor-btn">Become a Vendor</a></li>
        <?php endif; ?> -->

        <!-- Contact Link -->
        <li><a href="contact.php" > Contact</a></li>
        <li><a href="aboutus.php">About Us</a></li>
    </ul>
</nav>

<!-- Optional Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
