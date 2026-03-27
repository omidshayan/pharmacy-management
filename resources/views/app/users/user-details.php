<!-- start sidebar -->
<?php
$title = 'جزئیات کاربر: ' . $user['user_name'];
include_once('resources/views/layouts/header.php');
include_once('resources/views/scripts/change-status.php');
?>
<!-- end sidebar -->
<div id="alert" class="alert" style="display: none;">حالم بده، با برنامه نویس مه تماس بگیر :(</div>

<!-- loading and overlay -->
<div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>
<!-- Start content -->


<div class="content">

    <div class="content-title">
        جزئیات کارمند : <?= $user['user_name'] ?>
        <div>
            <span class="fs15">بالانس کاربر: </span>
            <?= ($account_balance['balance']) ? $this->formatNumber($account_balance['balance']) . _afghani : 0 ?>
        </div>
    </div>

    <!-- start page content -->
    <div class="box-container">

        <div class="accordion-title color-orange">مشخصات عمومی</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نام</div>
                    <div class="info-detaile"><?= $user['user_name'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نام پدر</div>
                    <div class="info-detaile"><?= ($user['father_name'] ? $user['father_name'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">شماره</div>
                    <div class="info-detaile"><?= $user['phone'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نوع کاربر</div>
                    <div class="info-detaile">
                        <?= $user['is_customer'] || $user['is_seller']
                            ? ($user['is_customer'] ? 'مشتری' : '') .
                            ($user['is_customer'] && $user['is_seller'] ? ' / ' : '') .
                            ($user['is_seller'] ? 'فروشنده' : '')
                            : '- - - -' ?>
                    </div>
                </div>
                <!-- <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">رمزعبور: </div>
                    <div class="info-detaile" id="passwordDisplay"><?= str_repeat('*', strlen($employee['password'])) ?></div>
                    <div class="eye-icon" onmousedown="showPassword()" onmouseup="hidePassword()">
                        <span id="eyeIcon" style="font-size: 18px;">&#128065;</span>
                    </div>
                </div> -->
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">آدرس</div>
                    <div class="info-detaile"><?= ($user['address'] ? $user['address'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">توضیحات</div>
                    <div class="info-detaile"><?= ($user['description'] ? $user['description'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">تاریخ ثبت</div>
                    <div class="info-detaile"><?= jdate('Y/m/d', strtotime($user['created_at'])) ?></div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">عکس</div>
                    <div class="info-detaile">
                        <?= ($user['user_image'] ? '<a href="' . asset('public/images/users/' . $user['user_image']) . '" target="_blank"><img src="' . asset('public/images/users/' . $user['user_image']) . '" class="w50" alt=""></a>' : '- - - - ') ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile"><a href="#" data-url="<?= url('change-status-center') ?>" data-id="<?= $user['id'] ?>" class="changeStatus color btn p5 w100 m10 center" id="submit">تغییر وضعیت</a></div>
                    <div class="info-detaile">
                        <div class="w100 m10 center status status-column" id="status"><?= ($user['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="accordion-title color-orange">
            اطلاعات مالی
            <?php if ($account_balance): ?>
                <?php if ($account_balance['balance'] > 0): ?>
                    <span class="fs11 color-green">(طلب‌کار است)</span>
                <?php elseif ($account_balance['balance'] < 0): ?>
                    <span class="fs11 color-red">(قرض‌دار است)</span>
                <?php else: ?>
                    <span class="fs11 color-gray">(تسویه است)</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php if (!empty($account_balance) && is_array($account_balance)): ?>
            <div class="accordion-content">
                <div class="child-accordioin">

                    <div class="p10 mr10 color-green">جزئیات خریدِ کاربر از ما</div>
                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">مجموع خرید</div>
                        <div class="info-detaile">
                            <?= isset($account_balance['total_purchase']) ? number_format($account_balance['total_purchase']) : '0' ?> <?= _afghani ?>
                        </div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">مجموع پرداختی</div>
                        <div class="info-detaile">
                            <?= isset($account_balance['total_payments']) ? number_format($account_balance['total_payments']) : '0' ?> <?= _afghani ?>
                        </div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">مجموع برگشت از خرید</div>
                        <div class="info-detaile">
                            <?= isset($account_balance['total_purchase_returns']) ? number_format($account_balance['total_purchase_returns']) : '0' ?> <?= _afghani ?>
                        </div>
                    </div>

                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">مجموع تخفیف</div>
                        <div class="info-detaile">
                            <?= isset($account_balance['total_discount_purchase']) ? number_format($account_balance['total_discount_purchase']) : '0' ?> <?= _afghani ?>
                        </div>
                    </div>

                    <?php
                    if ($account_balance['total_sales'] > 0) { ?>
                        <div class="p10 mr10 color-green">جزئیات فروش کاربر به ما</div>

                        <div class="detailes-culomn d-flex cursor-p">
                            <div class="title-detaile">مجموع فروش</div>
                            <div class="info-detaile">
                                <?= isset($account_balance['total_sales']) ? number_format($account_balance['total_sales']) : '0' ?> <?= _afghani ?>
                            </div>
                        </div>

                        <div class="detailes-culomn d-flex cursor-p">
                            <div class="title-detaile">مجموع رسید به مشتری</div>
                            <div class="info-detaile">
                                <?= isset($account_balance['total_receipts']) ? number_format($account_balance['total_receipts']) : '0' ?> <?= _afghani ?>
                            </div>
                        </div>

                        <div class="detailes-culomn d-flex cursor-p">
                            <div class="title-detaile">مجموع برگشت از فروش</div>
                            <div class="info-detaile">
                                <?= isset($account_balance['total_sales_returns']) ? number_format($account_balance['total_sales_returns']) : '0' ?> <?= _afghani ?>
                            </div>
                        </div>

                        <div class="detailes-culomn d-flex cursor-p">
                            <div class="title-detaile">مجموع تخفیف فروش</div>
                            <div class="info-detaile">
                                <?= isset($account_balance['total_discount_sales']) ? number_format($account_balance['total_discount_sales']) : '0' ?> <?= _afghani ?>
                            </div>
                        </div>
                    <?php }
                    ?>

                    <br>
                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile">تاریخ آخرین تراکنش</div>
                        <div class="info-detaile">
                            <?= isset($account_balance['last_transaction_at']) ? jdate('Y/m/d', strtotime($account_balance['last_transaction_at'])) : 'تراکنشی ثبت نشده' ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php else: ?>
            <div class="accordion-content">
                <div class="child-accordion">
                    <div class="detailes-culomn d-flex cursor-p">
                        <div class="title-detaile color-red p5 fs12 m-auto">هیچ اطلاعات مالی ثبت نشده است!</div>
                    </div>
                </div>
            </div>
        <?php endif; ?> -->



        <!-- --------------location is factor ------------- -->


        <a href="<?= url('users') ?>">
            <div class="btn center p5">برگشت</div>
        </a>
    </div>


    <!-- <button id="generate-pdf" class="generate-pdf cursor-p btn-icon bold fs15 hover d-flex">
        چاپ صورتحساب
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 700 700">
            <path d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
        </svg>
    </button> -->
    <!-- end page content -->
</div>
<!-- End content -->








<!-- print -->

<!-- barcode -->
<!-- <script src="<?= asset('public/assets/js/barcode.js') ?>"></script> -->
<!-- invoice print -->
<div class="form-container d-none" id="print">

    <!-- top header -->
    <div class="top-inv d-flex">
        <div class="center">
            <h2 class="color-print">صنایع رنگ و رزین سازی افغان فیضی</h2>
            <div class="color-print fs14">تولید کننده رنگ های روغنی، پلاستیکی، و مایع رنگ</div>
            <div class="color-print fs12"><span>0799999999</span> - <span>0799999999</span> - <span>0799999999</span> - <span>0799999999</span></div>
        </div>
        <div class="top-inv-logo">
            <img src="<?= asset('public/assets/img/logo.png') ?>" class="" alt="logo">
        </div>
    </div>

    <hr class="hr">

    <!-- invoice infos -->
    <div class="d-flex justify-between">
        <div class="top-desc-one mt5">
            <div class="fs15 color-print">نام مشتری: </div>
            <div class="fs15 color-print">شماره موبایل: </div>
            <div class="fs14 color-print">آدرس: </div>
        </div>
        <div class="top-desc-one mt5 d-flex align-center">
            <div class="fs15 color-print"><svg id="barcode"></svg></div>
        </div>
        <div class="top-desc-one mt5">
            <div class="fs15 color-print">تاریخ: </div>
            <div class="fs15 color-print">توسط: <?= $_SESSION['sk_em_name'] ?></div>
        </div>
    </div>
    <hr class="hr">

    <!-- products details -->
    <table class="table-print w100 color-print center mt15">
        <thead>
            <tr class="fs14">
                <th class="w20 fs11">#</th>
                <th class="w300">شماره بِل</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                <th>بدهکار</th>
                <th>طلبکار</th>
                <th>وضعیت</th>
                <th>باقیمانده</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $total_price = 0;
            if (!empty($invoices) && is_array($invoices)) {
                $number = 1;
                foreach ($invoices as $item) {
            ?>
                    <tr>
                        <td><?= $this->to_farsi_number($number) ?></td>
                        <td><?= $item['id'] ?></td>
                        <td><?= jdate('Y/m/d', $item['sale_invoice_date']) ?></td>
                    </tr>
            <?php
                    $number++;
                }
            }
            ?>
        </tbody>
    </table>

    <div class="d-flex justify-between">
        <div class="fs12 center mt5 color-print">آدرس: افغانستان-هرات، جاده بانک خون، رو به روی اتاق های تجارت</div>
        <div class="fs12 center mt5 color-print">www.faizipaint.com - E-Mail: info@faizipaint.com</div>
    </div>
</div>

<!-- <button type="button" id="generate-pdf" class="generate-pdf cursor-p fs18 hover">
    چاپ مجدد بِل
</button> -->




<!-- barcode -->
<!-- <script>
    var qrcode = "<?= $fa ?>";
    JsBarcode("#barcode", qrcode, {
        format: "CODE128",
        lineColor: "#000",
        width: 1,
        height: 40,
        displayValue: false,
        text: "",
        textAlign: "center",
    });
</script> -->




<!-- barcode lib -->
<!-- <script src="<?= asset('public/assets/js/jspdf.js') ?>"></script>
<script src="<?= asset('public/assets/js/htmlToCanvas.js') ?>"></script> -->




<!-- <script>
    const {
        jsPDF
    } = window.jspdf;

    async function generateAndPrintPDF() {
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
    }

    // وقتی روی دکمه کلیک شد:
    document.getElementById("generate-pdf").addEventListener("click", generateAndPrintPDF);

    // وقتی صفحه بارگذاری شد:
    window.addEventListener("load", function() {
        generateAndPrintPDF();
    });
</script> -->
<!-- end print -->




<?php include_once('resources/views/layouts/footer.php') ?>