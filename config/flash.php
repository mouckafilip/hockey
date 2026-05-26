<?php
// Kontrola přihlášení a flash zprávy
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function flash(string $message, string $type = 'success'): void {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}
?>