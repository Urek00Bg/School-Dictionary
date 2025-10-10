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
      <h4 class="mb-4 text-center">Administrator Login</h4>

      <?php if ($error === 'invalid'): ?>
        <div class="alert alert-danger">Invalid username or password.</div>
      <?php elseif ($error === 'empty'): ?>
        <div class="alert alert-warning">Please fill in all fields.</div>
      <?php elseif (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
        <div class="alert alert-success">Password changed successfully. Please log in.</div>
      <?php endif; ?>

    <form method="POST" action="../../includes/password/authenticate.php">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>

        <!-- 🔹 New reset password button -->
        <a href="../reset_password/index.php" class="btn btn-outline-secondary w-100">
          <i class="bi bi-key"></i> Forgot / Reset Password
        </a>
      </form>
    </div>
  </div>
</div>

