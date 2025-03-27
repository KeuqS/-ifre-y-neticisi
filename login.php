<?php
session_start();
$conn = new mysqli("localhost", "tzntalha_keuqs", "KeuqS577.", "tzntalha_aykut_pw");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed);

    if ($stmt->num_rows > 0 && $stmt->fetch() && password_verify($password, $hashed)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = "❌ Hatalı kullanıcı adı veya şifre.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
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
        <h2>🔐 Giriş Yap</h2>
        <?php if (isset($error)) echo "<div class='error'>{$error}</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="password" name="password" placeholder="Parola" required>
            <button type="submit">Giriş Yap</button>
        </form>
        <div style="text-align:center;">Hesabın yok mu? <a href="register.php" style="color:#00ffae;">Kayıt Ol</a></div>
    </div>
</body>
</html>
