<?php
$conn = new mysqli("localhost", "tzntalha_keuqs", "KeuqS577.", "tzntalha_aykut_pw");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "❌ Bu kullanıcı adı zaten kullanılıyor.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
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
        <h2>📝 Kayıt Ol</h2>
        <?php if (isset($error)) echo "<div class='error'>{$error}</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="password" name="password" placeholder="Parola" required>
            <button type="submit">Kayıt Ol</button>
        </form>
        <div style="text-align:center;">Zaten hesabın var mı? <a href="login.php" style="color:#00ffae;">Giriş Yap</a></div>
    </div>
</body>
</html>
