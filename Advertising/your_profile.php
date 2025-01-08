<?php
session_start();  // Start the session to access session data

if (!isset($_SESSION['product_owner_id'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit;
}

require 'db_connection.php';

// Fetch the product owner's data
$productOwnerId = $_SESSION['product_owner_id'];
$stmt = $pdo->prepare("SELECT * FROM product_owners WHERE id = :id");
$stmt->execute([':id' => $productOwnerId]);
$productOwner = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$productOwner) {
    echo "Profile not found!";
    exit;
}

// Fetch product images
$imageStmt = $pdo->prepare("SELECT * FROM product_images WHERE product_owner_id = :product_owner_id");
$imageStmt->execute([':product_owner_id' => $productOwnerId]);
$productImages = $imageStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $fullName = $_POST['full_name'];
    $region = $_POST['region'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];

    $stmt = $pdo->prepare("UPDATE product_owners 
                           SET full_name = :full_name, region = :region, email = :email, phone = :phone, product_name = :product_name, product_description = :product_description 
                           WHERE id = :id");
    $stmt->execute([
        ':full_name' => $fullName,
        ':region' => $region,
        ':email' => $email,
        ':phone' => $phone,
        ':product_name' => $productName,
        ':product_description' => $productDescription,
        ':id' => $productOwnerId,
    ]);

    header("Location: your_profile.php");
    exit;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image'])) {
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $imageName = $_FILES['product_image']['name'];
        $imageTmpPath = $_FILES['product_image']['tmp_name'];
        $uploadDir = 'uploads/';
        $uploadFilePath = $uploadDir . basename($imageName);

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($imageTmpPath, $uploadFilePath)) {
            $imageStmt = $pdo->prepare("INSERT INTO product_images (product_owner_id, image_path) VALUES (:product_owner_id, :image_path)");
            $imageStmt->execute([':product_owner_id' => $productOwnerId, ':image_path' => $uploadFilePath]);

            header("Location: your_profile.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-images img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin: 5px;
        }
        label{
            color:#7b50ef;
        }

        .logo {
            color: #24034b;
            font-family: "Bebas Neue", serif;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;


        }

        .navbar {
            border-bottom: 2px solid #7b50ef;
            border-top: 2px solid #7b50ef;
        }

        .navbar-brand {
            font-size: 1.5rem;
            /* Adjust logo size */
        }

        .navbar-nav .nav-link {
            margin-right: 1rem;
        }

        .navbar-nav .nav-link:hover {
            color: #24034b;

        }
        #logout-btn {
            margin-top: 10px; /* Adds space below the header */
        }
    </style>
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
                            <a href="index.php" class="nav-link active">Home</a>
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
    <div class="container mt-3 d-flex justify-content-end">
        <a href="logout.php" class="btn btn-danger btn-sm" id="logout-btn">Logout</a>
    </div>
   
    <div class="container py-5">
        
        <div class="row justify-content-center">
        
            <div class="col-md-8">
                <div class="profile-card">
                    <h3 class="text-center"><span style="color:#7b50ef;">Welcome</span>, <?php echo htmlspecialchars($productOwner['full_name']); ?></h3>
                    <hr>
                    <h5>Profile Information</h5>
                    <form method="POST">
                        <input type="hidden" name="update_profile" value="1">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($productOwner['full_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="region" class="form-label">Region</label>
                            <input type="text" class="form-control" id="region" name="region" value="<?php echo htmlspecialchars($productOwner['region']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($productOwner['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($productOwner['phone']); ?>" required>
                        </div>
                        <hr>
                        <h5>Product Information</h5>
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo htmlspecialchars($productOwner['product_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="product_description" class="form-label">Product Description</label>
                            <textarea class="form-control" id="product_description" name="product_description" rows="4" required><?php echo htmlspecialchars($productOwner['product_description']); ?></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                    <hr>
                    <h5>Product Images</h5>
                    <div class="product-images">
                        <?php if (count($productImages) > 0): ?>
                            <!-- Display existing images -->
                            <?php foreach ($productImages as $image): ?>
                                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Product Image" class="img-thumbnail" style="max-width: 200px;">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No images uploaded yet.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Show the upload form only if no images are uploaded -->
                    <?php if (count($productImages) == 0): ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="upload_image" value="1">
                            <div class="mb-3">
                                <label for="product_image" class="form-label">Upload New Image</label>
                                <input type="file" class="form-control" id="product_image" name="product_image" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Upload Image</button>
                            </div>
                        </form>
                    <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onbeforeunload = function() {
            // Make an AJAX request to log out the user when they try to leave the page
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "logout.php", true);
            xhr.send();
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>