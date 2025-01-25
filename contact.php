<?php
session_start(); 
include 'baglanti.php';

$login_message = '';
$register_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['sifre'])) {
        // Giriş işlemi
        $username = mysqli_real_escape_string($baglan, $_POST['username']);
        $password = mysqli_real_escape_string($baglan, $_POST['sifre']);

        $sql = "SELECT id FROM kullanicilar WHERE (kullanici_adi = '$username' OR email = '$username') AND password = '$password'";
        $result = $baglan->query($sql);

        if ($result->num_rows > 0) {
    $_SESSION['kullanici_adi'] = $username; // Oturum değişkeni burada set ediliyor
    header("Location: index.php");
    exit();
} else {
    $login_message = "Geçersiz kullanıcı adı veya şifre.";
}

    } 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FeyzaShop</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/bffc59eac1.js" crossorigin="anonymous"></script>
</head>

<body>

<!-- header -->
<section id="header">
    <a href="#"><img src="img/logo.png" alt="logo" class="logo"></a>
    <div class="search-container">
        <form id="searchForm" onsubmit="return false;">
            <input type="text" id="searchQuery" name="query" placeholder="Aradığınız ürünü yazınız" onkeyup="suggest(this.value)">
            <button type="submit" onclick="performSearch()"><i class="fa fa-search"></i></button>
        </form>
        <div id="suggestions"></div> 
    </div>
    <div>
        <ul id="navbar">
            <li><a href="index.php">Anasayfa</a></li>
            <li><a href="shop.php">Alışveriş</a></li>
            <li><a href="about.php">Hakkımızda</a></li>
            <li><a href="contact.php">İletişim</a></li>
            <li><a href="account.php">Hesap</a></li>
            <li id="lg-bag"><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
            <a href="#" id="close"><i class="fas fa-times"></i></a>
        </ul>
    </div>
</section>

	<!-- Arama Sonuçlarının Gösterileceği Alan -->
<div id="searchResults"></div>

<script>
function suggest(query) {
    if (query.length === 0) {
        document.getElementById("suggestions").innerHTML = "";
        return;
    }
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("suggestions").innerHTML = xhr.responseText;
        }
    };
    xhr.open("GET", "autocomplete.php?q=" + query, true);
    xhr.send();
}

function fillSearch(value) {
    document.getElementById('searchQuery').value = value;
    document.getElementById('suggestions').innerHTML = "";
}
function performSearch() {
    const query = document.getElementById('searchQuery').value;
    window.location.href = 'search_results.php?q=' + query;
}

</script>
<?php
if (isset($_SESSION['kullanici_adi'])) {
    $hosgeldiniz_mesaji = 'Hoş geldiniz, ' . htmlspecialchars($_SESSION['kullanici_adi']) . '!';
    $cikis_butonu = '<a href="logout.php" class="cikis-yap">Çıkış Yap</a>';
} else {
    $hosgeldiniz_mesaji = '';
    $cikis_butonu = '<a href="login.php" class="giris-yap">Giriş Yap</a>';
}
?>

<div class="welcome-message">
    <?php echo $hosgeldiniz_mesaji; ?>
    <?php echo $cikis_butonu; ?>
