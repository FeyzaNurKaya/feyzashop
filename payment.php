<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Formu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f7f7f7;
        }
        .payment-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        .payment-form h2 {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 15px;
        }
        .form-group .form-control {
            font-size: 15px;
        }
        .form-group select {
            font-size: 15px;
        }
        .btn-primary {
            width: 100%;
            font-size: 16px;
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
        .btn-primary:hover {
            background-color: #5a32a3;
            border-color: #5a32a3;
        }
    </style>
</head>
<body>
    <div class="payment-form">
        <h2>Ödeme Bilgileri</h2>
        <form id="paymentForm">
            <div class="form-group">
                <label for="kart_isim"><b>Kredi Kartı Üzerindeki İsim</b></label>
                <input type="text" class="form-control" id="kart_isim" name="kart_isim" placeholder="Kredi Kartı Üzerindeki İsim" autocomplete="off" size="20">
            </div>
            <div class="form-group">
                <label for="pan"><b>Kredi Kart Numarası</b></label>
                <input type="text" class="form-control" id="pan" name="pan" maxlength="16" onkeydown="sadece_rakam('pan');" placeholder="Kredi Kart Numarası" autocomplete="off" size="20">
            </div>
            <div class="form-group">
                <label><b>Son Kullanma Ay / Yıl</b></label>
                <div class="d-flex">
                    <select class="form-control mr-2" name="Ecom_Payment_Card_ExpDate_Month" id="Ecom_Payment_Card_ExpDate_Month">
                        <option value="01">01</option>
                        <option value="02">02</option>
                        <option value="03">03</option>
                        <option value="04">04</option>
                        <option value="05">05</option>
                        <option value="06">06</option>
                        <option value="07">07</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                    </select>
                    <select class="form-control" name="Ecom_Payment_Card_ExpDate_Year" id="Ecom_Payment_Card_ExpDate_Year">
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="cv2"><b>Güvenlik Kodu</b> <br/><span style="font-weight:100;">(Kartın arkasındaki son 3 hane)</span></label>
                <input type="text" class="form-control" id="cv2" name="cv2" maxlength="4" placeholder="Güvenlik Kodu" autocomplete="off" size="4" value="">
            </div>
            <button type="submit" id="devamEt" class="btn btn-primary">Devam Et</button>
        </form>
    </div>

    <!-- Başarı mesajı -->
    <div id="successMessage" style="display:none; text-align:center; margin-top:50px;">
        <h3>Sipariş Başarıyla Alındı</h3>
        <p>Ödemeniz başarılı bir şekilde tamamlandı. Ana sayfaya yönlendiriliyorsunuz...</p>
    </div>

    <script>
    // Sadece rakam girişi
    function sadece_rakam(id) {
        var element = document.getElementById(id);
        element.onkeypress = function (e) {
            if (isNaN(String.fromCharCode(e.which))) e.preventDefault();
        };
    }

    // Form gönderim işlemi
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('paymentForm');
        form.onsubmit = function (e) {
            e.preventDefault(); // Formun varsayılan gönderimini engelle

            // Form alanlarını kontrol et
            var kartIsim = document.getElementById('kart_isim').value.trim();
            var pan = document.getElementById('pan').value.trim();
            var month = document.getElementById('Ecom_Payment_Card_ExpDate_Month').value;
            var year = document.getElementById('Ecom_Payment_Card_ExpDate_Year').value;
            var cv2 = document.getElementById('cv2').value.trim();

            if (!kartIsim || !pan || !month || !year || !cv2) {
                alert("Lütfen tüm alanları doldurunuz.");
                return;
            }

            // Tüm içeriği gizle ve başarı mesajını göster
            document.body.innerHTML = `
                <div style="display: flex; justify-content: center; align-items: center; height: 100vh; font-size: 1.5em; color: green;">
                    Ödemeniz başarıyla tamamlandı!
                </div>
            `;

            // Sepeti temizlemek için bir AJAX isteği gönder
            fetch('clear_cart.php')
                .then(response => {
                    if (response.ok) {
                        console.log('Sepet temizlendi');
                    } else {
                        console.error('Sepet temizlenemedi');
                    }
                });

            // 3 saniye sonra ana sayfaya yönlendir
            setTimeout(function () {
                window.location.href = 'index.php';
            }, 3000);
        };
    });
</script>

</body>

</html>
