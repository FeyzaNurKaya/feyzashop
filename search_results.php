
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
<?php
include 'baglanti.php';

// Arama terimini al
$query = isset($_GET['q']) ? $_GET['q'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$productsPerPage = 8;
$offset = ($page - 1) * $productsPerPage;

// Arama terimi boşsa, kullanıcıya mesaj göster
if (empty($query)) {
    echo "Lütfen arama terimi girin.";
    exit;
}

// Veritabanındaki toplam ürün sayısını sorgula
$sqlTotal = "SELECT COUNT(*) FROM products WHERE name LIKE ?";
$stmtTotal = $baglan->prepare($sqlTotal);
$searchTerm = "%$query%";
$stmtTotal->bind_param("s", $searchTerm);
$stmtTotal->execute();
$totalResult = $stmtTotal->get_result();
$totalProducts = $totalResult->fetch_row()[0];

// Ürünleri sayfalama ile getir
$sql = "SELECT * FROM products WHERE name LIKE ? LIMIT ? OFFSET ?";
$stmt = $baglan->prepare($sql);
$stmt->bind_param("sii", $searchTerm, $productsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Ürünleri ekrana yazdır
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<a href='shopdetail.php?id=" . $row['id'] . "'>";
        echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
        echo "<div class='description'>";
        echo "<h5 style='color: black;'>" . $row['name'] . "</h5>";
        echo "<p>Size: " . $row['sizes'] . "</p>";
        echo "<h3 style='color: black;'>" . $row['price'] . " TL</h3>";
        echo "<a href='#' class='add-to-cart'></a>";
        echo "</div>";
        echo "</a>";
        echo "</div>";
    }
} else {
    echo "Arama kriterlerinize uygun ürün bulunamadı.";
}

// Pagination (sayfa numaralandırma) işlemi
$pagesCount = ceil($totalProducts / $productsPerPage);

echo '<section id="pagination" class="section-p1">';
if ($page > 1) {
    echo '<a href="?q=' . urlencode($query) . '&page=' . ($page - 1) . '"><i class="fa-solid fa-angle-left"></i></a>';
}
for ($i = 1; $i <= $pagesCount; $i++) {
    echo '<a href="?q=' . urlencode($query) . '&page=' . $i . '" class="' . ($i == $page ? 'active' : '') . '">' . $i . '</a>';
}
if ($page < $pagesCount) {
    echo '<a href="?q=' . urlencode($query) . '&page=' . ($page + 1) . '"><i class="fa-solid fa-angle-right"></i></a>';
}
echo '</section>';

$stmt->close();
$stmtTotal->close();
$baglan->close();
?>

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
