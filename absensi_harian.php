<?php
require 'includes/db.php';
require 'includes/auth.php';
check_login();
$user = current_user();

// Zona waktu Indonesia
date_default_timezone_set('Asia/Jakarta');

if ($user['role'] !== 'guru') {
    echo "Hanya guru.";
    exit;
}

$today = date('Y-m-d');

// Cek absensi hari ini
$already = $pdo->prepare("SELECT * FROM absensi WHERE guru_id=? AND tanggal=? LIMIT 1");
$already->execute([$user['id'], $today]);
$exists = $already->fetch();
?>
<!doctype html>
<html>
<head>
    <style>/* GLOBAL DEFAULT */
:root {
    --primary: #14b8a6;
    --primary-dark: #0f172a;
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
.absensi-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.absensi-camera {
    margin-top: 20px;
}

#video { width: 320px; border-radius:10px; }
#canvas { display:none; }
button { padding:10px 20px; margin-top:10px; cursor:pointer; }
.preview-box { margin-top:10px; }
</style>
<meta charset="utf-8">
<title>Absensi Kamera Realtime</title>
</head>
<body>

<?php include 'includes/sidebar_guru.php'; ?>

<main>
<h2>Absensi Hari Ini (<?= date('d-m-Y') ?>)</h2>

<?php if ($exists): ?>
    <p>Sudah absen pada jam <b><?= htmlspecialchars($exists['jam']) ?></b></p>

<?php else: ?>

    <p><b>Jam Sekarang:</b> <span id="clock"></span></p>

    <h3>Kamera Realtime</h3>

    <video id="video" autoplay></video>
    <canvas id="canvas" width="320" height="240"></canvas>

    <div class="preview-box">
        <p><b>Preview Foto:</b></p>
        <img id="preview" src="" width="300">
    </div>

    <br>

    <button id="takePhoto">Ambil Foto</button>
    <button id="submitAbsensi" disabled>Absen Sekarang</button>

    <form id="absenForm" method="post" action="save_photo.php">
        <input type="hidden" name="image_data" id="image_data">
        <input type="hidden" name="metode" value="kamera">
        <input type="text" name="lokasi" placeholder="opsional: lokasi (lat,lng)">
    </form>

    <script>
    // Jam realtime
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').innerText =
            now.toLocaleTimeString('id-ID', { hour12: false });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Kamera realtime
    const video = document.getElementById("video");
    const canvas = document.getElementById("canvas");
    const preview = document.getElementById("preview");
    const ctx = canvas.getContext("2d");

    // Aktifkan kamera
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => video.srcObject = stream)
        .catch(err => alert("Gagal mengakses kamera: " + err));

    // Foto
    document.getElementById("takePhoto").onclick = () => {
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataUrl = canvas.toDataURL("image/png");
        preview.src = dataUrl;
        document.getElementById("image_data").value = dataUrl;
        document.getElementById("submitAbsensi").disabled = false;
    };

    // Kirim form
    document.getElementById("submitAbsensi").onclick = () => {
        if (document.getElementById("image_data").value === "") {
            alert("Ambil foto dulu!");
            return;
        }
        document.getElementById("absenForm").submit();
    };
    </script>

<?php endif; ?>

</main>
</body>
</html>
