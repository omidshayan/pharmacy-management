<?php
$title = 'جزئیات معاش: ' . $salary['employee_name'];
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
    <div class="content-title"> جزئیات معاش : <?= $salary['employee_name'] ?></div>

    <!-- start page content -->
    <div class="mini-container">

        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">نام کارمند</div>
                <div class="w100 m10 center"><?= $salary['employee_name'] ?></div>
            </div>
        </div>

        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">مبلغ پرداختی</div>
                <div class="w100 m10 center"><?= $this->formatNumber($salary['amount']) . _afghani ?> </div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">تاریخ پرداخت</div>
                <div class="w100 m10 center"><?= jdate('Y/m/d', $salary['date']) ?></div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">نوع</div>
                <div class="w100 m10 center"><?= $this->getTransactionTypeName($salary['transaction_type']) ?></div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">توضیحات</div>
                <div class="w100 m10 center"><?= ($salary['description']) ? $salary['description'] : '- - - -' ?></div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">ماه</div>
                <div class="w100 m10 center"><?= $this->getMonthName($salary['month']) ?></div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">پرداخت توسط</div>
                <div class="w100 m10 center"><?= $salary['who_it'] ?></div>
            </div>
        </div>
        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">تاریخ ثبت</div>
                <div class="w100 m10 center"><?= jdate('Y/m/d', strtotime($salary['created_at'])) ?></div>
            </div>
        </div>

        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">
                    <a href="#" data-url="<?= url('change-status-salary') ?>" data-id="<?= $salary['id'] ?>" class="btn p5 changeStatus">تغییر وضعیت</a>
                </div>
                <div class="w100 m10 center status status-column" id="status"><?= ($salary['status'] == 1) ? '<span class="color-green">فعال</span>' : '<span class="color-red">غیرفعال</span>' ?></div>
            </div>
        </div>

        <a href="<?= $this->goBack(url('salaries')) ?>">
            <div class="btn center p5">برگشت</div>
        </a>

    </div>
    <!-- end page content -->
</div>
<!-- End content -->

<?php include_once('resources/views/layouts/footer.php') ?>