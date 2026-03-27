<?php
$title = 'تغییر رمزعبور';
include_once('resources/views/auth/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/error.php');
?>

<!-- login form -->
<div class="login">
    <div class="login-form">
        <h3>فرم تغییر رمزعبور</h3>
        <br>
        <form action="<?= url('reset-password-store/' . $forgot_token) ?>" method="POST">
            <div class="label-input">رمزعبور جدید</div>
            <input type="password" name="password" class="passInput checkInput" placeholder="رمزعبور را وارد کنید...">
            <div class="label-input">تکرار رمزعبور جدید</div>
            <input type="password" name="repeat_password" class="passInput checkInput" placeholder="تکرار رمزعبور را وارد کنید...">
            <div class="remember-login">
            </div>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="submit" value=" ارسال " id="submit" class="btn-custom btn-color bold">
        </form>
    </div>
</div>
</body>

</html>