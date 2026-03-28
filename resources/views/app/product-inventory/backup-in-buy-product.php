    <!-- start sidebar -->
    <?php
    $title = 'خرید دوا جدید';
    include_once('resources/views/layouts/header.php');
    include_once('resources/views/scripts/search.php');
    include_once('resources/views/scripts/live-search-seller.php');
    include_once('public/alerts/toastr.php');
    include_once('public/alerts/check-inputs.php');
    include_once('resources/views/scripts/datePicker.php');
    ?>
    <!-- end sidebar -->

    <div class="overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- barcode -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script> -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title">
            <span class="twinkle-b"></span>
            <span class="fs22">خـــریـــد مـــحصــول جــــدیـــد</span>
            <span class="twinkle-b"></span>

            <span class="help fs14 text-underline cursor-p color-orange" id="openModalBtn">(راهنما)</span>
        </div>
        <?php
        $help_title = _help_title;
        $help_content = _help_desc;
        include_once('resources/views/helps/help.php');
        ?>

























        <div class="producInfos">
            <div class="d-none product-name">نام دوا: <span></span></div>
            <div class="d-none now-inventory">موجودی فعلی: <span class="quan"></span> <span class="unitType"></span> </div>
        </div>

        <!-- start form for add new product -->
        <div class="search-content scroll-not">
            <div class="insert">
                <form action="<?= url('product-inventory-store') ?>" method="POST" enctype="multipart/form-data" id="myForm">

                    <!-- search product -->
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">جستجوی دوا <?= _star ?> </div>
                            <input type="hidden" name="product_id" id="item_id">
                            <input type="text"
                                class="checkInput"
                                name="product_name"
                                id="item_name"
                                placeholder="نام دوا را جستجو نمایید"
                                autocomplete="off"
                                autofocus
                                data-search-url="<?= url('search-product-purchase') ?>"
                                data-item-info-url="<?= url('get-product-infos') ?>" />
                        </div>
                        <ul class="search-back d-none" id="backResponse">
                            <li class="res search-item color" role="option"></li>
                        </ul>
                    </div>

                    <!-- table -->
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
                            <span class="color-tow fs14">قیمت دوا</span>
                            <hr class="hr">
                        </div>

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت خرید هر بسته / واحد <?= _star ?> </div>
                                <input type="text" class="checkInput" name="package_price_buy" placeholder="نام دوا را وارد نمایید" maxlength="40" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">قیمت فروش هر بسته / واحد <?= _star ?> </div>
                                <input type="text" class="checkInput" name="package_price_sell" placeholder="نام دوا را وارد نمایید" maxlength="40" />
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
                                <div class="label-form mb5 fs14">تخفیف به این دوا</div>
                                <input type="text" name="discount" class="discount" placeholder="تخفیف را وارد نمائید" />
                            </div>
                        </div>

                        <?php
                        if ($expire_date['expiration_date'] == 1) { ?>
                            <div class="inputs d-flex">
                                <div class="one">
                                    <div class="label-form mb5 fs14">تاریخ انقضا</div>
                                    <input type="hidden" class="d-none dateExpire date-server" name="expiration_date" autofocus>
                                    <input type="text" data-jdp class="expire_date cursor-p checkInput date-view" />
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
        <!-- end form -->

        <!-- table cart list -->
        <?php
        if ($purchase_invoices) { ?>
            <div class="content-container mb30">

                <div class="mb10 fs14"> اقلام بِل شماره: <?= $purchase_invoices['id'] ?> - فروشنده: <?= $user['user_name'] ?></div>
                <table class="fl-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام دوا</th>
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
        <?php }
        ?>
        <!-- end table cart list -->

        <!-- close invoice form -->
        <?php
        if ($purchase_invoices == false) { ?>
        <?php } else { ?>
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
                                <div class="label-form mb5 fs14">مجموع پرداخت بِل</div>
                                <input type="number" id="paid_amount" name="paid_amount" placeholder="قیمت کل" />
                            </div>
                            <div class="one">
                                <div class="label-form mb5 fs14">تخفیف کلی بِل</div>
                                <input type="number" name="discount" class="discount" placeholder="تخفیف را وارد نمائید" />
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
                                <input type="text" name="ref_id" placeholder="شماره بِل صادر شده از طرف فروشنده" />
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

                        <div class="inputs d-flex">
                            <div class="one">
                                <div class="label-form mb5 fs14">عکس بِل</div>
                                <input type="file" id="image" name="buy_inv_img" accept="image/*">
                            </div>
                        </div>
                        <div id="imagePreview">
                            <img src="" class="img" alt="">
                        </div>

                        <input type="hidden" name="quantity_in_pack" value="" />
                        <input type="hidden" name="seller_id" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['seller_id'])) ? $cart_lists[0]['seller_id'] : '' ?>">
                        <input type="hidden" name="invoice_id" value="<?= $purchase_invoices['id'] ?>" />
                        <input type="hidden" name="branch_id" value="<?= $purchase_invoices['branch_id'] ?>" />
                        <input type="hidden" name="total_price" value="<?= (!empty($cart_lists) && isset($cart_lists[0]['total_price'])) ? $cart_lists[0]['total_price'] : '0' ?>">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                        <?php if (!empty($cart_lists) && isset($cart_lists[0]['total_price'])): ?>
                            <input type="submit" id="submit" value="بستن فـاکـتـور" class="btn bold" />
                        <?php else: ?>
                            <p class="fs12 color-orange">لیست بِل خالی است</p>
                        <?php endif; ?>

                    </form>
                </div>
            <?php }
            ?>
            </div>
            <!-- end close invoice form -->





            <!-- ////////////////////////////////////////////////////////////////////////////////////// -->

            <!-- <div class="form-container" id="print">
                <div class="top-inv d-flex">
                    <div class="top-inv-text center">
                        <h2 class="color-print">صنایع رنگ و رزین سازی افغان فیضی</h2>
                        <div class="color-print">تولید کننده رنگ های روغنی، پلاستیکی، و مایع رنگ</div>
                    </div>
                    <div class="top-inv-logo">
                        <img src="<?= asset('public/assets/img/logo.png') ?>" class="" alt="logo">
                    </div>
                </div>
                <hr class="hr">
                <div class="top-desc-one d-flex mt5">
                    <div class="fs15 color-print">صورت حساب: ali jan</div>
                    <div class="fs15 color-print"> توسط: reza jan</div>
                    <div class="fs15 color-print">تاریخ: 1404/5/10</div>
                    <div class="fs15 color-print">شماره بِل: 12345</div>
                </div>
                <div class="top-desc-two d-flex mt15 align-center">
                    <div class="fs12 color-print">آدرس: جاده بانک خون، مارکت انصاری، دفتر مرکزی شرکت رنگسازی افغان فیضی</div>
                    <div class="d-flex align-center">
                    <div class="fs15 color-print">تماس: 099999999 - 0700121451</div>
                    <div class="fs15 color-print"><svg id="barcode"></svg></div>
                    </div>
                </div>

                <table class="table-print w100 color-print center">
                    <thead>
                        <tr>
                            <th>شماره</th>
                            <th>نام دوا</th>
                            <th>کارتن</th>
                            <th>فی کارتن</th>
                            <th>عدد</th>
                            <th>قیمت عدد</th>
                            <th>قیمت کل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>ddfddd</td>
                            <td>ddfdddjj</td>
                            <td>lkjj</td>
                            <td>dsfdsf</td>
                            <td>dsfdsf</td>
                            <td>dsfdsf</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>ddfddd</td>
                            <td>ddfdddjj</td>
                            <td>lkjj</td>
                            <td>dsfdsf</td>
                            <td>dsfdsf</td>
                            <td>dsfdsf</td>
                        </tr>
                        <tr class="color-culomn bold">
                            <td colspan="2">مبلغ به حروف</td>
                            <td colspan="3">سی هزار هشصد شصت</td>
                            <td>قیمت کل</td>
                            <td>5000 افغانی</td>
                        </tr>
                    </tbody>
                </table>

                <div>
                    <div class="fs12 center mt5 color-print">آدرس: افغانستان-هرات، شهرک صنعتی، فاز اول، بلوار سنبل، سنبل 4، مقابل اطفائیه، کارخانه رنگ سازی افغان فیضی</div>
                    <div class="fs12 center mt5 color-print">شماره‌های تماس: 0795000110 - 0799372472 - 0700400013 - تلفن 330244 (040)</div>
                    <div class="fs12 center mt5 color-print">Afghanistan - Herat, Industrial Estate, First Phase, Boulevard Sonbol, Against, Fire Station</div>
                    <div class="fs12 center mt5 color-print">website: www.faizipaint.com, E-Mail: info@faizipaint.com</div>
                    <div class="d-flex justify-between color-print fs14 bold prl40 mt15 center">
                        <div>امضاء کارآموز</div>
                        <div>امضاء مسئول (مدیر بخش مربوط)</div>
                        <div>امضاء و مهر رئیس اداره</div>
                    </div>
                </div>
            </div> -->

            <!-- barcode -->
            <!-- <script>
                JsBarcode("#barcode", "123456789", {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 1,
                    height: 25,
                    displayValue: false,
                    text: "",
                    textAlign: "center",
                    // fontSize: 14,
                });
            </script>
            <button id="generate-pdf" class="generate-pdf cursor-p bold fs15 hover">چاپ فرم</button> -->

            <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script>
                document.getElementById("generate-pdf").addEventListener("click", async function() {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF({
                        orientation: "portrait",
                        unit: "mm",
                        format: "a4",
                    });

                    const element = document.querySelector(".form-container");
                    const canvas = await html2canvas(element, {
                        scale: 2,
                        useCORS: true,
                    });

                    const imgData = canvas.toDataURL("image/jpeg", 0.9);
                    const imgWidth = 205;
                    const imgHeight = 270;
                    const marginLeft = 10;

                    doc.addImage(imgData, "JPEG", marginLeft, 0, imgWidth - marginLeft, imgHeight);

                    const pdfBlob = doc.output("blob");
                    const pdfUrl = URL.createObjectURL(pdfBlob);
                    const printWindow = window.open(pdfUrl, "_blank");
                    if (printWindow) {
                        printWindow.addEventListener("load", () => {
                            printWindow.print();
                        });
                    }
                });
            </script> -->


            <!-- ////////////////////////////////////////////////////////////////////////////////////// -->
    </div>
    <!-- End content -->

    <script>
        $(document).ready(function() {

            document.querySelectorAll(".delete-product").forEach(function(element) {
                element.addEventListener("click", function(event) {
                    let confirmDelete = confirm("آیا از حذف دوا اطمینان دارید؟");
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