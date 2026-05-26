<?php
session_start();

require_once __DIR__ . '/includes/flash.php';

flash('Byli jste úspěšně odhlášeni.', 'success');

$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;