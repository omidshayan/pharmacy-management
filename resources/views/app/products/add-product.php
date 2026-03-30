    <?php
    $title = 'ثبت دوا جدید';
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
        <div class="content-title">ثبت دوا جدید
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
                <form action="<?= url('product-store') ?>" method="POST" enctype="multipart/form-data">
                    <?= $this->helpSection('help_status', 'add-product') ?>
                    <div class="inputs d-flex mb30">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام دوا <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= old('product_name') ?>" name="product_name" placeholder="نام دوا را وارد نمایید" maxlength="124" autofocus />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب کمپانی <?= _star ?></div>
                            <select name="product_cat" class="checkSelect">
                                <option selected disabled>کمپانی</option>
                                <?php
                                foreach ($product_cats as $product_cat) { ?>
                                    <option value="<?= $product_cat['product_cat_name'] ?>"><?= $product_cat['product_cat_name'] ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نوع دوا <?= _star ?></div>
                            <select name="package_type" class="checkSelect">
                                <option selected disabled>انتخاب نوع</option>
                                <?php
                                foreach ($products_units as $product_unit) { ?>
                                    <option value="<?= $product_unit['product_unit'] ?>"><?= $product_unit['product_unit'] ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">تعداد / مقدار در هر واحد <?= _star ?> </div>
                            <input type="text" name="quantity_in_pack" placeholder="تعداد داخل هر بسته را وارد نمایید" maxlength="40" value="1" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">انتخاب واحد کوچکتر</div>
                            <select name="unit_type">
                                <option selected disabled>انتخاب واحد خردید و فروش</option>
                                <?php
                                foreach ($products_units as $product_unit) { ?>
                                    <option value="<?= $product_unit['product_unit'] ?>"><?= $product_unit['product_unit'] ?></option>
                                <?php }
                                ?>
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
                            <input type="text" class="checkInput" value="<?= old('package_price_buy') ?>" name="package_price_buy" placeholder="قیمت را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر بسته / واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= old('package_price_sell') ?>" name="package_price_sell" placeholder="قیمت را وارد نمایید" maxlength="40" />
                        </div>
                    </div>

                    <div class="inputs d-flex mb30">
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت خرید واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= old('unit_price_buy') ?>" name="unit_price_buy" placeholder="قیمت خرید واحد را وارد نمایید" maxlength="40" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">قیمت فروش هر واحد <?= _star ?> </div>
                            <input type="text" class="checkInput" value="<?= old('unit_price_sell') ?>" name="unit_price_sell" placeholder="قیمت خرید واحد را وارد نمایید" maxlength="40" />
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

                                <div class="text-right p10 fs14">ویژه‌گی‌های انتخابی</div>
                                <div class="inputs d-flex">
                                    <?php foreach ($checkboxAtts as $checkboxAtt) { ?>
                                        <label class="d-flex align-center m10">
                                            <input type="checkbox"
                                                class="checkbox-select mt15"
                                                name="attributes[<?= $checkboxAtt['id'] ?>]"
                                                value="1" checked>
                                            <div class="label-form mb5 fs16">
                                                <?= $checkboxAtt['att_name'] ?>
                                            </div>
                                        </label>
                                    <?php } ?>
                                </div>

                                <div class="text-right p10 fs14">ویژه‌گی‌های متن کوتاه</div>
                                <div class="detailes-culomn d-flex bg-none">
                                    <div class="inputs">

                                        <?php foreach ($textAtts as $textAtt) { ?>
                                            <div class="title-detaile fs14"><?= $textAtt['att_name'] ?></div>

                                            <input type="text"
                                                name="attributes[<?= $textAtt['id'] ?>]"
                                                placeholder="<?= $textAtt['att_name'] ?> را وارد نمایید">
                                        <?php } ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php }
                    ?>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <!-- check quantity in pack -->
    <script>
        const quantityInput = document.querySelector('input[name="quantity_in_pack"]');
        const unitSelect = document.querySelector('select[name="unit_type"]');
        const submitBtn = document.getElementById('submit');

        // وضعیت اولیه (چون پیشفرض 1 است)
        unitSelect.disabled = true;

        // فعال / غیرفعال شدن هنگام تغییر مقدار
        quantityInput.addEventListener('input', function() {

            if (this.value != 1 && this.value !== "") {
                unitSelect.disabled = false;
            } else {
                unitSelect.disabled = true;
                unitSelect.classList.remove('checkSelect');
                unitSelect.selectedIndex = 0; // برگشت به حالت پیشفرض
            }
        });

        // هنگام ارسال فرم
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