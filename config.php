<?php
// Anahtarlar
define('SECRET_KEY', 'Bu_Cok_Gizli_Bir_Key123');
define('SECRET_IV', 'IV_Cok_Gizli456');

// Ana parola (verify.php ile eşleşmeli)
define('MASTER_PASSWORD', 'SeninAnaParolan123');

// Şifreleme fonksiyonu
function encrypt($string) {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_encrypt($string, "AES-256-CBC", $key, 0, $iv);
    return base64_encode($output);
}

// Çözme fonksiyonu
function decrypt($string) {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $output = openssl_decrypt(base64_decode($string), "AES-256-CBC", $key, 0, $iv);
    return $output;
}
