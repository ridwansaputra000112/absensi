<?php
require 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $new_pass = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($new_pass !== $confirm) {
        $message = "Password tidak sama!";
    } else {
        // cek user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $message = "Email tidak terdaftar!";
        } else {
            $hash = password_hash($new_pass, PASSWORD_DEFAULT);

            $u = $pdo->prepare("UPDATE users SET password=? WHERE email=?");
            $u->execute([$hash, $email]);

            // Redirect ke login dengan pesan
            header("Location: login.php?msg=reset_success");
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Reset Password</title>
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





body {
    margin: 0;
    padding: 0;
    font-family: "Poppins", sans-serif;
}

.reset-page {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg,#0ea5a4,#0284c7);
    padding: 20px;
}

.reset-card {
    width: 380px;
    background: #fff;
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    animation: fadeInUp .45s ease;
}

.reset-card h2 {
    font-size: 22px;
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
    color: #0f172a;
}

/* MESSAGE */
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

/* FORM */
.reset-card form label {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 6px;
    color: #334155;
    display: block;
}

.reset-card input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 16px;
    transition: .25s;
}

.reset-card input:focus {
    border-color: #0ea5a4;
    box-shadow: 0 0 6px rgba(14,165,164,.35);
    outline: none;
}


.reset-card button:hover {
    background: #0c8584;
    transform: translateY(-2px);
}


/* ANIMATION */
@keyframes fadeInUp {
    0% {opacity: 0; transform: translateY(10px);}
    100% {opacity: 1; transform: translateY(0);}
}

/* RESPONSIVE */
@media(max-width: 450px) {
    .reset-card {
        width: 100%;
        padding: 26px;
        border-radius: 14px;
    }
}


</style>
</head>
<body>

<h2>Reset Password</h2>

<?php if ($message): ?>
    <p style="color:red;"><?= $message ?></p>
<?php endif; ?>

<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password Baru:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Ulangi Password:</label><br>
    <input type="password" name="confirm" required><br><br>

    <button type="submit">Reset Password</button>
</form>

</body>
</html>
