<?php
// includes/auth.php
if(session_status() === PHP_SESSION_NONE) session_start();

function check_login() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function require_admin() {
    check_login();
    if ($_SESSION['user']['role'] !== 'admin') {
        http_response_code(403);
        echo 'Akses ditolak. Hanya admin.';
        exit;
    }
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function refresh_session_user($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT id,name,email,role,created_at FROM users WHERE id=? LIMIT 1");
    $stmt->execute([$user_id]);
    $_SESSION['user'] = $stmt->fetch();
}
