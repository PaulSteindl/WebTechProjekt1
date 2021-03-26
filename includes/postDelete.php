<?php 
//löscht akutellen Post
    if(isset($_GET['Bdel'])){
        if(isset($_SESSION['rolle']) && isset($_SESSION['uid'])){
            $conn->DeletPost($_GET['Bdel'], $_SESSION['uid'], $_SESSION['rolle']);
        }
    }
    header("Location: index.php?page=beitragsanzeige");
    exit();
?>