<?php
    session_start();
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    unset($_SESSION['nome']);
    unset($_SESSION['path']);
    session_unset();
    header('Location: home.php');
?>