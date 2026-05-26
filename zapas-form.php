<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container my-5">
    <a href="zapasy.php" class="text-decoration-none mb-3 d-block">&larr; Zpět na zápasy</a>
    <h1>Nový zápas</h1>

    <div class="card shadow-sm p-4">
        <form action="ulozit-zapas.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Datum a čas *</label>
                    <input type="datetime-local" class="form-control" name="datum" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fáze</label>
                    <select class="form-select" name="faze">
                        <option value="skupina">Skupina</option>
                        <option value="playoff">Play-off</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Domácí tým *</label>
                    <select class="form-select" name="domaci_tym" required>
                        <option value="">-- vyber tým --</option>
                        </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Hosté *</label>
                    <select class="form-select" name="hoste_tym" required>
                        <option value="">-- vyber tým --</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Skóre domácí</label>
                    <input type="number" class="form-control" name="skore_domaci" min="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Skóre hosté</label>
                    <input type="number" class="form-control" name="skore_hoste" min="0">
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="prodlouzeni" id="prodlouzeni">
                        <label class="form-check-label" for="prodlouzeni">Rozhodnuto v prodloužení / nájezdech</label>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label">Aréna</label>
                    <input type="text" class="form-control" name="arena" placeholder="BCF Arena, Fribourg">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">&check; Uložit</button>
            <a href="zapasy.php" class="btn btn-secondary">Zrušit</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>