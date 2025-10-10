<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

if (is_logged_in()) {
    header("Location: ../dashboard/index.php");
    exit;
}

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
      <h4 class="text-center mb-4">Reset Password</h4>

      <?php if ($error === 'notfound'): ?>
        <div class="alert alert-danger">Username not found.</div>
      <?php elseif ($error === 'noreset'): ?>
        <div class="alert alert-warning">Password reset not required. Please log in.</div>
      <?php endif; ?>

      <form method="POST" action="../../includes/password/check_reset_request.php">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Continue</button>
      </form>
    </div>
  </div>
</div>

