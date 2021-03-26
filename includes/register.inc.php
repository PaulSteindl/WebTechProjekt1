<?php

if(isset($_POST["submit"]) && !empty($_POST)){

    //defending against injections
    $anrede = $_POST['gender']; 
    $vorname = $_POST['first'];
    $nachname = $_POST['last'];
    $username = $_POST['username'];
    $passwort = $_POST['pwd'];
    $passwortRepeat = $_POST['pwdRepeat'];
    $email = $_POST['email'];
    //$tel = $_POST['tel'];
    $geb = $_POST['bday'];
    
//errorhandling
// vorname
    //empty input
    if(($error = emptyInput($vorname) == 'emptyInput')) {
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }

// nachname
    //empty input
    if(($error = emptyInput($nachname) == 'emptyInput')) {
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }

// username
    //empty input
    if(($error = emptyInput($username) == 'emptyInput')) {
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }
    //user (username or email) existiert bereits
    if($error = $conn->usernameExists($username, $email) != false){
        $error = 'usernameTaken';
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }

// email
    //empty input
    if(($error = emptyInput($email) == 'emptyInput')) {
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }
    //email entspricht nicht dem email format ('@' und '.')
    if($error = invalidEmail($email) == 'invalidEmail'){
        $error = 'invalid';
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }

// passwort
    //empty input
    if(($error = emptyInput($passwort) == 'emptyInput') || ($result = emptyInput($passwortRepeat) == 'emptyInput')) {
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }
    //both password dont match each other
    if($error = pwdMatch($passwort, $passwortRepeat) == 'pwdNoMatch'){
        //aus irgendeinem grund stellt mir die func pwdMatch $error auf 1
        $error = 'pwdNoMatch';
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }

    //userdaten der reg func weitergeben + schutz slq injection
    $conn->secureInputReg($anrede, $vorname, $nachname, $username, $passwort, $passwortRepeat, $email, $geb, 1);
    //user in die Datenbank hinzufuegen
    $error = $conn->createUser();
    if($error == 'regNone'){
        //userdaten der login func mitgeben
        $conn->secureInputLog($username, $passwort);
        //user anmelden
        $error = $conn->loginUser('false');
        header("Location: ./index.php?page=home&error=$error");
        exit();
    } else {
        header("Location: ./index.php?page=register&error=$error");
        exit();
    }

}else {
    header("Location: ./index.php");
    exit();
}
?>