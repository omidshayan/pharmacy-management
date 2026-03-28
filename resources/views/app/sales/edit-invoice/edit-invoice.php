    <?php
    $title = 'ویرایش بِل فروش';
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/live-search-seller.php');
    include_once('public/alerts/error.php');
    include_once('public/alerts/toastr.php');
    include_once('public/alerts/check-inputs.php');
    include_once('resources/views/scripts/search-items.php');
    include_once('resources/views/scripts/datePicker.php');

    $helps = [
        'close-form' => [
            'title' => 'راهنمای بخش بستن بِل',
            'content' => '1. اگر مشتری انتخاب نشود، به صورت خودکار مشتری عمومی انتخاب می شود.
            </br>
            2. اگر منبع پرداخت انتخاب نشود، به صورت خودکار دخل انتخاب می شود.
            '
        ],
        'items' => [
            'title' => 'لیست بِل',
            'content' => '1. تعداد بسته و تعداد عدد نمی تواند هر دو 0 باشن.
            </br>
            2. دواهای که تعداد عددی نداشته باشن را نمی توان تغییر داد و همیشه 0 هستن
            '
        ],
    ];
    include_once('resources/views/helps/modal-help.php');
    ?>

    <div id="alert" class="added-alert" style="display: none;"></div>
    <!-- loading and overlay -->
    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- barcode -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script> -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title">
            <span class="twinkle-b"></span>
            <span class="fs22">ویـــرایـــش فاکــتـور فـــروش</span>
            <span class="twinkle-b"></span>

            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
        </div>
        <?php
        $help_title = _help_title;
        $help_content = _help_desc;
        include_once('resources/views/helps/help.php');
        include_once('resources/views/helps/product-modal-sale.php');
        ?>

        <!-- modal -->
        <div id="openModal-cont"></div>

        <a href="<?= url('sales') ?>" class="addBtn mt15 d-block center">بِل‌های فروش</a>

        <div class="modal-overlay-cont" id="modalOverlay-cont">
            <div class="modal-cont border">
                <div class="colse-btn-modal d-flex align-center">
                    <button class="close-btn-cont" id="closeModal-cont">✕</button>
                    <span class="mr10 bold">ویـــرایـــش فاکــتـور فـــروش</span>

                    <?= $this->modalItems('sales') ?>

                </div>

                <!-- loading -->
                <div class="show-hide d-none p10"></div>

                <hr class="hr">

                <!-- js -->
                <?php include_once('resources/views/app/sales/edit-invoice/edit-script.php'); ?>

                <!-- modal data -->
                <div class="product-modal d-flex mt15 flex-start">

                    <!-- add product and lists -->
                    <div class="product-modal-right">

                        <!-- search product section -->
                        <div class="search-content p2 m0 w100">
                            <div class="insert">
                                <div class="inputs d-flex">
                                    <div class="one">
                                        <div class="label-form mb5 fs14">جستجوی دوا <?= _star ?></div>
                                        <a href="#" class="color search-icon-database-s top23">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-10 search-icon w17">
                                                <circle cx="11" cy="11" r="8"></circle>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                            </svg>
                                        </a>

                                        <input
                                            type="text"
                                            class="p5 fs15 input w100 live-search"
                                            placeholder="جستجوی دوا..."
                                            autofocus
                                            id="search_input"

                                            data-ajax-url="<?= url('search-product-sale') ?>"
                                            data-result-container=".live-search-result"

                                            data-item-name="item"

                                            data-display-fields="product_name"

                                            data-hidden-fields="product_name, product_id, unit_type, package_price_sell, package_price_buy, unit_price_sell, unit_price_buy, quantity_in_pack" autocomplete="off" />

                                        <ul class="search-back t34 d-none live-search-result"></ul>
                                    </div>
                                </div>

                                <input type="hidden" id="selected_item_product_name" name="product_name" />
                                <input type="hidden" id="selected_item_product_id" name="product_id" />
                                <input type="hidden" id="selected_item_unit_type" name="unit_type" />
                                <input type="hidden" id="selected_item_package_price_buy" name="package_price_buy" />
                                <input type="hidden" id="selected_item_package_price_sell" name="package_price_sell" />
                                <input type="hidden" id="selected_item_unit_price_buy" name="unit_price_buy" />
                                <input type="hidden" id="selected_item_unit_price_sell" name="unit_price_sell" />
                                <input type="hidden" id="selected_item_quantity_in_pack" name="quantity_in_pack" />
                                <input type="hidden" id="select_invoice_id" name="invoice_id" value="<?= $sale_invoice['id'] ?>" />
                            </div>
                        </div>
                        <!-- end search product section -->

                        <!-- products list table -->
                        <div class="content-container mt20 pr">

                            <?= $this->helpSection('help_status', 'items') ?>

                            <table class="fl-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نام دوا</th>
                                        <th>تعداد بسته</th>
                                        <th>تعداد عدد</th>
                                        <th>تعداد کل</th>
                                        <th>قیمت فروش بسته</th>
                                        <th>قیمت فروش واحد</th>
                                        <th>قیمت کل</th>
                                        <th>جزئیات</th>
                                        <th>حذف</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items-tbody">

                                </tbody>
                            </table>
                        </div>
                        <!-- end product infos -->

                    </div>

                    <!-- close invoice -->
                    <div class="product-modal-left">
                        <div class="content-container border center pr">
                            <!-- help -->
                            <?= $this->helpSection('help_status', 'close-form') ?>

                            <div class="total-box show-hide">
                                مجموع بِل:
                                <span id="grand-total">0</span>
                                <?= _afghani ?>
                            </div>

                            <form action="<?= url('close-edit-invoice-store') ?>" method="POST" enctype="multipart/form-data">
                                <div class="insert show-hide">
                                    <!-- search customer -->
                                    <div class="inputs d-flex">
                                        <div class="one">
                                            <div class="label-form fs14">جستجوی مشتری </div>
                                            <?php
                                            $seller = $seller ?? ['id' => '', 'user_name' => ''];
                                            ?>
                                            <input type="hidden" name="seller_id" id="seller_id" value="<?= !empty($seller['id']) ? $seller['id'] : '' ?>">
                                            <div id="user_details"></div>
                                            <input type="text" class="checkInput input-disable" name="search_seller" id="search_seller" value="<?= !empty($user['user_name']) ? $user['user_name'] : '' ?>" placeholder="نام مشتری را جستجو نمایید" autocomplete="off" />
                                        </div>
                                        <ul class="search-back d-none" id="backResponseSeller">
                                            <li class="resSel search-item color" role="option"></li>
                                        </ul>

                                        <?= $this->branchSelectField(); ?>
                                    </div>

                                    <div class="inputs d-flex">
                                        <div class="one">
                                            <div class="label-form mb5 fs14">مجموع پرداخت بِل</div>
                                            <input type="number" id="paid_amount" class="input-disable" name="paid_amount" placeholder="قیمت کل" autocomplete="off" />
                                        </div>
                                    </div>
                                    <div class="inputs d-flex">
                                        <div class="one">
                                            <div class="label-form mb5 fs14"> انتخاب ورودی پول</div>
                                            <select name="source" id="source" disabled>
                                                <option disabled>مبلغ پرداختی کجا وارد شود</option>
                                                <?php
                                                foreach ($cash_boxes as $cash_box) { ?>
                                                    <option value="<?= $cash_box['id'] ?>"><?= $cash_box['name'] ?></option>
                                                <?php }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-title color-orange show-hide">جزئیات بیشتر</div>
                                <div class="accordion-content">
                                    <div class="child-accordioin">
                                        <div class="insert">
                                            <div class="inputs d-flex">
                                                <div class="one">
                                                    <div class="label-form mb5 fs14">تخفیف کلی بِل</div>
                                                    <input type="number" name="discount" class="discount" placeholder="تخفیف را وارد نمائید" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="inputs d-flex">
                                                <div class="one">
                                                    <div class="label-form mb5 fs14">تاریخ بِل رو انتخاب کنید <?= _star ?></div>
                                                    <input type="text" data-jdp class="form-control date-view checkInput" placeholder="تاریخ را انتخاب کنید">
                                                    <input type="hidden" class="date-server checkInput" name="date">
                                                </div>
                                            </div>
                                            <div class="inputs d-flex">
                                                <div class="one">
                                                    <div class="label-form mb5 fs14">توضیحات</div>
                                                    <textarea name="description" placeholder="توضیحات را وارد نمایید"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="invoice_id" id="invoice_id" value="0">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                                <input type="hidden" id="total-price" name="total_price" value="0>">
                                <input type="hidden" name="invoice_id" value="<?=$sale_invoice['id']?>">

                                <!-- loading -->
                                <div id="submit-loading" class="d-none text-center p-2">
                                    <div class="spinner-border spinner-border-sm text-primary"></div>
                                </div>

                                <div class="inputs m0 mr30 mt15 d-none show-hide">
                                    <div class="text-right invoice-print">
                                        <input type="checkbox" class="invoice-print" checked id="invoice-print" name="invoice_print">
                                        <label for="invoice-print" class="fs14">بِل چاپ شود</label>
                                    </div>
                                </div>

                                <input type="submit" value="بــستن فــاکتـور" class="btn p5 fs15 bold d-none show-hide">
                            </form>
                        </div>

                    </div>

                </div>

            </div>
            <!-- end modal data -->

        </div>
    </div>
    <?php include_once('resources/views/scripts/modal.php'); ?>
    <!-- end modal -->

    <!-- check for print -->
    <?php include_once('resources/views/app/prints/invoices-print/invoice-frame.php'); ?>


    <!-- active select cash -->
    <script>
        document.getElementById('paid_amount').addEventListener('input', function() {
            const val = this.value.trim();
            const source = document.getElementById('source');

            if (val !== "" && Number(val) > 0) {
                source.disabled = false;
            } else {
                source.disabled = true;
                source.value = "";
            }
        });
    </script>

    <?php include_once('resources/views/layouts/footer.php') ?>