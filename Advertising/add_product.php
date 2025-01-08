<?php
$message = ""; // Variable to hold success or error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'];
    $region = $_POST['region'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];

    // Include the database connection
    require 'db_connection.php'; // Assumes $pdo is defined in this file

    try {
        // Insert product owner data
        $stmt = $pdo->prepare("INSERT INTO product_owners (full_name, region, email, password, phone, product_name, product_description) 
                               VALUES (:full_name, :region, :email, :password, :phone, :product_name, :product_description)");
        $stmt->execute([
            ':full_name' => $fullName,
            ':region' => $region,
            ':email' => $email,
            ':password' => $password,
            ':phone' => $phone,
            ':product_name' => $productName,
            ':product_description' => $productDescription,
        ]);

        // Get the last inserted product owner ID
        $productOwnerId = $pdo->lastInsertId();

        // Ensure the uploads directory exists
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Handle file uploads
        foreach ($_FILES['product_images']['tmp_name'] as $key => $tmpName) {
            if (!empty($tmpName)) {
                $imageName = uniqid() . '_' . basename($_FILES['product_images']['name'][$key]);
                $imagePath = $uploadDir . $imageName;

                // Move the uploaded file to the uploads directory
                if (move_uploaded_file($tmpName, $imagePath)) {
                    // Insert image path into the product_images table
                    $stmt = $pdo->prepare("INSERT INTO product_images (product_owner_id, image_path) VALUES (:product_owner_id, :image_path)");
                    $stmt->execute([
                        ':product_owner_id' => $productOwnerId,
                        ':image_path' => $imagePath,
                    ]);
                }
            }
        }

        $message = "<div class='alert alert-success' role='alert'>
                        You have signed up successfully, now try to log in. <a href='login.php' class='alert-link'>Login</a>.
                    </div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger' role='alert'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="add_product.css">
</head>
<body>
    <header class="sticky-top bg-light">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a href="index.php" class="navbar-brand d-flex align-items-center">
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
                            <a href="index.php" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php#about" class="nav-link">about us</a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php#services" class="nav-link">services</a>
                        </li>
                        <li class="nav-item"></li>
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
    <section  class="signup-section py-5">
        <div class="container p-4">
            <h1 class="form-title text-center">Sign Up</h1>
            <?= $message; ?>
            <div class="row">
                <!-- Form on the left -->
                <div class="col-md-6 p-3">
                    <form action="add_product.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="full-name">Full Name</label>
                            <input type="text" id="full-name" name="full_name" class="form-control m-2"  autofocus required>
                        </div>
                        <div class="form-group">
                            <label for="region">Region</label>
                            <input type="text" id="region" name="region" class="form-control m-2" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control m-2" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control m-2" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone"  class="form-control m-2" required>
                        </div>
                        <div class="form-group">
                            <label for="product-picture">Pictures of the Product(1 to 4)</label>
                            <input type="file" id="product-picture" name="product_images[]" multiple accept="image/*"  class="form-control m-2"  multiple required>
                        </div>
                        <div class="form-group">
                            <label for="product-name">Name of the Product</label>
                            <input type="text" id="product-name" name="product_name" class="form-control m-2" required>
                        </div>
                        <div class="form-group">
                            <label for="product-description">Description of the Product</label>
                            <textarea id="product-description" name="product_description" class="form-control m-2" required></textarea>
                        </div>
                        <button type="submit" class=" btn  m-2 w-100">Submit</button>
                    </form>
                </div>
                <!-- Text and Image on the right -->
                <div class="col-md-6 text-center">
                    <p class="py-4 mb-4 floatingP">Join our platform to showcase your amazing products to a wide audience and grow your business effortlessly. Sign up today and start listing your products!.</p>
                    <img src="images/package.png" alt="Contact Us" class="img-fluid "
                        style="max-width: 100%; height: auto;">
                </div>
                <div>
                    <p class="text-center loginP m-4">Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
       
    </section>
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