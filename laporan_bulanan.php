<?php
require 'includes/db.php';
require 'includes/auth.php';
check_login();
$m = $_GET['m'] ?? date('m');
$y = $_GET['y'] ?? date('Y');
$stmt = $pdo->prepare('SELECT a.*, u.name FROM absensi a JOIN users u ON u.id=a.guru_id WHERE MONTH(tanggal)=? AND YEAR(tanggal)=? ORDER BY tanggal');
$stmt->execute([$m,$y]);
$rows = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Laporan Bulanan</title></head><body>
<?php include 'includes/sidebar_guru.php'; ?>
<main>
<h2>Laporan <?=htmlspecialchars($m)?>/<?=htmlspecialchars($y)?></h2>
<p><a href="cetak.php?m=<?=urlencode($m)?>&y=<?=urlencode($y)?>">Download PDF</a></p>
<table><tr><th>Tanggal</th><th>Nama</th><th>Jam</th></tr>
<?php foreach($rows as $r): ?>
<tr><td><?=htmlspecialchars($r['tanggal'])?></td><td><?=htmlspecialchars($r['name'])?></td><td><?=htmlspecialchars($r['jam'])?></td></tr>
<?php endforeach; ?>
</table>
</main></body></html>
<style>
/* GLOBAL DEFAULT */
:root {
    --primary: #14b8a6;
    --primary-dark: #0369a1;
    --accent: #14b8a6;
    --bg: #f8fafc;
    --text: #0f172a;
    --border: #e2e8f0;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Poppins", sans-serif;
    background: var(--bg);
    color: var(--text);
}

/* SIDEBAR */
nav {
    width: 240px;
    position: fixed;
    inset: 0 auto 0 0;
    background: var(--text);
    color: white;
    padding: 24px 16px;
}

nav h2 {
    text-align: center;
    margin-bottom: 20px;
}

nav a {
    display: block;
    padding: 12px;
    color: #cbd5e1;
    text-decoration: none;
    border-radius: 8px;
    transition: .25s;
}

nav a:hover {
    background: var(--accent);
    color: white;
}

/* MAIN CONTENT */
main {
    margin-left: 260px;
    padding: 28px;
}

/* BUTTON */
button {
    background: var(--primary);
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: .25s;
}

button:hover {
    background: var(--primary-dark);
}

/* TABLE */
table {
    width: 100%;
    border-collapse: 10px;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

th {
    background: var(--primary);
    color: white;
    padding: 14px;
    text-align: left;
}

td {
    padding: 12px;
    border-bottom: 1px solid var(--border);
}
</style>