<?php
include __DIR__ . '/includes/header.php';

// Pole zápasů
$zapasy = [
    ['datum' => '15. 5.', 'cas' => '20:15', 'tym1' => '🇨🇿 Česko', 'tym2' => '🇩🇰 Dánsko', 'vysledek' => '4:1', 'arena' => 'BCF Arena, Fribourg'],
    ['datum' => '16. 5.', 'cas' => '16:20', 'tym1' => '🇨🇿 Česko', 'tym2' => '🇸🇮 Slovinsko', 'vysledek' => '2:3 P', 'arena' => 'BCF Arena, Fribourg'],
    ['datum' => '18. 5.', 'cas' => '16:20', 'tym1' => '🇩🇪 Německo', 'tym2' => '🇨🇭 Švýcarsko', 'vysledek' => '—', 'arena' => 'Swiss Life Arena, Curych'],
    ['datum' => '18. 5.', 'cas' => '20:20', 'tym1' => '🇸🇪 Švédsko', 'tym2' => '🇨🇿 Česko', 'vysledek' => '—', 'arena' => 'BCF Arena, Fribourg'],
    ['datum' => '18. 5.', 'cas' => '20:20', 'tym1' => '🇫🇮 Finsko', 'tym2' => '🇺🇸 USA', 'vysledek' => '—', 'arena' => 'Swiss Life Arena, Curych'],
    ['datum' => '20. 5.', 'cas' => '16:20', 'tym1' => '🇨🇿 Česko', 'tym2' => '🇮🇹 Itálie', 'vysledek' => '—', 'arena' => 'BCF Arena, Fribourg'],
    ['datum' => '23. 5.', 'cas' => '20:20', 'tym1' => '🇨🇿 Česko', 'tym2' => '🇸🇰 Slovensko', 'vysledek' => '—', 'arena' => 'BCF Arena, Fribourg'],
    ['datum' => '26. 5.', 'cas' => '20:15', 'tym1' => '🇨🇿 Česko', 'tym2' => '🇨🇦 Kanada', 'vysledek' => '—', 'arena' => 'BCF Arena, Fribourg'],
];
?>

<style>
    /* Zachování stylu z minulé stránky */
    body { background-image: url('assets/images/image2.jpg') !important; background-attachment: fixed !important; background-size: cover !important; }
    .card { background-color: rgba(255, 255, 255, 0.95) !important; backdrop-filter: blur(5px); border: none !important; }
    .badge-p { background-color: #ffc107; color: #000; font-size: 0.8em; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
</style>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Zápasy</h1>
        <a href="zapas-form.php" class="btn btn-success">+ Přidat zápas</a>
    </div>

    <div class="mb-4">
        <button class="btn btn-primary">Všechny</button>
        <button class="btn btn-outline-primary">🇨🇿 Česko</button>
        <button class="btn btn-outline-primary">Odehrané</button>
        <button class="btn btn-outline-primary">Nadcházející</button>
    </div>

    <div class="card shadow-sm">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Datum</th>
                    <th>Zápas</th>
                    <th>Výsledek</th>
                    <th>Aréna</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($zapasy as $z): ?>
                <tr>
                    <td><?= htmlspecialchars($z['datum']) ?><br><small class="text-muted"><?= htmlspecialchars($z['cas']) ?></small></td>
                    <td><?= $z['tym1'] ?> vs <?= $z['tym2'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($z['vysledek']) ?></strong>
                        <?php if (strpos($z['vysledek'], 'P') !== false): ?>
                            <span class="badge-p">P</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($z['arena']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-danger">Smazat</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>