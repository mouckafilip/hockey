<?php
session_start();
require __DIR__ . '/config/flash.php';
include __DIR__ . '/includes/header.php';

// Detekce editace vs nový záznam
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;

// ZPRACOVÁNÍ FORMULÁŘE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $isEditPost = $id > 0;

    $kod = strtoupper(trim($_POST['kod'] ?? ''));
    $nazev = trim($_POST['nazev'] ?? '');
    $skupina = trim($_POST['skupina'] ?? '');
    $trener = trim($_POST['trener'] ?? '');
    $vlajka_emoji = trim($_POST['vlajka_emoji'] ?? '');

    // Validace
    $errors = [];
    if (empty($kod) || strlen($kod) !== 3) {
        $errors[] = "Kód musí mít přesně 3 znaky.";
    }
    if (empty($nazev)) {
        $errors[] = "Název týmu je povinný.";
    }
    if (empty($skupina) || !in_array($skupina, ['A', 'B'])) {
        $errors[] = "Vyberte skupinu A nebo B.";
    }
    if (empty($vlajka_emoji)) {
        $errors[] = "Emoji vlajka je povinná.";
    }

    if (empty($errors)) {
        try {
            if ($isEditPost) {
                $stmt = $conn->prepare("UPDATE tymy SET kod = ?, nazev = ?, skupina = ?, trener = ?, vlajka_emoji = ? WHERE id = ?");
                $stmt->execute([$kod, $nazev, $skupina, $trener, $vlajka_emoji, $id]);
            } else {
                $stmt = $conn->prepare("INSERT INTO tymy (kod, nazev, skupina, trener, vlajka_emoji) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$kod, $nazev, $skupina, $trener, $vlajka_emoji]);
            }
            header("Location: tymy.php");
            exit;
        } catch (PDOException $e) {
            // Detekce duplikátu
            if (strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'UNIQUE') !== false) {
                $errors[] = "Tým s kódem " . htmlspecialchars($kod) . " již existuje. Zvolte jiný kód.";
            } else {
                $errors[] = "Chyba při ukládání: " . $e->getMessage();
            }
        }
    }
}

// NAČTENÍ DAT PRO EDITACI
$team = ['kod' => '', 'nazev' => '', 'skupina' => '', 'trener' => '', 'vlajka_emoji' => ''];

if ($isEdit && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $conn->prepare("SELECT * FROM tymy WHERE id = ?");
    $stmt->execute([$id]);
    $fetched = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($fetched) {
        $team = $fetched;
    }
}
?>

<div class="container my-5">
    <a href="tymy.php" class="text-decoration-none text-primary mb-3 d-block">&larr; Zpět na seznam</a>
    
    <h1 class="mb-4"><?= $isEdit ? 'Upravit tým' : 'Nový tým' ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="tymy-form.php<?= $isEdit ? '?id=' . $id : '' ?>" method="POST">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kód (3 znaky) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="kod" maxlength="3" placeholder="CZE" value="<?= htmlspecialchars($team['kod']) ?>" required>
                        <small class="form-text text-muted">např. CZE, USA, SVK</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Název <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nazev" placeholder="Česko" value="<?= htmlspecialchars($team['nazev']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Skupina <span class="text-danger">*</span></label>
                        <select class="form-select" name="skupina" required>
                            <option value="">— Vyberte skupinu —</option>
                            <option value="A" <?= $team['skupina'] === 'A' ? 'selected' : '' ?>>Skupina A</option>
                            <option value="B" <?= $team['skupina'] === 'B' ? 'selected' : '' ?>>Skupina B</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Emoji vlajka <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="vlajka_emoji" placeholder="🇨🇿" value="<?= htmlspecialchars($team['vlajka_emoji']) ?>" required>
                        <small class="form-text text-muted">např. 🇨🇿, 🇺🇸, 🇸🇰</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Trenér</label>
                        <input type="text" class="form-control" name="trener" placeholder="Radim Rulík" value="<?= htmlspecialchars($team['trener']) ?>">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Uložit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>