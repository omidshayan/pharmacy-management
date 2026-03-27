<?php
$title = 'جزئیات بِل فروش: ' . $invoice['id'];
include_once('resources/views/layouts/header.php');
include_once('resources/views/scripts/change-status.php');
include_once('resources/views/scripts/show-img-modal.php');
include_once('public/alerts/error.php');
$remaining = (float)$invoice['total_amount'] - (float)$invoice['discount'] - (float)$invoice['paid_amount'];
$types = [
    1 => 'فروش',
    2 => 'خرید',
    3 => 'برگشت از فروش',
    4 => 'برگشت از خرید',
    5 => 'رسید پول',
    6 => 'پرداخت پول'
];
$type = $types[$invoice['invoice_type']] ?? 'نامشخص';
?>

<div id="alert" class="alert" style="display: none;"></div>

<div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<div class="content">
    <div class="content-title fs16"> جزئیات بِل <?= $type ?> شماره : <?= $invoice['invoice_number'] ?></div>
    <div class="content-title mb20"> نام کاربر: <?= $user_infos['user_name'] ?></div>

    <div class="content-container mb30">

        <div class="d-flex gap10">
            <div class="p5">مبلغ بل: <?= $this->twoFormatNumber($invoice['total_amount']) . _afghani ?> </div>
            <div class="p5"><?= ($remaining > 0) ? 'باقیمانده: ' . $this->twoFormatNumber($remaining) . _afghani : '<span class="color-green">تسویه شده</span>' ?> </div>
        </div>

        <table class="fl-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام محصول</th>
                    <th>تعداد بسته</th>
                    <th>تعداد عدد</th>
                    <th>تعداد کل</th>
                    <th>قیمت <?= $type ?> بسته</th>
                    <th>قیمت <?= $type ?> واحد</th>
                    <th>قیمت کل</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $number = 1;
                foreach ($invoice_items as $item) {
                    $unitPrices = $this->calculateUnitPrices($item);
                    $hasUnit = $item['quantity_in_pack'] ?? 0;
                ?>
                    <tr>
                        <td class="color-orange"><?= $number ?></td>
                        <td><?= $item['product_name'] ?></td>
                        <td class="fs16"><?= $this->formatNumber($item['package_qty'] ?? 0) ?></td>
                        <td class="fs16"><?= $this->formatNumber($item['unit_qty'] ?? 0) ?></td>
                        <td class="fs16"><?= $this->formatNumber($item['quantity'] ?? 0) ?></td>
                        <td class="fs16">
                            <?= $this->formatNumber(
                                in_array($invoice['invoice_type'], [1, 3]) ? $item['package_price_sell'] : $item['package_price_buy'],
                                2
                            ) ?>
                        <td class="fs16">
                            <?= ($hasUnit > 1)
                                ? $this->formatNumber(
                                    in_array($invoice['invoice_type'], [1, 3]) ? $unitPrices['sell'] : $unitPrices['buy'],
                                    2
                                )
                                : '- - -' ?>
                        </td class="fs16">
                        <td class="fs16"><?= $this->formatNumber($item['item_total_price'], 2) ?></td>
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

    <div class="content-container">

        <div class="accordion-title color-orange">مشخصات کامل بِل</div>
        <div class="accordion-contentd">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">شماره بِل</div>
                    <div class="info-detaile"><?= $invoice['invoice_number'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">فروشنده</div>
                    <div class="info-detaile"><?= $user_infos['user_name'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مبلغ بِل</div>
                    <div class="info-detaile"><?= $this->formatNumber($invoice['total_amount']) . _afghani ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile"> پرداختی</div>
                    <div class="info-detaile"><?= ($invoice['paid_amount'] ?  $this->formatNumber($invoice['paid_amount']) . '<span class="fs12"> (افغانی) </span>' :  $this->formatNumber(0)) ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile"> تخفیف</div>
                    <div class="info-detaile"><?= ($invoice['discount'] ?  $this->formatNumber($invoice['discount']) . ' <span class="fs11"> (افغانی) </span>' : $this->formatNumber(0)) ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">باقیمانده</div>
                    <div class="info-detaile">
                        <?= $this->formatNumber($remaining, 0) ?>
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

                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">مفاد این بِل</div>
                    <div class="info-detaile"><?= $this->formatNumber($profits['total_profit']) ?><?= _afghani ?></div>
                </div>

                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">توضیحات</div>
                    <div class="info-detaile"><?= ($invoice['description'] ? $invoice['description'] : '- - - - ') ?></div>
                </div>

                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">عکس بِل</div>
                    <div class="info-detaile flex-justify-align">
                        <?= $invoice['image']
                            ? '<img class="w50 cursor-p" src="' . asset('public/images/buy_invoices/' . $invoice['iamge']) . '" alt="logo" onclick="openModal(\'' . asset('public/images/buy_invoices/' . $invoice['iamge']) . '\')">'
                            : '- - - -' ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">ثبت شده توسط</div>
                    <div class="info-detaile"><?= $invoice['who_it'] ?></div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile"><a href="<?= url('cancel-invoice/' . $invoice['id']) ?>" class="color btn p5 w100 m10 center" id="submit">تغییر وضعیت</a></div>
                    <div class="info-detaile">
                        <div class="w100 m10 center status status-column" id="status"><?= ($invoice['status'] == 3) ? '<span class="color-red">غیرفعال</span>' : '<span class="color-green">فعال</span>' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <a href="<?= $this->goBack(url('')) ?>">
            <div class="btn center p5">برگشت</div>
        </a>

    </div>
</div>

<?php include_once('resources/views/layouts/footer.php') ?>