</div>
    <!-- contact  -->
    <section id="contact">
        <div class="contact-area">
            <div class="contact-text">
                <h3>TEMASTA OLALIM</h3>
                <h2>Acente lokasyonlarımızdan birini ziyaret edin veya bugün bizimle iletişime geçin</h2>
                <h3><b>Merkez</b></h3>
                <ul>
                    <li><a href="#"><i class="fa-regular fa-map"></i></a>34   Türkiye</li>
                    <li><a href="#"><i class="fa-regular fa-envelope"></i> </a>@gmail.com</li>
                    <li><a href="#"><i class="fa-solid fa-phone"></i></a>/li>
                    <li><a href="#"><i class="fa-regular fa-clock"></i></a> 10.00-18.00 Pazartesi-Cuma</li>
                </ul>
            </div>
            <div class="contact-map">
            </div>
        </div>
    </section>

    <section id="message">
        <div class="message-area">
            <div class="message-form">
                <h3>MESAJ BIRAKIN</h3>
                <h2>Sizden haber almayı seviyoruz</h2>
                <form action="contact.php" method="post">
                    <input type="text" name="isim" placeholder="Ad Soyad">
                    <input type="email" name="email" placeholder="E-mail adresiniz">
                    <input type="text" name="konu" placeholder="Konu">
                    <textarea  rows="10" cols="30" id="" name="mesaj" placeholder="Mesajın"></textarea>
                    <button type="submit">Gönder</button>
                </form>
            </div>
            <div class="message-person">
                <div class="person">
                    <img src="img/people/2.png" alt="">
                    <div class="person-info">
                        <h4>Feyza Kaya</h4>
                        <p>Kıdemli Yazılım Mühendisi</p>
                        <p>Phone : 22222222222 </p>
                        <p>Email : contact@gmail.com</p>
                    </div>
                </div>
                <div class="person">
                    <img src="img/people/1.png" alt="">
                    <div class="person-info">
                        <h4>Dilara Nisa Şenkulak</h4>
                        <p>Kıdemli Web Geliştiricisi</p>
                        <p>Phone : 33333333333 </p>
                        <p>Email : contact@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
   

    <!-- footer -->
    <footer class="section-p1">
        <div class="col">
            <img src="img/logo.png" alt="" class="logo">
            <h4><b>İletişim</b></h4>
            <ul>
                <li><a href=""><b>Adres: </b>Türkiye İstanbul / Başakşehir</a></li>
                <li><a href=""><b>Saatler: </b>10.00-18.00 Pazartesi-Cuma</a></li>
            </ul>

            <br />

            <h4><b>Bizi Takip Et</b></h4>
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-pinterest"></i></a>
            <a href="#"><i class="fa-brands fa-youtube"></i></a>
        </div>
        <div class="col">
            <h4><b>Hakkımızda</b></h4>
            <ul>
                <li><a href="#">Hakkımızda</a></li>
                <li><a href="#">Teslimat Bilgisi</a></li>
                <li><a href="#">Gizlilik Politikası</a></li>
                <li><a href="#">Şartlar ve Koşullar</a></li>
                <li><a href="#">Bize Ulaşın</a></li>
            </ul>
        </div>
        <div class="col">
            <h4><b>Hesabım</b></h4>
            <ul>
                <li><a href="#">Kayıt Ol</a></li>
                <li><a href="#">Sepeti Görüntüle</a></li>
                <li><a href="#">Favori Listem</a></li>
                <li><a href="#">Siparişim Nerde</a></li>
                <li><a href="#">Yardım</a></li>
            </ul>
        </div>
        <div class="col">
            <h4><b>Uygulamayı Yükle</b></h4>
            <p>App Store veya Google Play</p>
            <img class="border" src="img/pay/app.jpg" alt="">
            <img class="border" src="img/pay/play.jpg" alt="">
            <p>Güvenli Ödeme</p>
            <img src="img/pay/pay.png" alt="">
        </div>

    </footer>
    <section class="copyright section-p1">
        <center>&copy 2024 FeyzaShop </center>
    </section>



    <script src="script.js"></script>
</body>

</html>

<?php

include("baglanti.php");

if(isset($_POST["isim"], $_POST["email"], $_POST["konu"], $_POST["mesaj"]))
{
	$adsoyad=$_POST["isim"];
	$email=$_POST["email"];
	$konu=$_POST["konu"];
	$mesaj=$_POST["mesaj"];
	
	$ekle="INSERT INTO mesaj(adsoyad, email, konu, mesaj) 
	VALUES ('".$adsoyad."','".$email."','".$konu."','".$mesaj."')";
	
	if ($baglan->query($ekle)===TRUE)
	{
		echo "<script>
		alert('Mesajınız başarılı ile gönderilmiştir.')
		</script>";
	}
	
	else{
		echo " <script>
		alert('Mesajınız gönderilirken bir hata oluştu.')
		</scirpt>";
	}

}

 ?>