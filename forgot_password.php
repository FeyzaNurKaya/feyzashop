<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırlama</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .reset-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #6a1b9a; /* Purple color */
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="email"] {
            width: calc(100% - 22px); /* Adjusted width for padding and border */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box; /* Ensures padding is included in width */
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #6a1b9a; /* Purple color */
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #9c27b0; /* Lighter purple on hover */
        }
    </style>
</head>
<body>

<div class="reset-container">
    <h2>Şifre Sıfırlama</h2>
    <form action="send_reset_link.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Şifre Sıfırlama Bağlantısı Gönder</button>
    </form>
</div>

</body>
</html>
