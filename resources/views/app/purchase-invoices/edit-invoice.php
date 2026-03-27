    <?php
    $title = 'ویرایش بِل: ' . $purchase_invoices['invoice_number'];
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/live-search-product.php');
    include_once('resources/views/scripts/live-search-seller.php');
    include_once('public/alerts/toastr.php');
    include_once('public/alerts/check-inputs.php');
    include_once('resources/views/scripts/datePicker.php');
    ?>

    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Start content -->
    <div class="content">
        <div class="content-title"> ویرایش بِل: <?= $purchase_invoices['invoice_number'] ?>
            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
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
                <form action="<?= url('product-inventory-store') ?>" method="POST" enctype="multipart/form-data" id="myForm">
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
                                <div class="label-form mb5 fs14">جستجوی فروشنده <?= _star ?> </div>
                                <?php
                                $seller = $seller ?? ['id' => '', 'user_name' => ''];
                                ?>
                                <input type="hidden" name="seller_id" id="seller_id" value="<?= !empty($seller['id']) ? $seller['id'] : '' ?>">
                                <div id="user_details"></div>
                                <input type="text" class="checkInput" name="search_seller" id="search_seller" value="<?= !empty($user['user_name']) ? $user['user_name'] : '' ?>" placeholder="نام فروشنده را جستجو نمایید" autocomplete="off" />
                            </div>
                            <ul class="search-back d-none" id="backResponseSeller">
                                <li class="resSel search-item color" role="option"></li>
                            </ul>
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
                                <input type="text" class="" name="unit_price_buy" placeholder="نام محصول را وارد نمایید" maxlength="40" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت فروش هر دانه / عدد <?= _star ?> </div>
                                <input type="text" class="" name="unit_price_sell" placeholder="نام محصول را وارد نمایید" maxlength="40" />
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
        <div class="content-container mb30">
            <div class="mb10 fs14"> اقلام بِل شماره: <?= $purchase_invoices['invoice_number'] ?> - فروشنده: <?= $user['user_name'] ?></div>
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام محصول</th>
                        <th>تعداد بسته</th>
                        <th>تعداد عدد</th>
                        <th>تعداد کل</th>
                        <th>قیمت خرید واحد</th>
                        <th>تخفیف</th>
                        <th>قیمت کل</th>
                        <th>ویرایش</th>
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
                            <td><?= $this->formatNumber($item['package_qty'] ?? 0) ?></td>
                            <td><?= $this->formatNumber($item['unit_qty'] ?? 0) ?></td>
                            <td><?= $this->formatNumber($item['quantity'] ?? 0) ?></td>
                            <td><?= $this->formatNumber($unitPrices['buy'], 2) ?></td>
                            <td><?= $this->formatNumber($item['discount'] ?? 0, 2) ?></td>
                            <td><?= $this->formatNumber($item['item_total_price'], 2) ?></td>

                            <td>
                                <a href="<?= url('edit-product-cart/' . $item['id']) ?>" class="color-orange flex-justify-align">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" class="color-orange" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </a>
                            </td>

                            <td>
                                <a href="<?= url('delete-product-cart/' . $item['id']) ?>" class="delete-product">
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
        </div>
        <!-- end table cart list -->

        <div class="content-container">
            <div class="mb10 fs14 color-orange">
                <?php if (!empty($cart_lists) && isset($cart_lists[0]['total_price'])): ?>
                    مجموع بِل: <?= number_format($cart_lists[0]['total_price']) ?>
                    <span class="fs12">افغانی</span>
                <?php else: ?>
                    <span>بِل شماره <span><?= $purchase_invoices['id'] ?></span> باز است، اما لیست آن خالی است! <a href="<?= url('delete-invoice/' . $purchase_invoices['id']) ?>" class="color text-underline delete-invoice">حذف بِل</a></span>
                <?php endif; ?>
            </div>

            <div class="insert">
                <form action="<?= url('close-inventory-store') ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">مجموع پرداخت شده</div>
                            <input type="number" name="paid_amount" value="<?= $purchase_invoices['paid_amount'] ?>" placeholder="قیمت کل" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">تخفیف کلی بِل</div>
                            <input type="number" name="discount" class="discount" value="<?= $purchase_invoices['discount'] ?>" placeholder="تخفیف را وارد نمائید" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">تاریخ بِل رو انتخاب کنید <?= _star ?></div>
                            <input type="text" data-jdp class="form-control date-view checkInput" placeholder="تاریخ را انتخاب کنید">
                            <input type="hidden" class="date-server checkInput" name="buy_date">
                        </div>

                        <div class="one">
                            <div class="label-form mb5 fs14">شماره بِل صادر شده (فروشنده)</div>
                            <input type="text" name="ref_id" value="<?= $purchase_invoices['ref_id'] ?>" placeholder="شماره بِل صادر شده از طرف فروشنده" />
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"><?= $purchase_invoices['description'] ?></textarea>
                        </div>
                    </div>

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">عکس بِل</div>
                            <input type="file" id="image" name="buy_inv_img" accept="image/*">
                        </div>
                    </div>
                    <div id="imagePreview">
                        <img src="" class="img" alt="">
                    </div>
                    <div>
                        <img src="<?= ($purchase_invoices['image'] ? asset('public/images/buy_invoices/' . $purchase_invoices['image']) : asset('public/assets/img/empty.png')) ?>" class="img" alt="invoice img">
                    </div>
                    <div class="fs11">عکس فعلی</div>

                    <input type="hidden" name="quantity_in_pack" value="" />
                    <input type="hidden" name="seller_id" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['seller_id'])) ? $cart_lists[0]['seller_id'] : '' ?>">
                    <input type="hidden" name="invoice_id" value="<?= $purchase_invoices['id'] ?>" />
                    <input type="hidden" name="branch_id" value="<?= $purchase_invoices['branch_id'] ?>" />
                    <input type="hidden" name="total_price" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['total_price'])) ? $cart_lists[0]['total_price'] : '0' ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <?php if (!empty($cart_lists) && isset($cart_lists[0]['total_price'])): ?>
                        <input type="submit" id="submit" value="ویرایش فـاکـتـور" class="btn bold" />
                    <?php else: ?>
                        <p class="fs12 color-orange">لیست بِل خالی است</p>
                    <?php endif; ?>
                </form>
            </div>
            <?=$this->back_link('purchase-invoices')?>
        </div>
    </div>
    <!-- End content -->

    <!-- empty input search users and invoice date -->
    <script>
        $(document).ready(function() {

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