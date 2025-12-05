<?php
require 'includes/db.php';
require 'includes/auth.php';
check_login();
if ($_SESSION['user']['role'] !== 'guru') { header('Location: login.php'); exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Dashboard Guru</title>
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
    border-collapse: collapse;
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

.dashboard-guru {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
}

.box-info {
    flex: 1;
    min-width: 240px;
    background: white;
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 3px 14px rgba(0,0,0,.08);
}

.box-info h3 {
    font-size: 17px;
    font-weight: 600;
}
/* ==== MAIN LAYOUT ==== */
    main {
        padding: 30px;
        flex: 1;
        min-height: 100vh;
    }

    h1 {
        font-size: 26px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 25px;
    }

    p {
        font-size: 16px;
        padding: 12px 18px;
        background: #fff;
        border-left: 4px solid #3b82f6;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        border-radius: 6px;
        margin-bottom: 15px;
    }
</style>
</head><body>
<?php include 'includes/sidebar_guru.php'; ?>
<main>
<h1>Halo, <?=htmlspecialchars($_SESSION['user']['name'])?></h1>
<p><a href="absensi_harian.php">Absensi Hari Ini</a></p>
<p><a href="riwayat_absensi.php">Riwayat Absensi</a></p>
</main>
</body></html>
