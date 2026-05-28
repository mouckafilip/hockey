<?php
session_start();
require __DIR__ . '/config/flash.php';
include __DIR__ . '/includes/header.php';

// Načtení týmů z databáze podle skupin
$stmt = $conn->prepare("SELECT id, kod, nazev, skupina, trener, vlajka_emoji FROM tymy WHERE skupina = 'B' ORDER BY nazev");
$stmt->execute();
$tymyB = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT id, kod, nazev, skupina, trener, vlajka_emoji FROM tymy WHERE skupina = 'A' ORDER BY nazev");
$stmt->execute();
$tymyA = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    th.rank-cell {
        border: 4px solid #28a745 !important;
        text-align: center;
        vertical-align: middle !important;
        width: 54px;
        min-width: 54px;
        font-weight: bold;
        font-size: 1rem;
        padding: 8px !important;
    }
</style>

<div class="tymy-page">
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col">
                <h1>Týmy a tabulky skupin</h1>
            </div>
            <div class="col-auto d-flex align-items-center">
                <a href="tymy-form.php" class="btn btn-success">
                    <i class="bi bi-plus-lg"></i> Přidat tým
                </a>
            </div>
        </div>

        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="group-b-tab" data-bs-toggle="tab" data-bs-target="#group-b" type="button" role="tab" aria-controls="group-b" aria-selected="true">
                    🏒 Skupina B — Fribourg (s Českem)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="group-a-tab" data-bs-toggle="tab" data-bs-target="#group-a" type="button" role="tab" aria-controls="group-a" aria-selected="false">
                    Skupina A — Curych
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="group-b" role="tabpanel" aria-labelledby="group-b-tab">
                <div class="card shadow-sm border-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tým</th>
                                    <th>Z</th>
                                    <th>V</th>
                                    <th>VP</th>
                                    <th>PP</th>
                                    <th>P</th>
                                    <th>Skóre</th>
                                    <th>Body</th>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">Akce</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tymyB)): ?>
                                    <?php foreach ($tymyB as $i => $t): 
                                        $num = $i + 1;
                                        $rowClass = ($num === 1) ? 'table-info' : '';
                                        $showBox = ($num <= 3) ? true : false;
                                    ?>
                                    <tr class="<?= htmlspecialchars($rowClass) ?>">
                                        <th scope="row" <?= $showBox ? 'class="rank-cell"' : '' ?>>
                                            <?= htmlspecialchars($num) ?>.
                                        </th>
                                        <td><?= $t['vlajka_emoji'] ?> <strong><?= htmlspecialchars($t['nazev']) ?></strong> (<?= htmlspecialchars($t['kod']) ?>)</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td><span class="badge bg-primary">0</span></td>
                                        <td class="text-center align-middle p-2">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <a href="tymy-form.php?id=<?= $t['id'] ?>"
                                                    class="btn btn-primary btn-sm" 
                                                    title="Upravit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="tymy-delete.php?id=<?= $t['id'] ?>" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Smazat"
                                                    onclick="return confirm('Opravdu smazat?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">Zatím žádné týmy v této skupině</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="group-a" role="tabpanel" aria-labelledby="group-a-tab">
                <div class="card shadow-sm border-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tým</th>
                                    <th>Z</th>
                                    <th>V</th>
                                    <th>VP</th>
                                    <th>PP</th>
                                    <th>P</th>
                                    <th>Skóre</th>
                                    <th>Body</th>
                                    <th class="text-center" style="width: 1%; white-space: nowrap;">Akce</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tymyA)): ?>
                                    <?php foreach ($tymyA as $i => $t): 
                                        $num = $i + 1;
                                        $rowClass = ($num === 1) ? 'table-info' : '';
                                        $showBox = ($num <= 3) ? true : false;
                                    ?>
                                    <tr class="<?= htmlspecialchars($rowClass) ?>">
                                        <th scope="row" <?= $showBox ? 'class="rank-cell"' : '' ?>>
                                            <?= htmlspecialchars($num) ?>.
                                        </th>
                                        <td><?= $t['vlajka_emoji'] ?> <strong><?= htmlspecialchars($t['nazev']) ?></strong> (<?= htmlspecialchars($t['kod']) ?>)</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td><span class="badge bg-primary">0</span></td>
                                        <td class="text-center align-middle p-2">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <a href="tymy-form.php?id=<?= $t['id'] ?>"
                                                    class="btn btn-primary btn-sm" 
                                                    title="Upravit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="tymy-delete.php?id=<?= $t['id'] ?>" 
                                                    class="btn btn-danger btn-sm" 
                                                    title="Smazat"
                                                    onclick="return confirm('Opravdu smazat?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">Zatím žádné týmy v této skupině</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/includes/footer.php';
?>