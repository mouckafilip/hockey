<?php
    session_start();
    require __DIR__ . '/config/flash.php';
    require __DIR__ . '/config/db.php';
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container my-5">
    <a href="tymy.php" class="text-decoration-none mb-3 d-block">&larr; Zpět na seznam týmů</a>
    <h1>Nový tým</h1>

    <div class="card shadow-sm p-4">
        <form action="ulozit-tym.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Název týmu *</label>
                    <input type="text" class="form-control" name="nazev" required placeholder="např. HC Sparta Praha">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Zkratka *</label>
                    <input type="text" class="form-control" name="zkratka" maxlength="3" required placeholder="např. SPA">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Město / Země</label>
                    <input type="text" class="form-control" name="mesto" placeholder="např. Praha">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">URL odkazu na logo</label>
                    <input type="url" class="form-control" name="logo_url" placeholder="https://...">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Poznámka</label>
                    <textarea class="form-control" name="poznamka" rows="2"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">&check; Uložit tým</button>
            <a href="tymy.php" class="btn btn-secondary">Zrušit</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>