<?php
    if(isset($_COOKIE['angbleiben']) && !empty($_COOKIE['angbleiben'])){
        $error = $conn->loginCookie($_COOKIE['angbleiben']);
    }
?>

<h1>Login</h1>
<hr><br>
<div class="form">
    <form action="index.php?page=login" method="POST">
        <div class="login-content">
            <!-- <input type="text" id="userData" name="userData" placeholder="Username/E-Mail..." required>*<br> -->
            <input type="text" id="userData" name="userData" placeholder="Username..." pattern="[a-zA-Z0-9@.]{1,}" maxlength="50" title="Please only enter letters and numbers." required>*<br>
            <!-- <input type="password" id="passwort" name="passwort" placeholder="Passwort..." required>*<br> -->
            <input type="password" id="passwort" name="passwort" placeholder="Passwort..." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="60" title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required>*<br>
            <label for="angbleiben">Angemeldet bleiben</label>
            <input type="checkbox" id="angbleiben" name="angbleiben" value="true"><br>
            <button class="button" type="submitLog" name="submitLog"><span>Login </span></button>
        </div>
    </form>
    <div class="login-content">
            <label>Forgot your <a href="index.php?page=pwdReset">password</a> ?</label>
    </div>
    <div class="login-content">
            <label>New to our website? <a href="index.php?page=register"><button class="button">SIGN UP</button></a></label>
    </div>
</div>
