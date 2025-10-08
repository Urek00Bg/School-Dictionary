<?php
ob_start(); // start buffering, prevents any accidental output
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$csrf = $_POST['csrf'] ?? '';
if (!verifyCsrfToken($csrf)) {
    http_response_code(403);
    exit('Invalid CSRF token');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("
    SELECT id, username, password_hash, is_admin, is_superadmin 
    FROM administrators 
    WHERE username = :u 
    LIMIT 1
");
$stmt->execute([':u' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    loginUser($user);
    logAdminAction($pdo, $user['id'], 'login_success');

    // absolute redirect to avoid relative path confusion
    header('Location: /School-Dictionary/pages/dashboard/dashboard.php');
    ob_end_flush();
    exit;
}

// failed login
header('Location: /School-Dictionary/pages/login.php?error=1');
ob_end_flush();
exit;
