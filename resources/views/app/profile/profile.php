<!-- start sidebar -->
<?php
$title = 'مشخصات اکانت';
include_once('resources/views/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/error.php');
?>
<!-- end sidebar -->

<!-- Start content -->
<div class="content">
    <div class="content-title"> جزئیات حساب کاربری: <?= $profile['employee_name'] ?></div>
    <!-- start page content -->
    <div class="box-container">
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">نام</div>
                <div class="w100 m10 center"><?= $profile['employee_name'] ?></div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">تاریخ ثبت</div>
                <div class="w100 m10 center"><?= jdate('Y/m/d', strtotime($profile['created_at'])) ?></div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">شماره تماس</div>
                <div class="w100 m10 center"><?= $profile['phone'] ?></div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">ایمیل</div>
                <div class="w100 m10 center"><?= ($profile['email']) ? $profile['email'] : '- - - -' ?></div>
            </div>
        </div>
        <hr class="hr">
        <br>
    </div>

    <div class="box-container">
        <div class="change-password">
            تغییر رمزعبور
            <div class="insert">
                <form action="<?= url('edit-store-profile/' . $profile['id']) ?>" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">رمزعبور فعلی <?= _star ?> </div>
                            <input type="password" name="oldPassword" placeholder=" رمزعبور فعلی را وارد نمایید" required />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">رمزعبور جدید <?= _star ?> </div>
                            <input type="password" name="newPassword" placeholder="رمزعبور جدید را وارد نمایید" required />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">تکرار رمزعبور جدید <?= _star ?> </div>
                            <input type="password" name="newPasswordR" placeholder="تکرار رمزعبور جدید را وارد نمایید" required />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form fs12 text-underline cursor-p color-orange" id="openModalClosure">فراموشی رمزعبور (کلیک کنید)</div>
                        </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
        </div>
    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<!-- forgot password modal -->
<div id="closureModal" class="modal">
    <form action="<?= url('forgot-request') ?>" method="POST">
        <div class="modal-content border-radius">
            <div class="closureClose cursor-p float-right fs22 btn-close m10">&times;</div>
            <br>
            <span class="fs12 color-orange">لینک تغییر رمزعبور به ایمیل شما ارسال می شود</span>
            <div class="insert flex-justify-align center">
                <div class="inputs d-flex">
                    <div class="one">
                        <div class="label-form mb5 fs14">ایمیل خود را وارد نمائید <?= _star ?> </div>
                        <input type="email" name="email" class="checkInput" placeholder="ایمیل خود را وارد نمایید" required />
                    </div>
                </div>
            </div>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="submit" id="submit" value="ارسال" class="btn bold p10 w80" />
        </div>
    </form>
</div>

<!-- open modal input forget password -->
<script>
    var closureModal = document.getElementById("closureModal");
    var closureBtn = document.getElementById("openModalClosure");
    var closureClose = document.getElementsByClassName("closureClose")[0];
    closureBtn.onclick = function() {
        closureModal.style.display = "block";
    }
    closureClose.onclick = function() {
        closureModal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == closureModal) {
            closureModal.style.display = "none";
        }
    }
    document.getElementById("closureCancelBtn").onclick = function() {
        closureModal.style.display = "none";
    }
    document.getElementById("confirmBtn").onclick = function() {
        var email = document.getElementById("emailInput").value;
        closureModal.style.display = "none";
    }
</script>

<?php include_once('resources/views/layouts/footer.php') ?>