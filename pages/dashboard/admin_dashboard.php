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
    <?php if (isset($_GET['error']) && $_GET['error'] === 'noperms'): ?>
      <div class="alert alert-danger">You must be a super admin to add new administrators.</div>
    <?php endif; ?>

        <section class="d-flex justify-content-center align-items-center">
           <p class="h3">Dashboard</p>
           <button class="btn btn-primary ms-3" onclick="location.href='administrators.php'">Administrators</button>
        </section>

       <section class="cards">

    <div class="container text-center py-5">
        <div class="row justify-content-center g-4">
            
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
  <div class="card h-100 shadow-sm">
    <div class="card-body">
      <h5 class="card-title mb-3">Add Administrator</h5>

      <form method="POST" action="../../includes/add_admin.php">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin">
          <label class="form-check-label" for="is_admin">Is Admin</label>
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="force_password_change" name="force_password_change">
          <label class="form-check-label" for="force_password_change">Force Password Change</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Add Administrator</button>
      </form>
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