    <!-- start sidebar -->
    <?php
    $title = 'ثبت معاش کارمندان';
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php');
    include_once('resources/views/scripts/datePicker.php');
    include_once('resources/views/scripts/search-items.php');
    ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ثبت معاش کارمندان</div>

        <!-- start page content -->
        <div class="mini-container">
            <div class="insert">
                <form action="<?= url('salary-store') ?>" method="POST">

                    <!-- search seller -->
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">جستجوی کارمند <?= _star ?> </div>
                            <input type="hidden" name="selected_item_id" id="selected_item_id" />
                            <div id="user_details"></div>
                            <input type="text" class="checkInput"
                                name="search_input" id="search_input"
                                placeholder="نام را جستجو نمایید" autocomplete="off"
                                data-ajax-url="<?= url('search-employee') ?>"
                                data-result-container="#backResponse"
                                data-display-fields="employee_name"
                                data-item-name="item"
                                autofocus />
                        </div>
                        <ul class="search-back d-none" id="backResponse"></ul>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">مبلغ پرداختی معاش <?= _star ?></div>
                            <input type="text" name="amount" class="checkInput" placeholder="مبلغ پرداختی را وارد نمایید" maxlength="40" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">تاریخ پرداخت <?= _star ?></div>
                            <input type="text" class="checkInput date-view" data-jdp placeholder="تاریخ پرداخت را وارد نمایید" maxlength="40" />
                            <input type="hidden" name="date" class="date-server" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"> منبع پرداخت <?= _star ?></div>
                            <select name="source">
                                <option disabled>منبع پرداخت پول را انتخاب کنید</option>
                                <option value="1" selected>پرداخت از دخل</option>
                                <option value="2">پرداخت از صندوق اصلی</option>
                            </select>
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"></textarea>
                        </div>
                    </div>
                    <?= $this->branchSelectField(); ?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>