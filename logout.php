<?php
session_start();
session_unset();      // Tüm oturum değişkenlerini temizle
session_destroy();    // Oturumu yok et

// Giriş sayfasına yönlendir
header("Location: login.php");
exit;
?>
