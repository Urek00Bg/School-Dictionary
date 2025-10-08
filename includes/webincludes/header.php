  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <!--<img src="https://img.freepik.com/free-vector/illustration-gallery-icon_53876-27002.jpg" alt="Bootstrap" width="30" height="24">-->
                    <i class="bi bi-bootstrap-fill fs-3"></i>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-3 mb-lg-0 ml-5">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="/School-Dictionary/index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active isLoginActive" aria-current="page" href="/School-Dictionary/pages/login.php">Log in</a></li>
                    <li class="nav-item"><a class="nav-link active isLoginActive" aria-current="page" href="/School-Dictionary/pages/dashboard/dashboard.php">Dashboard</a></li>
                </ul>
                <form class="d-flex ms-lg-0 m-2 role="search" onsubmit="return false;">
                    <input id="q" class="form-control form-control-sm me-2" type="search" name="q" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success btn-sm" type="button" id="btnSearch">Search</button>
                </form>
                <form class="m-2" action="/School-Dictionary/pages/logout.php" method="post" class="d-inline">
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
              </form>
            </div>
      </div>
    </nav>
    <?php
    renderUserStatus();
    ?>
  </header>