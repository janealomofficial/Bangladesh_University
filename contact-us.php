<?php
require_once __DIR__ . "/includes/header.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        
        .contact-section {
            background-color: #0D2240; /* Dark Blue */
            color: white;
            padding: 60px 0;
        }

        .contact-section h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #F9D815; /* Yellow */
        }

        .contact-section p {
            font-size: 1.25rem;
        }

        .contact-form input, .contact-form textarea {
            border-radius: 5px;
            padding: 15px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            width: 100%;
        }

        .contact-form textarea {
            min-height: 150px;
        }

        .btn-submit {
            background-color: #9e1b32; /* Red */
            color: white;
            font-size: 1.2rem;
            padding: 15px 30px;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #850e29; /* Darker red */
        }

        .contact-info {
            background-color: #F9D815; /* Yellow */
            color: #0D2240; /* Dark Blue */
            padding: 30px;
            border-radius: 8px;
            margin-top: 40px;
        }

        .contact-info i {
            font-size: 2rem;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<!-- Contact Us Section -->
<section class="contact-section text-center">
    <div class="container">
        <h2>Contact Us</h2>
        <p>We would love to hear from you. Please fill out the form below or reach out via email or phone!</p>

        <!-- Contact Form -->
        <div class="contact-form">
            <form action="" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Your Message" required></textarea>
                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        </div>

        <!-- Contact Information Section -->
        <div class="contact-info mt-5">
            <h4>Our Contact Information</h4>
            <div class="d-flex justify-content-center mt-4">
                <div class="d-flex align-items-center mx-4">
                    <i class="bi bi-house-door-fill"></i>
                    <span>123 University St, City, Country</span>
                </div>
                <div class="d-flex align-items-center mx-4">
                    <i class="bi bi-telephone-fill"></i>
                    <span>+123 456 789</span>
                </div>
                <div class="d-flex align-items-center mx-4">
                    <i class="bi bi-envelope-fill"></i>
                    <span>contact@university.com</span>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
require_once __DIR__ . "/includes/footer.php";
?>