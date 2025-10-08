<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$token = $_GET['token'] ?? '';
$stmt = $pdo->prepare("
    SELECT admin_id, expires_at 
    FROM password_reset_tokens 
    WHERE token = :t 
    LIMIT 1
");
$stmt->execute([':t' => $token]);
$row = $stmt->fetch();

if (!$row || new DateTime() > new DateTime($row['expires_at'])) {
    exit('Invalid or expired token');
}

$csrf = generateCsrfToken();
?>
<form method="POST" action="reset_password_update.php">
  <input type="hidden" name="csrf" value="<?= $csrf ?>">
  <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

  <label for="username">Confirm your username</label>
  <input type="text" class="form-control mb-3" name="username" required>

  <label for="new_password">New password</label>
  <input type="password" class="form-control mb-3" name="new_password" required>

  <button class="btn btn-primary w-100">Update password</button>
</form>
