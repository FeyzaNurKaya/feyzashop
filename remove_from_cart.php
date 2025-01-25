<?php
session_start();
include 'baglanti.php'; // Veritabanı bağlantısını dahil et

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = $_POST['product_key'];

    if (isset($_SESSION['cart'][$key])) {
        // Sadece sepetten ürünü kaldır
        unset($_SESSION['cart'][$key]);
    }
}

header('Location: cart.php');
exit;
?>
