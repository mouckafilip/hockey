<?php
session_start();
require __DIR__ . '/config/flash.php';
require __DIR__ . '/config/db.php';

// Detekce, zda se jedná o editaci nebo nový tým
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;

// 1. ZPRACOVÁNÍ FORMULÁŘE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $isEditPost = $id > 0;

    $nazev = trim($_POST['nazev'] ?? '');
    $zkratka = strtoupper(trim($_POST['zkratka'] ?? ''));
    $vlajka = trim($_POST['vlajka'] ?? '');
    $skupina = trim($_POST['skupina'] ?? 'A');
    $trener = trim($_POST['trener'] ?? '');

    // Serverová validace
    if (!empty($nazev) && !empty($zkratka) && !empty($skupina)) {
        try {
            if ($isEditPost) {
                $stmt = $pdo->prepare("UPDATE tymy SET 
                    nazev = ?, zkratka = ?, vlajka = ?, skupina = ?, 
                    trener = ? 
                    WHERE id = ?");
                $stmt->execute([$nazev, $zkratka, $vlajka, $skupina, $trener, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO tymy 
                    (nazev, zkratka, vlajka, skupina, trener, z, v, vp, pp, p, goly_vstrelene, goly_obdrzene, body) 
                    VALUES (?, ?, ?, ?, ?, 0, 0, 0, 0, 0, 0, 0, 0)");
                $stmt->execute([$nazev, $zkratka, $vlajka, $skupina, $trener]);
            }
            header("Location: tymy.php");
            exit;
        } catch (PDOException $e) {
            $error = "Chyba při ukládání: " . $e->getMessage();
        }
    } else {
        $error = "Všechna pole musí být vyplněna.";
    }
}

// 2. NAČTENÍ DAT
$team = ['nazev' => '', 'zkratka' => '', 'vlajka' => '', 'skupina' => 'A', 'trener' => ''];

if ($isEdit && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM tymy WHERE id = ?");
    $stmt->execute([$id]);
    $fetched = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($fetched) $team = $fetched;
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<style>
    .tymy-form-container { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    .page-title { font-size: 2.25rem; font-weight: 500; margin-bottom: 1.5rem; }
    .form-card { background: #fff; border: 1px solid #dee2e6; border-radius: 8px; padding: 2rem; }
    .form-label { font-size: 0.95rem; margin-bottom: 0.4rem; color: #495057; }
    .form-control, .form-select { border: 1px solid #ced4da; padding: 0.55rem 0.75rem; border-radius: 6px; }
</style>

<div class="container my-5 tymy-form-container">
    <a href="tymy.php" class="text-decoration-none text-primary mb-3 d-block">&larr; Zpět na seznam týmů</a>
    
    <h1 class="page-title"><?= $isEdit ? 'Upravit tým' : 'Nový tým' ?></h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form action="tymy-form.php<?= $isEdit ? '?id=' . $id : '' ?>" method="POST">
            <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Název týmu</label>
                    <input type="text" class="form-control" name="nazev" placeholder="např. Česko" value="<?= htmlspecialchars($team['nazev']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fáze / Skupina</label>
                    <select class="form-select" name="skupina">
                        <option value="A" <?= $team['skupina'] === 'A' ? 'selected' : '' ?>>Skupina A</option>
                        <option value="B" <?= $team['skupina'] === 'B' ? 'selected' : '' ?>>Skupina B</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Zkratka</label>
                    <input type="text" class="form-control" name="zkratka" maxlength="3" placeholder="např. CZE" value="<?= htmlspecialchars($team['zkratka']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kód vlajky</label>
                    <input type="text" class="form-control" name="vlajka" placeholder="např. cz" value="<?= htmlspecialchars($team['vlajka']) ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Hlavní trenér</label>
                    <input type="text" class="form-control" name="trener" placeholder="např. Radim Rulík" value="<?= htmlspecialchars($team['trener']) ?>">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Uložit</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>