<?php
session_start();
include 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Please login to add favorites']);
    exit;
}

// Get book ID from request
if(!isset($_GET['book_id'])){
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Book ID not provided']);
    exit;
}

// ENSURE FAVORITES TABLE EXISTS - Create it if missing
$check_table = mysqli_query($conn, "SHOW TABLES LIKE 'favorites'");
if(mysqli_num_rows($check_table) == 0){
    // Table doesn't exist, create it
    $create_table_sql = "CREATE TABLE IF NOT EXISTS favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        book_id INT NOT NULL,
        added_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_favorite (user_id, book_id)
    )";
    mysqli_query($conn, $create_table_sql);
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['book_id']);

// Check if book exists
$book_check = mysqli_query($conn, "SELECT id FROM books WHERE id = '$book_id'");
if(mysqli_num_rows($book_check) == 0){
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Book not found']);
    exit;
}

// Check if already in favorites
$favorite_check = mysqli_query($conn, "SELECT id FROM favorites WHERE user_id = '$user_id' AND book_id = '$book_id'");

if(mysqli_num_rows($favorite_check) > 0){
    // Remove from favorites
    mysqli_query($conn, "DELETE FROM favorites WHERE user_id = '$user_id' AND book_id = '$book_id'") or die('query failed');
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Removed from favorites', 'is_favorite' => false]);
} else {
    // Add to favorites
    mysqli_query($conn, "INSERT INTO favorites (user_id, book_id) VALUES ('$user_id', '$book_id')") or die('query failed');
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Added to favorites', 'is_favorite' => true]);
}
?>
