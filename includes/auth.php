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
        header('Location: /School-Dictionary/pages/login/index.php');
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
        header('Location: /School-Dictionary/pages/login/index.php');
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

function isSuperAdmin(PDO $pdo): bool {
    if (empty($_SESSION['admin']['id'])) {
        return false;
    }

    $stmt = $pdo->prepare("SELECT is_superadmin FROM administrators WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $_SESSION['admin']['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result && (int)$result['is_superadmin'] === 1;
}


function addAdministrator(
    PDO $pdo,
    string $username,
    string $password,
    bool $is_admin = false,
    bool $is_superadmin = false,
    bool $force_password_change = false,
    ?int $created_by = null
): bool {
    // Hash password securely
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO administrators (
            username,
            password_hash,
            is_admin,
            is_superadmin,
            force_password_change,
            created_at,
            updated_at,
            last_password_reset_at,
            last_password_reset_by
        ) VALUES (
            :username,
            :password_hash,
            :is_admin,
            :is_superadmin,
            :force_password_change,
            NOW(),
            NOW(),
            NULL,
            :created_by
        )
    ");

    return $stmt->execute([
        ':username' => $username,
        ':password_hash' => $password_hash,
        ':is_admin' => $is_admin ? 1 : 0,
        ':is_superadmin' => $is_superadmin ? 1 : 0,
        ':force_password_change' => $force_password_change ? 1 : 0,
        ':created_by' => $created_by,
    ]);}

    function getUserByUsername(PDO $pdo, string $username): ?array {
    $stmt = $pdo->prepare("SELECT * FROM administrators WHERE username = :u LIMIT 1");
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ?: null;
}

function updatePassword(PDO $pdo, int $id, string $newPassword, bool $clearForceFlag = true): bool {
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "
        UPDATE administrators
        SET password_hash = :hash,
            updated_at = NOW(),
            last_password_reset_at = NOW(),
            last_password_reset_by = :id" .
        ($clearForceFlag ? ", force_password_change = 0" : "") . "
        WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':hash' => $hash, ':id' => $id]);
}

function requireForcePasswordChange(PDO $pdo): void {
    if (!is_logged_in()) {
        header('Location: /School-Dictionary/pages/login/index.php');
        exit;
    }

    if (hasForcePasswordFlag($pdo, $_SESSION['admin']['id'])) {
        header('Location: /School-Dictionary/pages/reset_password/index.php');
        exit;
    }
}