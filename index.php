<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hub - Discover Your Next Great Read</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();
include 'config.php';

// Redirect to login if not authenticated
if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])){
    header('Location: login.php');
    exit;
}

$notification_count = 0;
if(isset($_SESSION['user_id'])){
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM notifications WHERE recipient_type = 'user' AND recipient_id = '$user_id' AND is_read = 0");
    $notification_count = $count_result ? mysqli_fetch_assoc($count_result)['count'] : 0;
}
?>

<!-- Navigation -->
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
                <a class="nav-link" href="#books-section">Books</a>
                <a class="nav-link" href="favorites.php">
                    <i class="fas fa-heart"></i> Favorites
                </a>
                <a class="nav-link" href="cart.php">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a class="nav-link" href="purchases.php">
                    <i class="fas fa-history"></i> Purchases
                </a>
                <?php if(isset($_SESSION['user_id'])): ?>
                <a class="nav-link" href="notifications.php">
                    <i class="fas fa-bell"></i> Notifications
                    <?php if($notification_count > 0): ?>
                        <span class="badge bg-danger ms-1"><?php echo $notification_count; ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
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

<!-- Hero Section -->
<section class="hero-section">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Discover Your Next   <span class="gradient-text"> Favorite Book </span>
                    </h1>
                    <p class="hero-subtitle">
                         Shop trending books, timeless classics, and inspiring reads anytime, anywhere.
                    </p>
                    <div class="hero-buttons">
                        <a href="#books-section" class="btn btn-hero btn-primary">
                            <i class="fas fa-book"></i> Explore Books
                        </a>
                        <a href="<?php echo isset($_SESSION['user_id']) ? 'cart.php' : 'login.php'; ?>" class="btn btn-hero btn-outline-primary">
                            <i class="fas fa-shopping-cart"></i> Start Shopping
                        </a>
                    </div>
                    <div class="stats-container mt-5">
                        <div class="stat-item">
                            <h3>25+</h3>
                            <p>Premium Books</p>
                        </div>
                        <div class="stat-item">
                            <h3>100%</h3>
                            <p>Original Content</p>
                        </div>
                        <div class="stat-item">
                            <h3>24/7</h3>
                            <p>Available</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-illustration">
                    <div class="books-animation">
                        <div class="book book-1"></div>
                        <div class="book book-2"></div>
                        <div class="book book-3"></div>
                        <div class="reading-figure"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="search-section">
    <div class="container">
        <div class="search-container">
            <h2>Find Your Favorite Book</h2>
            <form method="get" class="search-form">
                <div class="input-group">
                    <i class="fas fa-search"></i>
                    <input type="search" name="search" placeholder="Search by title, author, or topic..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
                           class="form-control">
                    <button type="submit" class="btn btn-search">Search</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Books Section -->
<section class="books-section" id="books-section">

<div class="container py-5">
        <div class="section-header text-center mb-5">
            <h2>Featured Books</h2>
            <p class="text-muted">Choose from our premium collection</p>
        </div>

        <div class="row g-4">
            <?php
            $search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
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

            $query = "SELECT * FROM `books`";
            if($search_term !== ''){
                $query .= " WHERE title LIKE '%$search_term%' OR author LIKE '%$search_term%' OR description LIKE '%$search_term%'";
            } else {
                $query .= " LIMIT 16"; // Show 16 books on homepage to include new additions
            }

            $select_books = mysqli_query($conn, $query) or die('query failed: ' . mysqli_error($conn));
            if(mysqli_num_rows($select_books) > 0){
                while($fetch_books = mysqli_fetch_assoc($select_books)){
                    $rating = $fetch_books['rating'];
                    $stars = '';
                    for($i = 1; $i <= 5; $i++){
                        if($i <= $rating){
                            $stars .= '★';
                        }else{
                            $stars .= '☆';
                        }
                    }

                    $image = $fetch_books['image'];
                    if(empty($image) || strpos($image, 'via.placeholder.com') !== false){
                        $image = $book_covers[$fetch_books['title']] ?? 'https://via.placeholder.com/300x400?text=No+Image';
                    }
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="book-card">
                    <div class="book-image-wrapper">
                        <a href="book-details.php?book_id=<?php echo $fetch_books['id']; ?>" class="book-link">
                            <img src="<?php echo $image; ?>" class="book-image" alt="<?php echo $fetch_books['title']; ?>" 
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/300x400?text=No+Image';">
                        </a>
                        <div class="book-overlay">
                            <a href="book-details.php?book_id=<?php echo $fetch_books['id']; ?>" class="btn btn-sm btn-primary me-2">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="<?php echo isset($_SESSION['user_id']) ? 'add_to_cart.php?book_id='.$fetch_books['id'] : 'login.php'; ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-shopping-cart"></i> Add
                            </a>
                        </div>
                    </div>
                    <div class="book-info">
                        <a href="book-details.php?book_id=<?php echo $fetch_books['id']; ?>" class="book-title-link">
                            <h5 class="book-title"><?php echo substr($fetch_books['title'], 0, 30); ?></h5>
                        </a>
                        <p class="book-author"><?php echo substr($fetch_books['author'], 0, 25); ?></p>
                        <p class="book-description-preview">
                            <?php echo substr($fetch_books['description'], 0, 85); ?>...
                        </p>
                        <div class="book-rating">
                            <span class="stars"><?php echo $stars; ?></span>
                            <span class="rating-value"><?php echo $rating; ?>/5</span>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">Rs. <?php echo number_format($fetch_books['price'], 0); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><p class="text-center text-muted py-5">No books found. Try a different search.</p></div>';
            }
            ?>
        </div>

        <?php if($search_term === ''){ ?>
        <div class="text-center mt-5">
            <a href="#books-section" class="btn btn-lg btn-outline-primary">View All Books</a>
        </div>
        <?php } ?>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h4>Curated Collection</h4>
                    <p>Hand-picked books across all genres and categories</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Fast Checkout</h4>
                    <p>Quick and secure checkout process in seconds</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h4>Secure Payments</h4>
                    <p>Your data is always safe and protected</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row g-5 mb-4">
            <div class="col-md-4">
                <h5 class="footer-title">
                    <i class="fas fa-book-open"></i> Book Hub
                </h5>
                <p>Your one-stop destination for quality books at affordable prices. Discover new worlds through reading.</p>
            </div>
            <div class="col-md-4">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#books-section">Books</a></li>
                    <li><a href="<?php echo isset($_SESSION['user_id']) ? 'purchases.php' : 'login.php'; ?>">My Orders</a></li>
                    <li><a href="<?php echo isset($_SESSION['user_id']) ? 'cart.php' : 'login.php'; ?>">Cart</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="footer-title">Contact Us</h5>
                <p>
                    <i class="fas fa-envelope"></i> info@bookhub.com<br>
                    <i class="fas fa-phone"></i> +92-123-456-7890<br>
                    <i class="fas fa-map-marker-alt"></i> Karachi, Pakistan
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Book Hub. All rights reserved. | Designed with <i class="fas fa-heart" style="color: #e74c3c;"></i> for Book Lovers</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar-custom');
    if (window.scrollY > 50) {
        navbar.classList.add('navbar-scrolled');
    } else {
        navbar.classList.remove('navbar-scrolled');
    }
});
</script>

</body>
</html>