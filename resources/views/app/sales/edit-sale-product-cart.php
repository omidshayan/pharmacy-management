    <!-- start sidebar -->
    <?php
    $title = 'ویرایش دوا: ' . $product_cart['product_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    ?>
    <!-- end sidebar -->

    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>


    <!-- Start content -->
    <div class="content">
        <div class="content-title"> ویرایش دوا: <?= $product_cart['product_name'] ?></div>

        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <div class="producInfos">
                    <h5 class="d-none product-name">نام: <span></span></h5>
                </div>

                <form action="<?= url('edit-sale-product-cart-store/' . $product_cart['id']) ?>" method="POST">
                    <!-- search purcharc -->
                    <!-- <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">جستجوی فروشنده <?= _star ?> </div>
                            <input type="hidden" name="seller_id" value="<?= isset($user['id']) && $user['user_name'] ? $user['id'] : 0 ?>" id="seller_id">
                            <div id="user_details"></div>
                            <input type="text" class="checkInput" value="<?= isset($user['user_name']) && $user['user_name'] ? $user['user_name'] : 'عمومی' ?>"
                                id="search_seller" placeholder="نام فروشنده را جستجو نمایید" autocomplete="off" />
                        </div>
                        <ul class="search-back d-none" id="backResponseSeller">
                            <li class="resSel search-item color" role="option"></li>
                        </ul>
                    </div> -->

                    <div class="title-line m-auto">
                        <span class="color-tow fs14">تعداد کارتن / بسته - عدد / دانه‌ای</span>
                        <hr class="hr">
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">تعداد بسته / کارتن </div>
                            <input type="text" class="checkInputGroup" value="<?= $product_cart['package_qty'] ?>" name="package_qty" placeholder="تعداد بسته یا کارتن را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">تعداد عدد / دانه </div>
                            <input type="text" class="checkInputGroup" value="<?= $product_cart['unit_qty'] ?>" name="unit_qty" placeholder="تعداد عدد یا دانه را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <!-- <div class="title-line m-auto">
                        <span class="color-tow fs14">قیمت دوا</span>
                        <hr class="hr">
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت خرید هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= number_format($product_cart['package_price_buy']) ?>" name="package_price_buy" placeholder="قیمت خرید هر بسته را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= number_format($product_cart['package_price_sell']) ?>" name="package_price_sell" placeholder="قیمت فروش هر را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex mb30">
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت خرید هر دانه / عدد <?= _star ?> </div>
                            <input type="text" class="" name="unit_price_buy" value="<?= number_format($product_cart['unit_price_buy']) ?>" placeholder="قیمت خرید هر دانه را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر دانه / عدد <?= _star ?> </div>
                            <input type="text" class="" name="unit_price_sell" value="<?= number_format($product_cart['unit_price_sell']) ?>" placeholder="قمیت فروش هر دانه را وارد نمایید" maxlength="40" />
                        </div>
                    </div> -->

                    <div class="title-line m-auto">
                        <span class="color-tow fs14 color-green">اطلاعات تکمیلی</span>
                        <hr class="hr">
                    </div>

                    <!-- <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">تخفیف به این دوا</div>
                            <input type="text" name="discount" value="<?= ($product_cart['discount']) ? number_format($product_cart['discount']) : '' ?>" class="discount" placeholder="تخفیف را وارد نمائید" />
                        </div>
                    </div> -->

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ویرایش" class="btn" />
                </form>
            </div>
            <a href="<?= url('add-sale') ?>" class="color text-underline d-flex justify-center fs14">برگشت</a>
        </div>
        <!-- end page content -->

    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>