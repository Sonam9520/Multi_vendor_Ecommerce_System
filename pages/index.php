<?php
session_start();
include '../includes/header.php';  // Include header with navigation
include '../includes/config.php';  // Include DB connection

// Fetch products from the database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Multi-Vendor E-Commerce</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">  <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            font-weight: 600;
            color: #343a40;
            transition: color 0.3s;
        }

        h2:hover {
            color: #007bff;
        }

        /* Hero Section */
        .carousel-item img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }


        
        .carousel-caption {
            text-align: center;
            padding: 30px;
            /* background-color: rgba(0, 0, 0, 0.5); Adjusted to make text more visible */
        }

        .carousel-caption .btn {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            padding: 12px 30px;
            transition: background-color 0.3s ease;
        }

        .carousel-caption .btn:hover {
            background-color: #0056b3;
        }

        /* How We Work Section */
        .how-we-work {
            background-color: #e9f7fe;
            padding: 60px 0;
        }

        .how-we-work h2 {
            font-size: 2rem;
            margin-bottom: 40px;
            color: #343a40;
            text-align: center;
        }

        .how-we-work .step {
            margin-top: 30px;
            text-align: center;
        }

        .how-we-work h4 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #007bff;
        }

        .how-we-work p {
            font-size: 1rem;
            color: #777;
        }

        /* Product Cards */
        #products {
            margin-top: 60px;
            padding-bottom: 60px;
        }

        #products h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Bootstrap Grid System for Products */
        .product-card {
            width: 100%;
            max-width: 18rem;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .product-card .card-body {
            padding: 15px;
            text-align: center;
        }

        .product-card .card-title {
            font-size: 1.25rem;
            color: #343a40;
            font-weight: 600;
        }

        .product-card .card-text {
            color: #777;
            margin-bottom: 10px;
        }

        .product-card .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 30px;
            transition: background-color 0.3s;
        }

        .product-card .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Animation for product card */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 767px) {
            .product-card {
                margin-bottom: 20px;
            }

            .product-card img {
                height: 200px;
            }

            #products h2 {
                font-size: 1.8rem;
            }

            .product-card .card-body {
                padding: 15px;
            }

            .product-card .card-text {
                font-size: 0.9rem;
            }
        }


        /* Become a Vendor Section Styles */
#become-vendor {
    background-color: #e9f7fe;
    padding: 80px 0;
    font-family: 'Poppins', sans-serif;
}

#become-vendor h2 {
    font-size: 2.5rem;
    color: #343a40;
    margin-bottom: 40px;
    font-weight: 600;
}

.vendor-description {
    font-size: 1.2rem;
    color: #777;
    line-height: 1.6;
    margin-bottom: 30px;
}

#become-vendor .btn-primary {
    font-size: 1.1rem;
    padding: 15px 30px;
    border-radius: 30px;
    text-transform: uppercase;
    font-weight: bold;
    background-color: #007bff;
    border: none;
    transition: background-color 0.3s;
}

#become-vendor .btn-primary:hover {
    background-color: #0056b3;
}

#become-vendor .row {
    display: flex;
    align-items: center;
}

#become-vendor .col-md-6 {
    margin-bottom: 20px;
}

