<?php
session_start();  // Start the session

// Ensure the admin is logged in by checking the correct session variable
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");  // Redirect to login if admin is not logged in
    exit;
}
// Database connection
require 'db_connection.php';
$message = "";

// Delete Buyer
if (isset($_GET['delete_buyer'])) {
    $buyerId = $_GET['delete_buyer'];
    $sql = "DELETE FROM buyers WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':id' => $buyerId])) {
        $message = "Buyer deleted successfully.";
    } else {
        $message = "Error deleting buyer.";
    }
}

// Delete Product Owner
if (isset($_GET['delete_owner'])) {
    $ownerId = $_GET['delete_owner'];
    $sql = "DELETE FROM product_owners WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':id' => $ownerId])) {
        $message = "Product Owner deleted successfully.";
    } else {
        $message = "Error deleting product owner.";
    }
}
if (isset($_GET['delete_driver'])) {
    $driverId = $_GET['delete_driver'];

    // Fetch the driver's images before deletion
    $stmt = $pdo->prepare("SELECT * FROM delivery_personnel WHERE id = :id");
    $stmt->execute([':id' => $driverId]);
    $driver = $stmt->fetch();

    if ($driver) {
        // Delete the images from the server
        if (file_exists($driver['picture'])) {
            unlink($driver['picture']);
        }
        if (file_exists($driver['id_card'])) {
            unlink($driver['id_card']);
        }
        if (file_exists($driver['registration_certificate'])) {
            unlink($driver['registration_certificate']);
        }

        // Delete the driver from the database
        $stmt = $pdo->prepare("DELETE FROM delivery_personnel WHERE id = :id");
        $stmt->execute([':id' => $driverId]);

        // Set success message
        $message = "Driver deleted successfully.";
    }
}

// Fetch Buyers
$buyers = $pdo->query("SELECT * FROM buyers")->fetchAll();

