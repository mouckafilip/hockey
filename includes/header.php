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
    
    <style>
        /* Logo vlevo, výrazné, font Oswald */
        .navbar-brand-custom {
            font-family: 'Oswald', sans-serif;
            font-size: 2.4rem !important; 
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #ffffff !important;
            margin-left: 40px; /* Odsazení od levého okraje */
        }

        /* Styl tlačítek v menu */
        .nav-btn {
            font-family: 'Oswald', sans-serif;
            color: #ffffff !important;
            font-weight: 400;
            background-color: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.4) !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            padding: 8px 22px !important;
            text-transform: uppercase;
        }
        .nav-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: #ffffff !important;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-lg py-3">
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

        <a class="btn btn-light text-primary d-flex align-items-center gap-2 px-4 py-2 fw-bold shadow" href="login.php" style="border-radius: 8px; font-family: 'Oswald', sans-serif;">
          <i class="bi bi-person-circle"></i> PŘIHLÁSIT
        </a>

      </div>
    </div>
  </div>
</nav>

</body>
</html>