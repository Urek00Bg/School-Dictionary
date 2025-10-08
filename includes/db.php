<?php
/**
 * Use: require_once __DIR__ . '/db.php';
 */

$DB_HOST = 'host.docker.internal'; //localhost
$DB_USER = 'root';          //set username
$DB_PASS = 'rootpassword';              // set password
$DB_NAME = 'SchoolDictionary'; // change this to your DB name
$DB_CHARSET = 'utf8mb4';

// === CONNECTION ===
try {
    $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=$DB_CHARSET";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // throw exceptions on error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // return associative arrays
        PDO::ATTR_PERSISTENT         => true                     // reuse connections
    ]);
} catch (PDOException $e) {
    // Show clear message in dev, silent log in prod
    die(" Database connection failed: " . $e->getMessage());
}

function getAllAdministrators(PDO $pdo): array {
    $stmt = $pdo->prepare("
        SELECT 
            id,
            username,
            is_admin,
            is_superadmin,
            created_at,
            updated_at,
            force_password_change
        FROM administrators
        ORDER BY created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Optional: test connection once on load
// echo " Connected to database successfully";
?>