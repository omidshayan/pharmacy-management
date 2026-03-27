    <?php
    $title = 'ویرایش مشتری / فروشنده' . $user['user_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/error.php');
    ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ویرایش مشتری / فروشنده <?= $user['user_name'] ?></div>

        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('edit-user-store/' . $user['id']) ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام و تخلص <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= $user['user_name'] ?>" name="user_name" placeholder="نام و تخلص را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">نام پدر</div>
                            <input type="text" name="father_name" value="<?= $user['father_name'] ?>" placeholder="نام پدر را وارد نمایید" maxlength="40" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره <span class="fs12 color-orange">(تلگرام)</span> <?= _star ?> </div>
                            <input type="number" class="checkInput" value="<?= $user['phone'] ?>" name="phone" placeholder="شماره را وارد نمایید" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">رمزعبور </div>
                            <input type="password" name="password" placeholder="رمزعبور را وارد نمایید" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">آدرس</div>
                            <textarea name="address" placeholder="آدرس را وارد نمایید"><?= $user['address'] ?></textarea>
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"><?= $user['description'] ?></textarea>
                        </div>
                    </div>

                    <!-- <div class="inputs d-flex d-none">
                        <div class="one ">
                            <label class="d-flex justify-center">
                                <input type="checkbox" class="checkbox-select" name="is_customer" checked>
                                <div class="label-form mb5 fs22" for="name">مشتری</div>
                            </label>
                        </div>
                        <div class="one ">
                            <label class="d-flex justify-center">
                                <input type="checkbox" class="checkbox-select" name="is_seller" checked>
                                <div class="label-form mb5 fs22" for="name">فروشنده</div>
                            </label>
                        </div>
                    </div> -->

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب عکس</div>
                            <input type="file" id="image" name="user_image" accept="image/jpeg, image/png, image/gif">
                        </div>
                    </div>
                    <div id="imagePreview">
                        <img src="" class="img" alt="">
                    </div>

                    <div>
                        <img src="<?= ($user['user_image'] ? asset('public/images/users/' . $user['user_image']) : asset('public/assets/img/empty.png')) ?>" class="img" alt="logo">
                    </div>
                    <div class="fs11">تصویر فعلی</div>


                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="<?= _edit_btn ?>" class="btn" />
                </form>
            </div>
            <?= $this->back_link('users') ?>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>