<?php
// Database connection
session_start();

// Initialize message variable
$message = "";
$alertClass = "";

// Database connection
require 'db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $messageContent = htmlspecialchars($_POST['message']);

    // Prepare SQL query to insert data into the database
    $sql = "INSERT INTO buyers (name, email, phone, message) VALUES (:name, :email, :phone, :message)";
    $stmt = $pdo->prepare($sql);

    try {
        // Execute query and insert data
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':message' => $messageContent
        ]);

        // Success message
        $message = "Merci! Votre message a été envoyé avec succès.";
        $alertClass = "alert-success"; // Bootstrap success alert
    } catch (PDOException $e) {
        // Error message
        $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
        $alertClass = "alert-danger"; // Bootstrap error alert
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Header -->
    <header class="sticky-top bg-light">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <img src="images/fast-delivery.png">
                    <h5 class="ms-1 mb-0 logo">GoGoods</h5>
                </a>
                <button type="button" class="navbar-toggler border-0 menubtn " data-bs-toggle="collapse"
                    data-bs-target="#menuLinks">
                    <span class="navbar-toggler-icon "></span>
                </button>
                <div class="navbar-collapse collapse" id="menuLinks">
                    <ul class="navbar-nav ms-auto ">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="#about" class="nav-link">about us</a>
                        </li>
                        <li class="nav-item">
                            <a href="#services" class="nav-link">services</a>
                        </li>
                        <li class="nav-item"></li>
                        <a href="product.php" class="nav-link">Products</a>
                        </li>
                        <li class="nav-item">
                            <a href="#contact" class="nav-link">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-section text-center ">
        <div class="container">
            <h1>Welcome to <span>GoGoods!</span></h1>
            <p>Your trusted platform for advertising and delivery.</p>
            <a href="add_product.php" class="btn ">Sign Up</a>
            <a href="login.php" btn " class=" btn">Log In</a>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>About Us</h2>
                    <p>We connect buyers with product owners, providing a seamless platform to showcase products and
                        communicate effortlessly. Our secure delivery services ensure that your purchases arrive safely
                        and on time. With a commitment to reliability, transparency, and customer satisfaction, we
                        strive to create a trusted marketplace where your needs are always our priority!</p>
                </div>
                <div class="col-md-6 aboutImg">

                </div>
            </div>
        </div>


    </section>

    <!-- Services Section -->
    <section id="services" class="services-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center">Our Services</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card m-3 p-2">
                        <div class="card-body">
                            <h3>Advertising</h3>
                            <p>Showcase your products to a wide audience by sign up with us.</p>
                            <p>Our platform makes it easy to list your products and reach a larger audience, helping you
                                boost visibility and drive more sales effortlessly</p>
                            <a href="add_product.php" class="serviceBtn">Add your product</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card m-3 p-2">
                        <div class="card-body">
                            <h3>Delivery</h3>
                            <p>Emphasize reliable and secure delivery services.</p>
                            <p>Our platform ensures reliable and secure delivery services, connecting your products to
                                customers with speed and care you can trust</p>
                            <a class="serviceBtn" data-bs-toggle="collapse" data-bs-target="#content">See how</a>
                            <div id="content" class="collapse">
                                <div class="process-container">
                                    <h1 class="process-title">How Our Delivery Process Works</h1>
                                    <div class="steps">
                                        <div class="step">
                                            <div class="step-icon">🛒</div>
                                            <div class="step-content">
                                                <div class="step-title">1-Place Your Order</div>
                                                <p class="step-description">Browse and select the products you love.</p>
                                            </div>
                                        </div>
                                        <div class="step">
                                            <div class="step-icon">📞</div>
                                            <div class="step-content">
                                                <div class="step-title">2-Contact Us for Delivery</div>
                                                <p class="step-description">Reach out to us via phone, chat, or email to
                                                    schedule delivery. We’ll take care of the rest.</p>
                                            </div>
                                        </div>
                                        <div class="step">
                                            <div class="step-icon">🚚</div>
                                            <div class="step-content">
                                                <div class="step-title">3-We Handle Everything</div>
                                                <p class="step-description">Our reliable team ensures secure and fast
                                                    delivery. Track your package every step of the way.</p>
                                            </div>
                                        </div>
                                        <div class="step">
                                            <div class="step-icon">📦</div>
                                            <div class="step-content">
                                                <div class="step-title">4-Receive Your Products</div>
                                                <p class="step-description">Get your products delivered right to your
                                                    doorstep, hassle-free.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="product.php" class="action-button">Explore our products</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-us my-5">
        <div class="container text-center">
            <h2 class="mb-4">Why Choose Us?</h2>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-archive"></i>
                        </div>
                        <h5>Easy Listing</h5>
                        <p>List your products in minutes with our simple and intuitive interface.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-truck "></i>
                        </div>
                        <h5>Flexible Delivery</h5>
                        <p>We Choose for you from multiple delivery options to suit your needs.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm p-4">
                        <div class="icon mb-3">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h5>Secure Platform</h5>
                        <p>Your data and transactions are protected with industry-standard security.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Testimonials -->
    <section id="testimonials" class="py-5 bg-light testimonials">
        <div class="container text-center">
            <h2>Customers feedback</h2>
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <img src="images/girl.jpg" alt="Customer 1" class="img-fluid mb-2 testimonial">
                    <div class="cont  mb-3">
                        <p>"Fantastic service and great platform!"</p>
                        <strong>Maryem</strong>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <img src="images/soaad.jpg" alt="Customer 2" class="img-fluid mb-2 testimonial">
                    <div class="cont mb-3">
                        <p>"Reliable and easy to use! Highly recommend."</p>
                        <strong>Soaad</strong>
                    </div>
                </div>
                <div class="col-md-4 mb-3 ">
                    <img src="images/man.jpeg" alt="Customer 3" class="img-fluid mb-2 testimonial">
                    <div class="cont mb-3">
                        <p>"Great delivery service and support!"</p>
                        <strong>Khalid</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Contact Form -->
    <section id="contact" class="contact-section py-5">
    <div class="container">
            <?php if (!empty($message)): ?>
            <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

            <p class="py-4 mb-4">We'd love to hear from you! Contact us today, and we'll get back to you as soon as
                possible.</p>
            <div class="row">
                <!-- Form on the left -->
                <div class="col-md-6">
                    <form action="" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Name</label>
                                <input type="text" class="form-control m-2" name="name" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="email">Email</label>
                                <input type="email" class="form-control m-2" name="email" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="phone">Phone</label>
                                <input type="phone" class="form-control m-2" name="phone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control m-2" name="message" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn  mt-2 ">Submit</button>
                    </form>
                </div>
                <!-- Text and Image on the right -->
                <div class="col-md-6 text-center">

                    <img src="images/contact.jpg" alt="Contact Us" class="img-fluid"
                        style="max-width: 70%; height: auto;">

                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer text-white py-4">

        <div class="container text-center">
            <p>Contact us: <a href="mailto:GoGoodsInfo@gmail.com">GoGoodsInfo@gmail.com</a> | +123 456 7890</p>

            <div class="social-icons">
                <a href="https://www.facebook.com/GoGoods" class="mx-2" target="_blank">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://wa.me/212610228442" class="mx-2" target="_blank">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="https://www.instagram.com/GoGoods" class="mx-2" target="_blank">
                    <i class="bi bi-instagram"></i>
                </a>
            </div>
            <p>&copy; 2024 GoGoods. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>