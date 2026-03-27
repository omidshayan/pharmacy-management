<?php
$title = 'جزئیات پیام: ' . $notification['title'];
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
    <div class="content-title"> جزئیات پیام : <?= $notification['title'] ?></div>

    <!-- start page content -->
    <div class="mini-container">

        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">شرح</div>
                <div class="w100 m10 center"><?= $notification['msg'] ?></div>
            </div>
        </div>

        <div class="details">
            <div class="detail-item d-flex">
                <div class="w100 m10 center">تاریخ ثبت</div>
                <div class="w100 m10 center"><?= jdate('Y/m/d', strtotime($notification['created_at'])) ?></div>
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