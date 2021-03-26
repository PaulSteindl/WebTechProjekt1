<?php
    if (isset($_POST) && !empty($_POST['userDataReset'])) {
        $userData = $_POST['userDataReset'];

//errorhandeling
        // username
        //empty input
        if(($error = emptyInput($userData) == 'emptyInput')) {
            header("Location: ./index.php?page=pwdReset&error=$error");
            exit();
        }

        $conn->secureInputRes($userData);
        $error = $conn->sendPwdReset();
        header("Location: ./index.php?page=pwdReset&error=$error");
        exit();
    }
?>