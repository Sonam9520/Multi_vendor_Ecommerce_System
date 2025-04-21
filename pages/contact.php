<?php
session_start();
include '../includes/header.php';
include '../includes/config.php'; // Include the database config

// Include PHPMailer library
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// require 'vendor/autoload.php'; // Path to your PHPMailer autoload file

// Define variables and initialize with empty values
$name = $email = $message = "";
$name_err = $email_err = $message_err = "";

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Please enter a message.";
    } else {
        $message = trim($_POST["message"]);
    }

    // If no errors, save data to the database and send email
    if (empty($name_err) && empty($email_err) && empty($message_err)) {
        // Insert form data into the database
        $stmt = $conn->prepare("INSERT INTO contact_form_submissions (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        
        if ($stmt->execute()) {
            $success_msg = "Thank you for contacting us! We will get back to you soon.";

            // Now send an email using PHPMailer
            // $mail = new PHPMailer(true);
            // try {
            //     // Server settings
            //     $mail->isSMTP();
            //     $mail->Host = 'smtp.yourmailserver.com'; // Set the SMTP server to use (replace with your SMTP server)
            //     $mail->SMTPAuth = true;
            //     $mail->Username = 'your-email@example.com'; // SMTP username (replace with your email)
            //     $mail->Password = 'your-email-password'; // SMTP password (replace with your password)
            //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            //     $mail->Port = 587;

            //     // Recipients
            //     $mail->setFrom($email, $name);
            //     $mail->addAddress('your-email@example.com', 'Your Name'); // Add a recipient

            //     // Content
            //     $mail->isHTML(true);
            //     $mail->Subject = 'Contact Us Form Submission';
            //     $mail->Body    = "You have received a new message from $name.<br><br>Email: $email<br><br>Message:<br>$message";

            //     // Send the email
            //     $mail->send();
            // } catch (Exception $e) {
            //     $error_msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            // }
        } else {
            $error_msg = "Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>

<!-- HTML Form and page content here -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Add jQuery for AJAX functionality -->

<style>
    /* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f0f0;
    color: #333;
    line-height: 1.6;
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
}

/* Contact Information Section */
.contact-info {
    margin-bottom: 40px;
    animation: slideInLeft 1s ease-out;
}

.contact-info h2 {
    font-size: 2.5em;
    color: #333;
    margin-bottom: 20px;
}

.contact-info p {
    font-size: 1.1em;
    color: #777;
    margin-bottom: 20px;
}

.contact-details {
    display: flex;
    justify-content: space-around;
}

.contact-item {
    width: 30%;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s;
}

.contact-item:hover {
    transform: translateY(-10px);
}

.contact-item h3 {
    font-size: 1.5em;
    color: #333;
}

.contact-item p {
    color: #555;
}

/* Form Section */
.form-section {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
    animation: fadeInUp 2s ease-out;

}

.form-section h2 {
    font-size: 2.5em;
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-size: 1.1em;
    color: #333;
    margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border-radius: 5px;
    border: 2px solid grey;

    /* #ddd */
    font-size: 1.1em;
    color: #333;

}

.form-group textarea {
    resize: vertical;
}

.submit-btn {
    background-color: #007BFF;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    font-size: 1.1em;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: #0056b3;
}

/* Location Section */
.location {
    margin-bottom: 40px;
    text-align: center;
}

.location iframe {
    width: 100%;
    height: 400px;
    border: none;
}

/* Social Media Links Section */
.social {
    text-align: center;
    margin-bottom: 40px;
}

.social h2 {
    font-size: 2.5em;
    color: #333;
    margin-bottom: 20px;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.social-icon {
    display: inline-block;
    padding: 12px 25px;
    background-color: #333;
    color: white;
    font-size: 1.2em;
    text-decoration: none;
    border-radius: 30px;
    transition: background-color 0.3s;
}

.social-icon:hover {
    background-color: #444;
}

/* Footer */
footer {
    text-align: center;
    margin-top: 40px;
    color: #777;
    font-size: 1.1em;
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

@keyframes slideInLeft {
    from {
        transform: translateX(-100%);
    }
    to {
        transform: translateX(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-msg {
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
}

.error-msg {
    background-color: #f44336;
    color: white;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
}

</style>
</head>
<body>
    <div class="container">
        <section class="contact-info">
            <h2>Get in Touch</h2>
            <p>If you need help or want to inquire, use the form below, or reach out to us directly.</p>
            
            <div class="contact-details">
                <div class="contact-item">
                    <h3>Email Us</h3>
                    <p><a href="mailto:support@yourwebsite.com">support@yourwebsite.com</a></p>
                </div>
                <div class="contact-item">
                    <h3>Call Us</h3>
                    <p><a href="tel:+1234567890">+1 (234) 567-890</a></p>
                </div>
                <div class="contact-item">
                    <h3>Visit Us</h3>
                    <p>1234 Street Name, City, Country</p>
                </div>
            </div>
        </section>

        <section class="form-section">
            <h2>Send Us a Message</h2>

            <?php if (!empty($success_msg)) { ?>
                <div class="success-msg"><?php echo $success_msg; ?></div>
            <?php } elseif (!empty($error_msg)) { ?>
                <div class="error-msg"><?php echo $error_msg; ?></div>
            <?php } ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="contact-form" style=''>
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>" required placeholder="Enter your full name">
                    <span class="error"><?php echo $name_err; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required placeholder="Enter your email address">
                    <span class="error"><?php echo $email_err; ?></span>
                </div>

                <div class="form-group">
                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" required placeholder="Enter your message" rows="5"><?php echo $message; ?></textarea>
                    <span class="error"><?php echo $message_err; ?></span>
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn">Send Message</button>
                </div>
            </form>
        </section>

        <section class="location">
            <h2>Our Location</h2>
            <div class="map">
                <!-- Embed your Google Map iframe here -->
                <iframe src="https://www.google.com/maps/embed?pb=...your_map_code..." width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </section>

        <section class="social">
            <h2>Follow Us</h2>
            <div class="social-links">
                <a href="https://www.facebook.com" target="_blank" class="social-icon facebook">Facebook</a>
                <a href="https://www.twitter.com" target="_blank" class="social-icon twitter">Twitter</a>
                <a href="https://www.instagram.com" target="_blank" class="social-icon instagram">Instagram</a>
            </div>
        </section>

    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
