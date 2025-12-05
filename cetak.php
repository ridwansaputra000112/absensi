<?php
require 'includes/db.php';
require 'includes/auth.php';

// Pastikan variabel koneksi PDO bernama $pdo di includes/db.php
if (!isset($pdo)) {
    die("Koneksi database (PDO) tidak ditemukan. Pastikan includes/db.php mendefinisikan \$pdo.");
}

$query = "
    SELECT absensi.*, users.name 
    FROM absensi
    JOIN users ON absensi.guru_id = users.id
    ORDER BY absensi.id DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute();

$absensi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak Data Absensi Guru</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 6px; }
        img { max-width: 120px; }
        .center { text-align: center; }

        /* ⛔ HILANGKAN BUTTON SAAT PRINT */
        @media print {
            .btn-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<center>
    <h2>LAPORAN DATA BULANAN ABSENSI GURU</h2>

    <table>
        <tr>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Status</th>
            <th>Metode</th>
            <th>Lokasi</th>
            <th>Foto</th>
        </tr>

        <?php if (empty($absensi)): ?>
            <tr>
                <td colspan="8" class="center">Tidak ada data absensi</td>
            </tr>
        <?php else: ?>
            <?php foreach ($absensi as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= htmlspecialchars($r['tanggal']) ?></td>
                <td><?= htmlspecialchars($r['jam']) ?></td>
                <td><?= htmlspecialchars($r['status']) ?></td>
                <td><?= htmlspecialchars($r['metode']) ?></td>
                <td><?= htmlspecialchars($r['lokasi'] ?: '-') ?></td>

                <td>
                    <?php if (!empty($r['foto_path'])): ?>
                        <img src="<?= htmlspecialchars($r['foto_path']) ?>">
                    <?php else: ?>
                        Tidak ada
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <!-- Button cetak -->
    <button class="btn-print" onclick="window.print();">🖨 Cetak</button>

</center>

</body>
</html>
