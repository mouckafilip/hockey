<?php
session_start();
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/flash.php';

// ============================================
// KONTROLA 1: Přihlášení
// ============================================
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_error'] = 'Musíš se přihlásit, abys mohl přidávat/upravovat zápasy';
    header('Location: login.php');
    exit;
}

// ============================================
// KONTROLA 2: Admin oprávnění
// ============================================
if ($_SESSION['admin'] !== true) {
    $_SESSION['flash_error'] = 'Nemáš oprávnění k editaci zápasů. Pouze administrátoři mohou editovat.';
    http_response_code(403);
    header('Location: zapasy.php');
    exit;
}

// ============================================
// Editace vs. nový zápas
// ============================================
$match    = null;
$match_id = null;
$isEdit   = false;
$errors   = [];

if (!empty($_GET['id'])) {
    $match_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM zapasy WHERE id = ?");
    $stmt->execute([$match_id]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$match) {
        $_SESSION['flash_error'] = 'Zápas nebyl nalezen';
        header('Location: zapasy.php');
        exit;
    }
    $isEdit = true;
}

// Výchozí prázdné hodnoty pro nový zápas
if (!$match) {
    $match = [
        'id'            => null,
        'datum'         => '',
        'domaci_id'     => '',
        'hoste_id'      => '',
        'skore_domaci'  => '',
        'skore_hoste'   => '',
        'prodlouzeni'   => 0,
        'faze'          => 'Skupina',
        'arena'         => '',
    ];
}

// ============================================
// Dynamické načtení týmů ze DB
// ============================================
$stmt = $conn->prepare("SELECT id, nazev, vlajka_emoji FROM tymy ORDER BY nazev");
$stmt->execute();
$tymy = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ============================================
// Zpracování POST – uložení zápasu
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datum       = trim($_POST['datum'] ?? '');
    $domaci_id   = (int)($_POST['domaci_id'] ?? 0);
    $hoste_id    = (int)($_POST['hoste_id'] ?? 0);
    $prodlouzeni = isset($_POST['prodlouzeni']) ? 1 : 0;
    $faze        = trim($_POST['faze'] ?? 'Skupina');
    $arena       = trim($_POST['arena'] ?? '');

    // Skóre – prázdné pole → NULL, jinak int
    $skore_domaci = (isset($_POST['skore_domaci']) && $_POST['skore_domaci'] !== '')
        ? (int)$_POST['skore_domaci']
        : null;
    $skore_hoste  = (isset($_POST['skore_hoste']) && $_POST['skore_hoste'] !== '')
        ? (int)$_POST['skore_hoste']
        : null;

    // Validace
    if (empty($datum)) {
        $errors[] = 'Datum a čas jsou povinné';
    }

    if (empty($domaci_id)) {
        $errors[] = 'Vyber domácí tým';
    }

    if (empty($hoste_id)) {
        $errors[] = 'Vyber hostující tým';
    }

    if ($domaci_id && $hoste_id && $domaci_id === $hoste_id) {
        $errors[] = 'Domácí a hostující tým nemohou být stejné';
    }

    if (empty($arena)) {
        $errors[] = 'Aréna je povinná';
    }

    // Uložení do DB (pokud nejsou chyby)
    if (empty($errors)) {
        try {
            if ($isEdit) {
                $stmt = $conn->prepare("
                    UPDATE zapasy
                    SET datum       = ?,
                        domaci_id   = ?,
                        hoste_id    = ?,
                        skore_domaci = ?,
                        skore_hoste  = ?,
                        prodlouzeni = ?,
                        faze        = ?,
                        arena       = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $datum, $domaci_id, $hoste_id,
                    $skore_domaci, $skore_hoste,
                    $prodlouzeni, $faze, $arena,
                    $match_id,
                ]);
                $_SESSION['flash_success'] = 'Zápas byl úspěšně aktualizován';
            } else {
                $stmt = $conn->prepare("
                    INSERT INTO zapasy
                        (datum, domaci_id, hoste_id, skore_domaci, skore_hoste, prodlouzeni, faze, arena)
                    VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $datum, $domaci_id, $hoste_id,
                    $skore_domaci, $skore_hoste,
                    $prodlouzeni, $faze, $arena,
                ]);
                $_SESSION['flash_success'] = 'Zápas byl úspěšně přidán';
            }

            header('Location: zapasy.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Chyba při ukládání zápasu. Zkus to znovu.';
        }
    }

    // Při chybě zachovej zadaná data ve formuláři
    $match['datum']       = $datum;
    $match['domaci_id']   = $domaci_id;
    $match['hoste_id']    = $hoste_id;
    $match['skore_domaci'] = $skore_domaci;
    $match['skore_hoste']  = $skore_hoste;
    $match['prodlouzeni'] = $prodlouzeni;
    $match['faze']        = $faze;
    $match['arena']       = $arena;
}

