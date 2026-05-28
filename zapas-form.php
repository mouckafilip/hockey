<?php
session_start();
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/flash.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_error'] = 'Musíš se přihlásit, abys mohl editovat zápasy';
    header('Location: login.php');
    exit;
}

if ($_SESSION['admin'] !== true) {
    $_SESSION['flash_error'] = 'Nemáš oprávnění k editaci zápasů. Pouze administrátoři mohou editovat.';
    http_response_code(403);
    header('Location: zapasy.php');
    exit;
}

$match = null;
$match_id = null;

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
}

$stmt = $conn->prepare("SELECT id, nazev, vlajka_emoji FROM tymy ORDER BY nazev");
$stmt->execute();
$tymy = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datum = trim($_POST['datum'] ?? '');
    $domaci_id = (int)($_POST['domaci_id'] ?? 0);
    $hoste_id = (int)($_POST['hoste_id'] ?? 0);
    $skore_domaci = isset($_POST['skore_domaci']) && $_POST['skore_domaci'] !== '' ? (int)$_POST['skore_domaci'] : null;
    $skore_hoste = isset($_POST['skore_hoste']) && $_POST['skore_hoste'] !== '' ? (int)$_POST['skore_hoste'] : null;
    $prodlouzeni = isset($_POST['prodlouzeni']) ? 1 : 0;
    $faze = trim($_POST['faze'] ?? 'skupina');
    $arena = trim($_POST['arena'] ?? '');
    
    if (empty($datum) || empty($domaci_id) || empty($hoste_id) || empty($arena)) {
        $_SESSION['flash_error'] = 'Vyplň všechna povinná pole';
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    
    if ($domaci_id === $hoste_id) {
        $_SESSION['flash_error'] = 'Týmy nemohou být stejné';
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
    
    try {
        if ($match_id) {
            $stmt = $conn->prepare("
                UPDATE zapasy 
                SET datum = ?, domaci_id = ?, hoste_id = ?, skore_domaci = ?, skore_hoste = ?, prodlouzeni = ?, faze = ?, arena = ?
                WHERE id = ?
            ");
            $stmt->execute([$datum, $domaci_id, $hoste_id, $skore_domaci, $skore_hoste, $prodlouzeni, $faze, $arena, $match_id]);
            $_SESSION['flash_success'] = 'Zápas byl úspěšně aktualizován';
        } else {
            $stmt = $conn->prepare("
                INSERT INTO zapasy (datum, domaci_id, hoste_id, skore_domaci, skore_hoste, prodlouzeni, faze, arena)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$datum, $domaci_id, $hoste_id, $skore_domaci, $skore_hoste, $prodlouzeni, $faze, $arena]);
            $_SESSION['flash_success'] = 'Zápas byl úspěšně přidán';
        }
        
        header('Location: zapasy.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['flash_error'] = 'Chyba při ukládání zápasu. Zkus to později.';
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1><?= $match ? 'Upravit zápas' : 'Přidat nový zápas' ?></h1>
            
            <form method="POST" class="mt-4 needs-validation" novalidate>
                <div class="mb-3">
                    <label for="datum" class="form-label">
                        <span class="text-danger">*</span> Datum a čas
                    </label>
                    <input 
                        type="datetime-local" 
                        class="form-control" 
                        id="datum" 
                        name="datum" 
                        value="<?= $match ? substr($match['datum'], 0, 16) : '' ?>"
                        required>
                    <div class="invalid-feedback">
                        Zadej datum a čas
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="domaci_id" class="form-label">
                        <span class="text-danger">*</span> Domácí tým
                    </label>
                    <select class="form-select" id="domaci_id" name="domaci_id" required>
                        <option value="">-- Vyber domácí tým --</option>
                        <?php foreach ($tymy as $tym): ?>
                            <option value="<?= $tym['id'] ?>" <?= ($match && $match['domaci_id'] == $tym['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tym['vlajka_emoji'] . ' ' . $tym['nazev']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Vyber domácí tým
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="hoste_id" class="form-label">
                        <span class="text-danger">*</span> Hostující tým
                    </label>
                    <select class="form-select" id="hoste_id" name="hoste_id" required>
                        <option value="">-- Vyber hostující tým --</option>
                        <?php foreach ($tymy as $tym): ?>
                            <option value="<?= $tym['id'] ?>" <?= ($match && $match['hoste_id'] == $tym['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tym['vlajka_emoji'] . ' ' . $tym['nazev']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Vyber hostující tým
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="skore_domaci" class="form-label">
                            Skóre domácího
                            <small class="text-muted">(volitelné)</small>
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="skore_domaci" 
                            name="skore_domaci" 
                            value="<?= ($match && $match['skore_domaci'] !== null) ? $match['skore_domaci'] : '' ?>"
                            min="0">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="skore_hoste" class="form-label">
                            Skóre hostujícího
                            <small class="text-muted">(volitelné)</small>
                        </label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="skore_hoste" 
                            name="skore_hoste" 
                            value="<?= ($match && $match['skore_hoste'] !== null) ? $match['skore_hoste'] : '' ?>"
                            min="0">
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="prodlouzeni" 
                            name="prodlouzeni"
                            <?= ($match && $match['prodlouzeni']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="prodlouzeni">
                            Rozhodl se prodloužením (P)
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="faze" class="form-label">
                        Fáze
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="faze" 
                        name="faze" 
                        value="<?= htmlspecialchars($match['faze'] ?? 'skupina') ?>"
                        placeholder="skupina">
                </div>
                
                <div class="mb-3">
                    <label for="arena" class="form-label">
                        <span class="text-danger">*</span> Aréna
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="arena" 
                        name="arena" 
                        value="<?= htmlspecialchars($match['arena'] ?? '') ?>"
                        placeholder="BCF Arena, Fribourg"
                        required>
                    <div class="invalid-feedback">
                        Zadej arénu
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i>
                        Uložit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
  'use strict'
  window.addEventListener('load', function () {
    let forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
  }, false)
})()
</script>

<?php
include __DIR__ . '/includes/footer.php';
?>