<?php
session_start(); // Session'ı başlat
include 'baglanti.php';

// Sepeti al
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

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
    <title>Sepet</title>
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
<!-- Sepet -->
<section id="cart" class="section-p1">
    <div class="container">
        <h1>Sepetim</h1>

        <?php if (empty($cart)): ?>
            <p>Sepetinizde ürün bulunmamaktadır.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ÜRÜN</th>
                    <th>FİYAT</th>
                    <th>ADET</th>
                    <th>BEDEN</th>
                    <th>TOPLAM</th>
                    <th>SİL</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cart as $key => $item):
                    $image = isset($item['image']) ? $item['image'] : 'default.png';
                    $name = isset($item['name']) ? $item['name'] : 'Ürün adı yok';
                    $price = isset($item['price']) ? $item['price'] : 0;
                    $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                    $size = isset($item['size']) ? $item['size'] : 'Belirtilmedi';

                    $item_total = $price * $quantity;
                    $total += $item_total;
                ?>
                <tr>
                    <td><img src="<?php echo $image; ?>" width="50" /> <?php echo $name; ?></td>
                    <td><?php echo $price; ?> TL</td>
                    <td>
                        <form action="update_cart.php" method="POST">
                            <input type="hidden" name="product_key" value="<?php echo $key; ?>">
                            <select name="quantity" onchange="this.form.submit()">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php if ($i == $quantity) echo 'selected'; ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </form>
                    </td>
                    <td><?php echo $size; ?></td>
                    <td><?php echo $item_total; ?> TL</td>
                    <td>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="product_key" value="<?php echo $key; ?>">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="size" value="<?php echo $size; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">
                            <button type="submit" class="remove-btn">Sil</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <div class="summary-box">
                <p>Sipariş Toplamı: <strong><?php echo $total; ?> TL</strong></p>
                <p>Kargo Ücreti: <strong>Ücretsiz</strong></p>
                <p><a href="payment.php">Satın Al</a></p>
            </div>
        </div>

        <?php endif; ?>
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
<!-- Include Chatbot -->
<?php include 'chatbot.php'; ?>

</body>
</html>
