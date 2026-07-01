<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if(isset($_GET['mark_all'])){
    mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE recipient_type = 'user' AND recipient_id = '$user_id'");
    header('Location: notifications.php');
    exit();
}

$notifications_query = mysqli_query($conn, "SELECT * FROM notifications WHERE recipient_type = 'user' AND recipient_id = '$user_id' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Book Hub</title>
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
                <a class="nav-link active" href="notifications.php">
                    <i class="fas fa-bell"></i> Notifications
                </a>
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Notifications</h3>
                    <a href="notifications.php?mark_all=1" class="btn btn-sm btn-outline-primary">Mark All Read</a>
                </div>
                <div class="card-body">
                    <?php if($notifications_query && mysqli_num_rows($notifications_query) > 0): ?>
                        <div class="list-group">
                            <?php while($notification = mysqli_fetch_assoc($notifications_query)): ?>
                                <div class="list-group-item list-group-item-action mb-2 <?php echo $notification['is_read'] ? '' : 'border-start border-4 border-primary'; ?>">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?php echo htmlspecialchars($notification['title']); ?></h5>
                                        <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($notification['created_at'])); ?></small>
                                    </div>
                                    <p class="mb-1 text-muted"><?php echo htmlspecialchars($notification['message']); ?></p>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <p class="mb-0">You have no notifications yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
