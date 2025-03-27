<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['master'];

    if ($input === MASTER_PASSWORD) {
        $_SESSION['verified'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "❌ Hatalı ana parola!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ana Parola</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #121212; color: #f5f5f5; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: #1e1e1e; padding: 30px; border-radius: 10px; width: 100%; max-width: 400px; }
        h2 { text-align: center; }
        input, button {
            width: 100%; padding: 10px; margin: 10px 0;
            border: none; border-radius: 5px;
        }
        input { background: #2c2c2c; color: #fff; }
        button { background: #00ffae; color: #000; font-weight: bold; cursor: pointer; }
        .error { color: #ff5e5e; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔐 Ana Parola</h2>
        <?php if (isset($error)) echo "<div class='error'>{$error}</div>"; ?>
        <form method="POST">
            <input type="password" name="master" placeholder="Ana parolanız" required>
            <button type="submit">Devam Et</button>
        </form>
    </div>
</body>
</html>
