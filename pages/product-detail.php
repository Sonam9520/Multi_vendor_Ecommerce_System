<?php
session_start();
include '../includes/header.php';
include '../includes/config.php';

// Fetch product details based on ID
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    echo "<p>Invalid product ID.</p>";
    exit;
}

$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

// Fetch a random vendor name
$vendor_query = "SELECT vendor_name FROM vendors ORDER BY RAND() LIMIT 1";
$vendor_result = $conn->query($vendor_query);
$vendor = $vendor_result->fetch_assoc();

// Fetch recommended products (limit to 6 for carousel display)
$recommended_query = "SELECT * FROM products ";
$recommended_result = $conn->query($recommended_query);
?>
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }

        .container:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .product-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .product-image {
            flex: 1;
            padding: 15px;
            text-align: center;
        }

        .product-image img {
            width: 90%;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .product-image img:hover {
            transform: scale(1.05);
        }

        .product-info {
            flex: 1;
            padding: 20px;
        }

        .product-info h1 {
            color: #4a148c;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .price {
            color: #4caf50;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 15px 0;
        }

        .availability {
            color: #e53935;
            font-size: 1.1rem;
            margin: 15px 0;
        }

        .btn {
            display: inline-block;
            background-color: #4caf50;
            color: white;
            padding: 12px 25px;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #388e3c;
        }

        .details-section {
            margin-top: 40px;
        }

        .details-section h2 {
            color: #4a148c;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .reviews {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .review-card {
            background-color: #fff;
            border-radius: 5px;
            padding: 15px;
            width: 30%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-10px);
        }

        .review-card p {
            font-size: 0.95rem;
            margin: 8px 0;
        }

        .carousel-item {
            display: flex;
            justify-content: space-between;
            transition: transform 0.3s ease;
        }

        .product-card {
            background-color: #f4f4f4;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }

        .product-card img:hover {
            transform: scale(1.05);
        }

        .product-card h5 {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .product-card p {
            color: #4caf50;
            font-size: 1rem;
        }

        /* .carousel-control-prev, .carousel-control-next {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
        } */

        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: black;
        }

        /* Smooth transition for carousel */
        .carousel-inner {
            transition: transform 0.5s ease;
        }
        <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f7f6;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease-in-out;
    }

    .container:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
    }

    /* Header Styles */
    .header {
        background-color: #007bff; /* Primary blue color */
        color: #fff;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header .links a {
        color: #fff;
        margin-left: 15px;
        text-decoration: none;
        font-weight: bold;
    }

    .product-section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .product-image {
        flex: 1;
        padding: 15px;
        text-align: center;
    }

    .product-image img {
        width: 90%;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .product-image img:hover {
        transform: scale(1.05);
    }

    .product-info {
        flex: 1;
        padding: 20px;
    }

    .product-info h1 {
        color: #007bff; /* Primary blue color */
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .price {
        color: #28a745; /* Green color for price */
        font-size: 1.5rem;
        font-weight: bold;
        margin: 15px 0;
    }

    .availability {
        color: #e53935; /* Red color for urgency */
        font-size: 1.1rem;
        margin: 15px 0;
    }

    .btn {
        display: inline-block;
        background-color: #007bff; /* Primary blue color */
        color: white;
        padding: 12px 25px;
        text-align: center;
        border: none;
        border-radius: 5px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    .details-section {
        margin-top: 40px;
    }

    .details-section h2 {
        color: #007bff; /* Primary blue color */
        font-size: 1.8rem;
        margin-bottom: 15px;
    }

    .reviews {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .review-card {
        background-color: #fff;
        border-radius: 5px;
        padding: 15px;
        width: 30%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-10px);
    }

    .review-card p {
        font-size: 0.95rem;
        margin: 8px 0;
    }

    .carousel-item {
        display: flex;
        justify-content: space-between;
        transition: transform 0.3s ease;
    }

    .product-card {
        background-color: #f4f4f4;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        width: 100%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-card img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: transform 0.3s ease;
    }

    .product-card img:hover {
        transform: scale(1.05);
    }

    .product-card h5 {
        font-size: 1.1rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .product-card p {
        color: #28a745; /* Green color for price */
        font-size: 1rem;
    }

    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: #007bff; /* Primary blue color for control icons */
    }

    .carousel-inner {
        transition: transform 0.5s ease;
    }
</style>

    </style>
</head>
<body>

<div class="container">
    <div class="product-section">
        <div class="product-image">
            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <div class="product-info container">
            <h1 style='color:black;font-weight:bolder;'><?php echo ucwords(htmlspecialchars($product['name'])); ?></h1>
            <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
            <p class="availability">Hurry up! Only 4 items left</p>
            <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn">Add to Cart</a>
        </div>
    </div>

    <div class="details-section" >
        <div class='container'> 
        <h2>Detailed Description</h2>
        <p><?php echo nl2br(ucwords(htmlspecialchars($product['description']))); ?></p>

        </div>

        <h2>Reviews</h2>
        <div class="reviews container">
            <div class="review-card">
                <p><strong><?php echo ucwords($vendor['vendor_name']); ?></strong></p>
                <p>⭐⭐⭐⭐</p>
                <p>Great product, would buy again!</p>
            </div>

            <div class="review-card">
                <p><strong><?php echo ucwords($vendor['vendor_name']); ?></strong></p>
                <p>⭐⭐⭐</p>
                <p>Quality is good, but the size was too big for me.</p>
            </div>

            <div class="review-card">
                <p><strong><?php echo ucwords($vendor['vendor_name']); ?></strong></p>
                <p>⭐⭐⭐⭐⭐</p>
                <p>Excellent quality, highly recommend!</p>
            </div>
        </div>
        
        <h2>Recommended Products</h2>
        <div id="recommendedProductsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $counter = 0;
                while ($recommended_product = $recommended_result->fetch_assoc()):

                    // Start a new carousel item after every 3 products
                    if ($counter % 3 == 0) {
                        if ($counter > 0) {
                            echo '</div>';  // Close the previous row
                        }
                        echo '<div class="carousel-item' . ($counter == 0 ? ' active' : '') . '">';
                        echo '<div class="row">';  // Start a new row
                    }
                    ?>

                    <div class="col-12 col-md-4">
                        <div class="product-card">
                            <img src="../uploads/<?php echo $recommended_product['image']; ?>" alt="<?php echo $recommended_product['name']; ?>">
                            <h5><?php echo ucwords($recommended_product['name']); ?></h5>
                            <p>$<?php echo number_format($recommended_product['price'], 2); ?></p>
                        </div>
                    </div>

                    <?php
                    // Close the row after 3 products
                    if ($counter % 3 == 2) {
                        echo '</div>';  // Close the row
                    }
                    $counter++;
                endwhile;
                ?>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#recommendedProductsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#recommendedProductsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

</body>
</html>
