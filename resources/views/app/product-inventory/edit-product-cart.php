    <!-- start sidebar -->
    <?php
    $title = 'ویرایش دوا: ' . $product_cart['product_name'];
    include_once('resources/views/layouts/header.php');
    // include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/error.php');
    ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> ویرایش دوا: <?= $product_cart['product_name'] ?></div>

        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <div class="producInfos">
                    <h5 class="d-none product-name">نام: <span></span></h5>
                </div>

                <form action="<?= url('edit-product-cart-store/' . $product_cart['id']) ?>" method="POST">

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">تعداد <?=$product['package_type']?> </div>
                            <input type="text" class="checkInput" value="<?= $product_cart['package_qty'] ?>" name="package_qty" placeholder="تعداد بسته یا کارتن را وارد نمایید" maxlength="40" />
                        </div>
                        <?php if ($product && !empty($product['unit_type'])): ?>
                            <div class="one">
                                <div class="label-form mb5 fs14">تعداد <?=$product['unit_type']?> </div>
                                <input type="text" class="checkInput" value="<?= $product_cart['unit_qty'] ?>" name="unit_qty" placeholder="تعداد عدد یا دانه را وارد نمایید" maxlength="40" />
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="title-line m-auto">
                        <span class="color-tow fs14 color-green">اطلاعات تکمیلی</span>
                        <hr class="hr">
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">تخفیف به این دوا</div>
                            <input type="text" name="discount" value="<?= ($product_cart['discount']) ? number_format($product_cart['discount']) : '' ?>" class="discount" placeholder="تخفیف را وارد نمائید" />
                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
            <a href="<?= url('add-product-inventory') ?>" class="color text-underline d-flex justify-center fs14">برگشت</a>
        </div>
        <!-- end page content -->

    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>