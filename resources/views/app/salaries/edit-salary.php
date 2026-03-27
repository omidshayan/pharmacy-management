    <!-- start sidebar -->
    <?php
    $title = 'ویرایش معاش:' . $salary['employee_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/check-inputs.php');
    include_once('public/alerts/toastr.php');
    include_once('resources/views/scripts/datePicker.php');
    include_once('resources/views/scripts/search-items.php');
    ?>
    <!-- end sidebar -->

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ویرایش معاش: <?= $salary['employee_name'] ?></div>

        <!-- start page content -->
        <div class="mini-container">
            <div class="insert">
                <form action="<?= url('edit-salary-store/' . $salary['id']) ?>" method="POST">

                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">مبلغ پرداختی معاش <?= _star ?></div>
                            <input type="text" name="amount" value="<?=$this->formatNumber($salary['amount'], true)?>" class="checkInput" placeholder="مبلغ پرداختی را وارد نمایید" maxlength="40" />
                        </div>
                    </div>
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"><?=$salary['description']?></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
                    <input type="submit" id="submit" value="ثبت" class="btn" />
                </form>
            </div>
        </div>
        <!-- end page content -->
    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>