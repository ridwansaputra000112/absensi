<?php
require 'includes/db.php';
require 'includes/auth.php';
check_login();
$user = current_user();

date_default_timezone_set('Asia/Jakarta');

if ($user['role'] !== 'guru') {
    die("Tidak diizinkan.");
}

$today = date('Y-m-d');
$jam = date('H:i:s');

// cek sudah absen
$cek = $pdo->prepare("SELECT id FROM absensi WHERE guru_id=? AND tanggal=? LIMIT 1");
$cek->execute([$user['id'], $today]);
if ($cek->fetch()) {
    header("Location: riwayat_absensi.php?exists=1");
    exit;
}

if (empty($_POST['image_data'])) {
    die("Foto tidak ditemukan.");
}

$data = $_POST['image_data'];
$data = str_replace("data:image/png;base64,", "", $data);
$data = base64_decode($data);

$updir = __DIR__ . "/uploads";
if (!is_dir($updir)) mkdir($updir, 0755, true);

$filename = "foto_" . time() . ".png";
file_put_contents($updir . "/" . $filename, $data);

$foto_path = "uploads/" . $filename;

$metode = $_POST['metode'] ?? "kamera";
$lokasi = $_POST['lokasi'] ?? null;

// simpan absensi
$stmt = $pdo->prepare("
    INSERT INTO absensi (guru_id, tanggal, jam, status, metode, lokasi, foto_path)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$user['id'], $today, $jam, "Hadir", $metode, $lokasi, $foto_path]);

header("Location: riwayat_absensi.php?abs=1");
exit;
