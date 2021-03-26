<?php
//prüft um Kommentare zu löschen
    if(isset($_GET['del']) && !empty($_GET['del']) && isset($_SESSION['uid']) && isset($_GET['cmt']) && !empty($_GET['cmt']) && isset($_GET['uid']) && ($_GET['uid'] == $_SESSION['uid'] || $_SESSION['rolle'] == 0)){
        $conn->DelCmt($_GET['del']);
    }
    header("Location: index.php?page=comment&cmt=$_GET[cmt]");
    exit();
?>