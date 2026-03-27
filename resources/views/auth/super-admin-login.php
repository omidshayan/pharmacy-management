<?php
$title = 'ورود به سیستم';
include_once('resources/views/auth/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/error.php'); ?>
?>

<!-- login form -->
<div class="login">
    <div class="login-form">
        <h3>فرم ورود به سیستم (مدیریت)</h3>
        <div class="avatar-login">
            <img src="<?= asset('public/assets/img/profile.png') ?>" alt="">
        </div><br>
        <form action="<?= url('super-login') ?>" method="POST">
            <div class="label-input"> شماره موبایل </div>
            <input type="text" name="phone" class="checkInput" placeholder="شماره موبایل خود را وارد کنید..." value="<?= old('phone') ?>">
            <div class="label-input">رمزعبور</div>
            <input type="password" name="password" class="checkInput" placeholder="رمزعبور خود را وارد کنید..." value="<?= old('password') ?>">
            <div class="remember-login">
                <input type="checkbox" id="checkbox" checked class="remember-checkbox" name="remember_me">
                <label for="checkbox" class="check-title">مرا به خاطر بسپار</label>
            </div>
            <input type="submit" value=" ورود " id="submit" class="btn-custom btn-color">
        </form>
        <div class="other-auth">
            <div class="forget-pass">فراموشی رمزعبور - <a href="#">کلیک کنید</a></div>
            <div class="go-register">ثبت نام نکرده اید؟ - <a href="#">ثبت نام کنید</a></div>
        </div>
    </div>
</div>
</body>

</html>