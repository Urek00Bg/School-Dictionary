<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$username = trim($_POST['username'] ?? '');
$stmt = $pdo->prepare("
    SELECT id 
    FROM administrators 
    WHERE username = :u AND force_password_change = 1 
    LIMIT 1
");
$stmt->execute([':u' => $username]);
$user = $stmt->fetch();

if (!$user) {
    // Always respond the same to avoid username enumeration
    exit('If that username exists, a reset link has been generated.');
}

$token   = bin2hex(random_bytes(32));
$expires = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

$pdo->prepare("
    INSERT INTO password_reset_tokens (admin_id, username, token, expires_at)
    VALUES (:id, :username, :token, :exp)
")->execute([
    ':id'       => $user['id'],
    ':username' => $username,
    ':token'    => $token,
    ':exp'      => $expires
]);

echo "Reset link: <a href='reset_password.php?token=$token'>Reset password</a>";