<?php
// delete_absensi.php — versi robust
require 'includes/db.php';
require 'includes/auth.php';

if (session_status() === PHP_SESSION_NONE) session_start();
check_login();
require_admin();

// Pastikan request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed.');
}

// Validasi ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    die('ID tidak valid.');
}

$id = (int) $_POST['id'];

try {
    // Mulai transaction agar konsisten
    $pdo->beginTransaction();

    // Ambil foto_path (lock row)
    $stmt = $pdo->prepare("SELECT foto_path FROM absensi WHERE id = ? LIMIT 1 FOR UPDATE");
    $stmt->execute([$id]);
    $data = $stmt->fetch();

    if (!$data) {
        // tidak ditemukan -> rollback & redirect dengan pesan
        $pdo->rollBack();
        header("Location: all_absensi.php?error=" . urlencode('Absensi tidak ditemukan'));
        exit;
    }

    // Hapus file foto jika ada
    if (!empty($data['foto_path'])) {
        // Normalisasi path: foto_path biasanya 'uploads/2025/..' atau '/uploads/..'
        $rel = ltrim($data['foto_path'], '/\\');
        $file = __DIR__ . '/' . $rel;

        // Tambahan: jika path berisi ../ atau absolute path, bersihkan
        $file = str_replace(['..\\','../'], '', $file);

        if (file_exists($file)) {
            // coba hapus dan catat hasil
            if (!@unlink($file)) {
                // gagal hapus file — catat error tapi lanjutkan hapus DB
                error_log("delete_absensi: gagal menghapus file {$file} (permissions?)");
            }
        } else {
            // file tidak ada — log supaya bisa di-debug
            error_log("delete_absensi: file tidak ditemukan: {$file}");
        }
    }

    // Hapus record dari DB
    $del = $pdo->prepare("DELETE FROM absensi WHERE id = ?");
    $del->execute([$id]);

    $pdo->commit();

    header("Location: absensi_admin_view.php");
    exit;

} catch (PDOException $e) {
    // rollback on error
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("delete_absensi PDO Error: " . $e->getMessage());
    // Tampilkan pesan ramah ke user (jangan tampilkan pesan DB di production)
    header("Location: all_absensi.php?error=" . urlencode('Gagal menghapus absensi (DB error).'));
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log("delete_absensi Error: " . $e->getMessage());
    header("Location: all_absensi.php?error=" . urlencode('Terjadi kesalahan saat menghapus.'));
    exit;
}