// ============================================
// Dynamický titulek stránky
// ============================================
$page_title = $isEdit ? 'Upravit zápas' : 'Přidat zápas';

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

                        <div class="mb-3">
                            <label for="datum" class="form-label">
                                Datum a čas <span class="text-danger">*</span>
                            </label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="datum"
                                name="datum"
                                value="<?= htmlspecialchars($match['datum'] ? substr($match['datum'], 0, 16) : '') ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="domaci_id" class="form-label">
                                Domácí tým <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="domaci_id" name="domaci_id" required>
                                <option value="">— Vyber domácí tým —</option>
                                <?php foreach ($tymy as $tym): ?>
                                    <option
                                        value="<?= (int)$tym['id'] ?>"
                                        <?= ((int)$match['domaci_id'] === (int)$tym['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tym['vlajka_emoji'] . ' ' . $tym['nazev']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="hoste_id" class="form-label">
                                Hostující tým <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="hoste_id" name="hoste_id" required>
                                <option value="">— Vyber hostující tým —</option>
                                <?php foreach ($tymy as $tym): ?>
                                    <option
                                        value="<?= (int)$tym['id'] ?>"
                                        <?= ((int)$match['hoste_id'] === (int)$tym['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tym['vlajka_emoji'] . ' ' . $tym['nazev']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="skore_domaci" class="form-label">
                                    Skóre domácích
                                    <small class="text-muted">(volitelné)</small>
                                </label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="skore_domaci"
                                    name="skore_domaci"
                                    value="<?= ($match['skore_domaci'] !== null && $match['skore_domaci'] !== '') ? (int)$match['skore_domaci'] : '' ?>"
                                    min="0"
                                    placeholder="—">
                            </div>
                            <div class="col-md-6">
                                <label for="skore_hoste" class="form-label">
                                    Skóre hostujících
                                    <small class="text-muted">(volitelné)</small>
                                </label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="skore_hoste"
                                    name="skore_hoste"
                                    value="<?= ($match['skore_hoste'] !== null && $match['skore_hoste'] !== '') ? (int)$match['skore_hoste'] : '' ?>"
                                    min="0"
                                    placeholder="—">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="prodlouzeni"
                                    name="prodlouzeni"
                                    <?= $match['prodlouzeni'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="prodlouzeni">
                                    Rozhodlo se v prodloužení (P)
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="faze" class="form-label">Fáze</label>
                            <select class="form-select" id="faze" name="faze" required>
                                <option value="Skupina" <?= ($match['faze'] ?? '') === 'Skupina' ? 'selected' : '' ?>>Skupina</option>
                                <option value="Čtvrtfinále" <?= ($match['faze'] ?? '') === 'Čtvrtfinále' ? 'selected' : '' ?>>Čtvrtfinále</option>
                                <option value="Semifinále" <?= ($match['faze'] ?? '') === 'Semifinále' ? 'selected' : '' ?>>Semifinále</option>
                                <option value="Finále" <?= ($match['faze'] ?? '') === 'Finále' ? 'selected' : '' ?>>Finále</option>
                                <option value="O bronz" <?= ($match['faze'] ?? '') === 'O bronz' ? 'selected' : '' ?>>O bronz</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="arena" class="form-label">
                                Aréna <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="arena"
                                name="arena"
                                value="<?= htmlspecialchars($match['arena'] ?? '') ?>"
                                placeholder="BCF Arena, Fribourg"
                                required>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg"></i>
                                <?= $isEdit ? 'Uložit změny' : 'Přidat zápas' ?>
                            </button>
                            <a href="zapasy.php" class="btn btn-secondary">Zrušit</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Ochrana proti výběru stejného týmu na obou stranách
(function () {
    const domaci = document.getElementById('domaci_id');
    const hoste  = document.getElementById('hoste_id');

    function checkSameTeam(changed, other) {
        if (changed.value && changed.value === other.value) {
            changed.setCustomValidity('Domácí a hostující tým nesmí být stejný');
        } else {
            changed.setCustomValidity('');
            other.setCustomValidity('');
        }
    }

    domaci.addEventListener('change', () => checkSameTeam(domaci, hoste));
    hoste.addEventListener('change',  () => checkSameTeam(hoste, domaci));
})();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>