<?php
session_start(); 
include 'baglanti.php';

$login_message = '';
$register_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username']) && !empty($_POST['sifre'])) {
        $username = $_POST['username'];
        $password = $_POST['sifre'];

        // Hazırlanmış ifade kullanımı
        $stmt = $baglan->prepare("SELECT id FROM kullanicilar WHERE (kullanici_adi = ? OR email = ?) AND password = ?");
        $stmt->bind_param("sss", $username, $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['kullanici_adi'] = $username; // Oturum değişkeni burada set ediliyor
            header("Location: index.php");
            exit();
        } else {
            $login_message = "Geçersiz kullanıcı adı veya şifre.";
        }
    } else {
        $login_message = "Tüm alanları doldurmanız gerekiyor.";
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

<section class="account-page">
    <div class="container">
        <div class="account-wrapper">
            <div class="account-column">
                <h2>GİRİŞ</h2>
                <?php if ($login_message): ?>
                    <p class="error-message"><?= $login_message ?></p>
                <?php endif; ?>
                <form action="account.php" method="post">
                    <div>
                        <label>
                            <span>Kullanıcı Adı veya E-mail <span class="required">*</span></span>
                            <input type="text" name="username" required>
                        </label>
                    </div>
                    <div>
                        <label>
                            <span>Şifre <span class="required">*</span></span>
                            <input type="password" name="sifre" required>
                        </label>
                    </div>
                    <p class="remember">
                        <label>
                            <input type="checkbox">
                            <span>Beni Hatırla</span>
                        </label>
                        <button class="btn btn-sm" type="submit">Giriş</button>
                    </p>
                    <a href="forgot_password.php" class="form-link">Şifrenizi mi unuttunuz?</a>
                </form>
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
