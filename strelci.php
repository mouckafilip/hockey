<?php include __DIR__ . '/includes/header.php'; ?>

<style>
    /* Zelený pruh pouze pro první 3 místa */
    .pos-qualify {
        border-left: 5px solid #198754 !important;
    }
    .badge-score {
        font-weight: bold;
        padding: 5px 10px;
        background-color: #dc3545;
        color: white;
        border-radius: 4px;
    }
    .card {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(5px);
        border: none !important;
    }
</style>

<?php
$strelci = [
    ['hrac' => 'Roman Červenka', 'tym_vlajka' => '🇨🇿', 'tym_nazev' => 'Česko', 'pozice' => 'Útočník', 'klub' => 'Rapperswil-Jona', 'goly' => 2, 'trestne_minuty' => 0],
    ['hrac' => 'Lukáš Sedlák', 'tym_vlajka' => '🇨🇿', 'tym_nazev' => 'Česko', 'pozice' => 'Útočník', 'klub' => 'Pardubice', 'goly' => 1, 'trestne_minuty' => 15], 
    ['hrac' => 'Matěj Blümel', 'tym_vlajka' => '🇨🇿', 'tym_nazev' => 'Česko', 'pozice' => 'Útočník', 'klub' => 'Edmonton Oilers', 'goly' => 1, 'trestne_minuty' => 0],
    ['hrac' => 'Michael Špaček', 'tym_vlajka' => '🇨🇿', 'tym_nazev' => 'Česko', 'pozice' => 'Útočník', 'klub' => 'Frölunda', 'goly' => 1, 'trestne_minuty' => 28],
    ['hrac' => 'Ondřej Beránek', 'tym_vlajka' => '🇨🇿', 'tym_nazev' => 'Česko', 'pozice' => 'Útočník', 'klub' => 'Karlovy Vary', 'goly' => 1, 'trestne_minuty' => 35],
];
?>

<div class="container my-5">
    <h1>🎯 Tabulka střelců MS 2026</h1>
    
    <div class="card shadow-sm mt-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Pořadí</th>
                        <th>Hráč</th>
                        <th>Tým</th>
                        <th>Pozice</th>
                        <th>Klub</th>
                        <th>Góly</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($strelci as $i => $s): $num = $i + 1; ?>
                    <tr class="<?= ($num <= 3) ? 'pos-qualify' : '' ?>">
                        <td><?= $num ?>.</td>
                        <td><strong><?= htmlspecialchars($s['hrac']) ?></strong></td>
                        <td><?= $s['tym_vlajka'] ?> <?= htmlspecialchars($s['tym_nazev']) ?></td>
                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($s['pozice']) ?></span></td>
                        <td><?= htmlspecialchars($s['klub']) ?></td>
                        <td><span class="badge-score"><?= $s['goly'] ?></span></td>
                        <td><span class="badge bg-danger"><?= $s ['trestne_minuty'] ?> min</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted small">
            Tabulka zahrnuje pouze hráče, kteří vstřelili alespoň jeden gól zaznamenaný v databázi.
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>