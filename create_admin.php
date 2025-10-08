<?php
require_once __DIR__ . '/includes/db.php';

$username = 'admin';       // change this to your username
$password = '123';        // change this to your password
$is_admin = 1;
$is_superadmin = 1;

$hash = password_hash($password, PASSWORD_ARGON2ID);

try {
    $stmt = $pdo->prepare("
        INSERT INTO administrators (username, password_hash, is_admin, is_superadmin)
        VALUES (:u, :p, :a, :s)
    ");
    $stmt->execute([
        ':u' => $username,
        ':p' => $hash,
        ':a' => $is_admin,
        ':s' => $is_superadmin
    ]);

    echo " Admin account created successfully!<br>";
    echo "Username: <b>{$username}</b><br>Password: <b>{$password}</b><br>";
    echo "<br>!!!! For security, delete this file (create_admin.php) after logging in.";
} catch (PDOException $e) {
    echo " Failed to create admin: " . $e->getMessage();
}
