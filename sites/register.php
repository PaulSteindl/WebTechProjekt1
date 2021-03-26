<!--html für Registration-->
<h1>Registration</h1>
<hr><br>
<div class="form">
    <form action="index.php?page=register" method="POST" class="form_register">
        <div class="form-content">
            <input type="radio" id="male" name="gender" value="Herr"><label for="male">Male</label>
            <input type="radio" id="female" name="gender" value="Frau"><label for="female">Female</label><br>
            <input type="text" id="first" name="first" placeholder="Vorname..." pattern="[a-zA-Z]{1,}" maxlength="20" title="Please only enter letters." required>*<br>
            <input type="text" id="last" name="last" placeholder="Nachname..." pattern="[a-zA-Z]{1,}" maxlength="20" title="Please only enter letters." required>*<br>
            <input type="text" id="username" name="username" placeholder="Username..." pattern="[a-zA-Z0-9]{1,}" maxlength="20" title="Please only enter letters and numbers." required>*<br>
            <input type="password" id="pwd" name="pwd" placeholder="Passwort..." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="60" title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required>*<br>
            <input type="password" id="pwdRepeat" name="pwdRepeat" placeholder="Passwort bestätigen..." pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="60" title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required>*<br>
            <input type="email" id="email" name="email" placeholder="E-Mail..." pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>*<br>
            <!-- <input type="text" id="tel" name="tel" placeholder="Telefonnummer (optional)..."><br> -->
            <input type="date" id="bday" name="bday" placeholder="Geburtsdatum..." required>*<br>
            <button class="button" type="submit" name="submit"><span>Sign up</span></button>
        </div>
    </form>

        <div class="login">
            <label>Bereits ein Konto? <a href="index.php?page=login"><button class="button"><span>Login</span></button></a></label>
        </div>
</div>
