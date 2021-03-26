<?php 
//speichert Post ab
    if(isset($_GET['Bedit']) && isset($_SESSION['uid']) && isset($_POST['tags']) && isset($_POST['status']) && isset($_POST['titel']) && isset($_POST['PostText'])){
        $conn->SafePostEdit($_GET['Bedit'], $_SESSION['uid'], $_POST['status'], $_POST['tags'], $_POST['titel'], $_POST['PostText']);
    }else{
        echo "Es ist ein Fehleraufgetreten!";
        die();
    }
    header("Location: index.php?page=beitragsanzeige");
    exit();
?>