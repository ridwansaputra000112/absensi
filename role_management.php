<?php
require 'includes/db.php';
require 'includes/auth.php';
check_login();
require_admin();

// PROSES POST: UPDATE ROLE atau HAPUS USER
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete'])) {
        $uid = (int)$_POST['user_id'];
        if ($uid == $_SESSION['user_id']) {
            echo "<script>alert('Tidak dapat menghapus akun anda sendiri');window.location='role_management.php';</script>";
            exit;
        }
        $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$uid]);
        echo "<script>alert('User berhasil dihapus');window.location='role_management.php';</script>";
        exit;
    }

    if (isset($_POST['update'])) {
        $uid = $_POST['user_id'];
        $role = $_POST['role'];
        $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$role, $uid]);
        echo "<script>alert('Role berhasil diperbarui');window.location='role_management.php';</script>";
        exit;
    }
}

$users = $pdo->query("SELECT id, name, email, role FROM users ORDER BY role DESC, name")->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Role Management</title>

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
        padding: 35px;
        flex: 1;
        min-height: 100vh;
    }

    h2 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #1e293b;
    }

    /* ===== TABLE ===== */
    table {
        width: 100%;
        border-collapse: 10px;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    th {
        background: #0ea5a4;
        color: #fff;
        padding: 12px;
        font-size: 14px;
        text-align: left;
    }

    td {
        padding: 10px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
    }

    tr:hover td {
        background: #f0fdfa;
    }

    /* ===== SELECT ===== */
    select {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-size: 14px;
        margin-right: 6px;
        background: #fff;
        transition: 0.2s;
    }

    select:focus {
        border-color: #0ea5a4;
        outline: none;
        box-shadow: 0 0 4px rgba(14,165,164,0.4);
    }

    /* ===== BUTTON ===== */
    button {
        padding: 6px 12px;
        font-size: 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.25s;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    button[name="update"] {
        background: #0284c7;
        color: #fff;
    }
    button[name="update"]:hover {
        background: #0c8d8b;
        transform: translateY(-2px);
    }

    button[name="delete"] {
        background: #ef4444;
        color: #fff;
        margin-left: 6px;
    }
    button[name="delete"]:hover {
        background: #dc2626;
        transform: translateY(-2px);
    }

    form {
        display: inline-block;
    }

</style>

</head>
<body>

<?php include 'includes/sidebar_admin.php'; ?>

<main>
<h2>Role Management</h2>

<table>
<tr>
    <th>Nama</th>
    <th>Email</th>
    <th>Role</th>
    <th>Aksi</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= htmlspecialchars($u['role']) ?></td>
    <td>
        <form method="post">
            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">

            <select name="role">
                <option value="guru" <?= $u['role']=='guru' ? 'selected':'' ?>>Guru</option>
                <option value="admin" <?= $u['role']=='admin' ? 'selected':'' ?>>Admin</option>
            </select>

            <button name="update">Update</button>
        </form>

        <form method="post">
            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
            <button name="delete" onclick="return confirm('Yakin hapus user ini?')">Hapus</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>
</main>

</body>
</html>
