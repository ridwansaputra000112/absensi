<?php
require 'includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    if (!$name || !$email || !$pass) $err = 'Lengkapi semua field';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $err = 'Email tidak valid';
    else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email=?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) $err = 'Email sudah terdaftar';
        else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
            $ins->execute([$name,$email,$hash,'guru']);
            $uid = $pdo->lastInsertId();
            $pdo->prepare('INSERT INTO guru (user_id) VALUES (?)')->execute([$uid]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>

<style>
/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

/* PAGE BACKGROUND */
body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg,#0284c7,#0ea5a4);
    padding: 20px;
}

/* CARD */
main {
    width: 400px;
    background: #ffffff;
    padding: 34px;
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    animation: fadeInUp .45s ease;
}

/* TITLE */
main h2 {
    font-size: 22px;
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    color: #0f172a;
}

/* FORM */
form label {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 6px;
    color: #334155;
    display: block;
}

form input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 16px;
    transition: .25s;
}

form input:focus {
    border-color: #0284c7;
    box-shadow: 0 0 6px rgba(2,132,199,.35);
    outline: none;
}

button {
    width: 100%;
    background: #0284c7;
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: .25s;
    box-shadow: 0 4px 16px rgba(2,132,199,.25);
    color: #fff;
}

button:hover {
    background: #0e7490;
    transform: translateY(-2px);
}

/* ALERT */
.alert {
    padding: 10px 12px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 14px;
    text-align: center;
}

.error {
    background: #fee2e2;
    color: #b91c1c;
}

/* LINK */
p {
    text-align: center;
    margin-top: 12px;
    font-size: 14px;
}

a {
    color: #0284c7;
    text-decoration: none;
    font-weight: 500;
    transition: .25s;
}

a:hover {
    color: #0ea5a4;
}

/* ANIMATION */
@keyframes fadeInUp {
    0% {opacity: 0; transform: translateY(10px);}
    100% {opacity: 1; transform: translateY(0);}
}

/* RESPONSIVE */
@media(max-width: 450px) {
    main {
        width: 100%;
        padding: 26px;
        border-radius: 14px;
    }
}
</style>

</styl>

</head>
<body>

<main>
    <h2>Daftar Guru</h2>

    <?php if(isset($err)): ?>
        <p class="alert error"><?= htmlspecialchars($err) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Nama</label>
        <input name="name" required>

        <label>Email</label>
        <input name="email" type="email" required>

        <label>Password</label>
        <input name="password" type="password" required>

        <button>Daftar</button>
    </form>

    <p><a href="login.php">Kembali ke login</a></p>
</main>

</body>
</html>
