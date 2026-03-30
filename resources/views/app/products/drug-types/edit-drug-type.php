    <?php
    $title = 'ویرایش نوع: ' . $item['type_name'];
    include_once('resources/views/layouts/header.php');
    include_once('public/alerts/error.php');
    ?>

    <!-- Start content -->
    <div class="content">
        <div class="content-title">ویرایش نوع <?= $item['type_name'] ?></div>

        <div class="box-container">
            <div class="insert">
                <form id="myForm" action="<?=url('edit-drug-type-store/' . $item['id'])?>" method="POST">
                    <div class="inputs d-flex">
                        <div class="one">
                            <div class="label-form mb5 fs14"><?= _name ?> <?= _star ?> </div>
                            <input type="text" name="type_name" class="checkInput" value="<?= $item['type_name'] ?>" placeholder="واحد نوعیت را وارد نمایید" autocomplete="off" />
                        </div>
                        <?= $this->branchSelectField(); ?>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="submit" id="submit" value="ویرایش" class="btn bold" />
                </form>
            </div>
            <?= $this->back_link('drug-types') ?>
        </div>

    </div>
    <!-- End content -->

    <?php include_once('resources/views/layouts/footer.php') ?>