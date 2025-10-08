<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

if (!verifyCsrfToken($_POST['csrf'] ?? '')) {
    http_response_code(403);
    exit('Invalid CSRF token');
}

$token     = $_POST['token'] ?? '';
$username  = trim($_POST['username'] ?? '');
$newPass   = $_POST['new_password'] ?? '';

if ($token === '' || $username === '' || $newPass === '') {
    exit('Missing fields.');
}

if (strlen($newPass) < 8) {
    exit('Password must be at least 8 characters long.');
}

//  Validate token
$stmt = $pdo->prepare("
    SELECT admin_id, username, expires_at 
    FROM password_reset_tokens 
    WHERE token = :t 
    LIMIT 1
");
$stmt->execute([':t' => $token]);
$row = $stmt->fetch();

if (!$row || new DateTime() > new DateTime($row['expires_at'])) {
    exit('Invalid or expired reset token.');
}

//  Verify username matches the original request
if (strcasecmp($row['username'], $username) !== 0) {
    exit('Username does not match this reset request.');
}

//  Update password securely
$hash = password_hash($newPass, PASSWORD_ARGON2ID);

$update = $pdo->prepare("
    UPDATE administrators 
    SET password_hash = :ph, force_password_change = 0 
    WHERE id = :id
");
$update->execute([
    ':ph' => $hash,
    ':id' => $row['admin_id']
]);

//  Delete the used token
$pdo->prepare("DELETE FROM password_reset_tokens WHERE token = :t")
    ->execute([':t' => $token]);

//  Log the action
logAdminAction($pdo, $row['admin_id'], 'password_reset_success');

//  Redirect back to login
header('Location: /School-Dictionary/pages/login.php?reset=1');
exit;
