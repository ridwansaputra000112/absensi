<?php
require 'includes/db.php';
session_start();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if ($u && password_verify($pass, $u['password'])) {
        $_SESSION['user'] = [
            'id'=>$u['id'],
            'name'=>$u['name'],
            'email'=>$u['email'],
            'role'=>$u['role']
        ];
        header('Location: '.($u['role']=='admin' ? 'dashboard_admin.php' : 'dashboard_guru.php'));
        exit;
    } else {
        $msg = 'Email atau password salah';
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>

<style>
/* -----------------------------
   LOGIN PAGE STYLE
------------------------------ */
body {
    margin: 0;
    padding: 0;
    font-family: "Poppins", sans-serif;
}

.login-page {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg,#0ea5a4,#0284c7);
    padding: 20px;
}

.login-card {
    width: 380px;
    background: #fff;
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    animation: fadeInUp .45s ease;
}

.login-card h2 {
    font-size: 22px;
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    color: #0f172a;
}

.login-card form label {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 6px;
    color: #334155;
    display: block;
}

.login-card input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 16px;
    transition: .25s;
}

.login-card input:focus {
    border-color: #0ea5a4;
    box-shadow: 0 0 6px rgba(14,165,164,.35);
    outline: none;
}

.login-card button {
    width: 100%;
    background: #0ea5a4;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: .25s;
    box-shadow: 0 4px 16px rgba(14,165,164,.25);
    color: #fff;
}

.login-card button:hover {
    background: #0c8584;
    transform: translateY(-2px);
}

.success-msg {
    background: #d1fae5;
    color: #065f46;
    padding: 10px 12px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 14px;
}

.error-msg {
    background: #fee2e2;
    color: #b91c1c;
    padding: 10px 12px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 14px;
}

.login-card a {
    font-size: 14px;
    text-decoration: none;
    color: #0284c7;
    transition: .25s;
}

.login-card a:hover {
    color: #0ea5a4;
}

.links {
    text-align: center;
    margin-top: 15px;
}

.footer {
    text-align: center;
    font-size: 13px;
    color: #64748b;
    margin-top: 15px;
}

@keyframes fadeInUp {
    0% {opacity: 0; transform: translateY(10px);}
    100% {opacity: 1; transform: translateY(0);}
}

@media(max-width: 450px) {
    .login-card {
        width: 100%;
        padding: 26px;
        border-radius: 14px;
    }
}
</style>
</head>
<body>

<div class="login-page">
    <div class="login-card">
        <h2>Login</h2>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'reset_success'): ?>
            <p class="success-msg">Reset password berhasil. Silakan login.</p>
        <?php endif; ?>

        <?php if ($msg): ?>
            <p class="error-msg"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['registered'])): ?>
            <p class="success-msg">Registrasi berhasil. Silakan login.</p>
        <?php endif; ?>

        <form method="post">
            <label>Email</label>
            <input name="email" type="email" required>

            <label>Password</label>
            <input name="password" type="password" required>

            <button>Login</button>
        </form>

        <div class="links">
            <p><a href="reset_password.php">Lupa Password?</a></p>
            <p><a href="register.php">register</a></p>
        </div>

        <div class="footer">© 2025 Sistem Absensi</div>
    </div>
</div>

</body>
</html>
