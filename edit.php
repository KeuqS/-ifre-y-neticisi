<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';
$conn = new mysqli("localhost", "tzntalha_keuqs", "KeuqS577.", "tzntalha_aykut_pw");

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = $_POST['service'];
    $username = $_POST['username'];
    $password = encrypt($_POST['password']);

    $stmt = $conn->prepare("UPDATE passwords SET service_name=?, username=?, encrypted_password=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sssii", $service, $username, $password, $id, $user_id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM passwords WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Kayıt bulunamadı.";
    exit;
}

$decrypted = decrypt($row['encrypted_password']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Düzenle</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ Şifreyi Düzenle</h2>
        <form method="POST">
            <input type="text" name="service" value="<?= htmlspecialchars($row['service_name']) ?>" required>
            <input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>" required>
            <input type="text" name="password" value="<?= htmlspecialchars($decrypted) ?>" required>
            <button type="submit">Kaydet</button>
        </form>
    </div>
</body>
</html>
