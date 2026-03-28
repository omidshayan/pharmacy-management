    <?php
    $title = 'ویرایش: ' . $product['product_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php');

    // help sections
    $helps = [
        'add-product' => [
            'title' => 'راهنمای ثبت دوا جدید',
            'content' => '<hr class="hr mb5"><span class="color-orange">1.)</span> تعداد / مقدار در هر واحد: اگر این عدد 1 باشه یعنی دوا دارای واحد کوچکتر نیست و در این صورت انتخاب واحد کوچکتر غیر فعال است و نمی توانید انتخاب نمایید، اما اگر مقدار از یک بیشتر باشه انتخاب واحد کوچکتر فعال می شود و حتما باید یک واحد کوچکتر انتخاب نمایید تا دوا ثبت شود.
            </br>
            <span class="color-orange">2.)</span> اگر قیمت فروش بسته با قیمت فروش واحد (عددی) فرق می کرد، می تواند چک باکس (قیمت عدد مستقل باشد) را فعال نمایید و قیمت فروش واحد را تغییر دهید.
            '
        ]
    ];
    include_once('resources/views/helps/modal-help.php');
    ?>

    <!-- check price and quantity -->
    <script>
        $(document).ready(function() {

            // calulate inputs
            function calculateUnitPrices(changedInput) {
                let packageQty = parseFloat($('input[name="quantity_in_pack"]').val().replace(/,/g, '')) || 0;
                let packagePriceBuy = parseFloat($('input[name="package_price_buy"]').val().replace(/,/g, '')) || 0;
                let packagePriceSell = parseFloat($('input[name="package_price_sell"]').val().replace(/,/g, '')) || 0;
                let unitPriceBuy = parseFloat($('input[name="unit_price_buy"]').val().replace(/,/g, '')) || 0;
                let unitPriceSell = parseFloat($('input[name="unit_price_sell"]').val().replace(/,/g, '')) || 0;

                let independent = $('#independent_unit_price').is(':checked');

                if (changedInput === 'quantity_in_pack' && packageQty > 0) {

                    if (packagePriceBuy > 0) {
                        unitPriceBuy = packagePriceBuy / packageQty;
                        $('input[name="unit_price_buy"]').val(formatNumber(unitPriceBuy));
                    }

                    if (packagePriceSell > 0) {
                        unitPriceSell = packagePriceSell / packageQty;
                        $('input[name="unit_price_sell"]').val(formatNumber(unitPriceSell));
                    }
                }

                // change package price
                if (changedInput === 'package_price_buy' && packageQty > 0) {
                    unitPriceBuy = packagePriceBuy / packageQty;
                    $('input[name="unit_price_buy"]').val(formatNumber(unitPriceBuy));
                }

                if (changedInput === 'package_price_sell' && packageQty > 0) {
                    unitPriceSell = packagePriceSell / packageQty;
                    $('input[name="unit_price_sell"]').val(formatNumber(unitPriceSell));
                }

                // change unit price
                if (!independent) {

                    if (changedInput === 'unit_price_buy' && packageQty > 0) {
                        packagePriceBuy = unitPriceBuy * packageQty;
                        $('input[name="package_price_buy"]').val(formatNumber(packagePriceBuy));
                    }

                    if (changedInput === 'unit_price_sell' && packageQty > 0) {
                        packagePriceSell = unitPriceSell * packageQty;
                        $('input[name="package_price_sell"]').val(formatNumber(packagePriceSell));
                    }
                }
            }

            // cahnge inputs values
            $('input[name="quantity_in_pack"], input[name="package_price_buy"], input[name="package_price_sell"], input[name="unit_price_buy"], input[name="unit_price_sell"]').on('input', function() {
                // remove ,
                let rawValue = $(this).val().replace(/,/g, '');
                if (!isNaN(rawValue) && rawValue !== '') {
                    $(this).val(formatNumber(rawValue));
                }
                calculateUnitPrices($(this).attr('name'));
            });

            // format number
            function formatNumber(value) {
                let num = parseFloat(value);
                if (isNaN(num)) return '';

                let formatted = num.toFixed(2).replace(/\.00$/, '');
                return formatted.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });

        // remove , for send to server
        $(document).ready(function() {
            $('form').on('submit', function() {
                $('input[name="quantity_in_pack"], input[name="package_price_buy"], input[name="package_price_sell"], input[name="unit_price_buy"], input[name="unit_price_sell"]').each(function() {
                    let rawValue = $(this).val().replace(/,/g, '');
                    $(this).val(rawValue);
                });
            });
        });
    </script>

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
        <!-- start page content -->
        <div class="box-container pr">
            <div class="insert">
                <form action="<?= url('edit-product-store/' . $product['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= $this->helpSection('help_status', 'add-product') ?>

                    <div class="inputs d-flex mb30">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام دوا <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= $product['product_name'] ?>" name="product_name" placeholder="نام دوا را وارد نمایید" maxlength="124" />
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
                            <div class="label-form mb5 fs14">نوع بسته‌بندی یا واحد دوا <?= _star ?></div>
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

                        <?= $this->branchSelectField(); ?>
                    </div>

                    <div class="title-line m-auto">
                        <span class="color-tow fs14">جزئیات قیمت دوا</span>
                        <hr class="hr">
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت خرید هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= number_format($product['package_price_buy']) ?>" name="package_price_buy" placeholder="نام دوا را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= number_format($product['package_price_sell']) ?>" name="package_price_sell" placeholder="نام دوا را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex mb30">
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت خرید واحد <?= _star ?> </div>
                            <input type="text" value="<?= ($product['unit_price_buy']) ? number_format($product['unit_price_buy']) : null ?>" name="unit_price_buy" placeholder="قیمت خرید واحد را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر واحد <?= _star ?> </div>
                            <input type="text" value="<?= ($product['unit_price_sell']) ? number_format($product['unit_price_sell']) : null ?>" name="unit_price_sell" placeholder="قیمت خرید واحد را وارد نمایید" maxlength="40" />
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

                    <!-- ohter infos -->
                    <div class="accordion-title color-orange mb5">اطلاعات تکمیلی</div>
                    <div class="accordion-content">
                        <div class="child-accordioin">
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">بارکد</div>
                                    <input type="text" name="product_code" placeholder="بارکد را وارد نمایید" maxlength="40" />
                                </div>
                                <div class="one">
                                    <div class="label-form mb5 fs14">هشدار حداقل موجودی</div>
                                    <input type="number" name="reorder_point" placeholder="حداقل موجودی را وارد نمایید" maxlength="40" />
                                </div>
                            </div>
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">انتخاب کمپانی</div>
                                    <select name="unit_type">
                                        <option selected disabled>کمپانی را انتخاب نمایید</option>
                                        <?php
                                        foreach ($products_category as $product_category) { ?>
                                            <option value="<?= $product_category['product_category_name'] ?>"><?= $product_category['product_category_name'] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                                <div class="one">
                                    <div class="label-form mb5 fs14">توضیحات</div>
                                    <textarea name="description" placeholder="توضیحات را وارد نمایید"></textarea>
                                </div>
                            </div>
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">انتخاب عکس دوا</div>
                                    <input type="file" id="image" name="product_image" accept="image/*">
                                </div>
                            </div>
                            <div id="imagePreview" class="mb100">
                                <img src="" class="img" alt="">
                            </div>
                        </div>
                    </div>

                    <!-- check for attributes -->
                    <?php
                    if ($checkboxAtts || $textAtts) { ?>
                        <div class="accordion-title color-orange">ویژه‌گی‌های دوا</div>
                        <div class="accordion-content">
                            <div class="child-accordioin">

                                <?php if ($checkboxAtts) { ?>
                                    <div class="text-right p10 fs14">ویژه‌گی‌های انتخابی</div>
                                    <div class="inputs d-flex">
                                        <?php foreach ($checkboxAtts as $checkboxAtt) { ?>
                                            <label class="d-flex align-center m10">
                                                <input type="checkbox"
                                                    class="checkbox-select mt15"
                                                    name="attributes[<?= $checkboxAtt['id'] ?>]"
                                                    value="1"
                                                    <?= isset($attValuesMap[$checkboxAtt['id']]) ? 'checked' : '' ?>>
                                                <div class="label-form mb5 fs16">
                                                    <?= $checkboxAtt['att_name'] ?>
                                                </div>
                                            </label>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <?php if ($textAtts) { ?>
                                    <div class="text-right p10 fs14">ویژه‌گی‌های متن کوتاه</div>
                                    <div class="detailes-culomn d-flex bg-none">
                                        <div class="inputs">

                                            <?php foreach ($textAtts as $textAtt) { ?>
                                                <div class="title-detaile fs14">
                                                    <?= $textAtt['att_name'] ?>
                                                </div>

                                                <input type="text"
                                                    name="attributes[<?= $textAtt['id'] ?>]"
                                                    value="<?= $attValuesMap[$textAtt['id']] ?? '' ?>"
                                                    placeholder="<?= $textAtt['att_name'] ?> را وارد نمایید">
                                            <?php } ?>

                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    <?php }
                    ?>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ویــرایــش" class="btn" />
                </form>
            </div>
            <?= $this->back_link('products') ?>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <!-- check quantity in pack -->
    <script>
        const quantityInput = document.querySelector('input[name="quantity_in_pack"]');
        const unitSelect = document.querySelector('select[name="unit_type"]');
        const submitBtn = document.getElementById('submit');

        function checkUnitSelect() {
            let quantity = quantityInput.value;
            if (quantity != 1 && quantity !== "") {
                unitSelect.disabled = false;
            } else {
                unitSelect.disabled = true;
                unitSelect.classList.remove('checkSelect');
                unitSelect.selectedIndex = 0;
            }
        }

        checkUnitSelect();

        quantityInput.addEventListener('input', checkUnitSelect);

        submitBtn.addEventListener('click', function() {
            let quantity = quantityInput.value;
            if (quantity != 1 && unitSelect.selectedIndex === 0) {
                unitSelect.classList.add('checkSelect');
            } else {
                unitSelect.classList.remove('checkSelect');
            }
        });
    </script>

    <!-- enter 1 in input -->
    <script>
        $(document).ready(function() {
            let inputPackageQty = $('input[name="quantity_in_pack"]');
            inputPackageQty.on('focus', function() {
                if ($(this).val() === '1') {
                    $(this).val('');
                }
            });

            inputPackageQty.on('blur', function() {
                if ($(this).val() === '') {
                    $(this).val('1');
                }
            });
        });
    </script>

    <?php include_once('resources/views/layouts/footer.php') ?>