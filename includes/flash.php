<?php
function flash(string $message, string $type = 'success'): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}
?>