    <?php
    $title = 'تنظیمات بِل';
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php'); ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> تنظیمات بِل</div>
        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('factor-settings-store') ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"> نام فروشگاه / شرکت <?= _star ?></div>
                            <textarea name="center_name" class="checkInput" placeholder="نام فروشگاه / شرکت را وارد نمایید"><?= $factor_infos['center_name'] ?></textarea>
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">شعار</div>
                            <textarea name="slogan" placeholder="شعار فروشگاه / شرکت را وارد نمایید"><?= $factor_infos['slogan'] ?></textarea>
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره موبایل 1</div>
                            <input type="text" name="phone1" value="<?= $factor_infos['phone1'] ?>" placeholder="شماره موبایل را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره موبایل 2</div>
                            <input type="text" name="phone2" value="<?= $factor_infos['phone2'] ?>" placeholder="شماره موبایل را وارد نمایید" maxlength="40" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره موبایل 3</div>
                            <input type="text" name="phone3" value="<?= $factor_infos['phone3'] ?>" placeholder="شماره موبایل را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره موبایل 4</div>
                            <input type="text" name="phone4" value="<?= $factor_infos['phone4'] ?>" placeholder="شماره موبایل را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">وبسایت</div>
                            <input type="text" name="website" value="<?= $factor_infos['website'] ?>" placeholder="آدرس وبسایت را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">ایمیل</div>
                            <input type="text" name="email" value="<?= $factor_infos['email'] ?>" placeholder="آدرس ایمیل را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">آدرس</div>
                            <textarea name="address" placeholder="آدرس را وارد نمایید"><?= $factor_infos['address'] ?></textarea>
                        </div>
                    </div>

                    <?= $this->branchSelectField(); ?>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب لوگو</div>
                            <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif">
                        </div>
                    </div>
                    <div id="imagePreview">
                        <img src="" class="img" alt="">
                    </div>
                    <div>
                        <img src="<?= ($factor_infos['image'] ? asset('public/images/public/' . $factor_infos['image']) : asset('public/assets/img/empty.png')) ?>" class="img" alt="logo">
                    </div>
                    <div class="fs11">تصویر فعلی</div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="<?= _insert_btn ?>" class="btn" />
                </form>
            </div>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>