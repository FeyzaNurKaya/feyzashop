<?php

$vt_sunucu = "";
$vt_kullanici = "";
$vt_sifre = "";
$vt_adi = "";

// MySQLi bağlantısı oluşturma
$baglan = mysqli_connect($vt_sunucu, $vt_kullanici, $vt_sifre, $vt_adi);

// Bağlantıyı kontrol etme
if (!$baglan) {
    die("Veritabanı bağlantı işlemi başarısız" . mysqli_connect_error());
}

// Ürünleri çekme
$sql = "SELECT * FROM products";
$result = mysqli_query($baglan, $sql);


?>
