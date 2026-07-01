<?php
session_start();
include 'config.php';

// Redirect to login if not authenticated
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

// Check if book_id is provided
if(!isset($_GET['book_id']) || empty($_GET['book_id'])){
    header('location:index.php');
    exit;
}

$book_id = intval($_GET['book_id']);

// Fetch book details
$book_query = mysqli_query($conn, "SELECT * FROM `books` WHERE id = '$book_id'") or die('query failed: ' . mysqli_error($conn));

if(mysqli_num_rows($book_query) == 0){
    header('location:index.php');
    exit;
}

$book = mysqli_fetch_assoc($book_query);

// Book covers mapping
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

$image = $book['image'];
if(empty($image) || strpos($image, 'via.placeholder.com') !== false){
    $image = $book_covers[$book['title']] ?? 'https://via.placeholder.com/400x600?text=No+Image';
}

// Generate stars
$rating = $book['rating'];
$stars = '';
for($i = 1; $i <= 5; $i++){
    if($i <= $rating){
        $stars .= '★';
    }else{
        $stars .= '☆';
    }
}

// Check if book is in user's favorites
$is_favorite = false;
if(isset($_SESSION['user_id'])){
    $fav_check = mysqli_query($conn, "SELECT id FROM favorites WHERE user_id = '".$_SESSION['user_id']."' AND book_id = '$book_id'");
    if($fav_check && mysqli_num_rows($fav_check) > 0){
        $is_favorite = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $book['title']; ?> - Book Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .breadcrumb {
            background: transparent;
            padding: 1rem 0;
            font-size: 0.95rem;
        }

        .book-details-container {
            padding: 3rem 0;
            min-height: 80vh;
        }

        .book-image-large {
            width: 100%;
            max-width: 400px;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
        }

        .book-info-section {
            padding: 2rem 0;
        }

        .book-title-large {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .book-author-large {
            font-size: 1.3rem;
            color: var(--muted);
            margin-bottom: 1rem;
        }

        .book-rating-large {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stars-large {
            font-size: 1.5rem;
            color: var(--accent);
            letter-spacing: 0.2rem;
        }

        .rating-text {
            color: var(--muted);
            font-size: 1.1rem;
        }

        .book-price-large {
            font-size: 2.5rem;
            color: var(--secondary);
            font-weight: 800;
            margin: 1.5rem 0;
        }

        .price-label {
            color: var(--muted);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }

        .description-section {
            padding: 2rem 0;
            border-top: 2px solid var(--border);
            margin: 2rem 0;
        }

        .description-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .description-text {
            font-size: 1.05rem;
            color: #333;
            line-height: 1.8;
            text-align: justify;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }

        .btn-large {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .btn-add-cart {
            background: var(--secondary);
            color: white;
            border: none;
            flex: 1;
            min-width: 200px;
        }

        .btn-add-cart:hover {
            background: #0284c7;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(14, 165, 233, 0.4);
        }

        .btn-back {
            background: transparent;
            color: var(--secondary);
            border: 2px solid var(--secondary);
            flex: 1;
            min-width: 200px;
        }

        .btn-back:hover {
            background: var(--secondary);
            color: white;
        }

        .btn-favorite {
            background: #f8f9fa;
            color: #dc3545;
            border: 2px solid #dc3545;
            flex: 1;
            min-width: 200px;
            transition: all 0.3s ease;
        }

        .btn-favorite:hover {
            background: #dc3545;
            color: white;
            transform: scale(1.02);
        }

        .btn-favorite.is-favorite {
            background: #dc3545;
            color: white;
        }

        .btn-favorite.is-favorite:hover {
            background: #bb2d3b;
            color: white;
        }

        .book-info-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
            padding: 2rem;
            background: #f8fafc;
            border-radius: 1rem;
        }

        .meta-item {
            text-align: center;
        }

        .meta-label {
            font-size: 0.85rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            margin-bottom: 0.5rem;
        }

        .meta-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
        }

        .related-books {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 2px solid var(--border);
        }

        .related-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .book-title-large {
                font-size: 1.8rem;
            }

            .book-price-large {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-large {
                width: 100%;
            }
        }
    </style>
</head>
<body>

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

<!-- Main Content -->
<main class="book-details-container">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="index.php#books-section">Books</a></li>
                <li class="breadcrumb-item active"><?php echo substr($book['title'], 0, 40); ?></li>
            </ol>
        </nav>

        <!-- Book Details -->
        <div class="row">
            <!-- Book Image -->
            <div class="col-lg-4">
                <img src="<?php echo $image; ?>" alt="<?php echo $book['title']; ?>" 
                     class="book-image-large" 
                     onerror="this.onerror=null; this.src='https://via.placeholder.com/400x600?text=No+Image';">
            </div>

            <!-- Book Information -->
            <div class="col-lg-8">
                <div class="book-info-section">
                    <h1 class="book-title-large"><?php echo $book['title']; ?></h1>
                    <p class="book-author-large">
                        <i class="fas fa-pen-fancy"></i> by <?php echo $book['author']; ?>
                    </p>

                    <!-- Rating -->
                    <div class="book-rating-large">
                        <div>
                            <span class="stars-large"><?php echo $stars; ?></span>
                            <span class="rating-text"><?php echo $rating; ?>/5</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="price-label">Price</div>
                    <div class="book-price-large">Rs. <?php echo number_format($book['price'], 0); ?></div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <a href="add_to_cart.php?book_id=<?php echo $book['id']; ?>" class="btn btn-large btn-add-cart">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </a>
                        <?php } else { ?>
                            <a href="login.php" class="btn btn-large btn-add-cart">
                                <i class="fas fa-sign-in-alt"></i> Login to Buy
                            </a>
                        <?php } ?>
                        <a href="index.php#books-section" class="btn btn-large btn-back">
                            <i class="fas fa-arrow-left"></i> Back to Books
                        </a>
                        <?php if(isset($_SESSION['user_id'])){ ?>
                            <button class="btn btn-large btn-favorite <?php echo $is_favorite ? 'is-favorite' : ''; ?>" 
                                    onclick="toggleFavorite(<?php echo $book['id']; ?>)" 
                                    id="favorite-btn">
                                <i class="fas fa-heart"></i> <span id="fav-text"><?php echo $is_favorite ? 'Remove' : 'Add'; ?> to Favorites</span>
                            </button>
                        <?php } else { ?>
                            <a href="login.php" class="btn btn-large btn-favorite">
                                <i class="fas fa-heart"></i> Add to Favorites
                            </a>
                        <?php } ?>
                    </div>

                    <!-- Book Meta Information -->
                    <div class="book-info-meta">
                        <div class="meta-item">
                            <div class="meta-label">Book ID</div>
                            <div class="meta-value">#<?php echo $book['id']; ?></div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Rating</div>
                            <div class="meta-value"><?php echo $rating; ?> ⭐</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Price</div>
                            <div class="meta-value">Rs. <?php echo number_format($book['price'], 0); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="description-section">
            <h2 class="description-title">
                <i class="fas fa-book-reader"></i> About This Book
            </h2>
            <p class="description-text">
                <?php echo nl2br($book['description']); ?>
            </p>
        </div>

        <!-- Related Books -->
        <div class="related-books">
            <h2 class="related-title">
                <i class="fas fa-similar-books"></i> More Books You Might Like
            </h2>
            <div class="row">
                <?php
                $related_query = mysqli_query($conn, "SELECT * FROM `books` WHERE id != '$book_id' LIMIT 4") or die('query failed');
                
                while($related_book = mysqli_fetch_assoc($related_query)){
                    $rel_image = $related_book['image'];
                    if(empty($rel_image) || strpos($rel_image, 'via.placeholder.com') !== false){
                        $rel_image = $book_covers[$related_book['title']] ?? 'https://via.placeholder.com/400x600?text=No+Image';
                    }
                    $rel_rating = $related_book['rating'];
                    $rel_stars = '';
                    for($i = 1; $i <= 5; $i++){
                        if($i <= $rel_rating){
                            $rel_stars .= '★';
                        }else{
                            $rel_stars .= '☆';
                        }
                    }
                ?>
                <div class="col-md-6 col-lg-3">
                    <div class="book-card">
                        <div class="book-image-wrapper">
                            <a href="book-details.php?book_id=<?php echo $related_book['id']; ?>" style="text-decoration: none; color: inherit;">
                                <img src="<?php echo $rel_image; ?>" class="book-image" alt="<?php echo $related_book['title']; ?>" 
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/400x600?text=No+Image';">
                            </a>
                            <div class="book-overlay">
                                <a href="book-details.php?book_id=<?php echo $related_book['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                        <div class="book-info">
                            <h5 class="book-title"><a href="book-details.php?book_id=<?php echo $related_book['id']; ?>" style="text-decoration: none; color: inherit;"><?php echo substr($related_book['title'], 0, 30); ?></a></h5>
                            <p class="book-author"><?php echo substr($related_book['author'], 0, 25); ?></p>
                            <p class="book-description-preview">
                                <?php echo substr($related_book['description'], 0, 85); ?>...
                            </p>
                            <div class="book-rating">
                                <span class="stars"><?php echo $rel_stars; ?></span>
                                <span class="rating-value"><?php echo $rel_rating; ?>/5</span>
                            </div>
                            <div class="book-footer">
                                <span class="book-price">Rs. <?php echo number_format($related_book['price'], 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row g-5 mb-4">
            <div class="col-md-4">
                <h5 class="footer-title">
                    <i class="fas fa-book-open"></i> Book Hub
                </h5>
                <p>Your one-stop destination for quality books at affordable prices.</p>
            </div>
            <div class="col-md-4">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#books-section">Books</a></li>
                    <li><a href="<?php echo isset($_SESSION['user_id']) ? 'purchases.php' : 'login.php'; ?>">My Orders</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="footer-title">Contact Us</h5>
                <p>
                    <i class="fas fa-envelope"></i> info@bookhub.com<br>
                    <i class="fas fa-phone"></i> +92-123-456-7890
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Book Hub. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Navbar scroll effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar-custom');
    if (window.scrollY > 50) {
        navbar.classList.add('navbar-scrolled');
    } else {
        navbar.classList.remove('navbar-scrolled');
    }
});

// Toggle Favorite Function
function toggleFavorite(bookId) {
    fetch('toggle_favorite.php?book_id=' + bookId)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const btn = document.getElementById('favorite-btn');
                const txtSpan = document.getElementById('fav-text');
                
                if(data.is_favorite) {
                    btn.classList.add('is-favorite');
                    txtSpan.textContent = 'Remove from Favorites';
                } else {
                    btn.classList.remove('is-favorite');
                    txtSpan.textContent = 'Add to Favorites';
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
}
</script>

</body>
</html>
