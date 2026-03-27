    <?php
    $title = 'ویرایش: ' . $product['product_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/error.php');
    ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> ویرایش محصول: <?= $product['product_name'] ?>
            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
        </div>
        <?php
        $help_title = _help_title;
        $help_content = _help_desc;
        include_once('resources/views/helps/help.php');
        ?>
        <!-- start page content -->
        <div class="box-container">
            <div class="insert">
                <form action="<?= url('edit-product-store/' . $product['id']) ?>" method="POST" enctype="multipart/form-data">

                    <div class="inputs d-flex mb30">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام محصول <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= $product['product_name'] ?>" name="product_name" placeholder="نام محصول را وارد نمایید" maxlength="124" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب دسته بندی <?= _star ?></div>
                            <?php
                            $selectedCatName = $product['product_cat'];
                            ?>
                            <select name="product_cat" class="form-control">
                                <?php foreach ($product_cats as $cat): ?>
                                    <?php if ($cat['product_cat_name'] == $selectedCatName): ?>
                                        <option value="<?= htmlspecialchars($cat['product_cat_name']) ?>" selected><?= htmlspecialchars($cat['product_cat_name']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php foreach ($product_cats as $cat): ?>
                                    <?php if ($cat['product_cat_name'] != $selectedCatName): ?>
                                        <option value="<?= htmlspecialchars($cat['product_cat_name']) ?>"><?= htmlspecialchars($cat['product_cat_name']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نوع بسته‌بندی یا واحد محصول <?= _star ?></div>
                            <?php
                            $selectedPackageType = $product['package_type'];
                            ?>
                            <select name="package_type" class="form-control">
                                <?php foreach ($products_units as $product_category): ?>
                                    <?php if ($product_category['product_unit'] == $selectedPackageType): ?>
                                        <option value="<?= htmlspecialchars($product_category['product_unit']) ?>" selected>
                                            <?= htmlspecialchars($product_category['product_unit']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                                <?php foreach ($products_units as $product_category): ?>
                                    <?php if ($product_category['product_unit'] != $selectedPackageType): ?>
                                        <option value="<?= htmlspecialchars($product_category['product_unit']) ?>">
                                            <?= htmlspecialchars($product_category['product_unit']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">تعداد / مقدار در هر واحد <?= _star ?> </div>
                            <input type="text" name="quantity_in_pack" value="<?= $product['quantity_in_pack'] ?>" placeholder="مقدار داخل هر بسته را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت خرید هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= number_format($product['package_price_buy']) ?>" name="package_price_buy" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= number_format($product['package_price_sell']) ?>" name="package_price_sell" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب واحد کوچکتر</div>
                            <?php $selectedUnitType = $product['unit_type']; ?>
                            <select name="unit_type" class="form-control">
                                <option disabled <?= empty($selectedUnitType) ? 'selected' : '' ?>>انتخاب واحد خرید و فروش</option>
                                <?php foreach ($products_units as $cat): ?>
                                    <option value="<?= $cat['product_unit'] ?>" <?= ($cat['product_unit'] == $selectedUnitType) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['product_unit']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="title-line m-auto">
                        <span class="color-tow fs14">جزئیات قیمت محصول</span>
                        <hr class="hr">
                    </div>


                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت خرید هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= number_format($unitPrices['buy']) ?>" name="package_price_buy" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= number_format($unitPrices['buy']) ?>" name="package_price_sell" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex mb30">
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت خرید واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= old('unit_price_buy') ?>" name="unit_price_buy" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= old('unit_price_sell') ?>" name="unit_price_sell" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex ">
                        <label class="d-flex align-center">
                            <input type="checkbox" class="checkbox-select mt15" id="independent_unit_price">
                            <div class="label-form mb5 fs16">
                                قیمت عدد مستقل باشد
                            </div>
                        </label>
                    </div>










                    <div class="title-line m-auto">
                        <span class="color-tow fs14">اطلاعات تکمیلی</span>
                        <hr class="hr">
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"><?= $product['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب عکس محصول</div>
                            <input type="file" id="image" name="product_image" accept="image/*">
                        </div>
                    </div>
                    <div id="imagePreview">
                        <img src="" class="img" alt="">
                    </div>
                    <div>
                        <img src="<?= ($product['product_image'] ? asset('public/images/products/' . $product['product_image']) : asset('public/assets/img/empty.png')) ?>" class="img" alt="logo">
                    </div>
                    <div class="fs11">تصویر فعلی</div>

                    <!-- <div class="inputs d-flex">
                        <label class="d-flex align-center">
                            <input type="checkbox" class="checkbox-select mt15" name="award" <?= ($product['award'] == 1) ? 'checked' : '' ?>>
                            <div class="label-form mb5 fs16">محصول جایزه‌دار</div>
                        </label>
                    </div> -->

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
            <a href="<?= url('products') ?>">
                <div class="color fs14 text-underline center p5">برگشت</div>
            </a>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>