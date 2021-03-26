<?php

if(isset($_POST['submitMail']) && !empty($_POST)){

    $mailNew = $_POST['mailNew'];

// errorhandeling
    //empty input
    if(($error = emptyInput($mailNew) == 'emptyInput')) {
        header("Location: ./index.php?page=profileSettingsSettings&error=$error");
        exit();
    }
    //email entspricht nicht dem email format ('@' und '.')
    if($result = invalidEmail($mailNew) == 'invalidEmail'){
        $error = 'invalid';
        header("Location: ./index.php?page=profileSettings&error=invalid");
        exit();
    }
    //user (username or email) existiert bereits
    if($error = $conn->usernameExists($mailNew, $mailNew) != false){
        $error = 'usernameTaken';
        header("Location: ./index.php?page=profileSettings&error=$error");
        exit();
    }

    //userdaten der changeMail func weitergeben + schutz slq injection
    $conn->secureInputProfMail($mailNew);
    //mail wird geaendert
    $error = $conn->changeMail($_SESSION['uid']);
    header("Location: ./index.php?page=profileSettings&error=$error");
    exit();

}elseif (isset($_POST['submitUsername']) && !empty($_POST)){

    $usernameNew = $_POST['usernameNew'];
    
// errorhandeling
    //empty input
    if(($error = emptyInput($usernameNew) == 'emptyInput')) {
        header("Location: ./index.php?page=profileSettings&error=$error");
        exit();
    }
    //user (username or email) existiert bereits
    if($error = $conn->usernameExists($usernameNew, $usernameNew) != false){
        $error = 'usernameTaken';
        header("Location: ./index.php?page=profileSettings&error=$error");
        exit();
    }

    //userdaten der changeUsername func weitergeben + schutz slq injection
    $conn->secureInputProfUid($usernameNew);
    //username wird geaendert
    $error = $conn->changeUsername($_SESSION['uid']);
    header("Location: ./index.php?page=profileSettings&error=$error");
    exit();

}elseif(isset($_POST['submitPwd']) && !empty($_POST)){

    $pwdOld = $_POST['pwdOld'];
    $pwdNew = $_POST['pwdNew'];
    $pwdNewRepeat = $_POST['pwdNewRepeat'];

// errorhandeling
    //empty input
    if(($error = emptyInput($pwdOld) == 'emptyInput') || ($error = emptyInput($pwdNew) == 'emptyInput') || ($error = emptyInput($pwdNewRepeat) == 'emptyInput')) {
        header("Location: ./index.php?page=profileSettings&error=$error");
        exit();
    }
    //both password dont match each other
    if($error = pwdMatch($pwdNew, $pwdNewRepeat) == 'pwdNoMatch'){
        $error = 'pwdNoMatch';
        header("Location: ./index.php?page=profileSettings&error=$error");
        exit();
    }
    //wenn altes passwort gleich ist wie neues passwort, dann error='profNewPwdSameOld'(pwdMatch gibt '' zurueck, wenn gleiches passwort)
    elseif ($error = pwdMatch($pwdOld, $pwdNew) != 'pwdNoMatch') {
        $error = 'profNewPwdSameOld';
        header("Location: ./index.php?page=profileSettings&error=$error");
        exit();
    }

    $conn->secureInputProfPwd($pwdOld, $pwdNew, $pwdNewRepeat);

    $user = $conn->getProfData($_SESSION['uid']);
    $pwdOldHash = $user[0]->passwort;

    if (!password_verify($pwdOld, $pwdOldHash)) {
        $error = 'wrongPwd';
        header("Location: ./index.php?page=profileSettings&error=$error");
        exit();
    } else {
        $error = $conn->changePwd($_SESSION['uid']);
        header("Location: ./index.php?page=profileSettings&error=$error");
        exit();
    }
}
?>
