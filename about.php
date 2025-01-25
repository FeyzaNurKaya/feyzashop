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
	<!-- about -->

    <section id="about">
        <div class="about-content">
            <div class="about-img">
                <img src="img/about/a6.jpg" alt="">
            </div>
            <div class="about-text">
                <h1>Biz Kimiz ? </h1>
                <p>
                    Selçuk Üniversitesi Bilgisayar Mühendisliği 4.sınıf öğrencisi Feyza Nur Kaya olarak
					bu e-ticaret sitesini sizlere sunmuş bulunmaktayım. Bu web sitesini yapmamdaki amaç insanlarımıza güvenilir,
					hızlı ve kolay bir şekilde alışveriş deneyimi sunmaktır.
                <br />
                <br />
                <marquee behavior="" direction="left" loop="-1" scrollamount="5">🎉🎉🎉 FeyzaShop sitesinde 40% ye varan indirimlerden faydalanmak için hemen sitede gezinin 🎉🎉🎉</marquee>
            </div>
        </div>
    </section>
	
	<script>
        // Chatbot penceresini açma
        function openChatbox() {
            var chatbox = document.getElementById('chatbox');
            chatbox.style.display = 'flex';
        }

        // Chatbot penceresini kapatma
        function closeChatbox() {
            var chatbox = document.getElementById('chatbox');
            chatbox.style.display = 'none';
        }
    </script>
	
	<!-- feature -->
    <section id="feature" class="section-p1">
        <div class="fe-box">
            <img src="img/features/f1.png" alt="">
            <h6>Ücretsiz kargo</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f2.png" alt="">
            <h6>Online Sipariş</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f3.png" alt="">
            <h6>Para Biriktir</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f4.png" alt="">
            <h6>Promosyonlar</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f5.png" alt="">
            <h6>Mutlu Satış</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f6.png" alt="">
            <h6>7/24 İletişim</h6>
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
	<!-- Chatbot Button -->
    <div id="chatbot-button" onclick="openChatbox()">
        <i class="fa-solid fa-comment-dots"></i>
    </div>

    <!-- Chatbot -->
    <div id="chatbox">
        <div id="chatbox-header">
            <button onclick="closeChatbox()">X</button>
        </div>
        <div id="messages"></div>
        <div id="input">
            <input type="text" id="message" placeholder="Mesajınızı yazın..." />
            <button onclick="sendMessage()">Gönder</button>
        </div>
    </div>
	<!-- Include Chatbot -->
    <?php include 'chatbot.php'; ?>
	</body>


</html>