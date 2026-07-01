<?php
session_start();
include 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get all favorite books for this user
$favorites_query = "SELECT b.* FROM books b
                    INNER JOIN favorites f ON b.id = f.book_id
                    WHERE f.user_id = '$user_id'
                    ORDER BY f.added_at DESC";
$favorites_result = mysqli_query($conn, $favorites_query);
$favorite_books = [];
if($favorites_result){
    while($book = mysqli_fetch_assoc($favorites_result)){
        $favorite_books[] = $book;
    }
}

// Get user info
$user_query = mysqli_query($conn, "SELECT name FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($user_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites - Online Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .favorites-container {
            min-height: 70vh;
            padding: 40px 0;
        }

        .favorites-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 0;
        }

        .favorites-header h1 {
            font-size: 2.5rem;
            color: #001f3f;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .favorites-header p {
            font-size: 1.1rem;
            color: #666;
            margin: 0;
        }

        .empty-favorites {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-favorites i {
            font-size: 80px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-favorites h3 {
            color: #666;
            margin-bottom: 10px;
        }

        .empty-favorites p {
            color: #999;
            margin-bottom: 20px;
        }

        .empty-favorites .btn {
            margin-top: 10px;
        }

        .favorites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .favorite-book-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .favorite-book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .favorite-book-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .favorite-book-info {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .favorite-book-title {
            font-size: 1rem;
            font-weight: bold;
            color: #001f3f;
            margin-bottom: 5px;
            line-height: 1.3;
            flex: 1;
        }

        .favorite-book-author {
            font-size: 0.85rem;
            color: #0ea5e9;
            margin-bottom: 8px;
        }

        .favorite-book-price {
            font-size: 1.2rem;
            font-weight: bold;
            color: #f59e0b;
            margin-bottom: 10px;
        }

        .favorite-book-rating {
            font-size: 0.9rem;
            color: #f59e0b;
            margin-bottom: 10px;
        }

        .favorite-book-actions {
            display: flex;
            gap: 8px;
        }

        .favorite-book-actions .btn {
            font-size: 0.85rem;
            padding: 6px 12px;
            flex: 1;
        }

        .btn-favorite-remove {
            background-color: #ef4444;
            color: white;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-favorite-remove:hover {
            background-color: #dc2626;
            color: white;
        }

        .btn-view-details {
            background-color: #0ea5e9;
            color: white;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-view-details:hover {
            background-color: #0284c7;
            color: white;
        }

        @media (max-width: 768px) {
            .favorites-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }

            .favorites-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(90deg, #001f3f 0%, #1e3a8a 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php" style="font-weight: bold; font-size: 1.5rem;">
                <i class="fas fa-book"></i> BookStore
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#books-section"><i class="fas fa-book"></i> Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="favorites.php"><i class="fas fa-heart"></i> My Favorites</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="purchases.php"><i class="fas fa-history"></i> Purchases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notifications.php"><i class="fas fa-bell"></i> Notifications</a>
                    </li>
                    <?php if(isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/admin.php"><i class="fas fa-user-shield"></i> Admin</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container" style="margin-top: 100px;">
        <!-- Favorites Header -->
        <div class="favorites-header">
            <h1><i class="fas fa-heart"></i> My Favorite Books</h1>
            <p>Welcome back, <strong><?php echo htmlspecialchars($user['name']); ?></strong>!</p>
            <?php if(count($favorite_books) > 0): ?>
                <p style="color: #0ea5e9; font-size: 1rem; margin-top: 10px;">You have <strong><?php echo count($favorite_books); ?></strong> favorite book<?php echo count($favorite_books) != 1 ? 's' : ''; ?></p>
            <?php endif; ?>
        </div>

        <!-- Empty State -->
        <?php if(count($favorite_books) == 0): ?>
            <div class="empty-favorites">
                <i class="fas fa-heart-broken"></i>
                <h3>No Favorites Yet</h3>
                <p>You haven't added any books to your favorites list yet.</p>
                <p>Start exploring our collection and add your favorite books!</p>
                <a href="index.php" class="btn btn-primary" style="background-color: #0ea5e9; border: none;">
                    <i class="fas fa-search"></i> Browse Books
                </a>
            </div>
        <?php else: ?>
            <!-- Favorites Grid -->
            <div class="favorites-grid">
                <?php foreach($favorite_books as $book): ?>
                    <div class="favorite-book-card">
                        <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="favorite-book-image">
                        
                        <div class="favorite-book-info">
                            <h5 class="favorite-book-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                            <p class="favorite-book-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                            
                            <div class="favorite-book-rating">
                                <?php 
                                $rating = intval($book['rating']);
                                for($i = 0; $i < 5; $i++): 
                                    if($i < $rating):
                                        echo '<i class="fas fa-star"></i>';
                                    else:
                                        echo '<i class="far fa-star"></i>';
                                    endif;
                                endfor;
                                ?>
                                (<?php echo htmlspecialchars($book['rating']); ?>/5)
                            </div>

                            <p class="favorite-book-price">Rs. <?php echo number_format($book['price'], 2); ?></p>

                            <div class="favorite-book-actions">
                                <a href="book-details.php?book_id=<?php echo $book['id']; ?>" class="btn btn-view-details btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="btn btn-favorite-remove btn-sm" onclick="removeFavorite(<?php echo $book['id']; ?>)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer style="background: linear-gradient(90deg, #001f3f 0%, #1e3a8a 100%); color: white; padding: 30px 0; margin-top: 40px;">
        <div class="container text-center">
            <p>&copy; 2024 Online Bookstore. All rights reserved.</p>
            <p>
                <a href="index.php" style="color: #0ea5e9; text-decoration: none;">Home</a> | 
                <a href="cart.php" style="color: #0ea5e9; text-decoration: none;">Cart</a> | 
                <a href="purchases.php" style="color: #0ea5e9; text-decoration: none;">Purchases</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function removeFavorite(bookId) {
            if(confirm('Are you sure you want to remove this book from favorites?')){
                fetch('toggle_favorite.php?book_id=' + bookId)
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            // Reload page to update the list
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred');
                    });
            }
        }
    </script>
</body>
</html>
