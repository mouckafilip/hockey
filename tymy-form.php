<?php
session_start();
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/flash.php';

// DEBUG - Smaž to potom!
echo '<div style="background: #f8d7da; padding: 20px; margin: 20px; border: 2px solid red; font-family: monospace;">';
echo '<h3>🔍 DEBUG INFO</h3>';
echo '<strong>Session status:</strong> ' . session_status() . '<br>';
echo '<strong>Session data:</strong><br>';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
echo '<strong>Admin check:</strong> ';
var_dump(isset($_SESSION['admin']), $_SESSION['admin'] ?? 'NENÍ NASTAVENO');
echo '</div>';

// ============================================
// ⚠️ KONTROLA 1: Musíš být přihlášen
// ============================================
if (!isset($_SESSION['user_id'])) {
    echo '<div style="background: #f8d7da; padding: 20px; color: red;"><strong>❌ FAIL KONTROLA 1:</strong> Nejsi přihlášen (user_id chybí)</div>';
    flash('Musíš se přihlásit, abys mohl přidávat/upravovat týmy', 'danger');
    header('Location: login.php');
    exit;
}

echo '<div style="background: #d4edda; padding: 10px; margin: 10px; color: green;"><strong>✅ PASS KONTROLA 1:</strong> Jsi přihlášen</div>';

// ============================================
// ⚠️ KONTROLA 2: Musíš být admin
// ============================================
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== 1) {
    echo '<div style="background: #f8d7da; padding: 20px; color: red;">';
    echo '<strong>❌ FAIL KONTROLA 2:</strong> Nejsi admin!<br>';
    echo 'isset($_SESSION[\'admin\']) = ' . (isset($_SESSION['admin']) ? 'TRUE' : 'FALSE') . '<br>';
    echo '$_SESSION[\'admin\'] = ' . var_export($_SESSION['admin'] ?? null, true) . '<br>';
    echo '$_SESSION[\'admin\'] !== 1 = ' . (($_SESSION['admin'] ?? null) !== 1 ? 'TRUE' : 'FALSE') . '<br>';
    echo '</div>';
    flash('Nemáš oprávnění přidávat/upravovat týmy. Pouze administrátoři mohou spravovat týmy.', 'danger');
    header('Location: tymy.php');
    exit;
}

echo '<div style="background: #d4edda; padding: 10px; margin: 10px; color: green;"><strong>✅ PASS KONTROLA 2:</strong> Jsi admin!</div>';

// Include header AŽ PO kontrolách (aby se session už inicializoval)
include __DIR__ . '/includes/header.php';

// ============================================
// Kontrola, zda je editace nebo nové vytvoření
// ============================================
$team = null;
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

// Pokud tým není načten (nový tým), inicializuj prázdné hodnoty
if (!$team) {
    $team = [
        'id' => null,
        'kod' => '',
        'nazev' => '',
        'skupina' => '',
        'trener' => '',
        'vlajka_emoji' => ''
    ];
}

// ============================================
// Zpracování POST - přidání/editace týmu
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kod = trim($_POST['kod'] ?? '');
    $nazev = trim($_POST['nazev'] ?? '');
    $skupina = trim($_POST['skupina'] ?? '');
    $trener = trim($_POST['trener'] ?? '');
    $vlajka_emoji = trim($_POST['vlajka_emoji'] ?? '');
    
    // Validace
    if (empty($kod)) {
        $errors[] = "Kód týmu je povinný";
    } elseif (strlen($kod) > 3) {
        $errors[] = "Kód musí obsahovat max. 3 znaky";
    }
    
    if (empty($nazev)) {
        $errors[] = "Název týmu je povinný";
    }
    
    if (empty($skupina)) {
        $errors[] = "Skupina je povinná";
    } elseif (!in_array($skupina, ['A', 'B'], true)) {
        $errors[] = "Neplatná skupina. Musí být A nebo B";
    }
    
    if (empty($vlajka_emoji)) {
        $errors[] = "Emoji vlajka je povinná";
    }
    
    // Pokud nejsou chyby, ulož do DB
    if (empty($errors)) {
        try {
            if ($isEdit) {
                // ============================================
                // EDITACE existujícího týmu
                // ============================================
                $id = (int)$_GET['id'];
                
                $stmt = $conn->prepare("
                    UPDATE tymy 
                    SET kod = ?, nazev = ?, skupina = ?, trener = ?, vlajka_emoji = ?
                    WHERE id = ?
                ");
                $stmt->execute([$kod, $nazev, $skupina, $trener, $vlajka_emoji, $id]);
                
                flash('Tým "' . htmlspecialchars($nazev) . '" byl úspěšně upraven', 'success');
            } else {
                // ============================================
                // NOVÝ tým
                // ============================================
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
            // Detekce duplikátu
            if (strpos($e->getMessage(), 'Duplicate entry') !== false || strpos($e->getMessage(), 'UNIQUE') !== false) {
                $errors[] = "Tým s kódem " . htmlspecialchars($kod) . " již existuje. Zvolte jiný kód.";
            } else {
                $errors[] = "Chyba při ukládání: " . $e->getMessage();
            }
        }
    }
}

$pageTitle = $isEdit ? 'Upravit tým' : 'Přidat nový tým';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4"><?= $pageTitle ?></h2>
                    
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
                    
                    <form method="post" action="">
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

<?php
include __DIR__ . '/includes/footer.php';
?>