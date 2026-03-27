    <!-- start sidebar -->
    <?php
    $title = 'جزئیات بِل شماره: ' . $invoice['invoice_number'];
    include_once('resources/views/layouts/header.php');
    ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title fs18">جزئیات بِل شماره: <?= $invoice['invoice_number'] ?></div>
        <div class="content-title fs15 mb20">مجموع اقلام بِل: <?= count($invoice_items) ?></div>
        <!-- start page content -->

        <!-- invioce details -->
        <div class="content-container">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>شماره بِل</th>
                        <th>تاریخ</th>
                        <th>نوع تراکنش</th>
                        <th>مبلغ کل <?= _afghani_s ?></th>
                        <th>پرداخت</th>
                        <th>تخفیف</th>
                        <th>باقیمانده</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $transactionTypes = [
                        1 => 'بِل فروش',
                        2 => 'بِل خرید',
                        3 => 'برگشت از فروش',
                        4 => 'برگشت از خرید',
                        5 => 'پرداخت پول',
                        6 => 'دریافت از مشتری',
                        7 => 'هزینه',
                        8 => 'درآمد',
                    ];
                    $remainder = $invoice['total_amount'] - $invoice['paid_amount'] - $invoice['discount'];
                    ?>
                    <tr>
                        <td style="color: <?= ($invoice['invoice_type'] == 1) ? (($remainder > 0) ? 'red' : 'green') : 'inherit' ?>">
                            <?= $invoice['invoice_number'] ?>
                        </td>
                        <td><?= jdate('Y/m/d', $invoice['date']) ?></td>
                        <td><?= $transactionTypes[$invoice['invoice_type']] ?? 'نامشخص' ?></td>
                        <td><?= number_format($invoice['total_amount']) ?></td>
                        <td><?= number_format((float)($invoice['paid_amount'] ?? 0)) ?></td>
                        <td><?= number_format($invoice['discount'] ?? 0) ?></td>
                        <td><?= number_format($remainder) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- invoice items -->
        <div class="content-container mt20">
            اقلام بِل
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام محصول</th>
                        <th>تعداد بسته</th>
                        <th>تعداد عدد</th>
                        <th>تعداد کل</th>
                        <th>قیمت خرید</th>
                        <th>تخفیف</th>
                        <th>قیمت کل</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $number = 1;
                    foreach ($invoice_items as $item) {
                        $unit_price = $this->calculateUnitPrices($item);
                    ?>
                        <tr>
                            <td class="color-orange"><?= $number ?></td>
                            <td><?= $item['product_name'] ?></td>
                            <td><?= $item['package_qty'] ?? 0 ?></td>
                            <td><?= $item['unit_qty'] ?? 0 ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= $this->formatNumber($unit_price['buy']) ?></td>
                            <td><?= number_format($item['discount']) ?></td>
                            <td><?= number_format($item['item_total_price']) ?></td>
                        </tr>
                    <?php
                        $number++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- end page content -->

         <div class="mt15"><?= $this->back_link('user-account-statement/' . $invoice['user_id']) ?></div>
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>