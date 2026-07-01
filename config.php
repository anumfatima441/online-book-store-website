<?php

// Database connection details
$conn = mysqli_connect('localhost', 'root', '', 'online_book_store') or die('Connection failed');

// Check connection (Optional: for debugging)
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure notifications table exists
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_type ENUM('admin','user') NOT NULL,
    recipient_id INT DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)") or die('Notification table creation failed: ' . mysqli_error($conn));

?>