@media (max-width: 767px) {
    #become-vendor h2 {
        font-size: 2rem;
    }

    .vendor-description {
        font-size: 1rem;
    }
}
  /* Global Styles */
  body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            font-weight: 600;
            color: #343a40;
            transition: color 0.3s;
        }

        h2:hover {
            color: #007bff;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 30px;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Hero Section */
        .carousel-item img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .carousel-caption {
            text-align: center;
            padding: 30px;
        }

        /* How It Works Section */
        .how-we-work {
            background-color: #e9f7fe;
            padding: 60px 0;
        }

        .how-we-work h2 {
            font-size: 2rem;
            margin-bottom: 40px;
            color: #343a40;
            text-align: center;
        }

        .how-we-work .step {
            margin-top: 30px;
            text-align: center;
        }

        .how-we-work h4 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #007bff;
        }

        .how-we-work p {
            font-size: 1rem;
            color: #777;
        }

        /* Product Cards */
        #products {
            margin-top: 60px;
            padding-bottom: 60px;
        }

        #products h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Product Card Styles */
        .product-card {
            width: 100%;
            max-width: 18rem;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .product-card .card-body {
            padding: 15px;
            text-align: center;
        }

        .product-card .card-title {
            font-size: 1.25rem;
            color: #343a40;
            font-weight: 600;
        }

        .product-card .card-text {
            color: #777;
            margin-bottom: 10px;
        }

        .product-card .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 30px;
        }

        .product-card .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Animation for product card */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Vendor Section */
        #become-vendor {
            background-color: #e9f7fe;
            padding: 80px 0;
        }

        #become-vendor h2 {
            font-size: 2.5rem;
            color: #343a40;
            margin-bottom: 40px;
        }

        .vendor-description {
            font-size: 1.2rem;
            color: #777;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        #become-vendor .row {
            display: flex;
            align-items: center;
        }

        @media (max-width: 767px) {
            #become-vendor h2 {
                font-size: 2rem;
            }

            .vendor-description {
                font-size: 1rem;
            }

            .product-card {
                margin-bottom: 20px;
            }

            .product-card img {
                height: 200px;
            }

            #products h2 {
                font-size: 1.8rem;
            }

            .product-card .card-body {
                padding: 15px;
            }

            .product-card .card-text {
                font-size: 0.9rem;
            }
        }

        /* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    color: #343a40;
    overflow-x: hidden;
}

/* Header Styles */
h2 {
    font-size: 2.4rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
    transition: color 0.3s ease;
}

h2:hover {
    color: #007bff;
}

/* Hero Section */
.carousel-item img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    filter: brightness(90%); /* Darkens the image for better text visibility */
    transition: transform 0.3s ease;
}

.carousel-caption {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    padding: 10px;
    /* background: rgba(0, 0, 0, 0.5); Slightly dark background */
    border-radius: 10px;
    animation: fadeIn 1.5s ease-in-out;
}

.carousel-caption h1 {
    font-size: 2.6rem;
    font-weight: 800;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
    animation: bounceIn 2s ease-in-out;
}

.carousel-caption p {
    font-size: 1.3rem;
    font-weight: 400;
    margin-bottom: 10px;
    line-height: 1.5;
    opacity: 0.9;
    animation: slideIn 2.5s ease-in-out;
}

.carousel-caption .btn {
    font-size: 1.2rem;
    padding: 15px 30px;
    border-radius: 30px;
    background-color: #007bff;
    color: #fff;
    text-transform: uppercase;
    font-weight: 600;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s, transform 0.3s;
}

.carousel-caption .btn:hover {
    background-color: #0056b3;
    transform: translateY(-5px);
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

@keyframes bounceIn {
    0% {
        transform: translateY(-50px);
        opacity: 0;
    }
    60% {
        transform: translateY(10px);
        opacity: 1;
    }
    100% {
        transform: translateY(0);
    }
}

@keyframes slideIn {
    0% {
        transform: translateX(-100%);
        opacity: 0;
    }
    100% {
        transform: translateX(0);
        opacity: 1;
    }
}

/* How It Works Section */
.how-we-work {
    background-color: #e9f7fe;
    padding: 80px 0;
    animation: fadeIn 2s ease-in-out;
}

.how-we-work h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    text-align: center;
    margin-bottom: 40px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.how-we-work .step {
    text-align: center;
    margin-top: 30px;
}

.how-we-work h4 {
    font-size: 1.8rem;
    margin-bottom: 20px;
    color: #007bff;
    font-weight: 600;
    transition: color 0.3s ease;
}

.how-we-work h4:hover {
    color: #0056b3;
}

.how-we-work p {
    font-size: 1.1rem;
    color: #777;
    line-height: 1.6;
}

/* Vendor Section */
#become-vendor {
    background-color: #f1f1f1;
    /* padding: 80px 0; */
    /* padding-top:50px;
    padding-bottom:50px; */
}

#become-vendor h2 {
    font-size: 3rem;
    color: #343a40;
    text-align: center;
    margin-bottom: 40px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.vendor-description {
    font-size: 1.2rem;
    color: #555;
    line-height: 1.6;
    margin-bottom: 30px;
    text-align: center;
}

#become-vendor .btn-primary {
    font-size: 1.2rem;
    padding: 15px 30px;
    border-radius: 30px;
    text-transform: uppercase;
    font-weight: 600;
    background-color: #007bff;
    border: none;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

#become-vendor .btn-primary:hover {
    background-color: #0056b3;
    transform: translateY(-5px);
}

