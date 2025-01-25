<?php
session_start();
include 'baglanti.php'; // Bağlantı dosyasını dahil et

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    
    $stmt = $baglan->prepare("SELECT * FROM kullanicilar WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        $token = bin2hex(random_bytes(50)); 
        $stmt = $baglan->prepare("UPDATE kullanicilar SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // E-posta gönderme
        $resetLink = "https://yourwebsite.com/reset_password.php?token=" . $token;

        // PHPMailer ile e-posta gönderimi
        $mail = new PHPMailer(true);

        try {
            // SMTP ayarlarını yapılandırın
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = '@gmail.com'; 
            $mail->Port = ;

            // Alıcı ayarları
            $mail->setFrom('@gmail.com', 'Feyza Shop'); 
            $mail->addAddress($email); 

            // İçerik ayarları
            $mail->isHTML(true);
            $mail->Subject = 'Şifre Sıfırlama Bağlantısı';
            $mail->Body    = 'Şifrenizi sıfırlamak için <a href="' . $resetLink . '">buraya tıklayın</a>';
            $mail->AltBody = 'Şifrenizi sıfırlamak için şu bağlantıya gidin: ' . $resetLink;

            $mail->send();
            echo 'Şifre sıfırlama bağlantısı gönderildi.';
        } catch (Exception $e) {
            echo "E-posta gönderilemedi. Hata: {$mail->ErrorInfo}";
        }
    } else {
        echo "Bu e-posta adresi kayıtlı değil.";
    }
}
?>