// Fetch Product Owners
$owners = $pdo->query("
    SELECT p.id, p.full_name, p.region, p.email, p.phone, p.product_name, pi.image_path
    FROM product_owners p
    LEFT JOIN product_images pi ON p.id = pi.product_owner_id
")->fetchAll();

// Fetch Delivery Men
$drivers = $pdo->query("SELECT * FROM delivery_personnel")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file uploads
    $picture = $_FILES['picture'];
    $idCard = $_FILES['id_card'];
    $registrationCertificate = $_FILES['registration_certificate'];

    $uploadDir = 'uploads/';

    // Ensure the upload directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Process and move files to the upload directory
    $picturePath = $uploadDir . basename($picture['name']);
    $idCardPath = $uploadDir . basename($idCard['name']);
    $registrationCertificatePath = $uploadDir . basename($registrationCertificate['name']);

    if (
        move_uploaded_file($picture['tmp_name'], $picturePath) &&
        move_uploaded_file($idCard['tmp_name'], $idCardPath) &&
        move_uploaded_file($registrationCertificate['tmp_name'], $registrationCertificatePath)
    ) {

        // Insert data into the database
        $stmt = $pdo->prepare("INSERT INTO delivery_personnel (name, email, phone, region, picture, id_card, registration_certificate) 
                               VALUES (:name, :email, :phone, :region, :picture, :id_card, :registration_certificate)");

        $stmt->execute([
            ':name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':phone' => $_POST['phone'],
            ':region' => $_POST['region'],
            ':picture' => $picturePath,
            ':id_card' => $idCardPath,
            ':registration_certificate' => $registrationCertificatePath,
        ]);

        header("Location: admin_dashboard.php"); // Redirect back to dashboard after insertion
        exit;
    } 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden {
            display: none;
        }

        .welcome {
            color: #24034b;
        }

        .product-image {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <a href="logout.php" class="btn btn-danger btn-sm ms-auto" id="logout-btn">Logout</a>

        </div>
    </nav>

    <div class="container mt-4">
        <!-- Alert message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <h1 class="text-center welcome">Welcome admin AbdaSlam !</h1>

        <div class="mb-4 text-center">
            <button class="btn btn-primary" onclick="showSection('buyersTable')">View Buyers</button>
            <button class="btn btn-secondary" onclick="showSection('ownersTable')">View Product Owners</button>
            <button class="btn btn-success" onclick="showSection('addDeliveryForm')">Add Delivery Men</button>
            <button class="btn btn-warning" onclick="showSection('driversTable')">View Drivers</button>
        </div>

        <!-- Buyers Section -->
        <div id="buyersTable">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Buyers (Contact Form Messages)</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Message</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($buyers as $buyer): ?>
                                <tr>
                                    <td><?php echo $buyer['name']; ?></td>
                                    <td><?php echo $buyer['email']; ?></td>
                                    <td><?php echo $buyer['phone']; ?></td>
                                    <td><?php echo $buyer['message']; ?></td>
                                    <td>
                                        <a href="?delete_buyer=<?php echo $buyer['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Product Owners Section -->
        <div id="ownersTable" class="hidden">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h4>Product Owners</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Region</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Product</th>
                                <th>Product Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($owners as $owner): ?>
                                <tr>
                                    <td><?php echo $owner['full_name']; ?></td>
                                    <td><?php echo $owner['region']; ?></td>
                                    <td><?php echo $owner['email']; ?></td>
                                    <td><?php echo $owner['phone']; ?></td>
                                    <td><?php echo $owner['product_name']; ?></td>
                                    <td>
                                        <?php if ($owner['image_path']): ?>
                                            <img src="<?php echo htmlspecialchars($owner['image_path']); ?>" alt="Product Image" class="product-image">
                                        <?php else: ?>
                                            No image
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <a href="?delete_owner=<?php echo $owner['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Delivery Men Form -->
        <div id="addDeliveryForm" class="hidden">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>Add Delivery Men</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="region" class="form-label">Region</label>
                            <input type="text" class="form-control" id="region" name="region" required>
                        </div>
                        <div class="mb-3">
                            <label for="picture" class="form-label">Personnel picture</label>
                            <input type="file" class="form-control" id="picture" name="picture" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_card" class="form-label">ID Card</label>
                            <input type="file" class="form-control" id="Card" name="id_card" required>
                        </div>
                        <div class="mb-3">
                            <label for="registration_certificate" class="form-label">registration_certificate</label>
                            <input type="file" class="form-control" id="registration_certificate" name="registration_certificate" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add Delivery Man</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Drivers Section -->
        <div id="driversTable" class="hidden">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4>Drivers</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Region</th>
                                <th>Picture</th>
                                <th>ID Card</th>
                                <th>Registration Certificate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($drivers as $driver): ?>
                                <tr>
                                    <td><?php echo $driver['name']; ?></td>
                                    <td><?php echo $driver['email']; ?></td>
                                    <td><?php echo $driver['phone']; ?></td>
                                    <td><?php echo $driver['region']; ?></td>
                                    <td><img src="<?php echo htmlspecialchars($driver['picture']); ?>" alt="Driver Picture" class="img-thumbnail" style="max-width: 100px;"></td>
                                    <td><img src="<?php echo htmlspecialchars($driver['id_card']); ?>" alt="ID Card" class="img-thumbnail" style="max-width: 100px;"></td>
                                    <td><img src="<?php echo htmlspecialchars($driver['registration_certificate']); ?>" alt="Registration Certificate" class="img-thumbnail" style="max-width: 100px;"></td>
                                    <td>
                                        <a href="?delete_driver=<?php echo $driver['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.getElementById('buyersTable').classList.add('hidden');
            document.getElementById('ownersTable').classList.add('hidden');
            document.getElementById('addDeliveryForm').classList.add('hidden');
            document.getElementById('driversTable').classList.add('hidden');

            // Show the selected section
            document.getElementById(sectionId).classList.remove('hidden');
        }

        // Optional: Automatically show the "Buyers" table on page load
        document.addEventListener('DOMContentLoaded', function() {
            showSection('buyersTable');
        });
    </script>
     <script>
        window.onbeforeunload = function() {
            // Make an AJAX request to log out the user when they try to leave the page
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "logout.php", true);
            xhr.send();
        };
    </script>
</body>