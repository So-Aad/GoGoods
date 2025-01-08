<?php
// Démarrer la session
session_start();

// Inclure la connexion à la base de données
require_once 'db_connection.php';

// Récupérer les produits et leurs images depuis la base de données
$sql = "
    SELECT po.product_name, po.product_description, po.full_name, pi.image_path
    FROM product_owners po
    LEFT JOIN product_images pi ON po.id = pi.product_owner_id
";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #24034b;
            color: white;
        }
        .card {
            background-color: #7b50ef;
            border: none;
            border-radius: 10px;
            color: white;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }
        .card img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .btn-contact {
            background-color: #24034b;
            color: white;
            border: none;
        }
        .btn-contact:hover {
            background-color: black;
            color: white;
        }
        .container {
            padding-top: 50px;
            padding-bottom: 50px;
        }
        .logo{
    color:#24034b;
    font-family: "Bebas Neue", serif;
    font-size: 1.5rem;
    font-weight: bold;
    letter-spacing: 2px;
        
    
}
.navbar{
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
.navbar-nav .nav-link:hover{
    color:#24034b;
    
}
.footer{
    background: rgb(174, 208, 238);
    background: radial-gradient(circle, rgba(174, 208, 238, 1) 0%, rgba(113, 64, 226, 1) 100%);
}
.footer p{
    font-weight:600;
    font-size: large;
}
.footer a{
    text-decoration: none;
    color: #24034b;
}
.footer.social-icons a{
    color:#24034b;
}
.footer .social-icons a:hover{
    color: white;
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
<div class="container">
        <h1 class="text-center mb-5">Products</h1>
        <div class="row g-4">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card">
                        <?php if (!empty($product['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Produit" class="card-img-top">
                        <?php else: ?>
                            <img src="placeholder.jpg" alt="Image non disponible" class="card-img-top">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['product_description']); ?></p>
                            <p class="card-text"><small>seller : <?php echo htmlspecialchars($product['full_name']); ?></small></p>
                            <a href="index.php#contact" class="btn btn-contact w-100">Contact us</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
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
