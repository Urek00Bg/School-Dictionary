<?php
require_once __DIR__ . '/../includes/auth.php';

// Only start the session if not already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//  Fully destroy session
logoutUser();
session_destroy();

// Redirect to login page
header('Location: /School-Dictionary/pages/login.php?logout=1');
exit;