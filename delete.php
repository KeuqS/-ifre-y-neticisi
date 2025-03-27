<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';
$conn = new mysqli("localhost", "tzntalha_keuqs", "KeuqS577.", "tzntalha_aykut_pw");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM passwords WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
}

header("Location: index.php");
exit;
