<?php 
session_start();
include '../includes/header.php'; ?>
    <title>About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

      
        /* About Section */
        .about-section {
            text-align: center;
            padding: 50px 20px;
            background: linear-gradient(120deg, #ffffff, #f8f9fc);
            border-radius: 15px;
            margin-top: -30px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .about-section h1 {
            font-size: 2.8em;
            color: #0056a4;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            animation: fadeIn 1.5s ease-in-out;
        }

        .about-section p {
            font-size: 1.2em;
            margin-bottom: 30px;
            color: #666;
            animation: fadeIn 1.5s ease-in-out 0.5s;
        }

        .mission, .offer {
            margin-top: 40px;
        }

        .offer-grid {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .card {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.29);
            flex: 1 1 calc(33% - 30px);
            min-width: 280px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(0, 123, 255, 0.1), rgba(0, 86, 164, 0.2));
            z-index: -1;
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .card:hover::before {
            opacity: 1;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            color: #0056a4;
            margin-bottom: 15px;
            font-size: 1.5em;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card ul {
            list-style: none;
            padding: 0;
            color: #555;
        }

        .card ul li {
            margin-bottom: 10px;
            font-size: 1rem;
        }

        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        footer {
            background-color: #0056a4;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 0.9em;
        }

        footer a {
            color: #fff;
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #f8d800;
        }
    </style>

    <main>
        <section class="about-section">
            <div class="container">
                <h1>About Us</h1>
                <p>Welcome to <strong><?= htmlspecialchars($platformName ?? 'Our Platform') ?></strong>, where innovation meets opportunity.</p>
                <div class="mission">
                    <h2>Our Mission</h2>
                    <p>To empower users by providing a versatile platform that caters to their specific needs.</p>
                </div>
                <div class="offer">
                    <h2>What We Offer</h2>
                    <div class="offer-grid">
                        <div class="card">
                            <h3>For Users</h3>
                            <ul>
                                <li>Access to a wide range of services.</li>
                                <li>User-friendly interface for browsing and booking.</li>
                                <li>Transparent reviews and ratings.</li>
                            </ul>
                        </div>
                        <div class="card">
                            <h3>For Vendors</h3>
                            <ul>
                                <li>Showcase your skills to a diverse audience.</li>
                                <li>Efficient tools to manage your offerings.</li>
                                <li>A supportive community for growth.</li>
                            </ul>
                        </div>
                        <div class="card">
                            <h3>For Partners</h3>
                            <ul>
                                <li>Expand your reach to new markets.</li>
                                <li>Collaborate with a thriving network.</li>
                                <li>Achieve mutual success through synergy.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php include '../includes/footer.php'; ?>
<script src="scripts.js"></script>
</body>
</html>
