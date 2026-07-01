<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$purchases_query = mysqli_query($conn, "SELECT purchases.quantity, purchases.purchased_at, books.* FROM `purchases` JOIN `books` ON purchases.book_id = books.id WHERE purchases.user_id = '$user_id' ORDER BY purchases.purchased_at DESC") or die('query failed: ' . mysqli_error($conn));

$book_covers = [
    'The Great Gatsby' => 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg',
    'To Kill a Mockingbird' => 'https://covers.openlibrary.org/b/isbn/9780061120084-L.jpg',
    'Introduction to Algorithms' => 'https://covers.openlibrary.org/b/isbn/9780262033848-L.jpg',
    'Clean Code' => 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg',
    'The Pragmatic Programmer' => 'https://covers.openlibrary.org/b/isbn/9780201616224-L.jpg',
    'Computer Networks' => 'https://covers.openlibrary.org/b/isbn/9780132126953-L.jpg',
    'Operating System Concepts' => 'https://covers.openlibrary.org/b/isbn/9781118063330-L.jpg',
    'Database System Concepts' => 'https://covers.openlibrary.org/b/isbn/9780073523323-L.jpg',
    'Artificial Intelligence: A Modern Approach' => 'https://covers.openlibrary.org/b/isbn/9780136042594-L.jpg',
    'Python Crash Course' => 'https://covers.openlibrary.org/b/isbn/9781593276034-L.jpg',
    'Head First Design Patterns' => 'https://covers.openlibrary.org/b/isbn/9780596007126-L.jpg',
    'Code Complete' => 'https://covers.openlibrary.org/b/isbn/9780735619678-L.jpg',
    'A Brief History of Time' => 'https://covers.openlibrary.org/b/isbn/9780553380163-L.jpg',
    'The Selfish Gene' => 'https://covers.openlibrary.org/b/isbn/9780198788607-L.jpg',
    // New History Books
    'Sapiens: A Brief History of Humankind' => 'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg',
    'Guns, Germs, and Steel: The Fates of Human Societies' => 'https://covers.openlibrary.org/b/isbn/9780393317558-L.jpg',
    'The Rise and Fall of the Third Reich' => 'https://covers.openlibrary.org/b/isbn/9781451651683-L.jpg',
    'The Wright Brothers' => 'https://covers.openlibrary.org/b/isbn/9781476728742-L.jpg',
    // New Science Books
    'Cosmos' => 'https://covers.openlibrary.org/b/isbn/9780345331359-L.jpg',
    'The Gene: An Intimate History' => 'https://covers.openlibrary.org/b/isbn/9781476733500-L.jpg',
    'The Body: A Guide for Occupants' => 'https://covers.openlibrary.org/b/isbn/9780385539302-L.jpg',
    'The Immortal Life of Henrietta Lacks' => 'https://covers.openlibrary.org/b/isbn/9781400052189-L.jpg',
    'The Emperor of All Maladies: A Biography of Cancer' => 'https://covers.openlibrary.org/b/isbn/9781439170915-L.jpg',
    'Thinking, Fast and Slow' => 'https://covers.openlibrary.org/b/isbn/9780374533557-L.jpg',
    'The Sixth Extinction: An Unnatural History' => 'https://covers.openlibrary.org/b/isbn/9780805092998-L.jpg',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchased Books - Online Book Store</title>
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
                <a class="nav-link active" href="purchases.php">
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

<div class="container mt-5">
    <h2>Purchased Books</h2>
    <?php if(mysqli_num_rows($purchases_query) > 0){ ?>
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Author</th>
                        <th>Quantity</th>
                        <th>Purchased At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($purchase = mysqli_fetch_assoc($purchases_query)){
                        $image = $purchase['image'];
                        if(empty($image) || strpos($image, 'via.placeholder.com') !== false){
                            $image = $book_covers[$purchase['title']] ?? 'https://via.placeholder.com/300x300?text=No+Image';
                        }
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo $image; ?>" alt="Book Cover" style="width: 50px; height: 70px; object-fit: cover;">
                            <?php echo $purchase['title']; ?>
                        </td>
                        <td><?php echo $purchase['author']; ?></td>
                        <td><?php echo $purchase['quantity']; ?></td>
                        <td><?php echo date('d M Y, H:i', strtotime($purchase['purchased_at'])); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p class="mt-4">You have not bought any books yet.</p>
    <?php } ?>
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
