<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `orders` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    status VARCHAR(20) DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'cash',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)") or die('query failed: ' . mysqli_error($conn));

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `order_items` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
)") or die('query failed: ' . mysqli_error($conn));

$cart_query = mysqli_query($conn, "SELECT c.book_id, c.quantity, b.price FROM `cart` c LEFT JOIN `books` b ON c.book_id = b.id WHERE c.user_id = '$user_id'") or die('query failed: ' . mysqli_error($conn));

$total_amount = 0;
$cart_items = [];
while($cart_item = mysqli_fetch_assoc($cart_query)){
    $cart_items[] = $cart_item;
    $price = isset($cart_item['price']) ? (float)$cart_item['price'] : 0;
    $total_amount += $price * $cart_item['quantity'];
}

if(!empty($cart_items)){
    mysqli_query($conn, "INSERT INTO `orders` (user_id, total_amount, status, payment_method) VALUES ('$user_id', '$total_amount', 'pending', 'cash')") or die('query failed: ' . mysqli_error($conn));
    $order_id = mysqli_insert_id($conn);
    $order_code = '#ORD-' . str_pad($order_id, 4, '0', STR_PAD_LEFT);

    // Notify admin and user about the new order
    mysqli_query($conn, "INSERT INTO notifications (recipient_type, recipient_id, title, message) VALUES ('admin', NULL, 'New Order Received', 'New order {$order_code} has been placed by user #{$user_id}.')") or die('query failed: ' . mysqli_error($conn));
    mysqli_query($conn, "INSERT INTO notifications (recipient_type, recipient_id, title, message) VALUES ('user', '$user_id', 'Order Placed', 'Your order {$order_code} has been placed successfully.')") or die('query failed: ' . mysqli_error($conn));

    foreach($cart_items as $cart_item){
        $book_id = $cart_item['book_id'];
        $quantity = $cart_item['quantity'];
        $price = isset($cart_item['price']) ? (float)$cart_item['price'] : 0;

        mysqli_query($conn, "INSERT INTO `purchases` (user_id, book_id, quantity) VALUES ('$user_id', '$book_id', '$quantity')") or die('query failed: ' . mysqli_error($conn));
        mysqli_query($conn, "INSERT INTO `order_items` (order_id, book_id, quantity, price) VALUES ('$order_id', '$book_id', '$quantity', '$price')") or die('query failed: ' . mysqli_error($conn));
    }
}

mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed: ' . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Complete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-book-open"></i> Book Hub
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link" href="index.php#books-section">Books</a>
                <a class="nav-link" href="favorites.php">
                    <i class="fas fa-heart"></i> Favorites
                </a>
                <a class="nav-link" href="cart.php">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a class="nav-link" href="purchases.php">
                    <i class="fas fa-history"></i> Purchases
                </a>
                <a class="nav-link" href="notifications.php">
                    <i class="fas fa-bell"></i> Notifications
                </a>
                <?php if(isset($_SESSION['admin_id'])): ?>
                <a class="nav-link" href="admin/admin.php">
                    <i class="fas fa-user-shield"></i> Admin
                </a>
                <?php endif; ?>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 text-center">
    <div class="card p-4 shadow-sm">
        <h2 class="mb-3">Thank you for your purchase!</h2>
        <p>Your order has been placed successfully.</p>
        <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
    </div>
</div>

<footer class="bg-dark text-light mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Book Hub</h5>
                <p>Your one-stop destination for quality books at affordable prices.</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-light">Home</a></li>
                    <li><a href="cart.php" class="text-light">Cart</a></li>
                    <li><a href="purchases.php" class="text-light">Purchases</a></li>
                    <li><a href="logout.php" class="text-light">Logout</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <p>Email: info@bookhub.com</p>
                <p>Phone: +92-123-4567890</p>
                <p>&copy; 2026 Book Hub. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar-custom');
    if (navbar && window.scrollY > 50) {
        navbar.classList.add('navbar-scrolled');
    } else if (navbar) {
        navbar.classList.remove('navbar-scrolled');
    }
});
</script>

</body>
</html>
