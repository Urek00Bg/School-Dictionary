<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth.php';

requirePendingReset();

$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($new !== $confirm) {
    header("Location: ../../pages/reset_password/change.php?error=mismatch");
    exit;
}

$id = $_SESSION['pending_reset'];

// ✅ Update password and clear force flag
$updated = updatePassword($pdo, $id, $new, true);

if ($updated) {
    unset($_SESSION['pending_reset']);
    header("Location: ../../pages/login/index.php?reset=success");
    exit;
} else {
    header("Location: ../../pages/reset_password/change.php?error=failed");
    exit;
}
