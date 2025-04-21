<?php
session_start();
include '../includes/header.php';  // Include header with navigation
include '../includes/config.php';  // Include DB connection

// Fetch products and vendor details from the database
$query = "
    SELECT products.*, vendors.vendor_name
    FROM products
    JOIN vendors ON products.vendor_id = vendors.id
";  // Only display published products
// WHERE products.status = 'published'
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Styling for the hero section heading */
        .hero h2 {
            font-size: 3rem;
            font-weight: 600;
            color: #2a3d66;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
        }

        .hero h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: #007bff;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.4s ease-in-out;
        }

        .hero h2:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .service-card {
            border: 1px solid #ddd;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .service-img {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .service-card:hover .service-img {
            transform: scale(1.1);
        }

        .service-title {
            font-size: 1.6rem;
            color: #007bff;
            font-weight: 600;
            margin-bottom: 15px;
            transition: color 0.3s ease;
        }

        .service-card:hover .service-title {
            color: #0056b3;
        }

        .service-description {
            color: #555;
            margin-bottom: 20px;
        }

        .vendor-name {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .rating {
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: #f39c12;
            margin-bottom: 15px;
        }

        .rating i {
            margin-right: 5px;
        }

        .favorite {
            font-size: 1.8rem;
            color: #ff6347;
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .favorite.liked {
            color: #e74c3c;
            transform: scale(1.2);
        }

        .service-card-container {
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .service-card-container.visible {
            opacity: 1;
        }

        /* Hover effect for the view details button */
        .btn-primary {
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero">
    <div class="container text-center">
        <h2 class="mt-5">Our Premium Services</h2>
        <p class="lead">Browse through our wide range of services offered by our talented vendors.</p>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="container mt-5">
    <div class="row">
        <?php
        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            // Add visibility class with delay for animation effect
            $delay = $count * 0.2; // Add delay for animation
            echo '<div class="col-md-4 mb-4 service-card-container" style="animation-delay: ' . $delay . 's;">';
            ?>
                <div class="card service-card shadow-sm">
                    <img src="../uploads/<?= $row['image']; ?>" class="card-img-top service-img" alt="<?= $row['name']; ?>">
                    <div class="card-body position-relative">
                        <h5 class="card-title service-title"><?= $row['name']; ?></h5>
                        <p class="card-text service-description"><?= substr($row['description'], 0, 100) . '...'; ?></p>
                        <p class="vendor-name"><strong>Vendor: </strong><?= $row['vendor_name']; ?></p>
                        <!-- Dummy Rating -->
                        <div class="rating">
                            <i class="fas fa-star"></i><span>4.5</span> <!-- Change the rating dynamically if needed -->
                        </div>
                        <span class="favorite" onclick="toggleFavorite(this)">
                            <i class="fas fa-heart"></i>
                        </span>
                        <a href="product-detail.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-block mt-3">View Details</a>
                    </div>
                </div>
            </div>
            <?php
            $count++;
        }
        ?>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Add the 'visible' class to service cards when they enter the viewport
    $(document).ready(function () {
        var serviceCards = $('.service-card-container');
        var windowHeight = $(window).height();

        function checkVisibility() {
            serviceCards.each(function () {
                var cardTop = $(this).offset().top;
                if (cardTop < windowHeight * 0.8 + $(window).scrollTop()) {
                    $(this).addClass('visible');
                }
            });
        }

        $(window).on('scroll', checkVisibility);
        checkVisibility(); // Run it on page load
    });

    // Favorite icon functionality
    function toggleFavorite(icon) {
        icon.classList.toggle('liked');
    }
</script>

<?php include '../includes/footer.php'; ?>

</body>
</html>
