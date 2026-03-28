<!-- start sidebar -->
<?php
$title = 'نمایش فروشندگان / مشتریان';
include_once('resources/views/layouts/header.php');
include_once('public/alerts/check-inputs.php');
include_once('public/alerts/error.php');
?>
<!-- end sidebar -->

<!-- Start content -->
<div class="content">
    <div class="content-title mb20"> بِل شماره: <?= $sale_invoice['id'] ?>
    </div>

    <!-- start page content -->
    مبلغ کل: <?= number_format($sale_invoice['sale_total_amount']) . _afghani ?>
    <br>
    پرداختی: <?= ($sale_invoice['sale_paid_amount']) ? number_format($sale_invoice['sale_paid_amount']) . _afghani : 0 ?>
    <br>
    باقیمانده: <?= ($sale_invoice['remaining_amount']) ? number_format($sale_invoice['remaining_amount']) . _afghani : 0 ?>
    <!-- show employees -->
    <div class="content-container mt5">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام دوا</th>
                    <th>تعداد بسته</th>
                    <th>تعداد واحد</th>
                    <th>تعداد کل</th>
                    <th>قیمت واحد</th>
                    <th>تخفیف</th>
                    <th>قیمت کل</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $number = 1;
                foreach ($sale_invoice_items as $item) {
                ?>
                    <tr>
                        <td class="color-orange"><?= $number ?></td>
                        <td><?= $item['product_name'] ?></td>
                        <td><?= $item['package_qty'] ?></td>
                        <td><?= $item['unit_qty'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= $item['unit_price_sell'] ?></td>
                        <td><?= $item['sale_discount'] ?></td>
                        <td><?= $item['item_total_price'] ?></td>
                    </tr>
                <?php
                    $number++;
                }
                ?>
            </tbody>
            <tbody></tbody>
        </table>
        <div class="flex-justify-align mt20 paginate-section">
            <div class="table-info fs14">تعداد کل: <?= count($sale_invoice_items) ?></div>
            <?php
            if (count($sale_invoice_items) == null) { ?>
                <div class="center">
                    <i class="fa fa-comment"></i>
                    <?= 'اطلاعاتی ثبت نشده است' ?>
                </div>
            <?php } else {
                if (count($sale_invoice_items) > 10) {
                    echo paginateView($sale_invoice_items, 10);
                }
            }
            ?>
        </div>
    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>