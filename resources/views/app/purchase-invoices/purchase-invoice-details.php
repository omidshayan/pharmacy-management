<!-- start sidebar -->
<?php
$title = 'جزئیات بِل: ' . $invoice['invoice_number'];
include_once('resources/views/layouts/header.php');
include_once('resources/views/scripts/change-status.php');
include_once('resources/views/scripts/show-img-modal.php');
$remaining = (float)$invoice['total_amount'] - (float)$invoice['discount'] - (float)$invoice['paid_amount'];
?>
<!-- end sidebar -->
<div id="alert" class="alert" style="display: none;"></div>

<!-- loading and overlay -->
<div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>
<!-- Start content -->
<div class="content">
    <div class="content-title mb20"> جزئیات بِل خرید شماره : <?= $invoice['invoice_number'] ?></div>

    <!-- start page content -->
    <div class="content-container">

        <!-- details invoice -->
        <div class="accordion-title color-orange">مشخصات کامل بِل</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">شماره بِل</div>
                    <div class="info-detaile"><?= $invoice['invoice_number'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">فروشنده</div>
                    <div class="info-detaile"><?= $invoice['seller_name'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مبلغ بِل</div>
                    <div class="info-detaile"><?= $this->formatNumber($invoice['total_amount']) . _afghani ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مبلغ پرداختی</div>
                    <div class="info-detaile"><?= ($invoice['paid_amount'] ?  $this->formatNumber($invoice['paid_amount']) . '<span class="fs12"> (افغانی) </span>' :  $this->formatNumber(0)) ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مبلغ تخفیف</div>
                    <div class="info-detaile"><?= ($invoice['discount'] ?  $this->formatNumber($invoice['discount']) . ' <span class="fs11"> (افغانی) </span>' : $this->formatNumber(0)) ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مبلغ باقیمانده</div>
                    <div class="info-detaile">
                        <?= $this->formatNumber($remaining, 0) ?> <span class="fs11"> (افغانی) </span>
                    </div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">تاریخ صدور بِل</div>
                    <div class="info-detaile"><?= jdate('l Y/m/d', $invoice['date']) ?> <span class="color-orange fs12"> (<?= $this->calculateDays($invoice['date']) ?> روز پیش) </span></div>
                </div>

                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">تاریخ ثبت در سیستم</div>
                    <div class="info-detaile"><?= (jdate('Y/m/d', strtotime($invoice['created_at']))) ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">اقلام بِل</div>
                    <div class="info-detaile"><?= $this->formatNumber(count($invoice_items)) ?> قلم</div>
                </div>
                <div class="detailes-culomn d-flex">
                    <div class="title-detaile">وضعیت تصفیه بِل</div>
                    <div class="info-detaile">
                        <?php if ($remaining == 0): ?>
                            <span class="fs14 color-green"> تصفیه شده </span>
                        <?php else: ?>
                            <span class="fs14 color-red"> تصفیه نشده </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">توضیحات</div>
                    <div class="info-detaile"><?= ($invoice['description'] ? $invoice['description'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">وضعیت</div>
                    <div class="info-detaile">
                        <?= ($invoice['status'] == 2) ? '<span class="color-green">فعال</span>' : (($invoice['status'] == 3) ? '<span class="color-red">غیرفعال</span>' : (($invoice['status'] == 1) ? '<span class="color-orange">باز است</span>' : '')) ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile"><a href="#" data-url="<?= url('change-status-invoice') ?>" data-id="<?= $invoice['id'] ?>" class="changeStatus color btn p5 w100 m10 center" id="submit">تغییر وضعیت</a></div>
                    <div class="info-detaile">
                        <div class="w100 m10 center status status-column" id="status"><?= ($invoice['status'] == 3) ? '<span class="color-red">غیرفعال</span>' : '<span class="color-green">فعال</span>' ?></div>
                    </div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">عکس بِل</div>
                    <div class="info-detaile flex-justify-align">
                        <?= $invoice['image']
                            ? '<img class="w50 cursor-p" src="' . asset('public/images/buy_invoices/' . $invoice['image']) . '" alt="logo" onclick="openModal(\'' . asset('public/images/buy_invoices/' . $invoice['image']) . '\')">'
                            : '- - - -' ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">ثبت شده توسط</div>
                    <div class="info-detaile"><?= $invoice['who_it'] ?></div>
                </div>
            </div>
        </div>

        <!-- list of invoice -->
        <div class="accordion-title color-orange">لیست اقلام بِل</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">

                    <div class="content-container mb30">
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $number = 1;
                                foreach ($invoice_items as $item) {
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
                                    </tr>
                                <?php
                                    $number++;
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="flex-justify-align mt20 paginate-section">
                            <div class="table-info fs12">تعداد کل: <?= count($invoice_items) ?></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <a href="<?= url('purchase-invoices') ?>">
            <div class="btn center p5">برگشت</div>
        </a>
    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>