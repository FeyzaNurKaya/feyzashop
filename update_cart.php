<?php
include 'baglanti.php'; // Veritabanı bağlantısı

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_key = intval($_POST['product_key']);
    $new_quantity = intval($_POST['quantity']);

    // Sepetteki ürün miktarını güncelle
    if (isset($_SESSION['cart'][$product_key])) {
        $_SESSION['cart'][$product_key]['quantity'] = $new_quantity;
    }

    echo "Sepet güncellendi.";
}

header('Location: cart.php'); // Sepet sayfasına yönlendir
exit;
?>
