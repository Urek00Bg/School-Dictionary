<?php 
    require_once __DIR__ . '/../../includes/db.php';
    require_once __DIR__ . '/../../includes/auth.php';

    requireLogin();

    $admins = getAllAdministrators($pdo);

?>
<!DOCTYPE html>
<html>
        <?php require_once __DIR__ . '/../../includes/webincludes/head.php'; ?>
    <body>
        <?php
            require_once __DIR__ . '/../../includes/webincludes/header.php';
        ?>

        <section class="d-flex justify-content-center align-items-center">
           <p class="h3">Dashboard</p>
        </section>

       <section class="cards">

    <div class="container text-center py-5">
        <div class="row justify-content-center g-4">
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">Card subtitle</h6>
                    <p class="card-text">
                        Some quick example text to build on the card title and make up the bulk of the card’s content.
                    </p>
                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>

           
            <?php foreach ($admins as $a): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($a['username'], ENT_QUOTES, 'UTF-8') ?></h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">
                    <?= $a['is_superadmin'] ? 'Super Admin' : ($a['is_admin'] ? 'Admin' : 'User') ?>
                    </h6>
                    <p class="card-text">
                    <strong>ID:</strong> <?= htmlspecialchars($a['id']) ?><br>
                    <strong>Is admin:</strong><?= htmlspecialchars($a['is_admin'] ? 'Yes' : 'No') ?><br>
                    <strong>Is superadmin:</strong><?= htmlspecialchars($a['is_superadmin'] ? 'Yes' : 'No') ?><br>
                    <strong>Force password change:</strong><?= $a['force_password_change'] ? 'Yes' : 'No' ?><br>
                    <strong>Created:</strong> <?= htmlspecialchars($a['created_at']) ?>
                    <strong>Updated:</strong> <?= htmlspecialchars($a['updated_at']) ?>
                    </p>
                    <a href="#" class="card-link">Edit</a>
                    <a href="#" class="card-link text-danger">Delete</a>
                </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>

       </section>

    </body>
</html>