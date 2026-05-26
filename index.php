<?php
    session_start();
    require __DIR__ . '/config/flash.php';
    require __DIR__ . '/config/db.php';
?>


<?php
    include __DIR__ . '/includes/header.php';
?>

<div class="hero-section">
    <h1 class="hero-title">
        SLEDUJTE AKTUÁLNÍ<br>
        <span class="hero-highlight">STATISTIKY, VÝSLEDKY A TABULKY</span><br>
        Z MS V HOKEJI 2026 <i class="bi bi-trophy-fill fs-1"></i>
    </h1>
    <a href="zapasy.php" class="btn btn-lg mt-4 text-white hero-btn">
        ZOBRAZIT ZÁPASY
    </a>
</div>

<main class="container pb-5">
    <?php
        if (!empty($_SESSION['flash'])):
        endif;
    ?>
</main>

<?php 
include __DIR__ . '/includes/footer.php'; 
?>