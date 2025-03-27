<?php
session_start();

// Giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ana parola kontrolü
if (!isset($_SESSION['verified'])) {
    header("Location: verify.php");
    exit;
}

include 'config.php';
$conn = new mysqli("localhost", "tzntalha_keuqs", "KeuqS577.", "tzntalha_aykut_pw");

$search = $_GET['search'] ?? '';
$user_id = $_SESSION['user_id'];

// Şifre ekleme
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service = $_POST['service'];
    $username = $_POST['username'];
    $password = encrypt($_POST['password']);

    $stmt = $conn->prepare("INSERT INTO passwords (service_name, username, encrypted_password, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $service, $username, $password, $user_id);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Yöneticisi | AykutGüney.fun</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="/pw/manifest.json">
    <meta name="theme-color" content="#00ffae">
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/pw/service-worker.js');
        }
    </script>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { margin: 0; background: #121212; color: #f5f5f5; padding: 20px; }
        h1, h2, h3 { text-align: center; }
        form, .list, .generator { max-width: 500px; margin: 20px auto; background: #1e1e1e; padding: 20px; border-radius: 10px; }
        input, button, select {
            width: 100%; padding: 10px; margin: 10px 0;
            border: none; border-radius: 5px;
        }
        input { background: #2c2c2c; color: #f5f5f5; }
        button { background: #00ffae; color: #000; font-weight: bold; cursor: pointer; transition: 0.2s; }
        button:hover { opacity: 0.85; }
        .card {
            background: #1e1e1e; padding: 15px;
            border-left: 4px solid #00ffae;
            margin-bottom: 15px; border-radius: 8px;
            transition: 0.3s;
        }
        .card:hover { background: #222; }
        .card strong { font-size: 1.1em; display: block; margin-bottom: 5px; }
        .card-actions a {
            margin-right: 10px; text-decoration: none;
            color: #00ffae; font-weight: bold;
        }
        .row { display: flex; gap: 10px; flex-wrap: wrap; }
        .row input[type=checkbox] { width: auto; }
        .small-label { font-size: 0.9em; color: #ccc; }
        .copy-btn {
            margin-top: 5px; background: #333; color: #0f0; border: 1px solid #0f0;
        }
        .top-bar {
            text-align: center; margin-bottom: 10px;
        }
        .top-bar a {
            color: #00ffae; text-decoration: none;
        }
        .search-bar input[type=text] {
            width: 70%; display: inline-block;
        }
        .search-bar button {
            width: 28%; display: inline-block;
        }
    </style>
</head>
<body>
    <h1>🔐 Şifre Yöneticisi</h1>
    <div class="top-bar">
        Merhaba, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> |
        <a href="logout.php">Çıkış Yap</a> |
        <a href="export.php?type=csv">📤 CSV</a> |
        <a href="export.php?type=json">📤 JSON</a>
    </div>

    <form method="POST">
        <input type="text" name="service" placeholder="Servis Adı (örneğin: Gmail)" required>
        <input type="text" name="username" placeholder="Kullanıcı Adı" required>
        <div class="row">
            <input type="password" id="passwordInput" name="password" placeholder="Şifre" required>
            <button type="button" class="copy-btn" onclick="generatePassword()">🔄 Oluştur</button>
        </div>
        <button type="submit">Şifreyi Kaydet</button>
    </form>

    <div class="generator">
        <h3>🔧 Şifre Oluşturucu</h3>
        <label class="small-label">Uzunluk</label>
        <input type="number" id="length" min="6" max="32" value="16">
        <div class="row">
            <label><input type="checkbox" id="includeLower" checked> a-z</label>
            <label><input type="checkbox" id="includeUpper" checked> A-Z</label>
            <label><input type="checkbox" id="includeNumbers" checked> 0-9</label>
            <label><input type="checkbox" id="includeSymbols" checked> !@#$%</label>
        </div>
        <input type="text" id="generatedPassword" readonly>
        <button class="copy-btn" onclick="copyToInput()">Şifreyi Kullan</button>
    </div>

    <div class="list">
        <h2>📄 Kayıtlı Şifreler</h2>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Servis adıyla ara..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Ara</button>
        </form>
        <?php
        if ($search) {
            $stmt = $conn->prepare("SELECT * FROM passwords WHERE user_id = ? AND service_name LIKE ? ORDER BY created_at DESC");
            $like = '%' . $search . '%';
            $stmt->bind_param("is", $user_id, $like);
        } else {
            $stmt = $conn->prepare("SELECT * FROM passwords WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->bind_param("i", $user_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<strong>' . htmlspecialchars($row['service_name']) . '</strong>';
            echo '👤 ' . htmlspecialchars($row['username']) . '<br>';
            echo '🔑 ' . decrypt($row['encrypted_password']) . '<br>';
            echo '<div class="card-actions">';
            echo '<a href="edit.php?id=' . $row['id'] . '">✏️ Düzenle</a>';
            echo '<a href="delete.php?id=' . $row['id'] . '" onclick="return confirm(\'Silmek istediğine emin misin?\')">🗑️ Sil</a>';
            echo '</div></div>';
        }
        ?>
    </div>

    <script>
        function generatePassword() {
            const length = document.getElementById('length').value;
            const includeLower = document.getElementById('includeLower').checked;
            const includeUpper = document.getElementById('includeUpper').checked;
            const includeNumbers = document.getElementById('includeNumbers').checked;
            const includeSymbols = document.getElementById('includeSymbols').checked;

            const lower = "abcdefghijklmnopqrstuvwxyz";
            const upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const numbers = "0123456789";
            const symbols = "!@#$%^&*()_+-=[]{}|;:,.<>?";

            let chars = "";
            if (includeLower) chars += lower;
            if (includeUpper) chars += upper;
            if (includeNumbers) chars += numbers;
            if (includeSymbols) chars += symbols;

            let password = "";
            for (let i = 0; i < length; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            document.getElementById("generatedPassword").value = password;
        }

        function copyToInput() {
            const pass = document.getElementById("generatedPassword").value;
            document.getElementById("passwordInput").value = pass;
        }
    </script>
</body>
</html>
