<?php
require 'includes/db.php';
require 'includes/auth.php';

check_login();
require_admin();

// supaya errors PDO tampil saat debugging (hapus/ubah di production)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Tambah jadwal
    if (isset($_POST['add'])) {

        $guru_id     = isset($_POST['guru_id']) ? (int)$_POST['guru_id'] : 0;
        $kelas_id    = isset($_POST['kelas_id']) ? (int)$_POST['kelas_id'] : 0;
        $hari        = trim($_POST['hari'] ?? '');
        $jam_mulai   = $_POST['jam_mulai'] ?? '';
        $jam_selesai = $_POST['jam_selesai'] ?? '';

        // Validasi input dasar
        if (!$guru_id || !$kelas_id || !$hari || !$jam_mulai || !$jam_selesai) {
            $message = 'Semua field wajib diisi.';
        } else {
            // Pastikan guru_id ada
            $chkGuru = $pdo->prepare('SELECT id FROM users WHERE id=? AND role="guru" LIMIT 1');
            $chkGuru->execute([$guru_id]);
            if (!$chkGuru->fetch()) {
                $message = 'Guru tidak ditemukan (invalid guru_id).';
            } else {
                // Pastikan kelas_id ada
                $chkKelas = $pdo->prepare('SELECT id FROM kelas WHERE id=? LIMIT 1');
                $chkKelas->execute([$kelas_id]);
                if (!$chkKelas->fetch()) {
                    $message = 'Kelas tidak ditemukan (invalid kelas_id).';
                } else {
                    // (Opsional) cek bentrok jadwal: guru pada hari tsb & jam tumpang tindih
                    $chkOverlap = $pdo->prepare('SELECT COUNT(*) FROM jadwal WHERE guru_id=? AND hari=? AND ((jam_mulai <= ? AND jam_selesai > ?) OR (jam_mulai < ? AND jam_selesai >= ?))');
                    $chkOverlap->execute([$guru_id, $hari, $jam_mulai, $jam_mulai, $jam_selesai, $jam_selesai]);
                    $overlapCount = (int)$chkOverlap->fetchColumn();
                    if ($overlapCount > 0) {
                        $message = 'Terjadi bentrok jadwal untuk guru pada jam tersebut.';
                    } else {
                        // Semua OK -> lakukan insert dengan try/catch
                        try {
                            $stmt = $pdo->prepare('INSERT INTO jadwal (guru_id,kelas_id,hari,jam_mulai,jam_selesai) VALUES (?,?,?,?,?)');
                            $stmt->execute([$guru_id, $kelas_id, $hari, $jam_mulai, $jam_selesai]);
                            $message = 'Jadwal berhasil ditambahkan.';
                        } catch (PDOException $e) {
                            // Tangkap error constraint/SQL dan tampilkan pesan ramah
                            error_log('Jadwal insert error: ' . $e->getMessage());
                            $message = 'Gagal menambah jadwal. Periksa data dan foreign key (kelas/guru).';
                        }
                    }
                }
            }
        }
    }

    // Hapus jadwal
    if (isset($_POST['del']) && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        try {
            $del = $pdo->prepare('DELETE FROM jadwal WHERE id=?');
            $del->execute([$id]);
            $message = 'Jadwal berhasil dihapus.';
        } catch (PDOException $e) {
            error_log('Jadwal delete error: ' . $e->getMessage());
            $message = 'Gagal menghapus jadwal.';
        }
    }

    // Redirect ke halaman yang sama untuk mencegah resubmission
    header('Location: jadwal.php?msg=' . urlencode($message));
    exit;
}

// Ambil data guru & kelas untuk dropdown
$gurus = $pdo->query("SELECT id, name FROM users WHERE role='guru' ORDER BY name")->fetchAll();
$kelas = $pdo->query("SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();

// Ambil semua jadwal
$jadwal = $pdo->query("
    SELECT j.*, u.name, k.nama_kelas
    FROM jadwal j
    JOIN users u ON u.id = j.guru_id
    JOIN kelas k ON k.id = j.kelas_id
    ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), j.jam_mulai
")->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Jadwal Mengajar</title>
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


/* FORM */
form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px 20px;
    background: var(--card);
    padding: 18px;
    border-radius: var(--radius);
    margin-bottom: 24px;
    border: 1px solid var(--border);
}

form label {
    display: block;
    font-weight: 500;
}

form select,
form input[type=time] {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 15px;
}

form button {
    grid-column: span 2;
    justify-self: center;
    margin-top: 4px;
}

/* BUTTON */
button {
    background: var(--primary);
    color: white;
    padding: 10px 18px;
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    font-size: 15px;
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


tr:hover td {
    background: #f8fafc;
}

/* DELETE BUTTON */
td form button {
    padding: 7px 12px;
    font-size: 14px;
    background: #dc2626;
    border-radius: var(--radius);
}

td form button:hover {
    background: #b91c1c;
}



</style>
</head>
<body>
<?php include 'includes/sidebar_admin.php'; ?>
<main>
<h2>Jadwal Mengajar</h2>

<?php if (isset($_GET['msg'])): ?>
    <p class="alert"><?= htmlspecialchars($_GET['msg']) ?></p>
<?php endif; ?>

<form method="post">
    <label>Guru:</label>
    <select name="guru_id" required>
        <option value="">-- Pilih Guru --</option>
        <?php foreach($gurus as $g): ?>
            <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Kelas:</label>
    <select name="kelas_id" required>
        <option value="">-- Pilih Kelas --</option>
        <?php foreach($kelas as $k): ?>
            <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Hari:</label>
    <select name="hari" required>
        <option value="">-- Pilih Hari --</option>
        <option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option><option>Minggu</option>
    </select>

    <label>Jam Mulai:</label>
    <input type="time" name="jam_mulai" required>

    <label>Jam Selesai:</label>
    <input type="time" name="jam_selesai" required>

    <button name="add">Tambah Jadwal</button>
</form>

<table>
    <tr><th>Guru</th><th>Kelas</th><th>Hari</th><th>Jam</th><th>Aksi</th></tr>
    <?php foreach($jadwal as $j): ?>
    <tr>
        <td><?= htmlspecialchars($j['name']) ?></td>
        <td><?= htmlspecialchars($j['nama_kelas']) ?></td>
        <td><?= htmlspecialchars($j['hari']) ?></td>
        <td><?= htmlspecialchars($j['jam_mulai']) ?> - <?= htmlspecialchars($j['jam_selesai']) ?></td>
        <td>
            <form method="post" onsubmit="return confirm('Hapus jadwal ini?')">
                <input type="hidden" name="id" value="<?= $j['id'] ?>">
                <button name="del">Hapus</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</main>
</body>
</html>
