<?php
    $jePrihlasen = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HockeyTracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-lg py-1">
  <div class="container-fluid">
    
    <a class="navbar-brand navbar-brand-custom d-flex align-items-center" href="index.php">
        <span class="me-3">🏒</span>
        <span>HOCKEYTRACKER</span>
    </a>
    
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3 ms-auto mt-3 mt-lg-0 me-lg-4">
        
        <ul class="navbar-nav gap-2 flex-row flex-wrap">
          <li class="nav-item">
            <a class="nav-link btn nav-btn" href="tymy.php">Týmy</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn nav-btn" href="zapasy.php">Zápasy</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn nav-btn" href="strelci.php">Střelci</a>
          </li>
        </ul>

        <?php if ($jePrihlasen): ?>
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2 px-4 py-2 fw-bold" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5"></i> <?php echo htmlspecialchars($_SESSION['user']); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" href="logout.php">Odhlásit se</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a class="btn btn-light text-primary d-flex align-items-center gap-2 px-4 py-2 fw-bold shadow login-btn" href="login.php">
                <i class="bi bi-person-circle fs-5"></i> PŘIHLÁSIT
            </a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</nav>
<?php
// Flash zprávy
if (!empty($_SESSION['flash'])):
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $alertClass = $flash['type'] === 'error' ? 'danger' : $flash['type'];
    ?>
    <div class="alert alert-<?= htmlspecialchars($alertClass) ?> alert-dismissible fade show mb-0 text-center" role="alert">
        <?= htmlspecialchars($flash['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
