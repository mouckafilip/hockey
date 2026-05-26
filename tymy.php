<?php

include __DIR__ . '/includes/header.php';

$tymyB = [
    ['id' => 1, 'vlajka' => '🇨🇿', 'nazev' => 'Česko', 'z' => 2, 'v' => 1, 'vp' => 0, 'pp' => 1, 'p' => 0, 'skore' => '6:4', 'body' => 4],
    ['id' => 2, 'vlajka' => '🇸🇮', 'nazev' => 'Slovinsko', 'z' => 1, 'v' => 0, 'vp' => 1, 'pp' => 0, 'p' => 0, 'skore' => '3:2', 'body' => 2],
    ['id' => 3, 'vlajka' => '🇮🇹', 'nazev' => 'Itálie', 'z' => 0, 'v' => 0, 'vp' => 0, 'pp' => 0, 'p' => 0, 'skore' => '0:0', 'body' => 0],
    ['id' => 4, 'vlajka' => '🇨🇦', 'nazev' => 'Kanada', 'z' => 0, 'v' => 0, 'vp' => 0, 'pp' => 0, 'p' => 0, 'skore' => '0:0', 'body' => 0],
    ['id' => 5, 'vlajka' => '🇸🇰', 'nazev' => 'Slovensko', 'z' => 0, 'v' => 0, 'vp' => 0, 'pp' => 0, 'p' => 0, 'skore' => '0:0', 'body' => 0],
];

$tymyA = [
    ['id' => 6, 'vlajka' => '🇫🇮', 'nazev' => 'Finsko', 'z' => 0, 'v' => 0, 'vp' => 0, 'pp' => 0, 'p' => 0, 'skore' => '0:0', 'body' => 0],
    ['id' => 7, 'vlajka' => '🇱🇻', 'nazev' => 'Lotyšsko', 'z' => 0, 'v' => 0, 'vp' => 0, 'pp' => 0, 'p' => 0, 'skore' => '0:0', 'body' => 0],
    ['id' => 8, 'vlajka' => '🇭🇺', 'nazev' => 'Maďarsko', 'z' => 0, 'v' => 0, 'vp' => 0, 'pp' => 0, 'p' => 0, 'skore' => '0:0', 'body' => 0],
    ['id' => 9, 'vlajka' => '🇩🇪', 'nazev' => 'Německo', 'z' => 0, 'v' => 0, 'vp' => 0, 'pp' => 0, 'p' => 0, 'skore' => '0:0', 'body' => 0],
    ['id' => 10, 'vlajka' => '🇳🇴', 'nazev' => 'Norsko', 'z' => 0, 'v' => 0, 'vp' => 0, 'pp' => 0, 'p' => 0, 'skore' => '0:0', 'body' => 0],
];

?>

<style>
    /* CSS zůstává beze změny */
    body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; background-image: url('assets/images/image2.jpg') !important; background-size: cover !important; background-position: center !important; background-attachment: fixed !important; background-repeat: no-repeat !important; }
    .container { flex: 1; }
    .card, .tab-content, .nav-tabs .nav-link { background-color: rgba(255, 255, 255, 0.95) !important; backdrop-filter: blur(5px); border: none !important; }
    .nav-tabs .nav-link { color: #333 !important; border-radius: 8px 8px 0 0 !important; transition: 0.3s; border: none !important; }
    .nav-tabs .nav-link.active { background-color: #0d6efd !important; color: #ffffff !important; font-weight: bold; }
    .pos-qualify { border-left: 5px solid #198754 !important; }
    .badge { background-color: #0d6efd !important; color: #ffffff !important; font-weight: bold; padding: 5px 10px; border-radius: 4px; }
    h1 { font-size: 1.8rem !important; color: #ffffff !important; text-shadow: 0 0 10px #0d6efd, 0 0 20px #0d6efd; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
</style>

<div class="container my-5 tymy-page">
    <div class="row mb-4">
        <div class="col">
            <h1>Týmy a tabulky skupin</h1>
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
                            <tr><th>#</th><th>Tým</th><th>Z</th><th>V</th><th>VP</th><th>PP</th><th>P</th><th>Skóre</th><th>Body</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tymyB as $i => $t): $num = $i + 1;
                                $rowClass = ($num <= 3) ? 'pos-qualify' : '';
                                if ($t['nazev'] === 'Česko') $rowClass .= ' table-primary';
                            ?>
                            <tr class="<?= htmlspecialchars($rowClass) ?>">
                                <th scope="row"><?= htmlspecialchars($num) ?>.</th>
                                <td><?= htmlspecialchars($t['vlajka']) ?> <?= htmlspecialchars($t['nazev']) ?></td>
                                <td><?= htmlspecialchars($t['z']) ?></td>
                                <td><?= htmlspecialchars($t['v']) ?></td>
                                <td><?= htmlspecialchars($t['vp']) ?></td>
                                <td><?= htmlspecialchars($t['pp']) ?></td>
                                <td><?= htmlspecialchars($t['p']) ?></td>
                                <td><?= htmlspecialchars($t['skore']) ?></td>
                                <td><span class="badge bg-success"><?= htmlspecialchars($t['body']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
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
                            <tr><th>#</th><th>Tým</th><th>Z</th><th>V</th><th>VP</th><th>PP</th><th>P</th><th>Skóre</th><th>Body</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tymyA as $i => $t): $num = $i + 1;
                                $rowClass = ($num <= 3) ? 'pos-qualify' : '';
                            ?>
                            <tr class="<?= htmlspecialchars($rowClass) ?>">
                                <th scope="row"><?= htmlspecialchars($num) ?>.</th>
                                <td><?= htmlspecialchars($t['vlajka']) ?> <?= htmlspecialchars($t['nazev']) ?></td>
                                <td><?= htmlspecialchars($t['z']) ?></td>
                                <td><?= htmlspecialchars($t['v']) ?></td>
                                <td><?= htmlspecialchars($t['vp']) ?></td>
                                <td><?= htmlspecialchars($t['pp']) ?></td>
                                <td><?= htmlspecialchars($t['p']) ?></td>
                                <td><?= htmlspecialchars($t['skore']) ?></td>
                                <td><span class="badge"><?= htmlspecialchars($t['body']) ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/includes/footer.php';
?>