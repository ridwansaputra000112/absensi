<?php
require 'includes/db.php';
require 'includes/auth.php';
check_login();
$user = current_user();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name = trim($_POST['name']); $email = trim($_POST['email']);
    $pdo->prepare('UPDATE users SET name=?, email=? WHERE id=?')->execute([$name,$email,$user['id']]);
    refresh_session_user($pdo, $user['id']);
    $msg = 'Profil diperbarui';
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Edit Profil</title></head><body>
<?php include 'includes/sidebar_guru.php'; ?>
<main>
<h2>Edit Profil</h2>
<?php if(isset($msg)) echo '<p style="color:green">'.htmlspecialchars($msg).'</p>'; ?>
<form method="post"><label>Nama<br><input name="name" value="<?=htmlspecialchars($user['name'])?>"></label><br><label>Email<br><input name="email" value="<?=htmlspecialchars($user['email'])?>"></label><br><button>Simpan</button></form>
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

/* CARD FORM */
form {
    background: var(--card);
    padding: 24px;
    max-width: 500px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

label {
    display: block;
    font-weight: 500;
    margin-bottom: 14px;
}

input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 15px;
    margin-top: 8px;
    background: #ffffff;
}
