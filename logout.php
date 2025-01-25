<?php
session_start();
session_unset(); // Tüm oturum değişkenlerini kaldır
session_destroy(); // Oturumu tamamen yok et
header("Location: account.php"); // Anasayfaya yönlendir
exit();
?>

