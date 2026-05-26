<?php
    require __DIR__ . '/config/flash.php';

    session_start();
    session_destroy();
    header('Location: login.php?logout=1');
exit;