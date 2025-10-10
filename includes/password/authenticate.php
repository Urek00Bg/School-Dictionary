<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/login/index.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: ../../pages/login/index.php?error=empty');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM administrators WHERE username = :u LIMIT 1");
$stmt->execute([':u' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password_hash'])) {
    header('Location: ../../pages/login/index.php?error=invalid');
    exit;
}

// ✅ success
loginUser($user);
logAdminAction($pdo, $user['id'], 'login', null, ['username' => $user['username']]);

// If force password change is active → redirect there immediately
if ((int)$user['force_password_change'] === 1) {
    header("Location: ../../pages/reset_password/index.php");
    exit;
}

header('Location: ../../pages/dashboard/index.php');
exit;
