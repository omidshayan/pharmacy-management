<?php
$title = 'جزئیات کارمند: ' . $employee['employee_name'];
include_once('resources/views/layouts/header.php');
include_once('resources/views/scripts/change-status.php');
include_once('resources/views/scripts/show-img-modal.php');
$date = explode(' ', $employee['created_at']);
?>

<div id="alert" class="alert" style="display: none;">حالم بده، با برنامه نویس مه تماس بگیر :(</div>

<!-- loading and overlay -->
<div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>
<!-- Start content -->
<div class="content">
    <div class="content-title"> جزئیات کارمند : <?= $employee['employee_name'] ?></div>

    <!-- start page content -->
    <div class="box-container">

        <div class="accordion-title color-orange">مشخصات عمومی</div>
        <div class="accordion-content">
            <div class="child-accordioin">
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">نام: </div>
                    <div class="info-detaile"><?= $employee['employee_name'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">ایمیل: </div>
                    <div class="info-detaile"><?= ($employee['father_name'] ? $employee['father_name'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">شماره: </div>
                    <div class="info-detaile"><?= $employee['phone'] ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">آدرس: </div>
                    <div class="info-detaile"><?= ($employee['address'] ? $employee['address'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex cursor-p">
                    <div class="title-detaile">توضیحات: </div>
                    <div class="info-detaile"><?= ($employee['description'] ? $employee['description'] : '- - - - ') ?></div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile">عکس: </div>
                    <div class=" m10 flex-justify-align">
                        <?= $employee['image']
                            ? '<img class="w50 cursor-p" src="' . asset('public/images/employees/' . $employee['image']) . '" alt="employee image" onclick="openModal(\'' . asset('public/images/employees/' . $employee['image']) . '\')">'
                            : ' - - - - ' ?>
                    </div>
                </div>
                <div class="detailes-culomn d-flex align-center cursor-p">
                    <div class="title-detaile"><a href="#" data-url="<?= url('change-status-employee') ?>" data-id="<?= $employee['id'] ?>" class="changeStatus color btn p5 w100 m10 center" id="submit">تغییر وضعیت</a></div>
                    <div class="info-detaile">
                        <div class="w100 m10 center status status-column" id="status"><?= ($employee['state'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- salaries -->
        <div class="accordion-title color-orange">جزئیات معاش</div>
        <div class="accordion-content">
            <div class="child-accordioin">

                <?php
                $months = [];
                foreach ($employee_salaries as $item) {
                    $months[$item['month']][] = $item;
                }

                function getPersianMonthName($month)
                {
                    $months = [
                        1 => 'حمل',
                        2 => 'ثور',
                        3 => 'جوزا',
                        4 => 'سرطان',
                        5 => 'اسد',
                        6 => 'سنبله',
                        7 => 'میزان',
                        8 => 'عقرب',
                        9 => 'قوس',
                        10 => 'جدی',
                        11 => 'دلو',
                        12 => 'حوت'
                    ];
                    return $months[$month] ?? 'نامشخص';
                }

                $overallBalance = 0;
                $monthSummaries = [];

                foreach ($months as $month => $items) {
                    $total = 0;
                    foreach ($items as $item) {
                        $total += $item['amount'];
                    }

                    $lastItem = end($items);
                    $baseSalary = $lastItem['base_salary'];
                    $difference = $total - $baseSalary;

                    $overallBalance += $difference;

                    $monthSummaries[$month] = [
                        'items' => $items,
                        'baseSalary' => $baseSalary,
                        'total' => $total,
                        'difference' => $difference
                    ];
                }

                if ($overallBalance == 0) {
                    $overallStatus = 'تسویه شده';
                    $overallClass = 'bg-success';
                } elseif ($overallBalance > 0) {
                    $overallStatus = 'بدهکار (' . $this->formatNumber($overallBalance) . ' افغانی)';
                    $overallClass = 'bg-red';
                } else {
                    $overallStatus = 'طلبکار (' . $this->formatNumber(abs($overallBalance)) . ' افغانی)';
                    $overallClass = 'bg-orange';
                }
                ?>

                <div class="m30 p10 <?= $overallClass; ?> p15 br-10">
                    وضعیت کلی معاش: <strong><?= $overallStatus; ?></strong>
                </div>
                <hr class="hr">

                <?php foreach ($monthSummaries as $month => $data): ?>
                    <?php
                    $items = $data['items'];
                    $baseSalary = $data['baseSalary'];
                    $total = $data['total'];
                    $difference = $data['difference'];

                    if ($difference == 0) {
                        $status = 'تسویه شده';
                        $statusClass = 'bg-success';
                    } elseif ($difference > 0) {
                        $status = 'بدهکار (' . $this->formatNumber($difference) . ' افغانی)';
                        $statusClass = 'bg-red';
                    } else {
                        $status = 'طلبکار (' . $this->formatNumber(abs($difference)) . ' افغانی)';
                        $statusClass = 'bg-orange';
                    }
                    ?>

                    <table class="fl-table m30">
                        <thead>
                            <tr>
                                <th>ماه</th>
                                <th>تاریخ پرداخت</th>
                                <th>نوع</th>
                                <th>مبلغ</th>
                                <th>جزئیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= getPersianMonthName($item['month']); ?></td>
                                    <td><?= jdate('Y/m/d', $item['date']) ?></td>
                                    <td>
                                        <?=
                                        ($item['transaction_type'] == 1)
                                            ? 'معاش'
                                            : (($item['transaction_type'] == 2)
                                                ? 'اضافه کاری'
                                                : (($item['transaction_type'] == 3)
                                                    ? 'کسری'
                                                    : 'نامشخص'))
                                        ?>
                                    </td>
                                    <td><?= $this->formatNumber($item['amount']); ?> افغانی</td>
                                    <td>
                                        <a href="<?= url('salary-details/' . $item['id']) ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" class="color-orange" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <tr>
                                <td><strong>پایه معاش ماه <?= getPersianMonthName($month); ?>:</strong></td>
                                <td><strong><?= $this->formatNumber($baseSalary); ?> افغانی</strong></td>
                                <td colspan="2"><strong>مجموع دریافتی:</strong></td>
                                <td><strong><?= $this->formatNumber($total); ?> افغانی</strong></td>
                            </tr>

                            <tr>
                                <td colspan="5" class="<?= $statusClass; ?>">
                                    <strong>وضعیت این ماه: <?= $status; ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr class="hr">
                <?php endforeach; ?>

            </div>
        </div>

        <a href="<?= url('employees') ?>">
            <div class="btn center p5">برگشت</div>
        </a>

    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>