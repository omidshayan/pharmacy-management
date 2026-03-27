    <!-- start sidebar -->
    <?php
    $title = 'برگشت از خرید';
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/live-search-product.php');
    include_once('resources/views/scripts/live-search-seller.php');
    include_once('public/alerts/toastr.php');
    include_once('public/alerts/check-inputs.php');
    ?>
    <!-- end sidebar -->

    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Start content -->
    <div class="content">
        <div class="content-title pr"> برگشت از خرید
            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
            <img class="pa rfb-img" src="<?= asset('public/assets/img/rfb.png') ?>" alt="return from buy">
        </div>
        <?php
        $help_title = _help_title;
        $help_content = _help_desc;
        include_once('resources/views/helps/help.php');
        ?>

        <div class="producInfos">
            <h5 class="d-none product-name">نام: <span></span></h5>
        </div>

        <!-- start page content -->
        <div class="search-content scroll-not">
            <div class="insert">
                <form action="<?= url('return-from-buy-store') ?>" method="POST" enctype="multipart/form-data" id="myForm">
                    <!-- search product -->
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">جستجوی محصول <?= _star ?> </div>
                            <input type="hidden" name="product_id" id="product_id">
                            <div id="user_details"></div>
                            <input type="text" class="checkInput" name="product_name" id="product_name" placeholder="نام محصول را جستجو نمایید" autocomplete="off" autofocus />
                        </div>
                        <ul class="search-back d-none" id="backResponse">
                            <li class="res search-item color" role="option"></li>
                        </ul>
                    </div>

                    <div class="d-none my-form">
                        <!-- search purcharc -->
                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">جستجوی فروشنده <?= _star ?></div>
                                <?php
                                $seller = $seller ?? ['id' => '', 'user_name' => ''];
                                ?>
                                <input type="hidden" name="seller_id" id="seller_id" value="<?= !empty($seller['id']) ? $seller['id'] : '' ?>">
                                <div id="user_details"></div>
                                <input type="text" name="search_seller" class="checkInput" id="search_seller" value="<?= !empty($user['user_name']) ? $user['user_name'] : '' ?>" placeholder="نام فروشنده را جستجو نمایید" autocomplete="off" />
                            </div>
                            <ul class="search-back d-none" id="backResponseSeller">
                                <li class="resSel search-item color" role="option"></li>
                            </ul>
                            <?= $this->branchSelectField(); ?>
                        </div>

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
                                <div class="label-form mb5 fs14">قیمت خرید هر دانه / عدد <?= _star ?> </div>
                                <input type="text" class="" name="unit_price_buy" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                            </div>
                        </div>

                        <div class="title-line m-auto">
                            <span class="color-tow fs14">تعداد کارتن / بسته - عدد / دانه‌ای</span>
                            <hr class="hr">
                        </div>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">تعداد بسته / کارتن </div>
                                <input type="number" value="0" id="packageCount" class="checkInputGroup" name="package_qty" placeholder="تعداد بسته یا کارتن را وارد نمایید" maxlength="40" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تعداد عدد / دانه </div>
                                <input type="number" id="unitCount" class="checkInputGroup" value="0" name="unit_qty" placeholder="تعداد عدد یا دانه را وارد نمایید" maxlength="40" />
                            </div>
                        </div>
                        <span class="quantity"> تعداد عددی: </span>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت کل </div>
                                <input type="text" class="all_price" name="item_total_price" placeholder="قیمت کل" readonly />
                            </div>
                        </div>
                        <input type="hidden" name="discount" />
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
        if ($return_from_buy) { ?>

            <div class="content-container mb30">
                <div class="mb10 fs14"> اقلام برگشت از خرید، شماره: <?= $return_from_buy['id'] ?> - فروشنده: <?= $user['user_name'] ?></div>
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
                        ?>
                            <tr>
                                <td class="color-orange"><?= $number ?></td>
                                <td><?= $item['product_name'] ?></td>
                                <td><?= $item['package_qty'] ?? 0 ?></td>
                                <td><?= $item['unit_qty'] ?? 0 ?></td>
                                <td><?= $item['quantity'] ?? 0 ?></td>
                                <td><?= number_format($item['unit_price_buy']) ?></td>
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
                                    <a href="<?= url('return-buy-delete-cart/' . $item['id']) ?>" class="delete-product">
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
        if ($return_from_buy == false) { ?>
        <?php } else { ?>
            <div class="content-container">
                <div class="mb10 fs14 color-orange">
                    <?php if (!empty($cart_lists) && isset($cart_lists[0]['total_price'])): ?>
                        مجموع: <?= number_format($cart_lists[0]['total_price']) ?>
                        <span class="fs12">افغانی</span>
                    <?php else: ?>
                        <span>بِل برگشت از خرید شماره <span><?= $return_from_buy['id'] ?></span> باز است، اما لیست آن خالی است! <a href="<?= url('delete-return-buy-invoice/' . $return_from_buy['id']) ?>" class="color text-underline delete-invoice">حذف بِل</a></span>
                    <?php endif; ?>
                </div>

                <div class="insert">
                    <form action="<?= url('close-return-buy-invoice-store') ?>" method="POST">
                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">مجموع دریافتی</div>
                                <input type="number" name="paid_amount" placeholder="دریافتی از فروشنده" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تاریخ را انتخاب کنید</div>
                                <input type="hidden" class="d-none dateInvoice" name="r_b_date" autofocus>
                                <input type="text" class="start cursor-p checkInput" />
                            </div>
                        </div>
                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">توضیحات</div>
                                <textarea name="description" placeholder="توضیحات را وارد نمایید"></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="quantity_in_pack" value="" />
                        <input type="hidden" name="seller_id" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['seller_id'])) ? $cart_lists[0]['seller_id'] : '' ?>">
                        <input type="hidden" name="invoice_id" value="<?= $return_from_buy['id'] ?>" />
                        <input type="hidden" name="branch_id" value="<?= $return_from_buy['branch_id'] ?>" />
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

        });
    </script>

    <?php include_once('resources/views/layouts/footer.php') ?>