<?php
session_start();
require __DIR__ . '/config/flash.php';
include __DIR__ . '/includes/header.php';
 
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
 
if ($id > 0) {
    try {
        $stmt = $conn->prepare("DELETE FROM tymy WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Tiché chyby, jen přesměruj
    }
}
 
header("Location: tymy.php");
exit;
?>