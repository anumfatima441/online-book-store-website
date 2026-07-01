<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home - Online Book Store</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <a class="nav-link" href="home.php">Home</a>
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

<div class="container mt-5">
    <div class="mb-4">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
        <p>Explore our collection of books.</p>
    </div>
    
    <div class="row">
        <?php
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
        ];

        $select_books = mysqli_query($conn, "SELECT * FROM `books`") or die('query failed');
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
                    $image = $book_covers[$fetch_books['title']] ?? 'https://via.placeholder.com/300x300?text=No+Image';
                }
        ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="<?php echo $image; ?>" class="card-img-top" alt="Book Cover" onerror="this.onerror=null; this.src='https://via.placeholder.com/300x300?text=No+Image';">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $fetch_books['title']; ?></h5>
                    <p class="card-text"><small>By <?php echo $fetch_books['author']; ?></small></p>
                    <p class="card-text"><strong>Rs. <?php echo $fetch_books['price']; ?></strong></p>
                    <div class="rating mb-2">
                        <small><?php echo $stars; ?> (<?php echo $rating; ?>/5)</small>
                    </div>
                    <p class="card-text"><small><?php echo substr($fetch_books['description'], 0, 100); ?>...</small></p>
                    <a href="add_to_cart.php?book_id=<?php echo $fetch_books['id']; ?>" class="btn btn-primary btn-sm">Add to Cart</a>
                </div>
            </div>
        </div>
        <?php
            }
        }else{
            echo '<p class="empty">No books available!</p>';
        }
        ?>
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

</body>
</html>