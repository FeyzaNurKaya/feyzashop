<?php
session_start(); // Session başlat

// Sepet için session başlatın
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Ürün bilgilerini alın
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$product_image = $_POST['product_image'];
$quantity = $_POST['quantity'];
$size = $_POST['size'];

// Sepete eklemek için ürün array'i oluşturun
$product = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $product_price,
    'image' => $product_image,
    'quantity' => $quantity,
    'size' => $size
];

// Ürünü sepet array'ine ekleyin
$_SESSION['cart'][] = $product;

// Sepet sayfasına yönlendir
header('Location: cart.php');
exit();
