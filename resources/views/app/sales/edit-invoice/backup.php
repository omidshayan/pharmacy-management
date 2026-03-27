    <!-- start sidebar -->
    <?php
    $title = 'ویرایش بِل فروش شماره: ' . $sale_invoice['id'];
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/sales/search-product-for-sale.php');
    include_once('resources/views/scripts/live-search-seller.php');
    include_once('public/alerts/toastr.php');
    include_once('public/alerts/check-inputs.php');
    $invoice_number = '';
    $invoice_date = '';
    $formatted_date = '';

    if ($sale_invoice) {
        $formatted_date = $this->convertDateForBarcode($sale_invoice['created_at']);
        $invoice_number = $sale_invoice['id'];
        $invoice_date = jdate('Y/m/d', strtotime($sale_invoice['created_at']));
    }
    if (isset($invoice_print)) {
    }
    ?>
    <!-- end sidebar -->

    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- barcode -->
    <script src="<?= asset('public/assets/js/barcode.js') ?>"></script>

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> ویرایش بِل فروش شماره: <?= $sale_invoice['id'] ?>
            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
        </div>
        <?php
        $help_title = _help_title;
        $help_content = _help_desc;
        include_once('resources/views/helps/help.php');
        ?>

        <!-- main products infos -->
        <div class="d-none producInfos">
            <span class="color-tow fs14">
                نوع بسته: <span class="color-green pro_type fs15 bold"></span>
                <span class="color-green fs15 bold">
                    (داخل هر <span class="pro_type"></span> <span class="pro_quantity"></span> عدد)
                </span>
            </span>
            <div class="fs14 color-tow">
                موجودی فعلی:
                <span class="color-green fs15 bold">
                    <span class="current_inventory_pack"></span> <span class="pro_type"></span> و
                    <span class="current_unit"></span> <span class="uni_type color-green"></span>
                </span>
            </div>
            <div class="fs14 color-tow">
                موجودی کل به <span class="uni_type"></span>:
                <span class="color-green fs15 bold">
                    <span class="current_inventory"></span> <span class="uni_type"></span>
                </span>
            </div>
        </div>

        <!-- start page content -->
        <div class="search-content scroll-not">
            <div class="insert">
                <form action="<?= url('product-sale-store') ?>" method="POST">
                    <!-- search product -->
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">جستجوی محصول <?= _star ?> </div>
                            <input type="hidden" id="product_id">
                            <div id="user_details"></div>
                            <input type="text" class="checkInput" name="product_name" id="product_name" placeholder="نام محصول را جستجو نمایید" autocomplete="off" autofocus />
                        </div>
                        <ul class="search-back d-none" id="backResponse">
                            <li class="res search-item color" role="option"></li>
                        </ul>
                    </div>

                    <div class="d-none my-form">

                        <!-- search seller -->
                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">جستجوی مشتری <?= _star ?> </div>
                                <?php
                                $seller = $seller ?? ['id' => '', 'user_name' => 'عمومی'];
                                ?>
                                <input type="hidden" name="seller_id" id="seller_id" value="<?= !empty($seller['id']) ? $seller['id'] : '' ?>">
                                <div id="user_details"></div>
                                <input type="text" class="checkInput" name="search_seller" value="<?= !empty($seller['user_name']) ? $seller['user_name'] : 'عمومی' ?>" id="search_seller" placeholder="نام مشتری را جستجو نمایید" autocomplete="off" />
                            </div>
                            <ul class="search-back d-none" id="backResponseSeller">
                                <li class="resSel search-item color" role="option"></li>
                            </ul>
                        </div>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">تعداد <span class="pro_type color-green fs18"></span> </div>
                                <input type="number" name="package_qty" placeholder="تعداد را وارد نمایید" maxlength="40" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تعداد <span class="uni_type color-green fs18"></span> </div>
                                <input type="number" name="unit_qty" placeholder="تعداد عدد یا دانه را وارد نمایید" maxlength="40" />
                            </div>
                        </div>
                        <span class="quantity"> تعداد عددی: </span>

                        <div class="title-line m-auto">
                            <span class="color-tow fs14">قیمت محصول</span>
                            <hr class="hr">
                        </div>

                        <div class="inputs d-flex mb30">
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت فروش هر <span class="color-green pro_type"></span></div>
                                <input type="text" class="checkInput" name="package_price_sell" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت فروش هر <span class="color-green uni_type"></span></div>
                                <input type="text" class="checkInput-not" name="unit_price_sell" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                            </div>
                        </div>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت خرید هر <span class="color-green pro_type"></span></div>
                                <input class="cursor-not" type="password" name="package_price_buy" readonly id="package_price_buy" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                                <span class="show-eye cursor-p" onclick="togglePasswordVisibility()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                        <path fill="#878787" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت خرید هر <span class="color-green uni_type"></span></div>
                                <input class="cursor-not" type="password" name="unit_price_buy" readonly id="unit_price_buy" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                            </div>
                        </div>

                        <div class="title-line m-auto">
                            <span class="color-tow fs14 color-tow">اطلاعات تکمیلی</span>
                            <hr class="hr">
                        </div>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت کل </div>
                                <input type="text" class="all_price" name="item_total_price" placeholder="قیمت کل" readonly />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تخفیف به این محصول</div>
                                <input type="text" name="sale_discount" class="discount" placeholder="تخفیف را وارد نمائید" />
                            </div>
                        </div>

                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                        <input type="hidden" name="quantity_in_pack" value="" />
                        <input type="hidden" name="quantity" value="" />
                        <input type="hidden" name="product_id" />
                        <input type="submit" id="submit" value="ثبت" class="btn" />

                    </div>
                </form>
            </div>
        </div>
        <!-- end page content -->

        <!-- table cart list -->
        <div class="content-container mb30">
            <?php
            if ($sale_invoice == false) { ?>
                <div class="center color-orange fs12">
                    <i class="fa fa-comment"></i>
                    لیست بِل خالی است!
                </div>
            <?php } else { ?>
                <div class="mb10 fs14 d-flex"> اقلام بِل شماره: <?= $sale_invoice['id'] ?>
                    <div class="mr30 bold"> مشتری: <span><?= ($seller) ? $seller['user_name'] : 'عمومی' ?></span></div>
                    <div class="mr30 bold">
                        <span><?= isset($total_debt['debtor']) ? 'حساب قبلی: ' . number_format($total_debt['debtor']) : '' ?></span>
                    </div>
                </div>
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام محصول</th>
                            <th>تعداد بسته</th>
                            <th>تعداد عدد</th>
                            <th>تعداد کل</th>
                            <th>قیمت واحد</th>
                            <th>قیمت کل</th>
                            <th>ویرایش</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $number = 1;
                        foreach ($cart_lists as $item) {
                        ?>
                            <tr>
                                <td class="color-orange"><?= $number ?></td>
                                <td><?= $item['product_name'] ?></td>
                                <td><?= $item['package_qty'] ?></td>
                                <td><?= $item['unit_qty'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['unit_price_sell']) ?></td>
                                <td><?= number_format($item['item_total_price']) ?></td>

                                <td>
                                    <a href="<?= url('edit-sale-product-cart/' . $item['id']) ?>" class="color-orange flex-justify-align">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </a>
                                </td>

                                <td>
                                    <a href="<?= url('delete-sale-product-cart/' . $item['id']) ?>" class="delete-product flex-justify-align">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512">
                                            <path fill="#ff0000" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z" />
                                        </svg>
                                    </a>
                                </td>

                                <!-- <td>
                                    <a href="<?= url('product-cat-details/' . $item['id']) ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" class="color-orange" />
                                        </svg>
                                    </a>
                                </td> -->

                            </tr>
                        <?php
                            $number++;
                        }
                        ?>
                    </tbody>
                    <tbody></tbody>
                </table>
                <div class="flex-justify-align mt20 paginate-section">
                    <div class="table-info fs12">تعداد کل: <?= count($cart_lists) ?></div>
                </div>
            <?php }
            ?>

        </div>
        <!-- end table cart list -->

        <!-- close invoice form -->
        <?php
        if ($sale_invoice == false) { ?>
        <?php } else { ?>
            <div class="content-container">
                <div class="mb10 fs14 color-orange">
                    <?php if (!empty($cart_lists) && isset($cart_lists[0]['total_price'])): ?>
                        مجموع بِل: <?= number_format($cart_lists[0]['total_price']) ?>
                        <span class="fs12">افغانی</span>
                    <?php else: ?>
                        <span>بِل شماره <span><?= $sale_invoice['id'] ?></span> باز است، اما لیست آن خالی است! <a href="<?= url('delete-sale-invoice/' . $sale_invoice['id']) ?>" class="color-red text-underline delete-invoice">حذف بِل</a></span>
                    <?php endif; ?>
                </div>

                <div class="insert">
                    <form action="<?= url('close-sale-inventory-store') ?>" method="POST" enctype="multipart/form-data">
                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">پرداختی</div>
                                <input type="number" class="all_price" name="sale_paid_amount" placeholder="پرداختی این بِل روا وارد نمائید" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تخفیف کلی بِل</div>
                                <input type="number" name="sale_discount" class="sale_discount" placeholder="تخفیف را وارد نمائید" />
                            </div>
                        </div>
                        <div class="inputs d-flex">
                            <!-- <div class="one">
                                <div class="label-form mb5 fs14">مبلغ پرداختی</div>
                                <input type="text" name="paid_amount" placeholder="مبلغ پرداختی را وارد نمائید" />
                            </div> -->

                            <div class="one">
                                <div class="label-form mb5 fs14">تاریخ بِل رو انتخاب کنید</div>
                                <input type="hidden" class="d-none dateInvoice" name="sale_invoice_date" autofocus>
                                <input type="text" class="start cursor-p checkInput" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">توضیحات</div>
                                <textarea name="sale_inv_description" placeholder="توضیحات را وارد نمایید"></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="quantity_in_pack" value="" />
                        <input type="hidden" name="seller_id" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['seller_id'])) ? $cart_lists[0]['seller_id'] : '' ?>">
                        <input type="hidden" name="invoice_id" value="<?= $sale_invoice['id'] ?>" />
                        <input type="hidden" name="total_price" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['total_price'])) ? $cart_lists[0]['total_price'] : '0' ?>">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                        <?php if (!empty($cart_lists) && isset($cart_lists[0]['total_price'])): ?>
                            <div class="text-right invoice-print">
                                <input type="checkbox" class="invoice-print" checked id="invoice-print" name="invoice_print">
                                <label for="invoice-print" class="fs14">بِل چاپ شود</label>
                            </div>
                            <input type="submit" value="بستن فـاکـتـور" class="btn bold" id="close-invoice" />
                        <?php else: ?>
                            <p class="fs12 color-orange">لیست بِل خالی است</p>
                        <?php endif; ?>
                    </form>
                </div>
            <?php }
            ?>
            </div>
            <!-- end close invoice form -->
    </div>
    <!-- End content -->

    <!-- empty input search users and invoice date -->
    <script>
        $(document).ready(function() {
            let inputPackageQty = $('input[name="search_seller"]');
            inputPackageQty.on('focus', function() {
                if ($(this).val() === 'عمومی') {
                    $(this).val('');
                }
            });

            inputPackageQty.on('blur', function() {
                if ($(this).val() === '') {
                    $(this).val('عمومی');
                }
            });

            $(".start").pDatepicker({
                format: 'YYYY-MM-DD',
                autoClose: true,
                toolbox: {
                    calendarSwitch: {
                        enabled: true
                    }
                },
                observer: true,
                altField: '.dateInvoice'
            });

            document.querySelectorAll(".delete-product").forEach(function(element) {
                element.addEventListener("click", function(event) {
                    let confirmDelete = confirm("آیا از حذف محصول اطمینان دارید؟");
                    if (!confirmDelete) {
                        event.preventDefault();
                    }
                });
            });

            document.querySelectorAll(".delete-invoice").forEach(function(element) {
                element.addEventListener("click", function(event) {
                    let confirmDelete = confirm("آیا از حذف بِل اطمینان دارید؟");
                    if (!confirmDelete) {
                        event.preventDefault();
                    }
                });
            });

            document.querySelectorAll(".delete-all-products").forEach(function(element) {
                element.addEventListener("click", function(event) {
                    let confirmDelete = confirm("آیا از حذف همه بِلها اطمینان دارید؟");
                    if (!confirmDelete) {
                        event.preventDefault();
                    }
                });
            });


        });
    </script>

    <!-- show and hide buy price -->
    <script>
        function togglePasswordVisibility() {
            let input1 = document.getElementById("package_price_buy");
            let input2 = document.getElementById("unit_price_buy");

            input1.type = input1.type === "password" ? "text" : "password";
            input2.type = input2.type === "password" ? "text" : "password";
        }
    </script>


    <?php include_once('resources/views/layouts/footer.php') ?>