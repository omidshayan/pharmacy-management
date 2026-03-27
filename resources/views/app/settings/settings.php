<?php
$title = 'تنظیمات عمومی';
include_once('resources/views/layouts/header.php');
include_once('resources/views/scripts/activeNotActive.php');

$settingToggles = [
    [
        'title'   => 'ثبت بِل فروش در صورت کمبود جنس',
        'id'      => 'sellStatus',
        'key'     => 'sell_any_situation',
        'url'     => 'change-status-sale-invoice',
        'tooltip' => 'در صورت غیرفعال بودن این گزینه، اگر تعداد فروش بیشتر از موجودی کالا باشد، سیستم اجازه ثبت بِل را نمی‌دهد.'
    ],
    [
        'title'   => 'خرید کالا در صورت نبود موجودی کافی',
        'id'      => 'buyStatus',
        'key'     => 'buy_any_situation',
        'url'     => 'change-status-buy-invoice',
        'tooltip' => 'اگر موجودی داخل دخل برای خرید کالای جدید کافی نباشه، در صورت غیرفعال بودن این بخش اجازه خرید را ندارید'
    ],
    [
        'title'   => 'در صورت داشتن انبار، فعال نمائید',
        'id'      => 'warehouseStatus',
        'key'     => 'warehouse',
        'url'     => 'change-status-warehouse',
        'tooltip' => 'اگر فروشگاه شما دارای انبار می باشد، بعد از فعال نمودن صفحه را رفرش نمائید و گزینه انبار به منوها اضافه می شود.'
    ],
    [
        'title'   => 'نمایش فیلد تاریخ انقضا',
        'id'      => 'expiration_dateStatus',
        'key'     => 'expiration_date',
        'url'     => 'change-status-expiration',
        'tooltip' => 'اگر این قسمت فعال باشد، فیلد تاریخ انقضا در فرم خرید محصول نمایش داده می شود'
    ],
    [
        'title'   => 'نمایش راهنمای هر بخش',
        'id'      => 'help-status',
        'key'     => 'help_status',
        'url'     => 'change-status-help-status',
        'tooltip' => 'اگر این قسمت فعال باشد، راهنمای هر بخش نمایش داده می شود.'
    ],
];
?>

<style>
    .m-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 25px;
    }

    .m-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .m-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 25px;
    }

    .m-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 3.5px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.m-slider {
        background-color: #4CAF50;
    }

    input:checked+.m-slider:before {
        transform: translateX(24px);
    }
</style>

<div id="alert" class="alert" style="display: none;"><?= _error_programmer ?></div>
<!-- loading and overlay -->
<div class="overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<div class="content">
    <div class="content-title"> تنظیمات عمومی </div>

    <!-- change allow sell status invoce -->
    <?php
    foreach ($settingToggles as $item) {

        $title    = $item['title'];
        $statusId = $item['id'];
        $isActive = $settings[$item['key']] ?? 0;
        $dataUrl  = $item['url'];
        $tooltip  = $item['tooltip'];

        include 'resources/views/app/settings/setting-toggle.php';
    }
    ?>

</div>

<?php include('resources/views/layouts/footer.php') ?>