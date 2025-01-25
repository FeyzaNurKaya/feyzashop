<?php
include 'baglanti.php';

// Arama terimini al ve temizle
$search = isset($_GET['q']) ? $baglan->real_escape_string($_GET['q']) : '';

if (!empty($search)) {
    $sql = "SELECT id, image, name, sizes, price FROM products WHERE name LIKE '%$search%' LIMIT 8";
    $result = $baglan->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<a href='shopdetail.php?id=" . $row['id'] . "'>";
            echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
            echo "<div class='description'>";
            echo "<h5 style='color: black;'>" . htmlspecialchars($row['name']) . "</h5>";
            echo "<p>Size: " . htmlspecialchars($row['sizes']) . "</p>";
            echo "<h3 style='color: black;'>" . htmlspecialchars($row['price']) . " TL</h3>";
            echo "<a href='#' class='add-to-cart'><i class='fa-solid fa-cart-plus'></i></a>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
        }
    } else {
        echo "Ürün bulunamadı.";
    }
} else {
    echo "Lütfen arama terimi girin.";
}

$baglan->close();
?>
	
	
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

</html>
