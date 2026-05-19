<?php 
// 2.1: Načtení headeru
include __DIR__ . '/includes/header.php'; 
?>

<div class="hero-section" style="
    background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('image_2.jpeg'); 
    background-size: cover; 
    background-position: center; 
    height: 70vh; 
    display: flex; 
    flex-direction: column; 
    justify-content: center; 
    align-items: center; 
    text-align: center; 
    color: white; 
    padding: 20px;
    animation: moveBackground 30s ease-in-out infinite alternate;">

    <h1 style="font-size: 3.5rem; font-weight: 800; text-transform: uppercase; line-height: 1.2; text-shadow: 0 0 15px rgba(56, 189, 248, 0.8);">
        SLEDUJTE AKTUÁLNÍ <span style="color: #38bdf8; text-shadow: 0 0 15px #38bdf8;">STATISTIKY</span>, 
        <span style="color: #38bdf8; text-shadow: 0 0 15px #38bdf8;">VÝSLEDKY</span> 
        <br>
        <span style="color: #38bdf8; text-shadow: 0 0 15px #38bdf8;">A TABULKY Z MS V HOKEJI 2026.</span>
    </h1>

    <a href="zapasy.php" class="btn btn-lg mt-4 text-white" style="
        background: transparent; 
        border: 2px solid #38bdf8; 
        padding: 12px 30px; 
        text-transform: uppercase; 
        font-weight: bold;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.5);
        transition: 0.3s;">
        ZOBRAZIT ZÁPASY
    </a>
</div>

<style>
    /* Pomalá a plynulá animace */
    @keyframes moveBackground {
        0% { background-position: 50% 0%; }
        100% { background-position: 50% 100%; }
    }

    .btn:hover { 
        background: #38bdf8 !important; 
        color: black !important; 
        box-shadow: 0 0 25px #38bdf8 !important;
    }
</style>

<div class="container my-5">
    </div>

<?php 
// 2.1: Načtení footeru
include __DIR__ . '/../includes/footer.php'; 
?>