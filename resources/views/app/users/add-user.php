    <!-- start sidebar -->
    <?php
    $title = 'ثبت مشتری / فروشنده';
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php'); ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ثبت مشتری / فروشنده جدید</div>
        <br />
        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('user-store') ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام و تخلص <?= _star ?> </div>
                            <input type="text" class="checkInput" name="user_name" placeholder="نام و تخلص را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">شماره <span class="fs12 color-orange">(تلگرام)</span> <?= _star ?> </div>
                            <input type="number" class="checkInput" name="phone" placeholder="شماره را وارد نمایید" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">آدرس</div>
                            <textarea name="address" placeholder="آدرس را وارد نمایید"></textarea>
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"></textarea>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">باقیداری گذشته </div>
                            <input type="text" name="remnants_past" placeholder="باقیداری قبلی مشتری را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex d-none">
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
                    </div>

                    <?= $this->branchSelectField(); ?>

                    <div class="accordion-title color-orange">جزئیات بیشتر</div>
                    <div class="accordion-content">
                        <div class="child-accordioin">
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">نام معرف </div>
                                    <input type="text" name="reagent" placeholder="نام معرف را وارد نمایید" />
                                </div>
                                <div class="one">
                                    <div class="label-form mb5 fs14">شماره معرف <?= _star ?> </div>
                                    <input type="number" name="reagent_phone" placeholder="شماره معرف را وارد نمایید" />
                                </div>
                            </div>

                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">انتخاب عکس</div>
                                    <input type="file" id="image" name="user_image" accept="image/jpeg, image/png, image/gif">
                                </div>
                            </div>
                            <div id="imagePreview" class="mb100">
                                <img src="" class="img" alt="">
                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>