<?php
session_start();
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/flash.php';

// ============================================
// KONTROLA 1: Přihlášení
// ============================================
if (!isset($_SESSION['user_id'])) {
    flash('Musíš se přihlásit, abys mohl přidávat/upravovat týmy', 'danger');
    header('Location: login.php');
    exit;
}

// ============================================
// KONTROLA 2: Admin oprávnění
// ============================================
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    flash('Nemáš oprávnění přidávat/upravovat týmy. Pouze administrátoři mohou spravovat týmy.', 'danger');
    header('Location: tymy.php');
    exit;
}

// ============================================
// Editace vs. nový tým
// ============================================
$team   = null;
$isEdit = false;
$errors = [];

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($id > 0) {
        $stmt = $conn->prepare("SELECT id, kod, nazev, skupina, trener, vlajka_emoji FROM tymy WHERE id = ?");
        $stmt->execute([$id]);
        $team = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$team) {
            flash('Tým nebyl nalezen', 'danger');
            header('Location: tymy.php');
            exit;
        }
        $isEdit = true;
    }
}

// Výchozí prázdné hodnoty pro nový tým
if (!$team) {
    $team = [
        'id'           => null,
        'kod'          => '',
        'nazev'        => '',
        'skupina'      => '',
        'trener'       => '',
        'vlajka_emoji' => '',
    ];
}

// ============================================
// Zpracování POST – přidání/editace týmu
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kod          = trim($_POST['kod'] ?? '');
    $nazev        = trim($_POST['nazev'] ?? '');
    $skupina      = trim($_POST['skupina'] ?? '');
    $trener       = trim($_POST['trener'] ?? '');
    $vlajka_emoji = trim($_POST['vlajka_emoji'] ?? '');

    // Validace
    if (empty($kod)) {
        $errors[] = 'Kód týmu je povinný';
    } elseif (strlen($kod) > 3) {
        $errors[] = 'Kód musí obsahovat max. 3 znaky';
    }

    if (empty($nazev)) {
        $errors[] = 'Název týmu je povinný';
    }

    if (empty($skupina)) {
        $errors[] = 'Skupina je povinná';
    } elseif (!in_array($skupina, ['A', 'B'], true)) {
        $errors[] = 'Neplatná skupina. Musí být A nebo B';
    }

    if (empty($vlajka_emoji)) {
        $errors[] = 'Emoji vlajka je povinná';
    }

    // Uložení do DB (pokud nejsou chyby)
    if (empty($errors)) {
        try {
            if ($isEdit) {
                $id = (int)$_GET['id'];

                $stmt = $conn->prepare("
                    UPDATE tymy
                    SET kod = ?, nazev = ?, skupina = ?, trener = ?, vlajka_emoji = ?
                    WHERE id = ?
                ");
                $stmt->execute([$kod, $nazev, $skupina, $trener, $vlajka_emoji, $id]);

                flash('Tým "' . htmlspecialchars($nazev) . '" byl úspěšně upraven', 'success');
            } else {
                $stmt = $conn->prepare("
                    INSERT INTO tymy (kod, nazev, skupina, trener, vlajka_emoji)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$kod, $nazev, $skupina, $trener, $vlajka_emoji]);

                flash('Tým "' . htmlspecialchars($nazev) . '" byl úspěšně přidán', 'success');
            }

            header('Location: tymy.php');
            exit;
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'UNIQUE') !== false) {
                $errors[] = 'Tým s kódem ' . htmlspecialchars($kod) . ' již existuje. Zvolte jiný kód.';
            } else {
                $errors[] = 'Chyba při ukládání. Zkus to znovu.';
            }
        }
    }

    // Při chybě zachovej zadaná data ve formuláři
    $team['kod']          = $kod;
    $team['nazev']        = $nazev;
    $team['skupina']      = $skupina;
    $team['trener']       = $trener;
    $team['vlajka_emoji'] = $vlajka_emoji;
}

// ============================================
// Dynamický titulek stránky
// ============================================
$page_title = $isEdit ? 'Upravit tým' : 'Přidat nový tým';

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h2 class="card-title mb-4"><?= htmlspecialchars($page_title) ?></h2>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Chyby:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Kód (3 znaky) <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="kod"
                                    maxlength="3"
                                    placeholder="CZE"
                                    value="<?= htmlspecialchars($team['kod'] ?? '') ?>"
                                    required>
                                <small class="form-text text-muted">např. CZE, USA, SVK</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Název <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="nazev"
                                    placeholder="Česko"
                                    value="<?= htmlspecialchars($team['nazev'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Skupina <span class="text-danger">*</span></label>
                                <select class="form-select" name="skupina" required>
                                    <option value="">— Vyberte skupinu —</option>
                                    <option value="A" <?= ($team['skupina'] ?? '') === 'A' ? 'selected' : '' ?>>Skupina A</option>
                                    <option value="B" <?= ($team['skupina'] ?? '') === 'B' ? 'selected' : '' ?>>Skupina B</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Emoji vlajka <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="vlajka_emoji"
                                    placeholder="🇨🇿"
                                    value="<?= htmlspecialchars($team['vlajka_emoji'] ?? '') ?>"
                                    maxlength="10"
                                    required>
                                <small class="form-text text-muted">např. 🇨🇿, 🇺🇸, 🇸🇰</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Trenér</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="trener"
                                    placeholder="Radim Rulík"
                                    value="<?= htmlspecialchars($team['trener'] ?? '') ?>">
                                <small class="form-text text-muted">volitelné pole</small>
                            </div>

                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <?= $isEdit ? 'Uložit změny' : 'Přidat tým' ?>
                            </button>
                            <a href="tymy.php" class="btn btn-secondary">Zrušit</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>