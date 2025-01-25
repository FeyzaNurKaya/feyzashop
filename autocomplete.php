<?php
include 'baglanti.php';

// Arama terimini al ve temizle
$search = isset($_GET['q']) ? $baglan->real_escape_string($_GET['q']) : '';

if (!empty($search)) {
    $sql = "SELECT name FROM products WHERE name LIKE '%$search%' LIMIT 5";
    $result = $baglan->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='suggestion-item' onclick='fillSearch(\"" . htmlspecialchars($row['name']) . "\")'>";
            echo htmlspecialchars($row['name']);
            echo "</div>";
        }
    } else {
        echo "<div class='suggestion-item'>Sonuç bulunamadı</div>";
    }
}

$baglan->close();
?>
