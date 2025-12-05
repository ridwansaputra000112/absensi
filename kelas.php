<?php
require 'includes/db.php';
require 'includes/auth.php';
check_login();
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $n = trim($_POST['nama']);
        $pdo->prepare('INSERT INTO kelas (nama_kelas) VALUES (?)')->execute([$n]);
    }
    if (isset($_POST['del'])) {
        $pdo->prepare('DELETE FROM kelas WHERE id=?')->execute([$_POST['id']]);
    }
    header('Location: kelas.php');
    exit;
}

$kelas = $pdo->query('SELECT * FROM kelas ORDER BY nama_kelas')->fetchAll();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kelas</title>

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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: #f1f5f9;
            display: flex;
        }

        main {
            flex: 1;
            padding: 35px;
            min-height: 100vh;
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e293b;
        }

        /* ==== FORM TAMBAH KELAS ==== */
        form {
            margin-bottom: 25px;
            display: flex;
            gap: 10px;
        }


        input[name="nama"] {
            flex: 1;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            background: #fff;
            transition: 0.2s;

        }

        input[name="nama"]:focus {
            border-color: #0ea5a4;
            box-shadow: 0 0 4px rgba(14, 165, 164, 0.3);
            outline: none;
        }

        button {
            padding: 10px 16px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.25s;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        button[name="add"] {
            background: #0ea5a4;
            color: #fff;
        }

        button[name="add"]:hover {
            background: #0c8d8b;
            transform: translateY(-2px);
        }
    </style>

</head>

<body>

    <?php include 'includes/sidebar_admin.php'; ?>

    <main>
        <h2>Kelola Kelas</h2>

        <form method="post">
            <input name="nama" placeholder="Nama Kelas" required>
            <button name="add">Tambah</button>
        </form>

        <ul>
            <?php foreach ($kelas as $k): ?>
                <li>
                    <?= htmlspecialchars($k['nama_kelas']) ?>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="id" value="<?= $k['id'] ?>">
                        <button name="del" onclick="return confirm('Hapus kelas ini?')">Hapus</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

    </main>
</body>

</html>