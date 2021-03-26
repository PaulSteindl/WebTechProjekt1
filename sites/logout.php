<?php
//löscht daten aus session
    if (session_status() == PHP_SESSION_NONE) session_start();
    
    session_unset();
    session_destroy();
//booleanwert wird textlich abgespeichert, erleichtert abfrage
    setcookie('angbleiben', 'false', time()-604800);
    if(!isset($_GET['$error']) && empty($_GET['error']))$error = 'logoutNone';
    else $error = $_GET['error'];
    header("Location: ./index.php?page=home&error=$error");
    exit();