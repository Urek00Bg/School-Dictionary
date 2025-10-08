<?php
 require_once __DIR__ . '/includes/db.php';
 require_once __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html>
<?php require_once __DIR__ . '/includes/webincludes/head.php'; ?>
<html>
<body class="bg-light">

<?php require_once __DIR__ . '/includes/webincludes/header.php'; ?>

<section class="container mt-5 pt-3 mx-auto text-center">
  <h1>School Dictionary</h1>
  <p class="text-muted">A simple dictionary application for school use.</p>
</section>

<div class="container mt-3 text-center">
  <div class="d-inline-flex align-items-center gap-2">
    <label for="gradeSelect" class="fw-semibold mb-0">Filter by grade:</label>
    <select id="gradeSelect" class="form-select w-auto">
      <option value="0">All grades</option>
      <?php for ($i = 1; $i <= 12; $i++): ?>
        <option value="<?= $i ?>">Grade <?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>
</div>

<div class="container mt-4 pb-5">
  <div id="cardContainer" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4"></div>
</div>





<script src="assets/js/search.js"></script>
</body>

</html>