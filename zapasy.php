<?php
session_start();
require __DIR__ . '/config/flash.php';
require __DIR__ . '/config/db.php';

$stmt = $conn->prepare("
    SELECT 
        z.id,
        z.datum,
        z.domaci_id,
        z.hoste_id,
        z.skore_domaci,
        z.skore_hoste,
        z.prodlouzeni,
        z.faze,
        z.arena,
        td.nazev as domaci_nazev,
        td.vlajka_emoji as domaci_emoji,
        th.nazev as hoste_nazev,
        th.vlajka_emoji as hoste_emoji
    FROM zapasy z
    LEFT JOIN tymy td ON z.domaci_id = td.id
    LEFT JOIN tymy th ON z.hoste_id = th.id
    ORDER BY z.datum ASC
");
$stmt->execute();
$zapasy = $stmt->fetchAll(PDO::FETCH_ASSOC);

$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
$isLoggedIn = isset($_SESSION['user_id']);
?>

<?php
include __DIR__ . '/includes/header.php';
?>

<style>
    body {
        background-image: url('assets/images/image2.jpg') !important;
        background-attachment: fixed !important;
        background-size: cover !important;
    }
    .card {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(5px);
        border: none !important;
    }
    .badge-p {
        background-color: #ffc107;
        color: #000;
        font-size: 0.8em;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: bold;
    }
</style>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col">
            <h1>Zápasy</h1>
        </div>
        <div class="col-auto d-flex align-items-center">
            <?php if ($isAdmin): ?>
                <a href="zapas-form.php" class="btn btn-success">
                    <i class="bi bi-plus-lg"></i> Přidat zápas
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-4">
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-primary">Všechny</button>
            <button class="btn btn-outline-primary">🇨🇿 Česko</button>
            <button class="btn btn-outline-primary">Odehrané</button>
            <button class="btn btn-outline-primary">Nadcházející</button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Datum</th>
                        <th>Zápas</th>
                        <th>Výsledek</th>
                        <th>Aréna</th>
                        <th class="text-center" style="width: 1%; white-space: nowrap;">Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($zapasy)): ?>
                        <?php foreach ($zapasy as $z): 
                            $domaci = ($z['domaci_emoji'] ?? '') . ' ' . htmlspecialchars($z['domaci_nazev'] ?? 'N/A');
                            $hoste = ($z['hoste_emoji'] ?? '') . ' ' . htmlspecialchars($z['hoste_nazev'] ?? 'N/A');
                            
                            if ($z['skore_domaci'] !== null && $z['skore_hoste'] !== null) {
                                $vysledek = $z['skore_domaci'] . ':' . $z['skore_hoste'];
                                if ($z['prodlouzeni']) {
                                    $vysledek .= ' P';
                                }
                            } else {
                                $vysledek = '—';
                            }
                            
                            $casText = $z['datum'] ? date('H:i', strtotime($z['datum'])) : '—';
                            $datumText = $z['datum'] ? date('d. m.', strtotime($z['datum'])) : '—';
                        ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($datumText) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($casText) ?></small>
                            </td>
                            <td>
                                <?= $domaci ?> vs <?= $hoste ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($vysledek) ?></strong>
                                <?php if ($z['prodlouzeni']): ?>
                                    <span class="badge-p">P</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($z['arena'] ?? '—') ?></td>
                            <td class="text-center align-middle p-2">
                                <?php if ($isAdmin): ?>
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="zapas-form.php?id=<?= $z['id'] ?>"
                                            class="btn btn-primary btn-sm"
                                            title="Upravit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="zapas-delete.php?id=<?= $z['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            title="Smazat"
                                            onclick="return confirm('Opravdu smazat zápas <?= htmlspecialchars(addslashes($domaci . ' vs ' . $hoste)) ?>?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Zatím žádné zápasy
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>