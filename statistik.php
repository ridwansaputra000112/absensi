<?php
require 'includes/db.php';
require 'includes/auth.php';
check_login();
$data = $pdo->query("SELECT u.name, COUNT(a.id) as cnt FROM users u LEFT JOIN absensi a ON a.guru_id=u.id WHERE u.role='guru' GROUP BY u.id")->fetchAll();
$labels = []; $values = [];
foreach($data as $d){ $labels[] = $d['name']; $values[] = (int)$d['cnt']; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Statistik</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head><body>
<?php include 'includes/sidebar_guru.php'; ?>
<main>
<h2>Statistik Kehadiran</h2>
<canvas id="chart" width="800" height="400"></canvas>
<script>
const ctx = document.getElementById('chart').getContext('2d');
new Chart(ctx, { type:'bar', data:{ labels: <?=json_encode($labels)?>, datasets:[{label:'Jumlah Hadir', data: <?=json_encode($values)?>}] }, options:{} });
</script>
</main></body></html>
<style>
/* GLOBAL DEFAULT */
:root {
    --primary: #0284c7;
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