<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/reset_password/index.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
if ($username === '') {
    header("Location: ../../pages/reset_password/index.php?error=empty");
    exit;
}

$user = getUserByUsername($pdo, $username);

if (!$user) {
    header("Location: ../../pages/reset_password/index.php?error=notfound");
    exit;
}

if ((int)$user['force_password_change'] !== 1) {
    header("Location: ../../pages/login/index.php?error=noreset");
    exit;
}

// ✅ Valid — store user ID for reset
$_SESSION['pending_reset'] = $user['id'];

header("Location: ../../pages/reset_password/change.php");
exit;
