<?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    require_once('utility/dbh.inc.php');
    require_once('utility/Display.class.php');
    require_once('utility/Comment.class.php');
    require_once('utility/profilePage.class.php');
    require_once('utility/userClass.php');
    require_once('utility/PostHistory.class.php');
    require_once('includes/functions.inc.php');
    $conn = new db();

    // if user clicks like or dislike button
    if (isset($_POST['action'])) {
        $conn->registerClick($_SESSION['uid']);
    }
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hier könnte ihre Werbung stehen</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--Bootstrap-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- Like/Dislike -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    
    <!--ModalBoxen-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!--Eigenes Stylesheet-->
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <!-- <div class="page-container"> -->
        <header>
        <!-- Navbar -->
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <!-- <div class="collapse navbar-collapse" id="myNavbar"> -->
                    <div class="navbar-collapse" id="myNavbar">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="index.php?page=home">Home</a></li>
                        </ul>

                        <ul class="nav navbar-nav">
                            <li class=""><a href="index.php?page=beitragsanzeige">Posts</a></li>
                        </ul>

                        <?php if(isset($_SESSION['uid'])){ ?>
                            <ul class="nav navbar-nav">
                                <li class=""><a href="index.php?page=neuerPost">New Post</a></li>
                            </ul>

                                <ul class="nav navbar-nav navbar-right">
                                <?php if(isset($_SESSION['rolle']) && $_SESSION['rolle'] == 0){ ?>
                                    <li><a href='index.php?page=adminData'>Admin Data</a></li>
                                <?php } ?>

                                    <li class='dropdown'>
                                        <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><?php echo"$_SESSION[username]"?><span class='caret'></span></a>
                                        <ul class='dropdown-menu'>
                                            <li><a style='color:rgb(144, 144, 144);' href='index.php?page=profileSettings'>Profile Settings</a></li>

                                            <li role='separator' class='divider'></li>
                                            <li><a style='color:rgb(144, 144, 144);' href='index.php?page=logout'>Logout</a></li>
                                        </ul>
                                    </li>
                            <?php }else{ ?>
                                    <ul class="nav navbar-nav navbar-right">
                                    <li><a href='index.php?page=register'><span class='glyphicon glyphicon-log-in'></span> Sign Up</a></li>
                                    <li><a href='index.php?page=login'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

    <!-- linke sidebar -->
        <div class="container-fluid">    
            <div class="row content">
                <div class="col-sm-2 sidenav">
                </div>

                <div class="col-sm-8 text-left middlenav"> 
                    <?php

                        //error bereich
                        if(isset($_GET['error']) && !empty($_GET['error'])){
                            echo '<div class="errorMsg">';
                                //errorhandling    
                                switch ($_GET['error']) {
                                    //ich weiss nicht ob ich error & stmtFailed brauche
                                    case 'error':
                                        echo '<p>Error: Something went wrong!</p>';
                                        break;
                                    case 'userInactive':
                                        echo '<p>Konto ist auf inaktive gesetzt, Administrator kontaktieren.</p>';
                                        break;
                                    case 'stmtFailed':
                                        echo '<p>Error: Something went wrong!</p>';
                                        break;
                                    case 'regNone':
                                        echo '<p>You`ve sucessfully registered!</p>';
                                        break;
                                    case 'emptyInput':
                                        echo '<p>Error: Fill in all fields!</p>';
                                        break;
                                    case 'invalid':
                                        echo '<p>Error: Only use valid inputs!</p>';
                                        break;
                                    case 'pwdNoMatch':
                                        echo '<p>Error: Password don`t match!</p>';
                                        break;
                                    case 'usernameTaken':
                                        echo '<p>Error: Username or Email already taken!</p>';
                                        break;
                                    case 'wrongUsername':
                                        echo '<p>Error: User doesn`t exist!</p>';
                                        break;
                                    case 'logNone':
                                        echo '<p>You are now logged in!</p>';
                                        break;
                                    case 'wrongPwd':
                                        echo '<p>Error: Wrong password!</p>';
                                        break;
                                    case 'profMailNone':
                                        echo '<p>Email is now changed!</p>';
                                        break;
                                    case 'profUsernameNone':
                                        echo '<p>Username is now changed!</p>';
                                        break;
                                    case 'profPwdNone':
                                        echo '<p>Password is now changed!</p>';
                                        break;
                                    case 'profNewPwdSameOld':
                                        echo '<p>New password can`t be old password!</p>';
                                        break;
                                    case 'resNone':
                                        echo '<p>Password is now reset!</p>';
                                        break;
                                    case 'logoutNone':
                                        echo '<p>You are now logged out!</p>';
                                        break;
                                    case 'activeNone':
                                        echo '<p>User is set active!</p>';
                                        break;
                                    case 'inactiveNone':
                                        echo '<p>User is set inactive!</p>';
                                        break;
                                    default:
                                        break;
                                }
                            echo '</div>';
                        }
                        
//Unterscheidet zwischen angemeldet und nicht angemelet
//das obere ist angemeldete User
                        if(isset($_GET['page'])){
                            if (isset($_SESSION['uid'])) {
                                switch ($_GET['page']) {
                                    case 'profileSettings':
                                        if(isset($_POST['mailNew']) || isset($_POST['usernameNew']) || isset($_POST['pwdOld'])) {
                                            include('includes/profileSettings.inc.php');
                                        }
                                        include('sites/profileSettings.php');
                                        break;
                                    case 'Help':
                                        include('sites/help.html');
                                        break;
                                    case 'home':
                                        include('sites/WelcomePage.html');
                                        break;
                                    case 'SafePostEdit':
                                        include('includes/SafePostEdit.php');
                                        break;
                                    case 'ChangePfP':
                                        include('sites/UploadPfp.php');
                                        break;
                                    case 'UserHistory':
                                        include('sites/UserVerw_ListeBeitraege.php');
                                        break;
                                    case 'profilePage':
                                        include('sites/profilePage.php');
                                        break;
                                    case 'PostEdit':
                                        include('sites/postEdit.php');
                                        break;
                                    case 'commentDelet':
                                        include('includes/DelCmt.inc.php');
                                        break;
                                    case 'adminDataChange':
                                        include('includes/adminDataChange.php');
                                        break;
                                    case 'PostDel':
                                        include('includes/postDelete.php');
                                        break;
                                    case 'comment':
                                        include('sites/Kommentare.php');
                                        break;
                                    case 'logout':
                                        include('sites/logout.php');
                                        break;
                                    case 'neuerPost':
                                        include('sites/beitragErstellen.php');
                                        break;
                                    case 'adminData':
                                        include('sites/adminData.php');
                                        break;
                                    case 'beitragsanzeige':
                                        include('sites/Beitraganzeigen.php');
                                        break;
                                    case 'Impressum':
                                        include('sites/Impressum.html');
                                        break;
                                    case 'login':
                                        include('sites/WelcomePage.html');
                                        break;
                                    default:
                                        include('sites/404.php');
                                        break;
                                }
                            } elseif (!isset($_SESSION['uid'])) {
                                switch ($_GET['page']) {
                                    case 'register':
                                        if(isset($_POST['submit']) && !empty($_POST)) {
                                            include('includes/register.inc.php');
                                        }
                                        include('sites/register.php');
                                        break;
                                    case 'Help':
                                        include('sites/help.html');
                                        break;
                                    case 'login':
                                        if(isset($_POST['userData']) && isset($_POST['passwort'])) {
                                            include('includes/login.inc.php');
                                        }
                                        include('sites/login.php');
                                        break;
                                    case 'profilePage':
                                        include('sites/profilePage.php');
                                        break;
                                    case 'pwdReset':
                                        if(isset($_POST['pwdForgot']) && !empty($_POST['userDataReset'])) {
                                            include('includes/pwdReset.inc.php');
                                        }
                                        include('sites/pwdReset.php');
                                        break;
                                    case 'beitragsanzeige':
                                        include('sites/Beitraganzeigen.php');
                                        break;   
                                    case 'Impressum':
                                        include('sites/Impressum.html');
                                        break;                   
                                    default:
                                        include('sites/WelcomePage.html');
                                        break;
                                }
                            }
                        } else{
                            include('sites/WelcomePage.html');
                        }
                    ?>
                </div>


    <!-- rechte sidebar -->
                <div class="col-sm-2 sidenav">
                </div>
            </div>
        </div>
    <!-- </div> -->

        <!-- Footer -->
        <footer class="container-fluid">
            <p><a href="index.php?page=Impressum">Impressum</a> | <a href="index.php?page=Help">Hilfe</a></p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="scripts.js"></script>
<!-- JS fuer modal box auf profile page -->
        <script src="modalbox.js"></script>
    </body>
</html>