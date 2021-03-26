<!--html für passwort reset-->
<h1>Reset your Password</h1><hr>

<div class="container-fluid form">
    <form action="index.php?page=pwdReset" method="POST" class="form_register">
        <div class="row form-content">
            <div class="col-md">
                <input type="text" id="userDataReset" name="userDataReset" placeholder="Username/E-Mail..." required>*<br>
                <button class="button" type="submit" name="pwdForgot"><span>Reset</span></button>
            </div>
        </div>
    </form>
</div>