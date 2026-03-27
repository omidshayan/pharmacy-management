<?php
$title = 'فراموشی رمز عبور';
include_once('resources/views/auth/layouts/header.php');
?>

<div class="dark">
    <form action="<?= url('forgot/request') ?>" method="POST" id="form">
        <div class="authBox py-16">

            <!-- page circle effect -->
            <span id="cardWrap" class="authCircle"></span>
            <span id="cardWrap1" class="authCircle"></span>
            <span id="cardWrap2" class="authCircle"></span>
            <span id="cardWrap3" class="authCircle"></span>
            <span id="cardWrap4" class="authCircle"></span>
            <span id="cardWrap5" class="authCircle"></span>
            <!-- page circle effect -->

            <!-- card effect -->
            <div class="authBackEffect marginTop5 flex w-full h-full justify-center items-center">
                <div class="relative authBackEffectBox mt-4">
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                    <span class="bg-pic"></span>
                </div>
            </div>
            <!-- card effect -->

            <!-- login card -->
            <div class="w-full h-full flex flex-col justify-center items-center">
                <div class="flex flex-col justify center items-center w-72 sm:w-96 bg-blur-auth h-auto py-12 marginTop5">

                    <!-- logo img -->
                    <a href="<?= url('/') ?>">
                        <img class="w-25 h-20" style="margin-top: -100px;" src="<?= asset('/public/assets/img/landing/aryaLearn-1.png') ?>" alt="اریا لرن - arya learn">
                    </a>
                    <!-- logo img -->

                    <!-- title -->
                    <div class="w-full flex justify-start items-center  text-white text-2xl mb-4 p-8">
                        <h3><?= _password_recover ?></h3>
                    </div>
                    <!-- title -->

                    <!-- input box -->
                    <div class="flex flex-col justify-center items-center p-2">
                        <input class="authInput w-56 sm:w-72 h-12 border-box p-4 text-center text-white <?= (flash('email_war') ? 'c-war' : '') ?>" name="email" id="email" value="<?= (flash('email') ? flash('email') : '') ?>" type="email" placeholder="<?= _enter_email ?>">
                    </div>
                    <!-- input box -->
                    <!-- captcha -->
                    <span class="w-11/12 mx-auto c-capt"><span class="c-ab"><?= _security_code ?></span>
                        <div class="g-recaptcha c-ab <?= (flash('not_r') ? 'c-war' : '') ?>" data-sitekey="<?= _data_sitekey ?>"></div>
                    </span>
                    <!-- captcha -->
                    <!-- login btn -->
                    <div class="flex w-full justify-center items-center p-4 text-white">
                        <button class="authBtn h-12 w-6/12"><?= _send ?></button>
                    </div>
                    <!-- login btn -->

                    <!-- register link -->
                    <div class="flex w-full justify-center items-center text-sm text-white px-4 mt-8">
                        <a href="<?= url('login') ?>"><?= _go_to_login ?></a>
                    </div>
                    <!-- register link -->
                </div>
            </div>
            <!-- login card -->
        </div>
    </form>
</div>
</div>
<script>
    const form = document.querySelector('#form')
    const email = document.querySelector('#email')

    form.addEventListener('submit', (event) => {

        if (!email.value) {
            event.preventDefault();
            checkInputs()
        } else {
            form.classList.remove("error");
        }
    })

    const checkInputs = () => {
        const emailValue = email.value.trim()
        if (emailValue === '') {
            setError(email)
        }
    }

    const setError = (input) => {
        const formControl = input.parentElement
        formControl.className = 'formControl errorr'
    };
</script>
<?php
$message = flash('warning');
if (!empty($message)) { ?>
    <div class="alert-warning">
        <span class="close-btn">&times;</span>
        <span class="empty_comment">
            <?= $message ?>
        </span>
    </div>
<?php  }
$message = flash('success');
if (!empty($message)) { ?>
    <div class="alert-success">
        <span class="close-btn">&times;</span>
        <span class="empty_comment">
            <?= $message ?>
        </span>
    </div>
<?php  }
include_once('resources/views/auth/layouts/footer.php');
?>