/* Testimonials Section */
.testimonials {
    background-color: #f8f9fa;
    padding: 60px 0;
    animation: fadeIn 2s ease-in-out;
    align-item:center;
    justify-content:center;
}

.testimonials h2 {
    font-size: 2.5rem;
    color: #343a40;
    text-align: center;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.testimonial-card {
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    
    justify-content:center;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.testimonial-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin-bottom: 20px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #e9f7fe;
}

.testimonial-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    
    justify-content:center;
    
}

.testimonial-card .card-body {
    padding: 20px;
    text-align: center;
}

.testimonial-card p {
    font-style: italic;
    font-size: 1.1rem;
    color: #777;
    margin-bottom: 15px;
}

.testimonial-card h5 {
    font-size: 1.3rem;
    color: #007bff;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 767px) {
    .carousel-caption h1 {
        font-size: 2.5rem;
    }

    .carousel-caption p {
        font-size: 1.2rem;
    }

    .product-card {
        max-width: 100%;
    }

    .product-card .card-body {
        padding: 15px;
    }

    .how-we-work h2 {
        font-size: 2rem;
    }

    .how-we-work h4 {
        font-size: 1.5rem;
    }

    .testimonials h2 {
        font-size: 2rem;
    }
}
.expert-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .expert-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .expert-img {
            height: 150px;  
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
            color: #6c757d;
            background-color: #f0f0f0;
            transition: transform 0.3s ease;
            position: relative;
        }

        .expert-img i {
            font-size: 5rem;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #6c757d;
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

        .expert-card-container {
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
        }

        .expert-card-container.visible {
            opacity: 1;
        }

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
    </style>

    </style>
</head>
<body>

<!-- Hero Section (Carousel) -->
<section id="hero">
    <div id="heroCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <!-- <img src="../assets/images/DALL·E 2025-01-03 01.45.07 - A dynamic digital marketplace illustration featuring diverse buyers and sellers interacting in a modern eCommerce platform. Elements include laptops, .webp" class="d-block w-100" alt="Hero 1"> -->
                <img src="../assets/images/DALL·E 2025-01-03 01.44.53 - A vibrant and modern digital marketplace scene for a multi-vendor eCommerce website. Diverse characters interact as vendors and buyers, exchanging goo.webp" class="d-block w-100" alt="Hero 1">
               
                <div class="carousel-caption d-none d-md-block">
                    <h1 style='text-shadow: 10px 10px 20px black;'>Discover Top-Quality Product  And Services</h1>
                    <p style='text-shadow: 3px 3px 10px black; font-weight:bolder;'><b>Browse through our curated collection of premium products from trusted vendors</b></p>
                    <a href="#products" class="btn btn-primary btn-lg" style='pointer-events: none; '>Shop Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../assets/images/DALL·E 2025-01-03 01.45.07 - A dynamic digital marketplace illustration featuring diverse buyers and sellers interacting in a modern eCommerce platform. Elements include laptops, .webp" class="d-block w-100" alt="Hero 1">
                <!-- <img src="../assets/images/2.jpg" class="d-block w-100" alt="Hero 2"> -->
                <div class="carousel-caption d-none d-md-block">
                    <h1 style='text-shadow: 10px 10px 20px black;'>Your Trusted Marketplace</h1>
                    <p style='text-shadow: 3px 3px 10px black; font-weight:bolder;'> <b>Join thousands of happy customers enjoying an incredible shopping experience</b></p>
                    <a href="#products" class="btn btn-primary btn-lg" style='pointer-events: none; '>Shop Now</a>
                </div>
            </div>
            <div class="carousel-item">
            <!-- assets\images\istockphoto-1206800961-612x612.jpg -->
            <!-- assets\images\istockphoto-1335295270-612x612.jpg -->
                <!-- <img src="../assets/images/11.jpg" class="d-block w-100" alt="Hero 3"> -->
                 
                <img src="../assets/images/istockphoto-1335295270-612x612.jpg" class="d-block w-100" alt="Hero 3">
                <div class="carousel-caption d-none d-md-block">
                    <h1 style='text-shadow: 10px 10px 20px black;'>Start Your Own Business</h1>
                    <p style='text-shadow: 3px 3px 10px black; font-weight:bolder;'><b>Become a vendor and start selling your products on our platform today</b></p>
                    <a href="#become-vendor" class="btn btn-primary btn-lg" style='pointer-events: none; '>Become a Vendor</a>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-we-work">
    <h2>How It Works</h2>
    <div class="row">
        <div class="col-md-4 step">
            <div class="icon">
                <i class="fas fa-pencil-alt" style="font-size: 3rem; color: #007bff;"></i>
            </div>
            <h4>Step 1: Post a Request</h4>
            <p>Mention your task requirement, set a budget, due date, time & location.</p>
        </div>
        <div class="col-md-4 step">
            <div class="icon">
                <i class="fas fa-comments" style="font-size: 3rem; color: #007bff;"></i>
            </div>
            <h4>Step 2: Review Offers</h4>
            <p>Receive offers from experts, review their profiles, chat with them, select the best expert for your task & pay the initial amount.</p>
        </div>
        <div class="col-md-4 step">
            <div class="icon">
                <i class="fas fa-check-circle" style="font-size: 3rem; color: #007bff;"></i>
            </div>
            <h4>Step 3: Get It Done</h4>
            <p>On completion of the task, pay the balance amount directly to the expert.</p>
        </div>
    </div>
</section>

<!-- Product Listings Section -->
<section id="products" class="container mt-5">
    <h2>Featured Services</h2>

    <!-- Product Carousel -->
    <div id="productCarousel" class="carousel slide mb-4" data-ride="carousel">
        <div class="carousel-inner">
            <?php
            $count = 0;
            $itemsPerSlide = 3;  // Show 3 products per slide
            while ($row = mysqli_fetch_assoc($result)) {
                if ($count % $itemsPerSlide == 0) {
                    if ($count > 0) {
                        echo '</div></div>'; // Close previous slide
                    }
                    echo '<div class="carousel-item'.($count == 0 ? ' active' : '').'"><div class="product-list row">';
                }
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card shadow-sm">
                        <img src="../uploads/<?= $row['image']; ?>" class="card-img-top" alt="<?= $row['name']; ?>" style="height: 250px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $row['name']; ?></h5>
                            <p class="card-text"><?= substr($row['description'], 0, 100) . '...'; ?></p>
                            <a href="product-detail.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-block">View Details</a>
                        </div>
                    </div>
                </div>
                <?php
                $count++;
            }
            if ($count > 0) {
                echo '</div></div>'; // Close last slide
            }
            ?>
        </div>
        <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>

<!-- Become a Vendor Section -->
<section id="become-vendor" class="become-vendor">
    <div class="container">
        <h2 class="text-center">Become a Vendor</h2>
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="vendor-description">
                    Start selling your products Or Services on our platform and reach millions of potential customers! With our easy-to-use platform, you'll be able to manage your listings, track sales, and get paid seamlessly. Join our growing community of successful vendors today!
                </p>
            </div>
            <div class="col-md-6 text-center">
                <a href="#" class="btn btn-primary">Become a Vendor</a>
            </div>
        </div>
    </div>
</section>
<!-- Expert Carousel Section -->
<section id="experts" class="container mt-5">
    <h2 class="text-center mb-5">Meet Our Experts</h2>
    <div id="expertCarousel" class="carousel slide" data-ride="carousel" data-interval="2000" data-pause="hover">
        <div class="carousel-inner">
            <?php
            $count = 0;
            $query = "
    SELECT vendors.*, products.name AS product_name
    FROM vendors
    JOIN products ON vendors.id = products.vendor_id
    JOIN users ON vendors.user_id = users.id
";

$result = mysqli_query($conn, $query);
            $itemCount = 0;
            $itemHtml = '';

            while ($row = mysqli_fetch_assoc($result)) {
                // Set default values in case data is missing
                $vendor_name = !empty($row['vendor_name']) ? $row['vendor_name'] : 'Unknown Vendor';
                $vendor_description = !empty($row['vendor_description']) ? $row['vendor_description'] : 'No description available.';
                $contact_email = !empty($row['contact_email']) ? $row['contact_email'] : 'Not available';
                $image = !empty($row['image']) ? $row['image'] : ''; // Empty if no image exists

// Generate a random rating between 1 and 5 with 1 decimal place
$rating = number_format(rand(10, 50) / 10);

// Create the HTML for a single expert card
$expertCard = '<div class="col-md-4 mb-4">
    <div class="card expert-card text-center">
        <div class="expert-img">
            ' . (empty($image) ? '<i class="fas fa-user"></i>' : '<img src="../uploads/' . $image . '" alt="' . $vendor_name . '" class="img-fluid">') . '
        </div>
        <div class="card-body">
            <h5 class="card-title expert-title">' . $vendor_name . '</h5>
            <p class="card-text expert-description">' . substr($vendor_description, 0, 100) . '...</p>
            <p class="vendor-name"><strong>Email: </strong>' . $contact_email . '</p>
            <div class="rating">
                <i class="fas fa-star" style="margin-left:120px;"></i><span>' . $rating . '</span>
            </div>
            <span class="favorite" onclick="toggleFavorite(this)">
                <i class="fas fa-heart"></i>
            </span>
            <a href="#" class="btn btn-primary mt-3">View Profile</a>
        </div>
    </div>
</div>';

// expert-detail.php?id=' . $row['id'] . '
                $itemHtml .= $expertCard;

                // Every 3 items, create a new carousel item
                if (($count + 1) % 3 == 0 || mysqli_num_rows($result) == ($count + 1)) {
                    $activeClass = ($itemCount == 0) ? 'active' : '';
                    echo '<div class="carousel-item ' . $activeClass . '">
                            <div class="row justify-content-center">
                                ' . $itemHtml . '
                            </div>
                          </div>';
                    $itemHtml = ''; // Reset item HTML for the next set of 3
                    $itemCount++;
                }

                $count++;
            }
            ?>
        </div>
        <a class="carousel-control-prev" href="#expertCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#expertCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</section>


<!-- Testimonials Section -->
<section class="testimonials" style="background-color: #f8f9fa; padding: 60px 0;">
    <div class="container">
        <h2 class="text-center" style="font-size: 2.5rem; color: #343a40; margin-bottom: 30px;">What Our Experts Say About Us</h2>
        <div class="row">
            <!-- Testimonial 1 -->
            <div class="col-md-4">
                <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-body text-center">
                        <div class="testimonial-img" style="height: 200px; width: 200px; margin-bottom: 20px; overflow: hidden; border-radius: 50%; background: #e9f7fe; display: flex; justify-content: center; align-items: center;">
                            <img src="../assets/images/16.jpg" alt="Nikhat" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <p style="font-style: italic; color: #555;">"Employed in a high-end beauty parlor for long, I wanted to serve my clients better & in a more flexible way. eCommerce Store opened the doors to endless opportunities; in addition to my regular job, now I have my own team, & my personal client list is increasing month on month! I am able to work with whom I want, when I want, on my terms & in my way. In the end, my clients are more satisfied, I am earning extra & am on the road to make my own name."</p>
                        <h5 style="color: #007bff;">Nikhat</h5>
                        <p>Beautician & Makeup Artist</p>
                    </div>
                </div>
            </div>
            <!-- Testimonial 2 -->
            <div class="col-md-4">
                <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-body text-center">
                        <div class="testimonial-img" style="height: 200px; width: 200px; margin-bottom: 20px; overflow: hidden; border-radius: 50%; background: #e9f7fe; display: flex; justify-content: center; align-items: center;">
                            <img src="../assets/images/18.jpg" alt="Manraj" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <p style="font-style: italic; color: #555;">"I turned 18 this year & was looking for doing some meaningful work for the society & earn some money. eCommerce Store came as a God sent gift, it enabled me to connect with people who required help in their day-to-day life…. & here I was to their rescue! From being an on-demand driver to shopping assistant, I have done it all. At my age, where my friends are still trying to figure out their life, I have started investing what I earn …. years ahead of others!"</p>
                        <h5 style="color: #007bff;">Manraj</h5>
                        <p>Life Assistant</p>
                    </div>
                </div>
            </div>
            <!-- Testimonial 3 -->
            <div class="col-md-4">
                <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-body text-center">
                        <div class="testimonial-img" style="height: 200px; width: 200px; margin-bottom: 20px; overflow: hidden; border-radius: 50%; background: #e9f7fe; display: flex; justify-content: center; align-items: center;">
                            <img src="../assets/images/17.jpg" alt="Kabir" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <p style="font-style: italic; color: #555;">"eCommerce Store enabled me to connect with people who valued their memories & wanted to capture them for eternity, small businesses who wanted to create a pull for their customers by exceptional photographs of their products & services, professionals & artists who wanted stunning portfolios of themselves and many more…. absolutely stunning! What could’ve been better than pursuing one’s passion & earning some extra money as well."</p>
                        <h5 style="color: #007bff;">Kabir</h5>
                        <p>Photographer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<?php  include '../includes/footer.php'?>