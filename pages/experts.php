<?php
session_start();
include '../includes/header.php';  // Include header with navigation
include '../includes/config.php';  // Include DB connection

// Fetch vendor details along with associated products
$query = "
    SELECT vendors.*, products.name AS product_name
    FROM vendors
    JOIN products ON vendors.id = products.vendor_id
    JOIN users ON vendors.user_id = users.id
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experts</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Styling for the hero section */
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

        /* Styling the expert card */
        .expert-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .expert-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .expert-img {
            height: 150px;  /* Reduced image height */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
            color: #6c757d;
            background-color: #f0f0f0;
            transition: transform 0.3s ease;
        }

        .expert-card:hover .expert-img {
            transform: scale(1.05);
        }

        .expert-title {
            font-size: 1.4rem;
            color: #007bff;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .expert-description {
            color: #555;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .vendor-name {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .active-users {
            font-size: 0.95rem;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }

        .rating {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #f39c12;
        }

        .rating i {
            margin-right: 5px;
        }

        .favorite {
            font-size: 1.5rem;
            color: #ff6347;
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .favorite.liked {
            color: #e74c3c;
            transform: scale(1.2);
        }

        /* Add smooth fade-in effect for the card container */
        .expert-card-container {
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .expert-card-container.visible {
            opacity: 1;
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .hero h2 {
                font-size: 2.5rem;
            }

            .expert-card {
                margin-bottom: 15px;
            }

            .expert-title {
                font-size: 1.2rem;
            }

            .expert-description {
                font-size: 0.85rem;
            }
        }
        .expert-img {
    height: 150px;  /* Reduced image height */
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 3rem;
    color: #6c757d;
    background-color: #f0f0f0;
    transition: transform 0.3s ease;
    position: relative;  /* Allow absolute positioning of icon */
}

.expert-img i {
    font-size: 5rem;  /* Increased icon size */
    position: absolute;  /* Position icon absolutely within the container */
    top: 50%;  /* Center vertically */
    left: 50%;  /* Center horizontally */
    transform: translate(-50%, -50%);  /* Offset the icon from its own center */
    color: #6c757d;  /* Ensure icon color matches */
}

.expert-card:hover .expert-img {
    transform: scale(1.05);
}

    </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero">
    <div class="container text-center">
        <h2 class="mt-5">Our Top Experts</h2>
        <p class="lead">Meet the professionals who are in high demand and making a difference!</p>
    </div>
</section>

<!-- Expert Section -->
<section id="experts" class="container mt-5">
    <div class="row">
        <?php
        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            // Set default values in case data is missing
            $vendor_name = !empty($row['vendor_name']) ? $row['vendor_name'] : 'Unknown Vendor';
            $vendor_description = !empty($row['vendor_description']) ? $row['vendor_description'] : 'No description available.';
            $contact_email = !empty($row['contact_email']) ? $row['contact_email'] : 'Not available';
            $image = !empty($row['image']) ? $row['image'] : ''; // Empty if no image exists

            // Generate a random number of active users between 50 and 200
            $activeUsers = rand(50, 200);
            $rating = rand(3, 5) . '.0'; // Random rating between 3.0 and 5.0
            $delay = $count * 0.2; // Add delay for animation
            echo '<div class="col-md-4 col-sm-6 mb-4 expert-card-container" style="animation-delay: ' . $delay . 's;">';
            ?>
                <div class="card expert-card shadow-sm">
                    <!-- Check if vendor image exists, otherwise use FontAwesome user icon -->
                    <div class="expert-img">
                        <?php echo empty($image) ? '<i class="fas fa-user"></i>' : '<img src="../uploads/'.$image.'" alt="'.$vendor_name.'" class="img-fluid">'; ?>
                    </div>
                    <div class="card-body position-relative">
                        <h5 class="card-title expert-title"><?= $vendor_name; ?></h5>
                        <p class="card-text expert-description"><?= substr($vendor_description, 0, 100) . '...'; ?></p>
                        <p class="vendor-name"><strong>Email: </strong><?= $contact_email; ?></p>
                        <p class="active-users">Active Users: <?= $activeUsers; ?></p>
                        <div class="rating">
                            <i class="fas fa-star"></i><span><?= $rating; ?> Rating</span>
                        </div>
                        <span class="favorite" onclick="toggleFavorite(this)">
                            <i class="fas fa-heart"></i>
                        </span>
                        <a href="expert-detail.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-block mt-3">View Profile</a>
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
    // Add the 'visible' class to expert cards when they enter the viewport
    $(document).ready(function () {
        var expertCards = $('.expert-card-container');
        var windowHeight = $(window).height();

        function checkVisibility() {
            expertCards.each(function () {
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
