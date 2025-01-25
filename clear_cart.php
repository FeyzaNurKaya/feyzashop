<?php
session_start();
// Sepeti temizle
$_SESSION['cart'] = [];
http_response_code(200); // Başarılı yanıt
?>
