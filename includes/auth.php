<?php
session_start([
 'cookie_httponly' => true,
  'cookie_secure' => isset($_SERVER['HTTPS']),
  'cookie_samesite' => 'Strict',
    'cookie_path' => '/' //  makes cookie visible to ALL pages
]);

function is_logged_in(): bool {
    return isset($_SESSION['admin']['id']);
}


function loginUser(array $user): void{
    session_regenerate_id(true);
    $_SESSION['admin'] = [
        'id' => (int)$user['id'],
        'username' => htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'),
        'is_admin' => (bool)$user['is_admin'],
        'is_superadmin' => (bool)$user['is_superadmin'],
    ];
}

function logoutUser(): void{
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}

function requireLogin():void{
    if (!is_logged_in()) {
        header('Location: /School-Dictionary/pages/login.php');
        exit;
    }
}

function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function requirePendingReset(): void {
    if (!isset($_SESSION['pending_reset'])) {
        header('Location: /School-Dictionary/pages/login.php');
        exit;
    }
}

function hasForcePasswordFlag(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare("SELECT force_password_change FROM administrators WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    return $row && (bool)$row['force_password_change'];
}

function logAdminAction(PDO $pdo, int $actorId, string $action, ?int $targetId = null, array $meta = []): void {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO admin_logs (actor_id, target_id, action, meta, ip_address, user_agent)
            VALUES (:actor, :target, :action, :meta, :ip, :ua)
        ");
        $stmt->execute([
            ':actor'  => $actorId,
            ':target' => $targetId,
            ':action' => $action,
            ':meta'   => json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ':ip'     => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            ':ua'     => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Throwable $e) {
        error_log("Failed to log admin action: " . $e->getMessage());
    }
}

function renderUserStatus(): void {
    $username = $_SESSION['admin']['username'] ?? null;
    $displayName = htmlspecialchars($username ?? 'Guest', ENT_QUOTES, 'UTF-8');

    ?>
    <p class="text-end px-5">
      Hello:
      <strong><?= $displayName ?></strong>
    </p>
    <?php
}