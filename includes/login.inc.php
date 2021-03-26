<?php

if(isset($_POST) && !empty($_POST['userData']) && !empty($_POST)) {
    if(isset($_POST['angbleiben']) && !empty($_POST['angbleiben']) && $_POST['angbleiben'] == 'true'){
        $angbleiben = $_POST['angbleiben'];
    }else{
        $angbleiben = 'false';
    }
    $userData = $_POST['userData'];
    $passwort = $_POST['passwort'];

//errorhandeling
    // username
    //empty input
    if(($error = emptyInput($userData) == 'emptyInput')) {
        header("Location: ./index.php?page=login&error=$error");
        exit();
    }
    // passwort
    //empty input
    if(($error = emptyInput($passwort) == 'emptyInput')) {
        header("Location: ./index.php?page=login&error=$error");
        exit();
    }
    
    //userdaten der login func weitergeben + schutz slq injection
    $conn->secureInputLog($userData, $passwort);
    //user anmelden
    $error = $conn->loginUser($angbleiben);
    if($error == 'logNone' || $error == 'userInactive'){
        if($_SESSION['inaktiv'] == 0){
            header("Location: ./index.php?page=home&error=$error");
            exit();
        }else{
            $error = 'userInactive';
            header("Location: ./index.php?page=logout&error=$error");
            exit();
        }
    }else{
        header("Location: ./index.php?page=login&error=$error");
        exit();
    }
}
?>
