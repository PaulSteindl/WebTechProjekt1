<h1 class='profileh1'>Profile Settings</h1><hr>
<div class="container-fluid pref">
    <h3 class='profileh3'>Account Preferences</h3>
    <div class="row mailPref">

        <h4 class="profileh4">Change Profilepicture</h4>

        <div class="col-sm-5  profileData">
            <p>Change your profile picture here</p>
        </div>

        <div class="col-sm-4">
            <a href="index.php?page=ChangePfP"><button class="col-sm-4 button"><span>Change</span></button></a>
        </div>

    </div>
    <div class="row mailPref">
        <h4 class='profileh4'>Email Adress</h4>

            <div class="col-sm-5 profileData">
                <?php
                    //mail anzeigen 
                    $user = $conn->getProfData($_SESSION['uid']);
                    echo $user[0]->email;
                ?>
            </div>
            <!-- modal box -->


            <div class="col-sm-4">
                <!-- button fuer die modal box -->
                <button id="myBtn1" class="button"><span>Change</span></button>
                <!-- modal box -->
                <div id="myModal1" class="modal1">
                    <!-- modal content mit bootstrap -->
                    <div class="modal-content">
                        <span class="close1">&times;</span>
                        <p>Update your email below.</p>
                        <div class="container-fluid">
                            <div class="row">


                                <div class="col-sm-8">
                                    <!-- input box fuer neue mail -->
                                    <!-- moeglicherweise nochmals das passwort abpruefen -->
                                    <form action="index.php?page=profileSettings" class="form-content" method="POST">
                                        <input type="email" id="mailNew" name="mailNew" placeholder="new E-Mail..." pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>*
                                    
                                </div>

                                <div class="col-sm-4">
                                    <button class="button" type="submit" name="submitMail"><span>Save</span></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ende modal box -->
        <!-- </div> -->
    </div>

    <div class="row mailPref">
        <h4 class='profileh4'>Username</h4>


        <div class="col-sm-5  profileData">
            <?php
                //uid anzeigen
                echo $user[0]->username;
            ?>
        </div>
        <!-- modal box -->

        <div class="col-sm-4">
            <!-- button fuer die modal box -->
            <button id="myBtn2" class="button"><span>Change</span></button>
            <!-- modal box -->
            <div id="myModal2" class="modal2">
                <!-- modal content mit bootstrap -->
                <div class="modal-content">
                    <span class="close2">&times;</span>
                    <p>Update your username below.</p>
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-8">
                                <!-- input box fuer neue mail -->
                                <!-- moeglicherweise nochmals das passwort abpruefen -->
                                <form action="index.php?page=profileSettings" class="form-content" method="POST">
                                    <input type="text" id="usernameNew" name="usernameNew" placeholder="Username..." pattern="[a-zA-Z0-9]{1,}" maxlength="20" title="Please only enter letters and numbers." required>*

                            </div>

                            <div class="col-sm-4">
                                <button class="button" type="submit" name="submitUsername"><span>Save</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ende modal box -->
    </div>    

    <div class="row mailPref">              
        <h4 class='profileh4'>Password</h4>

        <div class="col-sm-5  profileData">
            <p>Change your password here</p>
        </div>
        <!-- modal box -->

        <div class="col-sm-4">
            <!-- button fuer die modal box -->
            <button id="myBtn3" class="button"><span>Change</span></button>
            <!-- modal box -->
            <div id="myModal3" class="modal3">
                <!-- modal content mit bootstrap -->
                <div class="modal-content">
                    <span class="close3">&times;</span>
                    <p>Update your password below.</p>
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-8">
                                <!-- input box fuer neue mail -->
                                <form action="index.php?page=profileSettings" class="form-content" method="POST">
                                    <input type="password" id="pwdOld" name="pwdOld" placeholder="altes Passwort..." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="60" title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required>*<br>
                                    <input type="password" id="pwdNew" name="pwdNew" placeholder="neues Passwort..." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="60" title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required>*<br>
                                    <input type="password" id="pwdNewRepeat" name="pwdNewRepeat" placeholder="neues Passwort bestätigen..." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="60" title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required>*
                            </div>

                            <div class="col-sm-4">
                                <button class="button" type="submit" name="submitPwd"><span>Save</span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ende modal box -->
    </div>
</div>
