    <?php
    $title = 'برگشت از خرید';
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/search.php');
    include_once('resources/views/scripts/live-search-seller.php');
    include_once('public/alerts/toastr.php');
    include_once('public/alerts/check-inputs.php');
    include_once('resources/views/scripts/datePicker.php');
    ?>

    <!-- spinner loading -->
    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Start content -->
    <div class="content">
        <div class="content-title pr">
            <span class="twinkle-s"></span>
            <span class="fs22">بــــــــرگــــشــتــــــ از فـــروش</span>
            <span class="twinkle-s"></span>
            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
        </div>
        <?php
        $help_title = _help_title;
        $help_content = _help_desc;
        include_once('resources/views/helps/help.php');
        ?>

        <!-- name product in title page -->
        <div class="producInfos">
            <div class="d-none product-name">نام محصول: <span></span></div>
            <div class="d-none now-inventory">موجودی فعلی: <span class="quan"></span> <span class="unitType"></span> </div>
        </div>

        <div class="search-content scroll-not">
            <div class="insert">
                <form action="<?= url('return-from-sale-store') ?>" method="POST" enctype="multipart/form-data" id="myForm">

                    <!-- search product -->
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">جستجوی محصول <?= _star ?> </div>
                            <input type="hidden" name="product_id" id="item_id">
                            <input type="text"
                                class="checkInput"
                                name="product_name"
                                id="item_name"
                                placeholder="نام محصول را جستجو نمایید"
                                autocomplete="off"
                                autofocus
                                data-search-url="<?= url('return-search-product') ?>"
                                data-item-info-url="<?= url('get-product-infos-return') ?>" />
                        </div>
                        <ul class="search-back d-none" id="backResponse">
                            <li class="res search-item color" role="option"></li>
                        </ul>
                    </div>


                    <div class="d-none my-form">

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
                            <?= $this->branchSelectField(); ?>
                        </div>

                        <div class="title-line m-auto">
                            <span class="color-tow fs14">تعداد <span class="packageType"></span> / <span class="unitType"></span></span> - تعداد در هر <span class="packageType"></span>: <span class="qip"></span>
                            <hr class="hr">
                        </div>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">تعداد <span class="packageType"></span> </div>
                                <input type="number" id="packageCount" class="checkInputGroup" name="package_qty" placeholder="تعداد بسته یا کارتن را وارد نمایید" maxlength="40" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تعداد <span class="unitType"></span> </div>
                                <input type="number" id="unitCount" class="checkInputGroup" name="unit_qty" placeholder="تعداد عدد یا دانه را وارد نمایید" maxlength="40" />
                            </div>
                        </div>
                        <span class="quantity"></span>

                        <div class="title-line m-auto">
                            <span class="color-tow fs14">قیمت محصول</span>
                            <hr class="hr">
                        </div>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت خرید هر بسته / واحد <?= _star ?> </div>
                                <input type="text" class="checkInput" name="package_price_buy" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت فروش هر بسته / واحد <?= _star ?> </div>
                                <input type="text" class="checkInput" name="package_price_sell" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                            </div>
                        </div>

                        <div class="inputs d-flex mb30">
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت خرید هر دانه / عدد <?= _star ?> </div>
                                <input type="text" name="unit_price_buy" disabled />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت فروش هر دانه / عدد <?= _star ?> </div>
                                <input type="text" name="unit_price_sell" disabled />
                            </div>
                        </div>

                        <div class="title-line m-auto">
                            <span class="color-tow fs14 color-green">اطلاعات تکمیلی</span>
                            <hr class="hr">
                        </div>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت کل </div>
                                <input type="text" class="all_price" name="item_total_price" placeholder="قیمت کل" readonly />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تخفیف به این محصول</div>
                                <input type="text" name="discount" class="discount" placeholder="تخفیف را وارد نمائید" />
                            </div>
                        </div>

                        <?php
                        if ($expire_date['expiration_date'] == 1) { ?>
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">تاریخ انقضا</div>
                                    <input type="hidden" class="d-none dateExpire" name="expiration_date" autofocus>
                                    <input type="text" class="expire_date cursor-p checkInput" />
                                </div>
                            </div>
                        <?php }
                        ?>

                        <!-- <div class="title-line m-auto">
                            <span class="color-tow fs14 color-orange">جزئیات پرداخت</span>
                            <hr class="hr">
                        </div> -->

                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                        <input type="hidden" name="quantity_in_pack" value="" />
                        <input type="hidden" name="quantity" value="" />
                        <input type="submit" id="submit" value="ثبت" class="btn" />

                    </div>







                </form>
            </div>
        </div>
        <!-- end page content -->

        <!-- table cart list -->
        <?php
        if ($return) { ?>

            <div class="content-container mb30">
                <div class="mb10 fs14"> اقلام برگشت از فروش، شماره: <?= $return['id'] ?> - فروشنده: <?= $user['user_name'] ?></div>
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام محصول</th>
                            <th>تعداد بسته</th>
                            <th>تعداد عدد</th>
                            <th>تعداد کل</th>
                            <th>قیمت خرید واحد</th>
                            <th>قیمت کل</th>
                            <!-- <th>ویرایش</th> -->
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $number = 1;
                        foreach ($cart_lists as $item) {
                            $unitPrices = $this->calculateUnitPrices($item);
                        ?>
                            <tr>
                                <td class="color-orange"><?= $number ?></td>
                                <td><?= $item['product_name'] ?></td>
                                <td><?= $item['package_qty'] ?? 0 ?></td>
                                <td><?= $item['unit_qty'] ?? 0 ?></td>
                                <td><?= $item['quantity'] ?? 0 ?></td>
                                <td><?= $this->formatNumber($unitPrices['buy'], 2) ?></td>
                                <td><?= number_format($item['item_total_price']) ?></td>

                                <!-- <td>
                                    <a href="<?= url('edit-product-cart/' . $item['id']) ?>" class="color-orange flex-justify-align">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" />
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                    </a>
                                </td> -->

                                <td>
                                    <a href="<?= url('return-delete-cart/' . $item['id']) ?>" class="delete-product">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512">
                                            <path fill="#ff0000" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z" />
                                        </svg>
                                    </a>
                                </td>
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
            </div>
        <?php }
        ?>
        <!-- end table cart list -->

        <!-- close invoice form -->
        <?php
        if ($return == false) { ?>
        <?php } else { ?>
            <div class="content-container">
                <div class="mb10 fs14 color-orange">
                    <?php if (!empty($cart_lists) && isset($cart_lists[0]['total_price'])): ?>
                        مجموع: <?= number_format($cart_lists[0]['total_price']) ?>
                        <span class="fs12">افغانی</span>
                    <?php else: ?>

                        <span>بِل برگشت از خرید شماره <span><?= $return['id'] ?></span> باز است، اما لیست آن خالی است! <a href="<?= url('delete-return-invoice/' . $return['id']) ?>" class="text-underline delete-invoice color-red">حذف بِل</a></span>
                    <?php endif; ?>
                </div>

                <div class="insert">
                    <form action="<?= url('close-return-sale-invoice-store') ?>" method="POST">
                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">مجموع پرداختی به مشتری</div>
                                <input type="number" id="paid_amount" name="paid_amount" placeholder="پرداختی به مشتری" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تاریخ را انتخاب کنید</div>
                                <input type="hidden" class="d-none date-server dateInvoice" name="buy_date" autofocus>
                                <input type="text" data-jdp class="start cursor-p date-view checkInput" />
                            </div>
                        </div>
                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14"> منبع پرداخت <?= _star ?></div>
                                <select name="source" id="source" disabled>
                                    <option disabled>منبع پرداخت پول را انتخاب کنید</option>
                                    <option value="1" selected>پرداخت از دخل</option>
                                    <option value="2">پرداخت از صندوق اصلی</option>
                                </select>
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">توضیحات</div>
                                <textarea name="description" placeholder="توضیحات را وارد نمایید"></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="quantity_in_pack" value="" />
                        <input type="hidden" name="seller_id" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['seller_id'])) ? $cart_lists[0]['seller_id'] : '' ?>">
                        <input type="hidden" name="invoice_id" value="<?= $return['id'] ?>" />
                        <input type="hidden" name="branch_id" value="<?= $return['branch_id'] ?>" />
                        <input type="hidden" name="total_price" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['total_price'])) ? $cart_lists[0]['total_price'] : '0' ?>">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                        <?php if (!empty($cart_lists) && isset($cart_lists[0]['total_price'])): ?>
                            <input type="submit" id="submit" value="بستن بِل برگشت از خرید" class="btn bold" />
                        <?php else: ?>
                            <p class="fs12 color-orange">لیست خالی است</p>
                        <?php endif; ?>

                    </form>
                </div>
            <?php }
            ?>
            </div>
            <!-- end close invoice form -->

            <!-- ////////////////////////////////////////////////////////////////////////////////////// -->
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

        });
    </script>

    <!-- active and disable select tag -->
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