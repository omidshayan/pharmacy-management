    <!-- start sidebar -->
    <?php
    $title = 'ویرایش: ' . $product['product_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php');
    ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> ویرایش دوا: <?= $product['product_name'] ?>
            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
        </div>
        <?php
        $help_title = _help_title;
        $help_content = _help_desc;
        include_once('resources/views/helps/help.php');
        ?>
        <br />
        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('edit-product-store/' . $product['id']) ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام دوا <?= _star ?> </div>
                            <input type="text" class="checkInput" name="product_name" value="<?= $product['product_name'] ?>" placeholder="نام دوا را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">کد دوا</div>
                            <input type="text" name="product_code" value="<?= $product['product_code'] ?>" placeholder="کد دوا را وارد نمایید" maxlength="40" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب دسته بندی</div>
                            <select name="product_cat">
                                <option selected disabled>لطفا دسته بندی را انتخاب نمایید</option>
                                <?php
                                foreach ($product_cats as $product_cat) { ?>
                                    <option value="<?= $product_cat['product_cat_name'] ?>"><?= $product_cat['product_cat_name'] ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"><?= $product['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب عکس دوا</div>
                            <input type="file" id="image" name="product_image" accept="image/*">
                        </div>
                    </div>
                    <div id="imagePreview">
                        <img src="" class="img" alt="">
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
            <a href="<?= url('products') ?>" class="color text-underline d-flex justify-center fs14">برگشت</a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>