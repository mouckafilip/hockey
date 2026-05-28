<?php
session_start();
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/flash.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_error'] = 'Musíš se přihlásit, abys mohl smazat tým';
    header('Location: login.php');
    exit;
}

if ($_SESSION['admin'] !== true) {
    $_SESSION['flash_error'] = 'Nemáš oprávnění k smazání týmu. Pouze administrátoři mohou mazat týmy.';
    http_response_code(403);
    header('Location: tymy.php');
    exit;
}

if (empty($_GET['id'])) {
    $_SESSION['flash_error'] = 'ID týmu není určeno';
    header('Location: tymy.php');
    exit;
}

$team_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT id, nazev FROM tymy WHERE id = ?");
$stmt->execute([$team_id]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$team) {
    $_SESSION['flash_error'] = 'Tým s tímto ID nebyl nalezen';
    header('Location: tymy.php');
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM tymy WHERE id = ?");
    $stmt->execute([$team_id]);
    
    $_SESSION['flash_success'] = 'Tým "' . htmlspecialchars($team['nazev']) . '" byl úspěšně smazán';
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Chyba při mazání týmu. Zkus to později.';
}

header('Location: tymy.php');
exit;
?>