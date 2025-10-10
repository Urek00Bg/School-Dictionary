<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

requirePendingReset();

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once __DIR__ . '/../../includes/webincludes/head.php'; ?>
<body class="bg-light">
<?php require_once __DIR__ . '/../../includes/webincludes/header.php'; ?>

<div class="container mt-5" style="max-width:420px;">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="text-center mb-4">Set a New Password</h4>

      <?php if ($error === 'mismatch'): ?>
        <div class="alert alert-danger">Passwords do not match.</div>
      <?php endif; ?>

      <form method="POST" action="../../includes/password/update_password.php">
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" class="form-control" name="new_password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" class="form-control" name="confirm_password" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Save Password</button>
      </form>
    </div>
  </div>
</div>

