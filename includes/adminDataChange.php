<?php
//ändert aktiv auf inaktiv bzw. inaktiv auf aktiv
    if(isset($_SESSION['rolle']) && $_SESSION['rolle'] == 0 && isset($_POST['submitAdmin']) && isset($_POST['ChangeUid']) && isset($_POST['ChangeActivity']) && !empty($_POST['ChangeActivity']) && !empty($_POST['ChangeUid'])) {
        if($_POST['ChangeActivity'] == 'active'){
            $conn->changeToInactive($_POST['ChangeUid']);
        }else{
            $conn->changeToActive($_POST['ChangeUid']);
        }
    }
    if (isset($_POST['submitAdminPwd']) && isset($_POST['ChangeUsername']) /*&& isset($_POST['ChangePwd']) && !empty($_POST['ChangePwd']) */&& !empty($_POST['ChangeUsername'])){
        //echo $row->username;
        $conn->secureInputRes($_POST['ChangeUsername']);
        $error = $conn->sendPwdReset();
        header("Location: ./index.php?page=adminData&error=$error");
        exit();
    } 
    header("Location: index.php?page=adminData");
    exit();
?>