<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Chat</title>
</head>

<body>
    <!-- Chatbot -->
    <div id="chatbox">
        <div id="chatbox-header">
            <button onclick="toggleChatbox()">X</button>
        </div>
        <div id="messages"></div>
        <div id="input">
            <input type="text" id="message" placeholder="Mesajınızı yazın..." />
            <button onclick="sendMessage()">Gönder</button>
        </div>
    </div>
    
    <div id="chatbot-button" onclick="toggleChatbox()">💬</div>

    <style>
        /* Chatbot Butonu Stili */
        #chatbot-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #9400D3;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            font-size: 24px;
            z-index: 1001;
            user-select: none; /* Seçilemez yapmak için */
        }

        /* Chatbot Stili */
        #chatbox {
            position: fixed;
            bottom: 90px; /* Chatbot butonunun üstünde görünmesi için */
            right: 20px;
            width: 300px;
            height: 400px;
            border-radius: 20px;
            border: 1px solid #ccc;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
        }

        #chatbox-header {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        #chatbox-header button {
            background: none;
            border: none;
            color: #9400D3;
            font-size: 20px;
            cursor: pointer;
        }

        /* Mesajlar ve input alanı */
        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        #input {
            display: flex;
            border-top: 1px solid #ccc;
        }

        #input input {
            flex: 1;
            padding: 10px;
            border: none;
        }

        #input button {
            padding: 10px;
            border: none;
            background: #9400D3;
            color: #fff;
            cursor: pointer;
        }

        #input button:hover {
            background: #7600B3;
        }
/* Yazıyor animasyonu */
.typing .dots {
    display: inline;
    font-size: 18px;
    font-weight: bold;
    color: #9400D3;
    animation: ellipsis 1.5s infinite;
}

@keyframes ellipsis {
    0% {
        content: "";
    }
    33% {
        content: ".";
    }
    66% {
        content: "..";
    }
    100% {
        content: "...";
    }
}


    </style>

    <!-- JavaScript -->
    <script>
        const botName = "Asistan";

        function toggleChatbox() {
            var chatbox = document.getElementById('chatbox');
            var messagesDiv = document.getElementById('messages');

            if (chatbox.style.display === 'none' || chatbox.style.display === '') {
                chatbox.style.display = 'flex';
                
                // İlk açılış mesajı
                if (messagesDiv.innerHTML.trim() === '') {
                    messagesDiv.innerHTML += '<div>' + botName + ': Merhaba, size nasıl yardımcı olabilirim?</div>';
                }
            } else {
                chatbox.style.display = 'none';
            }
        }

    async function sendMessage() {
    var message = document.getElementById('message').value;
    var messagesDiv = document.getElementById('messages');

    if (!message.trim()) {
        alert('Mesaj boş olamaz.');
        return;
    }

    // Kullanıcı mesajını ekle
    messagesDiv.innerHTML += '<div><strong>Kullanıcı:</strong> ' + message + '</div>';
    document.getElementById('message').value = ''; // Giriş alanını temizle
    messagesDiv.scrollTop = messagesDiv.scrollHeight; // Otomatik kaydırma

    // Bot yazıyor animasyonunu ekle
    var typingDiv = document.createElement('div');
    typingDiv.classList.add('typing');
    typingDiv.innerHTML = '<strong>' + botName + ':</strong> <span class="dots">...</span>';
    messagesDiv.appendChild(typingDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight; // Otomatik kaydırma

    try {
        // Backend'e mesaj gönder
        const response = await fetch('chatbot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'message=' + encodeURIComponent(message)
        });

        const data = await response.text();

        // Bot yanıtını ekle
        typingDiv.remove(); // "Yazıyor..." mesajını kaldır
        messagesDiv.innerHTML += '<div><strong>' + botName + ':</strong> ' + data + '</div>';
        messagesDiv.scrollTop = messagesDiv.scrollHeight; // Otomatik kaydırma
    } catch (error) {
        console.error('API çağrısı sırasında hata:', error);
        typingDiv.remove(); // "Yazıyor..." mesajını kaldır
        messagesDiv.innerHTML += '<div><strong>' + botName + ':</strong> Bir hata oluştu.</div>';
        messagesDiv.scrollTop = messagesDiv.scrollHeight; // Otomatik kaydırma
    }
}


    </script>
</body>
</html>
<?php
$apiKey = '';
$url = '' . $apiKey;

$userMessage = isset($_POST['message']) ? $_POST['message'] : 'Merhaba! Size nasıl yardımcı olabilirim?';

// API'ye gönderilecek JSON
$data = json_encode([
    'contents' => [
        ['parts' => [['text' => $userMessage]]]
    ]
]);

// cURL isteği
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo "Asistan: API isteği başarısız oldu.";
} else {
    $responseData = json_decode($response, true);

    // Yanıtı işleme
    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        $botResponse = $responseData['candidates'][0]['content']['parts'][0]['text'];
        echo " " . htmlspecialchars($botResponse);
    } else {
        echo "Cevap alınamadı, lütfen tekrar deneyin.";
    }
}
?>

