<?php
    session_start();

    // Verifica se a sessão está ativa

    if (session_status() == PHP_SESSION_ACTIVE) {

        // Destrói a sessão
        session_unset();
        session_destroy();

        header("Location: ../root/index.php");
    } else {
        header("Location: ../views/login.php");
    }

    exit;
?>
