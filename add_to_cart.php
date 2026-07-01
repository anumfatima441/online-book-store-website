<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    exit;
}

if(isset($_GET['book_id'])){
    $book_id = intval($_GET['book_id']);
    $user_id = $_SESSION['user_id'];

    $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id' AND book_id = '$book_id'") or die('query failed: ' . mysqli_error($conn));

    if(mysqli_num_rows($check_cart) > 0){
        mysqli_query($conn, "UPDATE `cart` SET quantity = quantity + 1 WHERE user_id = '$user_id' AND book_id = '$book_id'") or die('query failed: ' . mysqli_error($conn));
    }else{
        mysqli_query($conn, "INSERT INTO `cart` (user_id, book_id, quantity) VALUES ('$user_id', '$book_id', 1)") or die('query failed: ' . mysqli_error($conn));
    }
}

header('location:cart.php');
exit;
