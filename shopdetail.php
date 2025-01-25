<?php
// Veritabanı bağlantısını içerir
session_start();
include 'baglanti.php';

// Ürün ID'sini al
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id === 0) {
    header("Location: error.php?message=gecersiz-urun-id");
    exit();
}

// Ürün bilgilerini çek
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $baglan->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    header("Location: error.php?message=urun-bulunamadi");
    exit();
}

// Beden bilgilerini ayrıştır
$sizes = explode(',', $product['sizes']); // sizes sütunu virgülle ayrılmış beden değerlerini içermeli

// Kullanıcının görüntüleme kaydını ekle
if (isset($_SESSION['kullanici_adi'])) {
    $kullanici_adi = $_SESSION['kullanici_adi'];

    $checkQuery = "SELECT * FROM user_product_views WHERE kullanici_adi = ? AND product_id = ?";
    $stmt = $baglan->prepare($checkQuery);
    $stmt->bind_param("si", $kullanici_adi, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $insertQuery = "INSERT INTO user_product_views (kullanici_adi, product_id, viewed_at) VALUES (?, ?, NOW())";
        $stmt = $baglan->prepare($insertQuery);
        $stmt->bind_param("si", $kullanici_adi, $product_id);
        $stmt->execute();
    }
}


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

<section id="productdetail" class="section-p1">
    <div class="single-pro-image">
        <img src="<?php echo htmlspecialchars($product['image']); ?>" width="100%" id="MainImg" alt="">
    </div>

    <div class="product-detail">
        <h6 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h6>
        <h4><?php echo htmlspecialchars($product['description']); ?></h4>
        <h2><?php echo number_format($product['price'], 2); ?> TL</h2>
        <form id="add-to-cart-form" action="add_to_cart.php" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
            <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">

            <select name="size" id="size">
                <option value="">Seçiniz</option>
                <?php foreach ($sizes as $size): ?>
                    <option value="<?php echo htmlspecialchars(trim($size)); ?>"><?php echo htmlspecialchars(trim($size)); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Adet:</label>
            <input type="number" name="quantity" value="1" min="1" id="quantity">
            <button type="submit">Sepete Ekle</button>
        </form>

        <p id="error-message" style="color:red; display:none;">Lütfen bir beden seçiniz.</p>

        <script>
            function validateForm() {
                var size = document.getElementById('size').value;
                if (size === "") {
                    document.getElementById('error-message').style.display = 'block';
                    return false; // Form gönderimini engelle
                }
                return true; // Formu gönder
            }
        </script>
    </div>
</section>



<script>
        function goToShop() {
            window.location.href = "shop.php";
        }

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

    <!-- footer -->
    <footer class="section-p1">
        <div class="col">
            <img src="img/logo.png" alt="" class="logo">
            <h4><b>İletişim</b></h4>
            <ul>
                <li><a href=""><b>Adres: </b>Türkiye İstanbul / Başakşehir</a></li>
                <li><a href=""><b>Saatler: </b>10.00-18.00 Pazartesi-Cuma</a></li>
            </ul>

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
