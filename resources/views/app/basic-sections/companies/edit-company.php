    <?php
    $title = 'ویرایش کمپانی: ' . $company['company_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    ?>

    <div class="content">
        <div class="content-title">ویرایش کمپانی: <?= $company['company_name'] ?></div>
        <div class="box-container">
            <div class="insert">
                <form id="myForm" action="<?= url('edit-company-store/' . $company['id']) ?>" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14">نام کمپانی‌ <?= _star ?> </div>
                            <input type="text" name="company_name" class="checkInput" value="<?= $company['company_name'] ?>" placeholder="نام نام کمپانی را وارد نمایید" autocomplete="off" />
                        </div>
                        <div class="one">
                            <div class="label-form mb5 fs14">توضیحات</div>
                            <textarea name="description" placeholder="توضیحات را وارد نمایید"><?= $company['description'] ?></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
            <?= $this->back_link('companies') ?>
        </div>
    </div>

    <?php include_once('resources/views/layouts/footer.php') ?>