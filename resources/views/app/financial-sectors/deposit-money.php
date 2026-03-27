    <?php
    $title = 'پرداخت / دریافت پول';
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php');
    include_once('resources/views/scripts/datePicker.php');
    include_once('resources/views/scripts/live-search-items.php');
    ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title print-none">پرداخت / دریافت پول</div>

        <!-- search box -->
        <div class=" flex-justify-align mb10">
            <div class="search-database-s flex-justify-align border"
                data-url="<?= url('search-itemm') ?>"
                data-input-id="search_user"
                data-result-id="backResponseSeller"
                data-field-name="customer_name"
                data-target-id="item_id">
                <a href="#" class="color search-icon-database-s">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-10 search-icon w17">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </a>
                <input type="text" class="p5 fs15 input w100 input-border checkInput" id="search_user" placeholder="جستجوی مشتری / کاربر" autofocus />
                <ul class="search-back d-none top40" id="backResponseSeller">
                    <li class="search-item color" role="option"></li>
                </ul>
            </div>
        </div>

        <!-- start page content -->
        <div class="box-container">
            <div class="insert">

                <form action="<?= url('deposit-money-store') ?>" method="POST" id="transactionForm">

                    <!-- select type -->
                    <div class="type-transaction m-auto" id="transactionTypeBox">
                        <div class="type-switch">
                            <input type="radio" name="type" id="type-receive" value="5" />
                            <input type="radio" name="type" id="type-pay" value="6" />
                            <span class="type-slider"></span>
                            <label for="type-receive">دریافت</label>
                            <label for="type-pay">پرداخت</label>
                        </div>
                    </div>
                    <?php include_once('resources/views/helps/radio-select.php'); ?>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">مبلغ پرداختی <?= _star ?></div>
                            <input type="number" class="checkInput" value="<?= old('amount') ?>" name="amount" autofocus placeholder="مبلغ پرداختی را وارد نمایید" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">نوع ارز <?= _star ?></div>
                            <select name="currency" class="checkSelect">
                                <option value="af">افغانی</option>
                            </select>
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"> منبع مالی <?= _star ?></div>
                            <select name="source">
                                <option disabled>مبلغ پرداختی کجا وارد شود</option>
                                <?php
                                foreach ($cash_boxes as $cash_box) { ?>
                                    <option value="<?= $cash_box['id'] ?>"><?= $cash_box['name'] ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">تاریخ را انتخاب نمایید</div>
                            <input type="hidden" class="d-none date-server" name="transaction_date" autofocus>
                            <input type="text" data-jdp class="cursor-p checkInput date-view" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات </div>
                            <textarea name="description" placeholder="توضیحات را وارد نمائید"></textarea>
                        </div>
                    </div>

                    <div class="inputs">
                        <div class="text-right invoice-print">
                            <input type="checkbox" class="invoice-print" id="invoice-print" name="invoice_print">
                            <label for="invoice-print" class="fs14">بِل تراکنش چاپ شود</label>
                        </div>
                    </div>

                    <input type="hidden" name="user_id" id="item_id">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>

        </div>

        <!-- end page content -->
    </div>
    <!-- End content -->

    <!-- check for print -->
    <?php include_once('resources/views/app/prints/financials/payment-frame.php'); ?>

    <!-- active select tag -->
    <script>
        document.getElementById('price_type').addEventListener('change', function() {
            let sourceSelect = document.getElementById('source');
            if (this.value == "2") {
                sourceSelect.disabled = false;
            } else {
                sourceSelect.disabled = true;
                sourceSelect.value = "1";
            }
        });
    </script>

    <?php include_once('resources/views/layouts/footer.php') ?>