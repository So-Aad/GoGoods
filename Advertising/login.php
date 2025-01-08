<?php
session_start();  // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'])); // Convert email to lowercase for comparison
    $password = $_POST['password'];

    // Database connection
    require 'db_connection.php';

    // Check if admin
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE LOWER(email) = :email");
    $stmt->execute([':email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Admin login successful, store session variables
        $_SESSION['admin_id'] = $admin['id'];  // Store the admin's ID in the session
        $_SESSION['admin_email'] = $admin['email'];  // Store the admin's email in the session
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit;
    }

    // Check if product owner
    $stmt = $pdo->prepare("SELECT * FROM product_owners WHERE LOWER(email) = :email");
    $stmt->execute([':email' => $email]);
    $productOwner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($productOwner && password_verify($password, $productOwner['password'])) {
        // Product owner login successful, store session variables
        $_SESSION['product_owner_id'] = $productOwner['id'];  // Store the product owner's ID in the session
        $_SESSION['product_owner_email'] = $productOwner['email'];  // Store the product owner's email in the session
        $_SESSION['full_name'] = $productOwner['full_name'];  // Store the product owner's full name
        header("Location: your_profile.php"); // Redirect to product owner profile
        exit;
    }

    echo "<div class='alert alert-danger'>Invalid email or password. Please try again.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header class="sticky-top bg-light">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a href="index.php" class="navbar-brand d-flex align-items-center">
                    <img src="images/fast-delivery.png">
                    <h5 class="ms-1 mb-0 logo">GoGoods</h5>
                </a>
                <button type="button" class="navbar-toggler border-0 menubtn " data-bs-toggle="collapse" data-bs-target="#menuLinks">
                    <span class="navbar-toggler-icon "></span>
                </button>
                <div class="navbar-collapse collapse" id="menuLinks">
                    <ul class="navbar-nav ms-auto ">
                        <li class="nav-item">
                            <a href="index.php#" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php#about" class="nav-link">about us</a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php#services" class="nav-link">services</a>
                        </li>
                        <li class="nav-item">
                            <a href="product.php" class="nav-link">Products</a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php#contact" class="nav-link">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <section class="login-section">
        <div class="container p-5">
            <h2 class="form-title text-center mb-5">Login</h2>
            <div class="row text-center">
                <div class="col-md-6 formContainer">
                    <form action="login.php" method="post" enctype="multipart/form-data">
                        <div class="form-group p-4">
                            <input type="email" id="email" name="email" class="form-control mt-5" placeholder="Enter your Email" required>
                        </div>
                        <div class="form-group p-4">
                            <input type="password" id="password" name="password" class="form-control mt-5" placeholder="Enter your Password" autofocus required>
                        </div>
                        <button type="submit" class="form-submit m-5 btn logbtn">Submit</button>
                    </form>
                </div>

                <!-- Text and Image on the right -->
                <div class="col-md-6 text-center imgContainer">
                    <img src="images/loginPic.png" alt="Contact Us" class="img-fluid " style="max-width: 100%; height: auto;">
                </div>
                <div>
                    <p class="text-center m-4 signupP">You don't have an account? <a href="add_product.php">Sign up here</a></p>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer text-white py-4">
        <div class="container text-center">
            <p>Contact us: <a href="mailto:GoGoodsInfo@gmail.com">GoGoodsInfo@gmail.com</a> | +123 456 7890</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/GoGoods" class="mx-2" target="_blank"><i class="bi bi-facebook"></i></a>
                <a href="https://wa.me/212610228442" class="mx-2" target="_blank"><i class="bi bi-whatsapp"></i></a>
                <a href="https://www.instagram.com/GoGoods" class="mx-2" target="_blank"><i class="bi bi-instagram"></i></a>
            </div>
            <p>&copy; 2024 GoGoods. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
