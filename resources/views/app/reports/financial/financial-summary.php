    <!-- start sidebar -->
    <?php
    $title = 'خلاصه گزارش مالی';
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    $value = $financial_summary['current_balance'];
    $is_negative = $value < 0;
    $formatted = number_format(abs($value));
    ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title">خلاصه گزارش مالی <?= _afghani_s ?></div>
        <br />
        <div class="content-container">
            <table class="fl-table">
                <thead>
                    <tr>
                        <th>موجودی فعلی</th>
                        <th>فروشات</th>
                        <th>خرید‌ها</th>
                        <th>مفاد</th>
                        <th>مصارف</th>
                        <th>دریافتی‌ها</th>
                        <th>برداشت‌ها</th>
                        <th>قرضداری‌ها</th>
                        <th>طلب‌ها</th>
                        <th>تخفیف‌های فروش</th>
                        <th>تخفیف‌های خرید</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= format_number($financial_summary['current_balance']) ?></td>
                        <td><?= format_number($financial_summary['total_sales_amount'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_purchase_amount'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_profit'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_expense'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_cash_in'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_cash_out'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_debt_to_users'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_debt_from_users'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_sales_discount'] ?? 0) ?></td>
                        <td><?= format_number($financial_summary['total_purchase_discount'] ?? 0) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>