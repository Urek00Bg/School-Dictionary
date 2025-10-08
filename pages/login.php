<?php 
    require_once __DIR__ . '/../includes/db.php';
    require_once __DIR__ . '/../includes/auth.php';

    if(is_logged_in()){
        // absolute redirect to avoid relative path confusion
        header('Location: /School-Dictionary/pages/dashboard/dashboard.php');
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once __DIR__ . '/../includes/webincludes/head.php'; ?>
    </head>
    <body>
        <style>
            .isLoginActive{
                opacity: .2;
                pointer-events: none;
            }
        </style>
        <?php
            require_once __DIR__ . '/../includes/webincludes/header.php';
        ?>

        <section class="d-flex justify-content-center align-items-center pt-5">
            <div class="card shadow-sm p-4" style="max-width: 380px; width: 100%;">
                <div class="text-center mb-4">
                <h1 class="h3 mb-3 fw-normal">Login</h1>
                <p class="text-muted">Sign in to access your dashboard</p>
                </div>
                <?php require_once __DIR__ . '/../includes/auth.php'; $csrf = generateCsrfToken(); ?>
                <form method="POST" action="authenticate.php" autocomplete="off">
                <input type="hidden" name="csrf" value="<?= $csrf ?>">
                <div class="mb-3 text-start">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control form-control-sm" id="username" name="username" required>
                </div>

                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                </div>
                <p class="mt-3"><a href="password/forgot_password.php">Forgot password?</a></p>

                <button type="submit" class="btn btn-primary w-100">Log In</button>
                </form>
            </div>
        </section>


    </body>
</html>