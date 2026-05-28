<?php
session_start();
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/flash.php';
include __DIR__ . '/includes/header.php';

// ⚠️ KONTROLA 1: Musíš být přihlášen
if (!isset($_SESSION['user_id'])) {
    flash('Musíš se přihlásit, abys mohl smazat tým', 'danger');
    header('Location: login.php');
    exit;
}

// ⚠️ KONTROLA 2: Musíš být admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    flash('Nemáš oprávnění mazat týmy. Pouze administrátoři mohou mazat týmy.', 'danger');
    header('Location: tymy.php');
    exit;
}

// ⚠️ KONTROLA 3: ID musí být platné
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    flash('Neplatné ID týmu', 'danger');
    header('Location: tymy.php');
    exit;
}

// ⚠️ KONTROLA 4: Zkontroluj, že tým existuje
try {
    $stmt = $conn->prepare("SELECT nazev FROM tymy WHERE id = ?");
    $stmt->execute([$id]);
    $team = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$team) {
        flash('Tým nebyl nalezen', 'danger');
        header('Location: tymy.php');
        exit;
    }
    
    // Smazání
    $stmt = $conn->prepare("DELETE FROM tymy WHERE id = ?");
    $stmt->execute([$id]);
    
    flash('Tým "' . htmlspecialchars($team['nazev']) . '" byl smazán', 'success');
} catch (PDOException $e) {
    flash('Chyba při mazání týmu: ' . $e->getMessage(), 'danger');
}

header('Location: tymy.php');
exit;
?>