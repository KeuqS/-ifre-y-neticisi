<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    exit("Yetkisiz erişim.");
}

$conn = new mysqli("localhost", "tzntalha_keuqs", "KeuqS577.", "tzntalha_aykut_pw");
$user_id = $_SESSION['user_id'];
$type = $_GET['type'] ?? 'csv';

$stmt = $conn->prepare("SELECT service_name, username, encrypted_password FROM passwords WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "service" => $row['service_name'],
        "username" => $row['username'],
        "password" => decrypt($row['encrypted_password']),
    ];
}

if ($type === 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="passwords.json"');
    echo json_encode($data, JSON_PRETTY_PRINT);
} else {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="passwords.csv"');
    $output = fopen("php://output", "w");
    fputcsv($output, ["Servis", "Kullanıcı Adı", "Şifre"]);
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
}
